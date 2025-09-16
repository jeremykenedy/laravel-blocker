<div class="row" id="search_blocked_form">
    <div class="col-sm-8 offset-sm-4 col-md-6 offset-md-6 col-lg-5 offset-lg-7 col-xl-4 offset-xl-8">
        {{ html()->form('POST', route('laravelblocker::search-blocked'))
                ->attribute('role', 'form')
                ->class('needs-validation')
                ->id('search_blocked')
                ->open() }}
            @csrf
            <div class="input-group mb-3">
                {{ html()->text('blocked_search_box')
                        ->id('blocked_search_box')
                        ->class('form-control')
                        ->attribute('placeholder', trans('laravelblocker::laravelblocker.forms.search-blocked-ph'))
                        ->attribute('aria-label', trans('laravelblocker::forms.search-users-ph')) }}
                <div class="input-group-append">
                    <a href="#" class="btn btn-warning clear-search" style="display: none;" data-toggle="tooltip" title="{!! trans('laravelblocker::laravelblocker.tooltips.clear-search') !!}">
                        @if(config('laravelblocker.blockerEnableFontAwesomeCDN'))
                            <i class="fa fas fa-times mt-1" aria-hidden="true">
                                <span class="sr-only">
                                    {!! trans('laravelblocker::laravelblocker.tooltips.clear-search') !!}
                                </span>

                            </i>
                        @else
                            {!! trans('laravelblocker::laravelblocker.tooltips.clear-search') !!}
                        @endif
                    </a>
                    @if(config('laravelblocker.blockerEnableFontAwesomeCDN'))
                        {{ html()->button()
                                ->class('btn btn-secondary')
                                ->type('submit')
                                ->attribute('data-toggle', 'tooltip')
                                ->attribute('data-placement', 'bottom')
                                ->attribute('title', trans('laravelblocker::laravelblocker.tooltips.submit-search'))
                                ->html('<i class="fa fas fa-search" aria-hidden="true"></i> <span class="sr-only"> ' . trans('laravelblocker::laravelblocker.tooltips.submit-search') . ' </span>') }}
                    @else
                        {{ html()->button(trans('laravelblocker::laravelblocker.tooltips.submit-search'))
                                ->class('btn btn-secondary')
                                ->type('submit')
                                ->attribute('title', trans('laravelblocker::laravelblocker.tooltips.submit-search')) }}
                    @endif
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
