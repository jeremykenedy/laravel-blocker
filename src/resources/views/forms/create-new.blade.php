<form action="{{ route('laravelblocker::blocker.store') }}" method="POST" role="form" class="needs-validation">
    @csrf
    @include('laravelblocker::forms.partials.item-type-select')
    @include('laravelblocker::forms.partials.item-value-input')
    @include('laravelblocker::forms.partials.item-blocked-user-select')
    @include('laravelblocker::forms.partials.item-note-input')
    <div class="row">
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-success btn-block margin-bottom-1 mb-1 float-right" type="submit">
                {{ trans('laravelblocker::laravelblocker.buttons.create-larger') }}
            </button>
        </div>
    </div>
</form>
