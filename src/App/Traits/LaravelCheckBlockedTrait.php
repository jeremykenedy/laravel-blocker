<?php

namespace jeremykenedy\LaravelBlocker\App\Traits;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;

trait LaravelCheckBlockedTrait
{
    use IpAddressDetails;

    public static function checkBlocked()
    {
        $requestIp = Request::ip();
        $candidates = [$requestIp];
        $type = 'ip';

        if (self::isRegistrationRequest()) {
            $email = Request::input('email');
            if (is_string($email)) {
                $candidates[] = self::getEmailDomain($email);
                $candidates[] = $email;
            }
            $type = 'register';
        }

        if (\Auth::check()) {
            $email = Request::user()->email;
            $candidates[] = self::getEmailDomain($email);
            $candidates[] = $email;
            $type = 'auth';
        }

        $blocked = self::hasBlockedValue($candidates) || self::hasBlockedLocation($requestIp);

        return self::checkBlockedActions($blocked, $type);
    }

    private static function isRegistrationRequest()
    {
        $route = Request::route();

        return Request::method() === 'POST' && $route && $route->uri() === 'register';
    }

    private static function hasBlockedLocation($ip)
    {
        $details = static::checkIP($ip);
        $locations = [];
        foreach (['city', 'state', 'country', 'countryCode', 'continent', 'region'] as $field) {
            if (!empty($details[$field])) {
                $locations[] = $details[$field];
            }
        }

        return self::hasBlockedValue($locations);
    }

    private static function checkBlockedActions($blocked, $type = null)
    {
        if (!$blocked) {
            return;
        }
        if ($type === 'register') {
            return Redirect::back()->withError('Not allowed');
        }
        switch (config('laravelblocker.blockerDefaultAction')) {
            case 'view':
                abort(response()->view(config('laravelblocker.blockerDefaultActionView')));
                break;

            case 'redirect':
                self::redirectBlockedRequest();
                break;

            default:
                abort(config('laravelblocker.blockerDefaultActionAbortType'));
                break;
        }
    }

    private static function redirectBlockedRequest()
    {
        $currentRoute = Request::route() ? Request::route()->getName() : null;
        $redirectRoute = config('laravelblocker.blockerDefaultActionRedirect');

        if ($currentRoute != $redirectRoute && Request::url() !== url($redirectRoute)) {
            abort(Redirect::to($redirectRoute));
        }
    }

    private static function getEmailDomain($email)
    {
        return is_string($email) && strpos($email, '@') !== false ? substr(strrchr($email, '@'), 1) : '';
    }

    private static function hasBlockedValue(array $candidates)
    {
        if (empty($candidates)) {
            return false;
        }
        $query = BlockedItem::select('value');
        $numeric = array_filter($candidates, function ($value) {
            return !is_string($value) || is_numeric($value);
        });
        if (empty($numeric)) {
            $query->whereIn('value', $candidates);
        }
        foreach ($query->cursor() as $item) {
            if (in_array($item->value, $candidates)) {
                return true;
            }
        }

        return false;
    }
}
