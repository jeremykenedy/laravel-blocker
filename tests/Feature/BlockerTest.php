<?php

namespace jeremykenedy\LaravelBlocker\Tests\Feature;

use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;
use jeremykenedy\LaravelBlocker\Tests\TestCase;

class BlockerTest extends TestCase
{
    private function item($value = 'blocked.example')
    {
        $type = BlockedType::firstOrCreate(['slug' => 'domain'], ['name' => 'Domain']);

        return BlockedItem::create(['typeId' => $type->id, 'value' => $value, 'note' => 'A note']);
    }

    public function test_crud_and_soft_delete_lifecycle(): void
    {
        $type = BlockedType::create(['slug' => 'domain', 'name' => 'Domain']);
        $data = ['typeId' => $type->id, 'value' => 'blocked.example', 'note' => 'Original'];
        $this->post('/blocker', $data)->assertRedirect('/blocker')->assertSessionHas('success');
        $item = BlockedItem::firstOrFail();
        $this->assertSame('Original', $item->note);
        $this->get('/blocker/'.$item->id)->assertOk()->assertSee('blocked.example');
        $this->put('/blocker/'.$item->id, array_merge($data, ['note' => 'Updated']))->assertRedirect();
        $this->assertSame('Updated', $item->fresh()->note);
        $this->delete('/blocker/'.$item->id)->assertRedirect('/blocker');
        $this->assertNull(BlockedItem::find($item->id));
        $this->get('/blocker-deleted/'.$item->id)->assertOk();
        $this->put('/blocker-deleted/'.$item->id)->assertRedirect('/blocker');
        $this->assertNotNull(BlockedItem::find($item->id));
        $this->delete('/blocker/'.$item->id);
        $this->delete('/blocker-deleted/'.$item->id)->assertRedirect('/blocker-deleted');
        $this->assertNull(BlockedItem::withTrashed()->find($item->id));
    }

    public function test_validation_rejects_missing_unknown_and_duplicate_values(): void
    {
        $item = $this->item();
        $this->postJson('/blocker', [])->assertStatus(422)->assertJsonValidationErrors(['typeId', 'value']);
        $this->postJson('/blocker', ['typeId' => [1], 'value' => 'other.example'])->assertStatus(422);
        $this->postJson('/blocker', ['typeId' => $item->typeId, 'value' => 'other.example', 'userId' => 999])->assertStatus(422)->assertJsonValidationErrors('userId');
        $this->postJson('/blocker', ['typeId' => 999, 'value' => 'other.example'])->assertStatus(422)->assertJsonValidationErrors('typeId');
        $this->postJson('/blocker', ['typeId' => $item->typeId, 'value' => $item->value])->assertStatus(422)->assertJsonValidationErrors('value');
        $this->postJson('/blocker', ['typeId' => $item->typeId, 'value' => str_repeat('a', 256), 'note' => str_repeat('b', 501)])->assertStatus(422)->assertJsonValidationErrors(['value', 'note']);
        $type = BlockedType::create(['slug' => 'email', 'name' => 'Email']);
        $this->postJson('/blocker', ['typeId' => $type->id, 'value' => 'invalid'])->assertStatus(422)->assertJsonValidationErrors('value');
        $this->postJson('/blocker', ['typeId' => $type->id, 'value' => ['invalid']])->assertStatus(422)->assertJsonValidationErrors('value');
        $this->post('/blocker', ['typeId' => $type->id, 'value' => 'a@example.org'])->assertRedirect();
        $this->assertSame(2, BlockedItem::count());
    }

    public function test_search_keeps_legacy_json_shape_and_separates_deleted_items(): void
    {
        $active = $this->item('match-active.example');
        $deleted = $this->item('match-deleted.example');
        $deleted->delete();
        foreach (['/search-blocked' => $active, '/search-blocked-deleted' => $deleted] as $url => $expected) {
            $response = $this->postJson($url, ['blocked_search_box' => 'match'])->assertOk();
            $rows = json_decode($response->json()[0], true);
            $this->assertCount(1, $rows);
            $this->assertSame($expected->id, $rows[0]['id']);
            $this->assertSame('domain', $rows[0]['type']);
        }
        $this->postJson('/search-blocked', [])->assertStatus(422);
    }

    public function test_bulk_actions_only_affect_deleted_items(): void
    {
        $active = $this->item('active.example');
        $deleted = $this->item('deleted.example');
        $deleted->delete();
        $this->post('/blocker-deleted-restore-all')->assertRedirect();
        $this->assertSame(2, BlockedItem::count());
        $deleted->fresh()->delete();
        $this->delete('/blocker-deleted-destroy-all')->assertRedirect();
        $this->assertSame($active->id, BlockedItem::withTrashed()->first()->id);
    }

    public function test_all_views_render_for_each_framework_and_escape_values(): void
    {
        $item = $this->item('<script>alert(1)</script>');
        foreach (['legacy', 'bootstrap5', 'tailwind'] as $framework) {
            config(['laravelblocker.frontend' => $framework]);
            foreach (['/blocker', '/blocker/create', '/blocker/'.$item->id, '/blocker/'.$item->id.'/edit', '/blocker-deleted'] as $url) {
                $this->get($url)->assertOk()->assertDontSee('<script>alert(1)</script>', false);
            }
        }
        config(['laravelblocker.frontend' => 'legacy', 'laravelblocker.blockerBootstapVersion' => '3']);
        $this->get('/blocker')->assertOk()->assertSee('panel panel-warning', false);
        $item->delete();
        foreach (['legacy', 'bootstrap5', 'tailwind'] as $framework) {
            config(['laravelblocker.frontend' => $framework]);
            $this->get('/blocker-deleted')->assertOk()->assertDontSee('<script>alert(1)</script>', false);
            $this->get('/blocker-deleted/'.$item->id)->assertOk()->assertDontSee('<script>alert(1)</script>', false);
        }
    }

    public function test_modern_search_pagination_and_theme(): void
    {
        $this->item('match-one.example');
        $this->item('match-two.example');
        $this->item('other.example');
        config(['laravelblocker.frontend' => 'tailwind', 'laravelblocker.theme' => 'dark', 'laravelblocker.blockerPaginationEnabled' => true, 'laravelblocker.blockerPaginationPerPage' => 1]);
        $this->get('/blocker?q=match')->assertOk()->assertSee('match-one.example')->assertDontSee('other.example')->assertSee('data-theme="dark"', false)->assertSee('q=match', false);
        $this->getJson('/blocker?q[]=invalid')->assertStatus(422);
    }

    public function test_disabled_modern_search_still_validates_query_input(): void
    {
        config(['laravelblocker.frontend' => 'bootstrap5', 'laravelblocker.enableSearchBlocked' => false]);
        foreach (['/blocker', '/blocker-deleted'] as $url) {
            $this->getJson($url.'?q[]=invalid')->assertStatus(422)->assertJsonValidationErrors('q');
            $this->getJson($url.'?q='.str_repeat('a', 256))->assertStatus(422);
        }
        $this->item('visible.example');
        $this->get('/blocker?q=unmatched')->assertOk()->assertSee('visible.example');
    }

    public function test_modern_captions_use_totals_and_deleted_landmark_labels(): void
    {
        $first = $this->item('first.example');
        $second = $this->item('second.example');
        config(['laravelblocker.frontend' => 'bootstrap5', 'laravelblocker.blockerPaginationEnabled' => true, 'laravelblocker.blockerPaginationPerPage' => 1]);
        $this->get('/blocker')->assertOk()->assertSee('2 total blocks');
        $first->delete();
        $second->delete();
        $this->get('/blocker-deleted')->assertOk()->assertSee('2 total blocks')->assertSee('aria-label="Deleted items"', false);
    }

    public function test_missing_items_do_not_mutate_existing_records(): void
    {
        $item = $this->item();
        $this->get('/blocker/999')->assertNotFound();
        $this->delete('/blocker/999')->assertNotFound();
        $this->delete('/blocker-deleted/'.$item->id)->assertRedirect('/blocker-deleted');
        $this->assertNotNull($item->fresh());
    }

    public function test_relationships_and_deleted_types_remain_readable(): void
    {
        $item = $this->item();
        $type = $item->blockedType;
        $this->assertSame($item->id, $type->blockedItems()->first()->id);
        $type->delete();
        $this->get('/blocker')->assertOk();
        $this->postJson('/blocker', ['typeId' => $type->id, 'value' => 'other.example'])->assertStatus(422);
    }
}
