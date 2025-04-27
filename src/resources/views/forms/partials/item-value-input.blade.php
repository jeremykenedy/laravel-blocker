<div class="form-group has-feedback row">
    <label for="value" class="col-md-3 control-label" id="blockerValueLabel1">{{ trans('laravelblocker::laravelblocker.forms.blockedValueLabel') }}</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" name="value" id="value" class="{{ $errors->has('value') ? 'form-control is-invalid' : 'form-control' }}" placeholder="{{ trans('laravelblocker::laravelblocker.forms.blockedValuePH') }}" value="@isset($item){{ $item->value }}@endisset">
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
