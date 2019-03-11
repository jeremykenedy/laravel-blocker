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
        $all = Request::all();
        $method = Request::method();
        $route = Request::route();
        $ipAddressDetails = IpAddressDetails::checkIP($requestIp);
        $blocked = false;
        $type = null;

        // Check IP
        $blocked = self::checkedBlockedList($requestIp, $blocked);

        // Check Ip Address Details
        if ($ipAddressDetails) {
            // Check City
            $blocked = self::checkedBlockedList($ipAddressDetails['city'], $blocked);

            // Check State
            $blocked = self::checkedBlockedList($ipAddressDetails['state'], $blocked);

            // Check Country
            $blocked = self::checkedBlockedList($ipAddressDetails['country'], $blocked);

            // Check Country Code
            $blocked = self::checkedBlockedList($ipAddressDetails['countryCode'], $blocked);

            // Check Continent
            $blocked = self::checkedBlockedList($ipAddressDetails['continent'], $blocked);

            // Check Continent
            $blocked = self::checkedBlockedList($ipAddressDetails['continent'], $blocked);

            // Check Region
            $blocked = self::checkedBlockedList($ipAddressDetails['region'], $blocked);

            $type = 'ip';
        }

        // Registering
        if ($method === 'POST' && $route->uri === 'register') {
            $domain_name = self::getEmailDomain($all['email']);
            $blocked = self::checkedBlockedList($domain_name, $blocked);
            $blocked = self::checkedBlockedList($all['email'], $blocked);
            $type = 'register';
        }

        // Logged IN
        if (\Auth::check()) {
            $userId = Request::user()->id;
            $userEmail = Request::user()->email;
            $domain_name = self::getEmailDomain($userEmail);
            $blocked = self::checkedBlockedList($domain_name, $blocked);
            $blocked = self::checkedBlockedList($userEmail, $blocked);
            $type = 'auth';
        }

        self::checkBlockedActions($blocked, $type);
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
                            $currentRoute = Request::route()->getName();
                            $redirectRoute = config('laravelblocker.blockerDefaultActionRedirect');

                            if ($currentRoute != $redirectRoute) {
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
        return substr(strrchr($email, '@'), 1);
    }

    /**
     * { function_description }.
     *
     * @param string $checkAgainst The check against
     * @param bool   $blocked      The blocked
     *
     * @return bool ( description_of_the_return_value )
     */
    private static function checkedBlockedList($checkAgainst, $blocked)
    {
        $blockedItems = BlockedItem::all();

        foreach ($blockedItems as $blockedItems) {
            if ($blockedItems->value == $checkAgainst) {
                $blocked = true;
            }
        }

        return $blocked;
    }
}
