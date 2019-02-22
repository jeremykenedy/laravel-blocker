@extends(config('laravelblocker.laravelBlockerBladeExtended'))

@section(config('laravelblocker.laravelBlockerTitleExtended'))
    {!! trans('laravelblocker::laravelblocker.titles.create-blocked') !!}
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
@endsection


@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="{{ $containerClass }} {{ $blockerBootstrapCardClasses }}">
                    <div class="{{ $containerHeaderClass }}">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            {!! trans('laravelblocker::laravelblocker.titles.create-blocked') !!}
                            <div class="pull-right">
                                <a href="{{ url('blocker') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('laravelblocker::laravelblocker.tooltips.back-blocked') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('laravelblocker::laravelblocker.buttons.back-to-blocked') !!}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="{{ $containerBodyClass }}">

                        {!! Form::open(array('route' => 'laravelblocker::blocker.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('blockedType') ? ' has-error ' : '' }}">
                                {!! Form::label('blockedType', "Blocked Type", array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control" name="blockedType" id="blockedType">
                                            <option value="">
                                                Select Blocked Type
                                            </option>
                                            @if ($blockedTypes)
                                                @foreach($blockedTypes as $blockedType)
                                                    <option value="{{ $blockedType->id }}">{{ $blockedType->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="blockedType">
                                                <i class="fa fas fa-fw fa-shield" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('blockedType'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('blockedType') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                                {!! Form::label('name', "Name", array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => "Name")) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="name">
                                                <i class="fa fa-fw fa-pencil" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('slug') ? ' has-error ' : '' }}">
                                {!! Form::label('slug', "Slug", array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('slug', NULL, array('id' => 'slug', 'class' => 'form-control', 'placeholder' => "Slug")) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="slug">
                                                <i class="fa fa-fw fa-key" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('slug'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button("Create New Blocked Item", array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section(config('laravelblocker.blockerBladePlacementJs'))
    @if(config('laravelblocker.tooltipsEnabled'))
        @include('laravelblocker::scripts.tooltips')
    @endif
@endsection
