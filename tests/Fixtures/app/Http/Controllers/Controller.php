<?php

namespace App\Http\Controllers;

if (getenv('BLOCKER_PLAIN_CONTROLLER')) {
    class Controller
    {
    }
} else {
    class Controller extends \Illuminate\Routing\Controller
    {
    }
}
