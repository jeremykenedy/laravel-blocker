<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Traits;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;

use Illuminate\Support\Facades\Log;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Http\Traits\IpAddressDetails;

trait LaravelCheckBlockedTrait
{
    use IpAddressDetails;

    /**
     * { function_description }
     *
     * @param      <type>  $check  The check
     */
    public static function checkBlocked($check = null)
    {
        $requestIp          = Request::ip();
        $all                = Request::all();
        $method             = Request::method();
        $route              = Request::route();
        $ipAddressDetails   = IpAddressDetails::checkIP($requestIp);
        $blocked            = false;

        // Check IP
        $blocked = self::checkedBlockedList($requestIp, $blocked);

        // Check Ip Address Details
        if ($ipAddressDetails) {
            // Check City
            $blocked = self::checkedBlockedList($ipAddressDetails["city"], $blocked);

            // Check State
            $blocked = self::checkedBlockedList($ipAddressDetails["state"], $blocked);

            // Check Country
            $blocked = self::checkedBlockedList($ipAddressDetails["country"], $blocked);

            // Check Country Code
            $blocked = self::checkedBlockedList($ipAddressDetails["countryCode"], $blocked);

            // Check Continent
            $blocked = self::checkedBlockedList($ipAddressDetails["continent"], $blocked);

            // Check Continent
            $blocked = self::checkedBlockedList($ipAddressDetails["continent"], $blocked);

            // Check Region
            $blocked = self::checkedBlockedList($ipAddressDetails["region"], $blocked);
        }

        // Registering
        if ($method === "POST" && $route->uri === 'register') {
            $domain_name = self::getEmailDomain($all['email']);
            $blocked = self::checkedBlockedList($domain_name, $blocked);
        }

        // Logged IN
        if (\Auth::check()) {
            $userId         = Request::user()->id;
            $userEmail      = Request::user()->email;
            $domain_name    = self::getEmailDomain($userEmail);
            $blocked        = self::checkedBlockedList($domain_name, $blocked);
        }

        self::checkBlockedActions($blocked);

    }

    /**
     * { function_description }
     *
     * @param      <type>  $blocked  The blocked
     */
    private static function checkBlockedActions($blocked)
    {
        if ($blocked) {




            abort(403);




        }
    }

    /**
     * Gets the email domain.
     *
     * @param      <type>  $email  The email
     *
     * @return     <type>  The email domain.
     */
    private static function getEmailDomain($email)
    {
        return substr(strrchr($email, "@"), 1);
    }

    /**
     * { function_description }
     *
     * @param      <type>   $checkAgainst  The check against
     * @param      boolean  $blocked       The blocked
     *
     * @return     boolean  ( description_of_the_return_value )
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

// $requestIp = "50.38.37.19";
// $requestIp = "192.168.10.1";
// array:12 [▼
//   "city" => "Beaverton"
//   "state" => "Oregon"
//   "country" => "United States"
//   "countryCode" => "US"
//   "continent" => "North America"
//   "continent_code" => "NA"
//   "latitude" => "45.4505"
//   "longitude" => "-122.8652"
//   "currencyCode" => "USD"
//   "areaCode" => ""
//   "dmaCode" => "820"
//   "region" => "Oregon"
// ]
