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
        $route = Request::route();
        $candidates = [$requestIp];
        $type = 'ip';

        if (Request::method() === 'POST' && $route && $route->uri() === 'register') {
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

        $blocked = self::hasBlockedValue($candidates);
        if (!$blocked) {
            $details = static::checkIP($requestIp);
            $locations = [];
            foreach (['city', 'state', 'country', 'countryCode', 'continent', 'region'] as $field) {
                if (!empty($details[$field])) {
                    $locations[] = $details[$field];
                }
            }
            $blocked = self::hasBlockedValue($locations);
        }

        return self::checkBlockedActions($blocked, $type);
    }

    private static function checkBlockedActions($blocked, $type = null)
    {
        if ($blocked) {
            switch ($type) {
                case 'register':
                    return Redirect::back()->withError('Not allowed');
                    break;

                case 'auth':
                case 'ip':
                default:
                    switch (config('laravelblocker.blockerDefaultAction')) {
                        case 'view':
                            abort(response()->view(config('laravelblocker.blockerDefaultActionView')));
                            break;

                        case 'redirect':
                            $currentRoute = Request::route() ? Request::route()->getName() : null;
                            $redirectRoute = config('laravelblocker.blockerDefaultActionRedirect');

                            if ($currentRoute != $redirectRoute && Request::url() !== url($redirectRoute)) {
                                abort(redirect($redirectRoute));
                            }
                            break;

                        case 'abort':
                        default:
                            abort(config('laravelblocker.blockerDefaultActionAbortType'));
                            break;
                    }
                    break;
            }
        }
    }

    private static function getEmailDomain($email)
    {
        return is_string($email) && strpos($email, '@') !== false ? substr(strrchr($email, '@'), 1) : '';
    }

    private static function hasBlockedValue(array $candidates)
    {
        if (!$candidates) {
            return false;
        }
        $query = BlockedItem::select('value');
        $numeric = array_filter($candidates, function ($value) {
            return !is_string($value) || is_numeric($value);
        });
        if (!$numeric) {
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
