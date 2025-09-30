<div class="form-group has-feedback row">
    {{ html()->label('note', trans('laravelblocker::laravelblocker.forms.blockedNoteLabel'))->class('col-md-3 control-label') }}
    <div class="col-md-9">
        <div class="input-group">
            @php($oldValue = old('note', isset($item) ? $item->note : ''))
            @php($noteInputClass = $errors->has('note') ? 'form-control is-invalid' : 'form-control')
            {{ html()->textarea('note', $oldValue)
                    ->id('note')
                    ->class($noteInputClass)
                    ->attribute('placeholder', trans('laravelblocker::laravelblocker.forms.blockedNotePH')) }}
            <div class="input-group-append">
                <label class="input-group-text" for="note">
                    <i class="fa fa-fw fa-pencil" aria-hidden="true"></i>
                </label>
            </div>
        </div>
        @if ($errors->has('note'))
            <span class="help-block">
                <strong>{{ $errors->first('note') }}</strong>
            </span>
        @endif
    </div>
</div>
