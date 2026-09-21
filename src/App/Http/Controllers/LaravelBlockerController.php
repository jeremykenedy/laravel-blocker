<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use jeremykenedy\LaravelBlocker\App\Http\Requests\SearchBlockerRequest;
use jeremykenedy\LaravelBlocker\App\Http\Requests\StoreBlockerRequest;
use jeremykenedy\LaravelBlocker\App\Http\Requests\UpdateBlockerRequest;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;

class LaravelBlockerController extends Controller
{
    private $_authEnabled;
    private $_rolesEnabled;
    private $_rolesMiddlware;

    protected function blockedItems($deleted = false)
    {
        $query = BlockedItem::with('blockedType');
        if ($deleted) {
            $query->onlyTrashed();
        }
        if (in_array(config('laravelblocker.frontend'), ['bootstrap5', 'tailwind'], true)) {
            $input = request()->validate(['q' => 'nullable|string|max:255']);
            if (config('laravelblocker.enableSearchBlocked') && isset($input['q']) && $input['q'] !== '') {
                $this->filterBlockedItems($query, $input['q']);
            }
        }

        return config('laravelblocker.blockerPaginationEnabled')
            ? $query->paginate(config('laravelblocker.blockerPaginationPerPage'))
            : $query->get();
    }

    protected function filterBlockedItems($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('id', 'like', $term.'%')
                ->orWhere('typeId', 'like', $term.'%')
                ->orWhere('value', 'like', $term.'%')
                ->orWhere('note', 'like', $term.'%')
                ->orWhere('userId', 'like', $term.'%');
        });
    }

    protected function blockerView($view)
    {
        $framework = config('laravelblocker.frontend', 'legacy');

        return 'laravelblocker::'.(in_array($framework, ['bootstrap5', 'tailwind'], true) ? 'modern.' : 'laravelblocker.').$view;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_authEnabled = config('laravelblocker.authEnabled');
        $this->_rolesEnabled = config('laravelblocker.rolesEnabled');
        $this->_rolesMiddlware = config('laravelblocker.rolesMiddlware');

        if ($this->_authEnabled && method_exists($this, 'middleware')) {
            $this->middleware('auth');
        }

        if ($this->_rolesEnabled && method_exists($this, 'middleware')) {
            $this->middleware($this->_rolesMiddlware);
        }
    }

    /**
     * Show the laravel ip email blocker dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blocked = $this->blockedItems();

        $deletedBlockedItems = BlockedItem::onlyTrashed();

        return view($this->blockerView('index'), compact('blocked', 'deletedBlockedItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blockedTypes = BlockedType::all();
        $users = config('laravelblocker.defaultUserModel')::all();

        return view($this->blockerView('create'), compact('blockedTypes', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBlockerRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlockerRequest $request)
    {
        BlockedItem::create($request->blockedFillData());

        return redirect('blocker')
                    ->with('success', trans('laravelblocker::laravelblocker.messages.blocked-creation-success'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = BlockedItem::findOrFail($id);

        return view($this->blockerView('show'), compact('item'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blockedTypes = BlockedType::all();
        $users = config('laravelblocker.defaultUserModel')::all();
        $item = BlockedItem::findOrFail($id);

        return view($this->blockerView('edit'), compact('blockedTypes', 'users', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBlockerRequest $request
     * @param int                  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlockerRequest $request, $id)
    {
        $item = BlockedItem::findOrFail($id);
        $item->fill($request->blockedFillData());
        $item->save();

        return redirect()
                    ->back()
                    ->with('success', trans('laravelblocker::laravelblocker.messages.update-success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blockedItem = BlockedItem::findOrFail($id);
        $blockedItem->delete();

        return redirect('blocker')
                    ->with('success', trans('laravelblocker::laravelblocker.messages.delete-success'));
    }

    /**
     * Method to search the blocked items.
     *
     * @param SearchBlockerRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(SearchBlockerRequest $request)
    {
        $searchTerm = $request->validated()['blocked_search_box'];
        $results = $this->filterBlockedItems(BlockedItem::with('blockedType'), $searchTerm)->get();

        $results->map(function ($item) {
            $item['type'] = $item->blockedType ? $item->blockedType->slug : '';

            return $item;
        });

        return response()->json([
            json_encode($results),
        ], Response::HTTP_OK);
    }
}
