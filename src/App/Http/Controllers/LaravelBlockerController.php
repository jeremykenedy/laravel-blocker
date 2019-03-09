<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;
use jeremykenedy\LaravelBlocker\App\Http\Requests\StoreBlockerRequest;
use jeremykenedy\LaravelBlocker\App\Http\Requests\SearchBlockerRequest;

class LaravelBlockerController extends Controller
{
    private $_authEnabled;
    private $_rolesEnabled;
    private $_rolesMiddlware;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_authEnabled     = config('laravelblocker.authEnabled');
        $this->_rolesEnabled    = config('laravelblocker.rolesEnabled');
        $this->_rolesMiddlware  = config('laravelblocker.rolesMiddlware');

        if ($this->_authEnabled) {
            $this->middleware('auth');
        }

        if ($this->_rolesEnabled) {
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
        if (config('laravelblocker.blockerPaginationEnabled')) {
            $blocked = BlockedItem::paginate(config('laravelblocker.blockerPaginationPerPage'));
        } else {
            $blocked = BlockedItem::all();
        }

        return view('laravelblocker::laravelblocker.index', compact('blocked'));
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

        return view('laravelblocker::laravelblocker.create')
                   ->with(compact('blockedTypes', 'users'));
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
        $validatedData = $request->validated();

        $userId = null;
        if (array_key_exists('userId', $validatedData)) {
            $userId = $validatedData['userId'];
        }

        $blockedItem = BlockedItem::create([
            'typeId'    => $validatedData['typeId'],
            'value'     => $validatedData['value'],
            'note'      => $validatedData['note'],
            'userId'    => $userId,
        ]);

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

        return view('laravelblocker::laravelblocker.show')
                   ->with(compact('item'));
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
        $item = BlockedItem::findOrFail($id);

        return view('laravelblocker::laravelblocker.edit')
                   ->with(compact('item'));
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
        $results    = BlockedItem::where('id', 'like', $searchTerm .'%')
                            ->orWhere('typeId', 'like', $searchTerm .'%')
                            ->orWhere('value', 'like', $searchTerm .'%')
                            ->orWhere('note', 'like', $searchTerm .'%')
                            ->orWhere('userId', 'like', $searchTerm.'%')
                            ->get();

        $results->map(function ($item) {
            $item['type'] = $item->blockedType->slug ;
            return $item;
        });

        return response()->json([
            json_encode($results),
        ], Response::HTTP_OK);
    }

}
