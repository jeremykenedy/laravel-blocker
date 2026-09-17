<?php

namespace jeremykenedy\LaravelBlocker\App\Traits;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;

trait LaravelCheckBlockedTrait
{
    use IpAddressDetails;

    /**
     * Check if on laravel blocer list and respond accordingly.
     */
    public static function checkBlocked()
    {
        $requestIp = Request::ip();
        $route = Request::route();
        $blockedItems = BlockedItem::all();
        $blocked = self::checkedBlockedList($requestIp, false, $blockedItems);
        $type = 'ip';

        if (!$blocked) {
            $details = static::checkIP($requestIp);
            foreach (['city', 'state', 'country', 'countryCode', 'continent', 'region'] as $field) {
                if (!empty($details[$field])) {
                    $blocked = self::checkedBlockedList($details[$field], $blocked, $blockedItems);
                }
            }
        }

        if (Request::method() === 'POST' && $route && $route->uri() === 'register') {
            $email = Request::input('email');
            if (is_string($email)) {
                $blocked = self::checkedBlockedList(self::getEmailDomain($email), $blocked, $blockedItems);
                $blocked = self::checkedBlockedList($email, $blocked, $blockedItems);
            }
            $type = 'register';
        }

        if (\Auth::check()) {
            $email = Request::user()->email;
            $blocked = self::checkedBlockedList(self::getEmailDomain($email), $blocked, $blockedItems);
            $blocked = self::checkedBlockedList($email, $blocked, $blockedItems);
            $type = 'auth';
        }

        return self::checkBlockedActions($blocked, $type);
    }

    /**
     * How to responde to a blocked item.
     *
     * @param string $blocked The blocked item
     * @param string $type    The type of blocked item
     */
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

    /**
     * Gets the email domain.
     *
     * @param string $email The email
     *
     * @return string The email domain.
     */
    private static function getEmailDomain($email)
    {
        return is_string($email) && strpos($email, '@') !== false ? substr(strrchr($email, '@'), 1) : '';
    }

    /**
     * { function_description }.
     *
     * @param string $checkAgainst The check against
     * @param bool   $blocked      The blocked
     *
     * @return bool ( description_of_the_return_value )
     */
    private static function checkedBlockedList($checkAgainst, $blocked, $blockedItems)
    {
        foreach ($blockedItems as $blockedItem) {
            if ($blockedItem->value == $checkAgainst) {
                $blocked = true;
                break;
            }
        }

        return $blocked;
    }
}
