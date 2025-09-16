<div class="form-group has-feedback row">
    {{ html()->label('note', trans('laravelblocker::laravelblocker.forms.blockedNoteLabel'))->class('col-md-3 control-label') }}
    <div class="col-md-9">
        <div class="input-group">
            @php($noteInputClass = $errors->has('note') ? 'form-control is-invalid' : 'form-control')
            @isset($item)
                {{ html()->textarea('note', old('note', $item->note))
                        ->id('note')
                        ->class($noteInputClass)
                        ->attribute('placeholder', trans('laravelblocker::laravelblocker.forms.blockedNotePH')) }}
            @else
                {{ html()->textarea('note', old('note'))
                        ->id('note')
                        ->class($noteInputClass)
                        ->attribute('placeholder', trans('laravelblocker::laravelblocker.forms.blockedNotePH')) }}
            @endisset
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
