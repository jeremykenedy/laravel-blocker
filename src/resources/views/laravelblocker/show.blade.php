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
                                {!! trans('laravelblocker::laravelblocker.blocked-items-title') !!}
                            </span>
                        </div>
                    </div>
                    <div class="{{ $containerBodyClass }}">
                        {{ $item->id }} <br/>
                        {{ $item->typeId }} <br/>
                        {!! $item->blockedType->slug !!} <br/>
                        {{ $item->value }} <br/>
                        {{ $item->note }} <br/>
                        @if ($item->userId)
                            {!! $item->userId !!}
                        @else
                            <span class="disabled">
                                {!! trans('laravelblocker::laravelblocker.none') !!}
                            </span>
                        @endif
                        <br/>
                        {!! $item->created_at->format('m/d/Y H:ia') !!} <br/>
                        {!! $item->updated_at->format('m/d/Y H:ia') !!} <br/>
                        <a class="btn btn-sm btn-info btn-block" href="/blocker/{{ $item->id }}/edit" data-toggle="tooltip" title="{{ trans("laravelblocker::laravelblocker.tooltips.edit") }}">
                            {!! trans("laravelblocker::laravelblocker.buttons.edit") !!}
                        </a>
                        <br/>
                        <form method="POST" action="/blocker/{{ $item->id }}" accept-charset="UTF-8" data-toggle="tooltip" title="Delete Blocked Item">
                            {!! Form::hidden("_method", "DELETE") !!}
                            {!! csrf_field() !!}
                            <button class="btn btn-danger btn-sm" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Blocked Item" data-message="{!! trans("laravelblocker::laravelblocker.modals.delete_blocked_message", ["blocked" => $item->slug]) !!}">
                                {!! trans("laravelblocker::laravelblocker.buttons.delete") !!}
                            </button>
                        </form>
                        <br/>
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
