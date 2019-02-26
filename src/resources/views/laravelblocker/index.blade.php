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
            $containerClass = 'panel panel-danger';
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
                                        <th class="no-search no-sort" colspan="3">
                                            {!! trans('laravelblocker::laravelblocker.blocked-table.actions') !!}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="blocked-table-body">
                                    @foreach($blocked as $blockedItem)
                                        <tr>
                                            <td>
                                                {!! $blockedItem->id !!}
                                            </td>
                                            <td>
                                                {!! $blockedItem->blockedType->slug !!}
                                            </td>
                                            <td>
                                                {!! $blockedItem->value !!}
                                            </td>
                                            <td class="hidden-xs">
                                                {!! $blockedItem->note !!}
                                            </td>
                                            <td class="hidden-xs hidden-sm">
                                                @if ($blockedItem->userId)
                                                    {!! $blockedItem->userId !!}
                                                @else
                                                    <span class="disabled">
                                                        {!! trans('laravelblocker::laravelblocker.none') !!}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="hidden-xs hidden-sm hidden-md">
                                                {!! $blockedItem->created_at->format('m/d/Y H:ia') !!}
                                            </td>
                                            <td class="hidden-xs hidden-sm hidden-md">
                                                {!! $blockedItem->updated_at->format('m/d/Y H:ia') !!}
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-success btn-block" href="/blocker/{{ $blockedItem->id }}" data-toggle="tooltip" title="{{ trans("laravelblocker::laravelblocker.tooltips.show") }}">
                                                    {!! trans("laravelblocker::laravelblocker.buttons.show") !!}
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-info btn-block" href="/blocker/{{ $blockedItem->id }}/edit" data-toggle="tooltip" title="{{ trans("laravelblocker::laravelblocker.tooltips.edit") }}">
                                                    {!! trans("laravelblocker::laravelblocker.buttons.edit") !!}
                                                </a>
                                            </td>
                                            <td>
                                                <form method="POST" action="/blocker/{{ $blockedItem->id }}" accept-charset="UTF-8" data-toggle="tooltip" title="Delete Blocked Item">
                                                    {!! Form::hidden("_method", "DELETE") !!}
                                                    {!! csrf_field() !!}
                                                    <button class="btn btn-danger btn-sm" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Blocked Item" data-message="{!! trans("laravelblocker::laravelblocker.modals.delete_blocked_message", ["blocked" => $blockedItem->slug]) !!}">
                                                        {!! trans("laravelblocker::laravelblocker.buttons.delete") !!}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if(config('laravelblocker.enableSearchBlocked'))
                                    <tbody id="search_results"></tbody>
                                @endif
                            </table>

                            @if(config('laravelblocker.blockerPaginationEnabled'))
                                <div id="blocked_pagination">
                                    {{ $blocked->links() }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('laravelblocker::modals.modal-delete')

@endsection

@section(config('laravelblocker.blockerBladePlacementJs'))
    @if (config('laravelblocker.enabledDatatablesJs'))
        @include('laravelblocker::scripts.datatables')
    @endif
    @include('laravelblocker::scripts.delete-modal-script')
    @if(config('laravelblocker.tooltipsEnabled'))
        @include('laravelblocker::scripts.tooltips')
    @endif
    @if(config('laravelblocker.enableSearchBlocked'))
        @include('laravelblocker::scripts.search-blocked')
    @endif
@endsection
