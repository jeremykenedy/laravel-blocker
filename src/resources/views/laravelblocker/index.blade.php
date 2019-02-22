@extends(config('laravelblocker.laravelBlockerBladeExtended'))

@section(config('laravelblocker.laravelBlockerTitleExtended'))
    {!! trans('laravelblocker::laravelblocker.titles.show-blocked') !!}
@endsection

@php
    switch (config('laravelblocker.blockerBootstapVersion')) {
        case '4':
            $containerClass = 'card';
            $containerHeaderClass = 'card-header bg-danger text-white';
            $containerBodyClass = 'card-body';
            break;
        case '3':
        default:
            $containerClass = 'panel panel-default';
            $containerHeaderClass = 'panel-heading';
            $containerBodyClass = 'panel-body';
    }
    $blockerBootstrapCardClasses = (is_null(config('laravelblocker.blockerBootstrapCardClasses')) ? '' : config('laravelblocker.blockerBootstrapCardClasses'));
@endphp

@section(config('laravelblocker.blockerBladePlacementCss'))
    @if(config('laravelblocker.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('laravelblocker.datatablesCssCDN') }}">
    @endif
    @if(config('laravelblocker.blockerEnableFontAwesomeCDN'))
        <link rel="stylesheet" type="text/css" href="{{ config('laravelblocker.blockerFontAwesomeCDN') }}">
    @endif
    @include('laravelblocker::partials.styles')
    @include('laravelblocker::partials.bs-visibility-css')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="{{ $containerClass }} {{ $blockerBootstrapCardClasses }}">
                    <div class="{{ $containerHeaderClass }}">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {!! trans('laravelblocker::laravelblocker.blocked-items-title') !!}
                            </span>

                            <div class="btn-group pull-right btn-group-xs">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v fa-fw" aria-hidden="true"></i>
                                    <span class="sr-only">
                                        {!! trans('laravelblocker::laravelblocker.users-menu-alt') !!}
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('laravelblocker::blocker.create') }}">
                                        <i class="fa fa-fw fa-plus" aria-hidden="true"></i>
                                        {!! trans('laravelblocker::laravelblocker.buttons.create-new-blocked') !!}
                                    </a>
                                    <a class="dropdown-item" href="{{ url('/blocker/deleted') }}">
                                        <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                        {!! trans('laravelblocker::laravelblocker.buttons.show-deleted-blocked') !!}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="{{ $containerBodyClass }}">
                        @if(config('laravelblocker.enableSearchBlocked'))
                            @include('laravelblocker::partials.search-blocked-form')
                        @endif
                        <div class="table-responsive blocked-table">
                            <table class="table table-sm table-striped data-table">
                                <caption id="blocked_count">
                                    {!! trans_choice('laravelblocker::laravelblocker.blocked-table.caption', 1, ['blockedcount' => $blocked->count()]) !!}
                                </caption>
                                <thead class="thead">
                                    <tr>
                                        <th scope="col">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.id') !!}
                                        </th>
                                        <th scope="col">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.type') !!}
                                        </th>
                                        <th scope="col">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.value') !!}
                                        </th>
                                        <th scope="col" class="hidden-xs">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.note') !!}
                                        </th>
                                        <th scope="col" class="hidden-xs hidden-sm">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.userId') !!}
                                        </th>
                                        <th scope="col" class="hidden-xs hidden-sm hidden-md">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.createdAt') !!}
                                        </th>
                                        <th scope="col" class="hidden-xs hidden-sm hidden-md">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.updatedAt') !!}
                                        </th>
                                        <th class="no-search no-sort">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.actions') !!}
                                        </th>
                                        <th class="no-search no-sort"></th>
                                        <th class="no-search no-sort"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blocked as $blockedItem)
                                        <tr>
                                            <th>
                                                {!! $blockedItem->id !!}
                                            </th>
                                            <td>
                                                {!! $blockedItem->type !!}
                                            </td>
                                            <td>
                                                {!! $blockedItem->value !!}
                                            </td>
                                            <td>
                                                @if ($blockedItem->userId)
                                                    {!! $blockedItem->userId !!}
                                                @else
                                                    <span class="disabled">
                                                        {!! trans('laravelblocker::laravelblocker.na') !!}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {!! $blockedItem->note !!}
                                            </td>
                                            <td>
                                                <span class="disabled">
                                                    n/a
                                                </span>
                                            </td>
                                            <td>
                                                {!! $blockedItem->created_at !!}
                                            </td>
                                            <td>
                                                {!! $blockedItem->updated_at !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if(config('laravelblocker.enableSearchBlocked'))
                                    <tbody id="search_results"></tbody>
                                @endif
                            </table>

                            @if(config('laravelblocker.blockerPaginationEnabled'))
                                {{ $blocked->links() }}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section(config('laravelblocker.blockerBladePlacementJs'))

    @if (config('laravelblocker.enabledDatatablesJs'))
        @include('laravelblocker::scripts.datatables')
    @endif

    {{--
    @include('laravelblocker::scripts.delete-modal-script')
    --}}

    @if(config('laravelblocker.tooltipsEnabled'))
        @include('laravelblocker::scripts.tooltips')
    @endif

    @if(config('laravelblocker.enableSearchBlocked'))
        @include('laravelblocker::scripts.search-blocked')
    @endif

@endsection
