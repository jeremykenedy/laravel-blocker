<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use jeremykenedy\LaravelBlocker\App\Models\Blocked;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;

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
            $blocked = Blocked::paginate(config('laravelblocker.blockerPaginationPerPage'));
        } else {
            $blocked = Blocked::all();
        }
        $blockedTypes = BlockedType::all();

        return View('laravelblocker::laravelblocker.index', compact('blocked', 'blockedTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blockedTypes = BlockedType::all();

        return view('laravelblocker::laravelblocker.create')->with(compact('blockedTypes'));
    }

}
