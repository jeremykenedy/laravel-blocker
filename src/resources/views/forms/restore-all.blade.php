{{ html()->form('POST', route('laravelblocker::blocker-deleted-restore-all'))
        ->attribute('accept-charset', 'UTF-8')
        ->open() }}
    @csrf
    {{ html()->button()->type('button')
            ->class('btn dropdown-item')
            ->attribute('data-toggle', 'modal')
            ->attribute('data-target', '#confirmRestore')
            ->attribute('data-title', trans('laravelblocker::laravelblocker.modals.resotreAllBlockedTitle'))
            ->attribute('data-message', trans('laravelblocker::laravelblocker.modals.resotreAllBlockedMessage'))
            ->html('<i class="fa fa-fw fa-history" aria-hidden="true"></i> ' .
                trans_choice('laravelblocker::laravelblocker.buttons.restore-all-blocked', 1, ['count' => $blocked->count()])) }}
{{ html()->form()->close() }}
