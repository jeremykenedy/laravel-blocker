@extends(config('laravelblocker.laravelBlockerBladeExtended'))

@section(config('laravelblocker.laravelBlockerTitleExtended'))
    {!! trans('laravelblocker::laravelblocker.titles.show-blocked-item') !!}
@endsection

@php
    switch (config('laravelblocker.blockerBootstapVersion')) {
        case '4':
            $containerClass = 'card';
            $containerHeaderClass = 'card-header bg-warning text-white';
            $containerBodyClass = 'card-body';
            break;
        case '3':
        default:
            $containerClass = 'panel panel-warning';
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
                                <a href="{{ url('blocker') }}" class="btn btn-warning text-white btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('laravelblocker::laravelblocker.tooltips.back-blocked') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('laravelblocker::laravelblocker.buttons.back-to-blocked') !!}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="{{ $containerBodyClass }}">
                        @include('laravelblocker::forms.edit-form')
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
    @include('laravelblocker::scripts.blocked-form')
@endsection
