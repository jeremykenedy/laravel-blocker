{{ html()->form('POST', route('laravelblocker::blocker.destroy', $item->id))
        ->attribute('accept-charset', 'UTF-8')
        ->attribute('data-toggle', 'tooltip')
        ->attribute('title', trans('laravelblocker::laravelblocker.tooltips.delete'))
        ->open() }}
    @csrf
    @method('DELETE')
    <button class="btn btn-danger btn-block edit-form-delete" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('laravelblocker::laravelblocker.modals.delete_blocked_title') }}" data-message="{!! trans('laravelblocker::laravelblocker.modals.delete_blocked_message', ['blocked' => $item->value]) !!}">
        {!! trans('laravelblocker::laravelblocker.buttons.delete-larger') !!}
    </button>
{{ html()->form()->close() }}
