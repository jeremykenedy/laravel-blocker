@extends(config('laravelblocker.laravelBlockerBladeExtended'))

@section(config('laravelblocker.laravelBlockerTitleExtended'))
    {!! trans('laravelblocker::laravelblocker.titles.show-blocked-item') !!}
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
                                {!! trans('laravelblocker::laravelblocker.edit-blocked-item-title', ['name' => $item->value]) !!}
                            </span>
                            <div class="pull-right">
                                <a href="{{ url('blocker') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('laravelblocker::laravelblocker.tooltips.back-blocked') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('laravelblocker::laravelblocker.buttons.back-to-blocked') !!}
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="{{ $containerBodyClass }}">





                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                ID
                                <span class="badge badge-pill">
                                    {{ $item->id }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                TypeId
                                <span class="badge badge-pill">
                                    {{ $item->typeId }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Slug
                                <span class="badge badge-pill">
                                    {!! $item->blockedType->slug !!}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Value
                                <span class="badge badge-pill">
                                    {{ $item->value }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Note
                                <span class="badge badge-pill">
                                    {{ $item->note }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                UserId
                                <span class="badge badge-pill">
                                    @if ($item->userId)
                                        {!! $item->userId !!}
                                    @else
                                        <span class="disabled">
                                            {!! trans('laravelblocker::laravelblocker.none') !!}
                                        </span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Created At
                                <span class="badge badge-pill">
                                    {!! $item->created_at->format('m/d/Y H:ia') !!}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Updated At
                                <span class="badge badge-pill">
                                    {!! $item->updated_at->format('m/d/Y H:ia') !!}
                                </span>
                            </li>
                            @if ($item->deleted_at)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Updated At
                                    <span class="badge badge-pill">
                                        {!! $item->deleted_at->format('m/d/Y H:ia') !!}
                                    </span>
                                </li>
                            @endif
                        </ul>




                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <a class="btn btn-sm btn-success btn-block" href="/blocker/{{ $item->id }}/edit" data-toggle="tooltip" title="{{ trans("laravelblocker::laravelblocker.tooltips.edit") }}">
                                    Save Changes
                                </a>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <form method="POST" action="/blocker/{{ $item->id }}" accept-charset="UTF-8" data-toggle="tooltip" title="Delete Blocked Item">
                                    {!! Form::hidden("_method", "DELETE") !!}
                                    {!! csrf_field() !!}
                                    <button class="btn btn-danger btn-sm" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Blocked Item" data-message="{!! trans("laravelblocker::laravelblocker.modals.delete_blocked_message", ["blocked" => $item->slug]) !!}">
                                        {!! trans("laravelblocker::laravelblocker.buttons.delete") !!}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    @include('laravelblocker::modals.modal-delete')

@endsection

@section(config('laravelblocker.blockerBladePlacementJs'))
    @include('laravelblocker::scripts.delete-modal-script')
    @if(config('laravelblocker.tooltipsEnabled'))
        @include('laravelblocker::scripts.tooltips')
    @endif
@endsection
