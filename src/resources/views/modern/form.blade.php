<div class="lb-toolbar"><h2>{{ trans('laravelblocker::laravelblocker.ui.'.($editing ? 'edit' : 'create')) }}</h2></div>
<form class="lb-body" method="POST" action="{{ $editing ? route('laravelblocker::blocker.update', $item->id) : route('laravelblocker::blocker.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif
    <div class="lb-field">
        <label for="typeId">{{ trans('laravelblocker::laravelblocker.ui.type') }}</label>
        <select id="typeId" name="typeId" required class="lb-input {{ config('laravelblocker.frontend') === 'bootstrap5' ? 'form-select' : 'w-full rounded-lg border p-3' }}" aria-invalid="{{ $errors->has('typeId') ? 'true' : 'false' }}">
            <option value="">{{ trans('laravelblocker::laravelblocker.ui.select_type') }}</option>
            @foreach($blockedTypes as $blockedType)
                <option value="{{ $blockedType->id }}" data-type="{{ $blockedType->slug }}" {{ old('typeId', $editing ? $item->typeId : '') == $blockedType->id ? 'selected' : '' }}>{{ $blockedType->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="lb-field">
        <label for="value">{{ trans('laravelblocker::laravelblocker.ui.value') }}</label>
        <input id="value" name="value" required maxlength="255" value="{{ old('value', $editing ? $item->value : '') }}" class="lb-input {{ config('laravelblocker.frontend') === 'bootstrap5' ? 'form-control' : 'w-full rounded-lg border p-3' }}" aria-invalid="{{ $errors->has('value') ? 'true' : 'false' }}">
    </div>
    <div class="lb-field">
        <label for="userId">{{ trans('laravelblocker::laravelblocker.ui.user') }}</label>
        <select id="userId" name="userId" class="lb-input">
            <option value="">{{ trans('laravelblocker::laravelblocker.none') }}</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" data-email="{{ $user->email }}" {{ old('userId', $editing ? $item->userId : '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="lb-field">
        <label for="note">{{ trans('laravelblocker::laravelblocker.ui.note') }}</label>
        <textarea id="note" name="note" rows="4" maxlength="500" class="lb-input" aria-invalid="{{ $errors->has('note') ? 'true' : 'false' }}">{{ old('note', $editing ? $item->note : '') }}</textarea>
    </div>
    <div class="lb-actions">
        <button type="submit" class="lb-button lb-primary">{{ trans('laravelblocker::laravelblocker.ui.save') }}</button>
        <a href="{{ route('laravelblocker::blocker.index') }}">{{ trans('laravelblocker::laravelblocker.ui.cancel') }}</a>
    </div>
</form>
