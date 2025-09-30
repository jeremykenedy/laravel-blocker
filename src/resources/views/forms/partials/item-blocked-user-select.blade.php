<div class="form-group has-feedback row">
    {{ html()->label('userId', trans('laravelblocker::laravelblocker.forms.blockedUserLabel'))->class('col-md-3 control-label disabled')->id('blockerUserLabel1') }}
    <div class="col-md-9">
        <div class="input-group">
            <select class="{{ $errors->has('userId') ? 'custom-select form-control is-invalid disabled' : 'custom-select form-control disabled' }}" name="userId" id="userId">
                <option value="">
                    {{ trans('laravelblocker::laravelblocker.forms.blockedUserSelect') }}
                </option>
                @php($oldValue = old('userId', isset($item) ? $item->userId : ''))
                @if($users)
                    @foreach($users as $aUser)
                        <option value="{{ $aUser->id }}" data-email="{{ $aUser->email }}" {{ $oldValue == $aUser->id ? 'selected' : '' }} >
                            {{ $aUser->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <div class="input-group-append">
                <label class="input-group-text disabled" for="userId" id="blockerUserLabel2">
                    <i class="fa fas fa-fw fa-shield" aria-hidden="true"></i>
                </label>
            </div>
        </div>
        @if ($errors->has('userId'))
            <span class="help-block">
                <strong>{{ $errors->first('userId') }}</strong>
            </span>
        @endif
    </div>
</div>
