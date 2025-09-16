<div class="form-group has-feedback row">
    {{ html()->label('value', trans('laravelblocker::laravelblocker.forms.blockedValueLabel'))->class('col-md-3 control-label')->id('blockerValueLabel1') }}
    <div class="col-md-9">
        <div class="input-group">
            @php($valueInputClass = $errors->has('value') ? 'form-control is-invalid' : 'form-control')
            @isset($item)
                {{ html()->text('value', old('value', $item->value))
                        ->id('value')
                        ->class($valueInputClass)
                        ->attribute('placeholder', trans('laravelblocker::laravelblocker.forms.blockedValuePH')) }}
            @else
                {{ html()->text('value', old('value'))
                        ->id('value')
                        ->class($valueInputClass)
                        ->attribute('placeholder', trans('laravelblocker::laravelblocker.forms.blockedValuePH')) }}
            @endisset
            <div class="input-group-append">
                <label class="input-group-text" for="value" id="blockerValueLabel2">
                    <i class="fa fa-fw fa-key fa-rotate-90" aria-hidden="true"></i>
                </label>
            </div>
        </div>
        @if ($errors->has('value'))
            <span class="help-block">
                <strong>{{ $errors->first('value') }}</strong>
            </span>
        @endif
    </div>
</div>
