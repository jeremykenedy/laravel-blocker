<form method="POST" action="{{ route('laravelblocker::'.$routeName, empty($bulk) ? $item->id : []) }}" data-confirm="{{ trans('laravelblocker::laravelblocker.ui.confirm_'.$action) }}">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <button type="submit" class="lb-button {{ strpos($action, 'restore') === 0 ? '' : 'lb-danger' }}">{{ trans('laravelblocker::laravelblocker.ui.'.$action) }}</button>
</form>
