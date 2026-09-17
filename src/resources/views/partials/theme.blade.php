@if(in_array(config('laravelblocker.theme'), ['dark', 'system'], true))
    @include('laravelblocker::modern.styles')
    <style>
        [data-blocker-root] .card, [data-blocker-root] .panel, [data-blocker-root] .list-group-item,
        [data-blocker-root] .modal-content, [data-blocker-root] .form-control,
        [data-blocker-root] .custom-select, [data-blocker-root] .dropdown-menu { background:var(--lb-bg); color:var(--lb-text); border-color:var(--lb-border); }
        [data-blocker-root] .table, [data-blocker-root] .badge, [data-blocker-root] .dropdown-item { color:var(--lb-text); }
        [data-blocker-root] .table td, [data-blocker-root] .table th { border-color:var(--lb-border); }
        [data-blocker-root] .table-striped tbody tr:nth-of-type(odd), [data-blocker-root] .input-group-text { background:var(--lb-surface); color:var(--lb-text); }
        [data-blocker-root] .form-control::placeholder, [data-blocker-root] caption { color:var(--lb-muted); }
    </style>
@endif
