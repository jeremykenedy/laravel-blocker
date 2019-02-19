@extends(config('laravelblocker.laravelBlockerBladeExtended'))

@php
    switch (config('laravelblocker.bootstapVersion')) {
        case '4':
            $containerClass = 'card';
            $containerHeaderClass = 'card-header';
            $containerBodyClass = 'card-body';
            break;
        case '3':
        default:
            $containerClass = 'panel panel-default';
            $containerHeaderClass = 'panel-heading';
            $containerBodyClass = 'panel-body';
    }
    $bootstrapCardClasses = (is_null(config('laravelblocker.bootstrapCardClasses')) ? '' : config('laravelblocker.bootstrapCardClasses'));
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="{{ $containerClass }} {{ $bootstrapCardClasses }}">
                    <div class="{{ $containerHeaderClass }}">

                        Title

                    </div>
                    <div class="{{ $containerBodyClass }}">

                        Body


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
