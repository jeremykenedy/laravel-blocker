<form action="{{ route('laravelblocker::blocker.destroy', $item->id) }}" method="POST" accept-charset="UTF-8" data-toggle="tooltip" title="{{ trans('laravelblocker::laravelblocker.tooltips.delete') }}">
    <input type="hidden" name="_method" value="DELETE">
    @csrf
    <button class="btn btn-danger btn-sm" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Blocked Item" data-message="{{ trans('laravelblocker::laravelblocker.modals.delete_blocked_message', ['blocked' => $item->value]) }}">
        {{ trans('laravelblocker::laravelblocker.buttons.delete-larger') }}
    </button>
</form>
