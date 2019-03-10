<form method="POST" action="/blocker/{{ $blockedItem->id }}" accept-charset="UTF-8" data-toggle="tooltip" title="Delete Blocked Item">
    {!! Form::hidden("_method", "DELETE") !!}
    {!! csrf_field() !!}
    <button class="btn btn-danger btn-sm" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Blocked Item" data-message="{!! trans("laravelblocker::laravelblocker.modals.delete_blocked_message", ["blocked" => $blockedItem->slug]) !!}">
        {!! trans("laravelblocker::laravelblocker.buttons.delete") !!}
    </button>
</form>
