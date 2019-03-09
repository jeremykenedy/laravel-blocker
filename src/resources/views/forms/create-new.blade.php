    {!! Form::open(array('route' => 'laravelblocker::blocker.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

        {!! csrf_field() !!}

        <div class="form-group has-feedback row">
            {!! Form::label('typeId', trans('laravelblocker::laravelblocker.forms.blockedTypeLabel'), array('class' => 'col-md-3 control-label')); !!}
            <div class="col-md-9">
                <div class="input-group">
                    <select class="{{ $errors->has('typeId') ? 'custom-select form-control is-invalid ' : 'custom-select form-control' }}" name="typeId" id="typeId" >
                        <option value="">
                            {{ trans('laravelblocker::laravelblocker.forms.blockedTypeSelect') }}
                        </option>
                        @if($blockedTypes)
                            @foreach($blockedTypes as $blockedType)
                                <option value="{{ $blockedType->id }}" data-type="{{ $blockedType->slug }}">{{ $blockedType->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="input-group-append">
                        <label class="input-group-text" for="typeId">
                            <i class="fa fas fa-fw fa-shield" aria-hidden="true"></i>
                        </label>
                    </div>
                </div>
                @if ($errors->has('typeId'))
                    <span class="help-block">
                        <strong>{{ $errors->first('typeId') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group has-feedback row">
            {!! Form::label('value', trans('laravelblocker::laravelblocker.forms.blockedValueLabel'), array('class' => 'col-md-3 control-label', 'id' => 'blockerValueLabel1')); !!}
            <div class="col-md-9">
                <div class="input-group">
                    {!! Form::text('value', NULL, array('id' => 'value', 'class' => $errors->has('value') ? 'form-control is-invalid ' : 'form-control', 'placeholder' => trans('laravelblocker::laravelblocker.forms.blockedValuePH'))) !!}
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

        <div class="form-group has-feedback row">
            {!! Form::label('userId', trans('laravelblocker::laravelblocker.forms.blockedUserLabel'), array('class' => 'col-md-3 control-label disabled', 'id' => 'blockerUserLabel1')); !!}
            <div class="col-md-9">
                <div class="input-group">
                    <select class="{{ $errors->has('userId') ? 'custom-select form-control is-invalid disabled' : 'custom-select form-control disabled' }}" name="userId" id="userId">
                        <option value="">
                            {{ trans('laravelblocker::laravelblocker.forms.blockedUserSelect') }}
                        </option>
                        @if($users)
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-email="{{ $user->email }}">{{ $user->name }}</option>
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

        <div class="form-group has-feedback row">
            {!! Form::label('note', trans('laravelblocker::laravelblocker.forms.blockedNoteLabel'), array('class' => 'col-md-3 control-label')); !!}
            <div class="col-md-9">
                <div class="input-group">
                    {!! Form::textarea('note', NULL, array('id' => 'note', 'class' => $errors->has('note') ? 'form-control is-invalid ' : 'form-control', 'placeholder' => trans('laravelblocker::laravelblocker.forms.blockedNotePH'))) !!}
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

        {!! Form::button("Create New Blocked Item", array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}

    {!! Form::close() !!}
