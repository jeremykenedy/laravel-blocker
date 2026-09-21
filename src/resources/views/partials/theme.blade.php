@if(in_array(config('laravelblocker.theme'), ['dark', 'system'], true))
    @include('laravelblocker::modern.styles')
    <link rel="stylesheet" href="{{ route('laravelblocker::assets', 'legacy.css') }}">
@endif
