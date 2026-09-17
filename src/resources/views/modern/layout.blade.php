@extends(config('laravelblocker.laravelBlockerBladeExtended'))

@section(config('laravelblocker.laravelBlockerTitleExtended'), trans('laravelblocker::laravelblocker.ui.title'))

@section('content')
    @include('laravelblocker::modern.styles')
    <div class="lb-app {{ config('laravelblocker.frontend') === 'tailwind' ? 'mx-auto max-w-6xl px-4 py-8' : 'container py-4' }}" data-blocker-root data-theme="{{ config('laravelblocker.theme', 'light') }}">
        <header class="lb-header">
            <div>
                <p class="lb-eyebrow">{{ trans('laravelblocker::laravelblocker.ui.access') }}</p>
                <h1>{{ trans('laravelblocker::laravelblocker.ui.title') }}</h1>
                <p class="lb-muted">{{ trans('laravelblocker::laravelblocker.ui.description') }}</p>
            </div>
            <label class="lb-theme-label">{{ trans('laravelblocker::laravelblocker.ui.appearance') }}
                <select data-blocker-theme class="lb-input {{ config('laravelblocker.frontend') === 'bootstrap5' ? 'form-select' : 'rounded-lg border px-3 py-2' }}">
                    @foreach(['light', 'dark', 'system'] as $theme)
                        <option value="{{ $theme }}" {{ config('laravelblocker.theme', 'light') === $theme ? 'selected' : '' }}>{{ trans('laravelblocker::laravelblocker.ui.'.$theme) }}</option>
                    @endforeach
                </select>
            </label>
        </header>
        <nav class="lb-nav" aria-label="{{ trans('laravelblocker::laravelblocker.ui.navigation') }}">
            <a href="{{ route('laravelblocker::blocker.index') }}">{{ trans('laravelblocker::laravelblocker.ui.active') }}</a>
            <a href="{{ route('laravelblocker::blocker-deleted') }}">{{ trans('laravelblocker::laravelblocker.ui.deleted') }}</a>
            <a class="lb-button lb-primary {{ config('laravelblocker.frontend') === 'bootstrap5' ? 'btn btn-primary' : 'inline-flex rounded-lg px-4 py-2 font-medium' }}" href="{{ route('laravelblocker::blocker.create') }}">{{ trans('laravelblocker::laravelblocker.ui.create') }}</a>
        </nav>
        @if(config('laravelblocker.blockerFlashMessagesEnabled'))
            @foreach(['success', 'error'] as $status)
                @if(session($status))
                    <div class="lb-alert" role="status">{{ session($status) }}</div>
                @endif
            @endforeach
        @endif
        @if($errors->any())
            <div class="lb-alert lb-error" role="alert">
                <p>{{ trans('laravelblocker::laravelblocker.ui.validation') }}</p>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <section class="lb-card {{ config('laravelblocker.frontend') === 'bootstrap5' ? 'card' : 'rounded-xl border shadow-sm' }}">
            @yield('blocker-content')
        </section>
        @include('laravelblocker::modern.scripts')
    </div>
@endsection
