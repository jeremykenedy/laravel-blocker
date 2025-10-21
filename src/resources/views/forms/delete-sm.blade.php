{{ html()->form('POST', route('laravelblocker::blocker.destroy', $blockedItem->id))
        ->attribute('accept-charset', 'UTF-8')
        ->attribute('data-toggle', 'tooltip')
        ->attribute('title', trans('laravelblocker::laravelblocker.tooltips.delete'))
        ->open() }}
    @csrf
    @method('DELETE')
    <button class="btn btn-danger btn-sm btn-block" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Blocked Item" data-message="{!! trans('laravelblocker::laravelblocker.modals.delete_blocked_message', ['blocked' => $blockedItem->value]) !!}">
        {!! trans('laravelblocker::laravelblocker.buttons.delete') !!}
    </button>
{{ html()->form()->close() }}
