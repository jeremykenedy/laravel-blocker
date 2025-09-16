{{ html()->form('POST', route('laravelblocker::blocker.update', $item->id))
        ->attribute('role', 'form')
        ->class('needs-validation')
        ->open() }}
    @csrf
    @method('PUT')
    @include('laravelblocker::forms.partials.item-type-select')
    @include('laravelblocker::forms.partials.item-value-input')
    @include('laravelblocker::forms.partials.item-blocked-user-select')
    @include('laravelblocker::forms.partials.item-note-input')
    <div class="row">
        <div class="col-sm-6 offset-sm-6 mt-1">
            {{ html()->button(trans('laravelblocker::laravelblocker.buttons.save-larger'))
                    ->class('btn btn-success btn-block margin-bottom-1 mb-1 float-right')
                    ->type('submit') }}
        </div>
    </div>
{{ html()->form()->close() }}
<div class="row">
    <div class="col-sm-6 mt-2 mt-sm-0">
        @include('laravelblocker::forms.delete-full')
    </div>
</div>
