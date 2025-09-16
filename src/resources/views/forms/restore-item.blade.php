@if($restoreType == 'full')
    @php
        $itemId = $item->id;
        $itemValue = $item->value;
        $itemClasses = 'btn btn-success btn-block';
        $itemText = trans('laravelblocker::laravelblocker.buttons.restore-blocked-item-full');
    @endphp
@endif
@if($restoreType == 'small')
    @php
        $itemId = $blockedItem->id;
        $itemValue = $blockedItem->value;
        $itemClasses = 'btn btn-sm btn-success btn-block';
        $itemText = trans('laravelblocker::laravelblocker.buttons.restore-blocked-item');
    @endphp
@endif

{{ html()->form('POST', route('laravelblocker::blocker-item-restore', $itemId))
        ->attribute('accept-charset', 'UTF-8')
        ->attribute('data-toggle', 'tooltip')
        ->attribute('title', trans('laravelblocker::laravelblocker.tooltips.restoreItem'))
        ->open() }}
    @csrf
    @method('PUT')
    {{ html()->button($itemText)
            ->type('button')
            ->class($itemClasses)
            ->attribute('data-toggle', 'modal')
            ->attribute('data-target', '#confirmRestore')
            ->attribute('data-title', trans('laravelblocker::laravelblocker.modals.resotreBlockedItemTitle'))
            ->attribute('data-message', trans('laravelblocker::laravelblocker.modals.resotreBlockedItemMessage', ['value' => $itemValue])) }}
{{ html()->form()->close() }}
