<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return View('laravelblocker::laravelblocker.index');
    }
}


