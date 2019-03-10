<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;
use jeremykenedy\LaravelBlocker\App\Http\Requests\SearchBlockerRequest;

class LaravelBlockerDeletedController extends LaravelBlockerController
{

    /**
     * Get Soft Deleted BlockedItem.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedBlockedItem($id)
    {
        $item = BlockedItem::onlyTrashed()->where('id', $id)->get();
        if (count($item) != 1) {
            return abort(redirect('blocker-deleted')->with('error', trans('laravelblocker::laravelblocker.errors.errorBlockerNotFound')));
        }

        return $item[0];
    }

    /**
     * Show the laravel ip blocker deleted dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (config('laravelblocker.blockerPaginationEnabled')) {
            $blocked = BlockedItem::onlyTrashed()->paginate(config('laravelblocker.blockerPaginationPerPage'));
        } else {
            $blocked = BlockedItem::onlyTrashed()->get();
        }

        return view('laravelblocker::laravelblocker.deleted.index', compact('blocked'));
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
        $item           = self::getDeletedBlockedItem($id);
        $typeDeleted    = 'deleted';

        return view('laravelblocker::laravelblocker.show')
                   ->with(compact('item', 'typeDeleted'));

        // return view('usersmanagement.show-deleted-user')->withUser($user);
    }



    /**
     * Method to search the deleted blocked items.
     *
     * @param SearchBlockerRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(SearchBlockerRequest $request)
    {
        $searchTerm = $request->validated()['blocked_search_box'];
        $results = BlockedItem::onlyTrashed()->where('id', 'like', $searchTerm .'%')->onlyTrashed()
                        ->orWhere('typeId', 'like', $searchTerm .'%')->onlyTrashed()
                        ->orWhere('value', 'like', $searchTerm .'%')->onlyTrashed()
                        ->orWhere('note', 'like', $searchTerm .'%')->onlyTrashed()
                        ->orWhere('userId', 'like', $searchTerm.'%')->onlyTrashed()
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
