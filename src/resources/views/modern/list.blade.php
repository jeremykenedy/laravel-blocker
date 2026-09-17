<div class="lb-toolbar">
    <div>
        <h2>{{ trans('laravelblocker::laravelblocker.ui.'.($deleted ? 'deleted' : 'active')) }}</h2>
        <p class="lb-muted">{{ trans('laravelblocker::laravelblocker.ui.list_help') }}</p>
    </div>
    @if(config('laravelblocker.enableSearchBlocked'))
        <form method="GET" class="lb-search" action="{{ route('laravelblocker::'.($deleted ? 'blocker-deleted' : 'blocker.index')) }}">
            <label for="blocker-search" class="lb-muted">{{ trans('laravelblocker::laravelblocker.ui.search') }}</label>
            <input id="blocker-search" type="search" name="q" maxlength="255" value="{{ request('q') }}" class="lb-input">
            <button class="lb-button" type="submit">{{ trans('laravelblocker::laravelblocker.ui.search') }}</button>
        </form>
    @endif
</div>
<div class="lb-table-wrap" role="region" aria-label="{{ trans('laravelblocker::laravelblocker.ui.active') }}" tabindex="0">
    <table class="{{ config('laravelblocker.frontend') === 'bootstrap5' ? 'table align-middle' : 'w-full text-left text-sm' }}">
        <caption>{{ trans_choice('laravelblocker::laravelblocker.blocked-table.caption', 1, ['blockedcount' => $blocked->count()]) }}</caption>
        <thead><tr>
            @foreach(['type', 'value', 'note', 'actions'] as $column)
                <th scope="col">{{ trans('laravelblocker::laravelblocker.blocked-table.'.$column) }}</th>
            @endforeach
        </tr></thead>
        <tbody>
        @forelse($blocked as $item)
            <tr>
                <td>{{ $item->blockedType ? $item->blockedType->name : $item->typeId }}</td>
                <td><a href="{{ route('laravelblocker::'.($deleted ? 'blocker-item-show-deleted' : 'blocker.show'), $item->id) }}">{{ $item->value }}</a></td>
                <td>{{ $item->note }}</td>
                <td><div class="lb-actions">
                    @if($deleted)
                        @include('laravelblocker::modern.action', ['action' => 'restore', 'routeName' => 'blocker-item-restore', 'method' => 'PUT'])
                        @include('laravelblocker::modern.action', ['action' => 'destroy', 'routeName' => 'blocker-item-destroy', 'method' => 'DELETE'])
                    @else
                        <a href="{{ route('laravelblocker::blocker.edit', $item->id) }}">{{ trans('laravelblocker::laravelblocker.ui.edit') }}</a>
                        @include('laravelblocker::modern.action', ['action' => 'delete', 'routeName' => 'blocker.destroy', 'method' => 'DELETE'])
                    @endif
                </div></td>
            </tr>
        @empty
            <tr><td colspan="4"><div class="lb-empty">{{ trans('laravelblocker::laravelblocker.ui.empty') }}</div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@if(config('laravelblocker.blockerPaginationEnabled'))
    <div class="lb-body">{{ $blocked->appends(['q' => request('q')])->links('laravelblocker::modern.pagination') }}</div>
@endif
@if($deleted && $blocked->count())
    <div class="lb-body lb-actions">
        @include('laravelblocker::modern.action', ['action' => 'restore_all', 'routeName' => 'blocker-deleted-restore-all', 'method' => 'POST', 'bulk' => true])
        @include('laravelblocker::modern.action', ['action' => 'destroy_all', 'routeName' => 'destroy-all-blocked', 'method' => 'DELETE', 'bulk' => true])
    </div>
@endif
