<?php

namespace jeremykenedy\LaravelBlocker\App\Traits;

trait IpAddressDetails
{
    /**
     * Get the Location of the IP Address.
     *
     * @param string $ip          (optional, no value will always return NULL)
     * @param string $purpose     (optional)
     * @param bool   $deep_detect (optional)
     *
     * @return array|string|null
     */
    public static function checkIP($ip = null, $purpose = 'location', $deep_detect = true)
    {
        $ip = static::detectBlockerIpAddress($ip, $deep_detect);
        $purpose = str_replace(['name', "\n", "\t", ' ', '-', '_'], '', strtolower(trim($purpose)));
        if (!filter_var($ip, FILTER_VALIDATE_IP) || !in_array($purpose, ['country', 'countrycode', 'state', 'region', 'city', 'location', 'address'])) {
            return null;
        }
        $ipdat = static::lookupIpAddress($ip);
        if (!is_object($ipdat) || strlen(trim((string) ($ipdat->geoplugin_countryCode ?? ''))) !== 2) {
            return null;
        }

        return static::formatBlockerIpDetails($ipdat, $purpose);
    }

    private static function detectBlockerIpAddress($ip, $deepDetect)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        if ($deepDetect) {
            foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP'] as $header) {
                if (filter_var($_SERVER[$header] ?? null, FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER[$header];
                }
            }
        }

        return $ip;
    }

    private static function formatBlockerIpDetails(object $ipdat, $purpose)
    {
        if ($purpose === 'location') {
            return static::formatBlockerLocation($ipdat);
        }
        if ($purpose === 'address') {
            return static::formatBlockerAddress($ipdat);
        }
        $fields = [
            'city'        => 'geoplugin_city',
            'state'       => 'geoplugin_regionName',
            'region'      => 'geoplugin_regionName',
            'country'     => 'geoplugin_countryName',
            'countrycode' => 'geoplugin_countryCode',
        ];

        return $ipdat->{$fields[$purpose]} ?? null;
    }

    private static function formatBlockerAddress(object $ipdat)
    {
        $address = [$ipdat->geoplugin_countryName ?? null];
        foreach (['geoplugin_regionName', 'geoplugin_city'] as $field) {
            if (strlen((string) ($ipdat->$field ?? '')) >= 1) {
                $address[] = $ipdat->$field;
            }
        }

        return implode(', ', array_reverse($address));
    }

    private static function formatBlockerLocation(object $ipdat)
    {
        $continents = [
            'AF' => 'Africa',
            'AN' => 'Antarctica',
            'AS' => 'Asia',
            'EU' => 'Europe',
            'OC' => 'Australia (Oceania)',
            'NA' => 'North America',
            'SA' => 'South America',
        ];

        $fields = [
            'city'           => 'geoplugin_city',
            'state'          => 'geoplugin_regionName',
            'country'        => 'geoplugin_countryName',
            'countryCode'    => 'geoplugin_countryCode',
            'continent'      => 'geoplugin_continentCode',
            'continent_code' => 'geoplugin_continentCode',
            'latitude'       => 'geoplugin_latitude',
            'longitude'      => 'geoplugin_longitude',
            'currencyCode'   => 'geoplugin_currencyCode',
            'areaCode'       => 'geoplugin_areaCode',
            'dmaCode'        => 'geoplugin_dmaCode',
            'region'         => 'geoplugin_region',
        ];
        $location = [];
        foreach ($fields as $key => $field) {
            $location[$key] = $ipdat->$field ?? null;
        }
        $location['continent'] = $continents[strtoupper($ipdat->geoplugin_continentCode ?? '')] ?? null;

        return $location;
    }

    protected static function lookupIpAddress($ip)
    {
        $url = config('laravelblocker.geolocationUrl', 'http://www.geoplugin.net/json.gp');
        if (!is_string($url) || !in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            return null;
        }
        $url .= (strpos($url, '?') === false ? '?' : '&').'ip='.rawurlencode($ip);
        $timeout = max(0.1, (float) config('laravelblocker.geolocationTimeout', 2));
        $context = stream_context_create(['http' => ['timeout' => $timeout]]);
        $response = @file_get_contents($url, false, $context);

        return $response === false ? null : json_decode($response);
    }
}
