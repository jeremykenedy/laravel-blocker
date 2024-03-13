<form action="{{ route('laravelblocker::destroy-all-blocked') }}" method="POST" accept-charset="UTF-8">
    <input type="hidden" name="_method" value="DELETE">
    @csrf
    <button class="dropdown-item btn pointer" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('laravelblocker::laravelblocker.modals.destroy_all_blocked_title') }}" data-message="{{ trans('laravelblocker::laravelblocker.modals.destroy_all_blocked_message') }}">
        <i class="fa fa-trash fa-fw" aria-hidden="true"></i> {{ trans_choice("laravelblocker::laravelblocker.buttons.destroy-all", 1, ['count' => $blocked->count()]) }}
    </button>
</form>
