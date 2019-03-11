<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelBlocker\App\Http\Requests\SearchBlockerRequest;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;

class LaravelBlockerDeletedController extends LaravelBlockerController
{
    /**
     * Get Soft Deleted BlockedItem.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedBlockedItem($id)
    {
        $item = BlockedItem::onlyTrashed()->where('id', $id)->get();
        if (count($item) != 1) {
            return abort(redirect('blocker-deleted')
                            ->with('error', trans('laravelblocker::laravelblocker.errors.errorBlockerNotFound')));
        }

        return $item[0];
    }

    /**
     * Show the laravel ip blocker deleted dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (config('laravelblocker.blockerPaginationEnabled')) {
            $blocked = BlockedItem::onlyTrashed()->paginate(config('laravelblocker.blockerPaginationPerPage'));
        } else {
            $blocked = BlockedItem::onlyTrashed()->get();
        }

        return view('laravelblocker::laravelblocker.deleted.index', compact('blocked'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = self::getDeletedBlockedItem($id);
        $typeDeleted = 'deleted';

        return view('laravelblocker::laravelblocker.show', compact('item', 'typeDeleted'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreBlockedItem(Request $request, $id)
    {
        $item = self::getDeletedBlockedItem($id);
        $item->restore();

        return redirect('blocker')
                    ->with('success', trans('laravelblocker::laravelblocker.messages.successRestoredItem'));
    }

    /**
     * Restore all the specified resource from soft deleted storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAllBlockedItems(Request $request)
    {
        $items = BlockedItem::onlyTrashed()->get();
        foreach ($items as $item) {
            $item->restore();
        }

        return redirect('blocker')
                    ->with('success', trans('laravelblocker::laravelblocker.messages.successRestoredAllItems'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = self::getDeletedBlockedItem($id);
        $item->forceDelete();

        return redirect('blocker-deleted')
                    ->with('success', trans('laravelblocker::laravelblocker.messages.successDestroyedItem'));
    }

    /**
     * Destroy all the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyAllItems(Request $request)
    {
        $items = BlockedItem::onlyTrashed()->get();

        foreach ($items as $item) {
            $item->forceDelete();
        }

        return redirect('blocker')
                    ->with('success', trans('laravelblocker::laravelblocker.messages.successDestroyedAllItems'));
    }

    /**
     * Method to search the deleted blocked items.
     *
     * @param SearchBlockerRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(SearchBlockerRequest $request)
    {
        $searchTerm = $request->validated()['blocked_search_box'];
        $results = BlockedItem::onlyTrashed()->where('id', 'like', $searchTerm.'%')->onlyTrashed()
                        ->orWhere('typeId', 'like', $searchTerm.'%')->onlyTrashed()
                        ->orWhere('value', 'like', $searchTerm.'%')->onlyTrashed()
                        ->orWhere('note', 'like', $searchTerm.'%')->onlyTrashed()
                        ->orWhere('userId', 'like', $searchTerm.'%')->onlyTrashed()
                        ->get();

        $results->map(function ($item) {
            $item['type'] = $item->blockedType->slug;

            return $item;
        });

        return response()->json([
            json_encode($results),
        ], Response::HTTP_OK);
    }
}
