@extends('laravelblocker::modern.layout')
@section('blocker-content')
    <div class="lb-toolbar"><h2>{{ $item->value }}</h2></div>
    <div class="lb-body">
        <dl class="lb-details">
            @foreach(['id', 'typeId', 'value', 'note', 'userId', 'created_at', 'updated_at', 'deleted_at'] as $field)
                <dt>{{ trans('laravelblocker::laravelblocker.ui.'.$field) }}</dt><dd>{{ $item->$field ?: trans('laravelblocker::laravelblocker.none') }}</dd>
            @endforeach
        </dl>
        <div class="lb-actions">
            @if(isset($typeDeleted))
                @include('laravelblocker::modern.action', ['action' => 'restore', 'routeName' => 'blocker-item-restore', 'method' => 'PUT'])
                @include('laravelblocker::modern.action', ['action' => 'destroy', 'routeName' => 'blocker-item-destroy', 'method' => 'DELETE'])
            @else
                <a class="lb-button" href="{{ route('laravelblocker::blocker.edit', $item->id) }}">{{ trans('laravelblocker::laravelblocker.ui.edit') }}</a>
                @include('laravelblocker::modern.action', ['action' => 'delete', 'routeName' => 'blocker.destroy', 'method' => 'DELETE'])
            @endif
        </div>
    </div>
@endsection
