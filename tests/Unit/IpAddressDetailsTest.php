<?php

namespace jeremykenedy\LaravelBlocker\Tests\Unit;

use jeremykenedy\LaravelBlocker\App\Traits\IpAddressDetails;
use jeremykenedy\LaravelBlocker\Tests\TestCase;

class IpAddressDetailsTest extends TestCase
{
    public function test_location_fields_and_legacy_purpose_aliases(): void
    {
        FakeIpDetails::$response = (object) [
            'geoplugin_city'          => 'Paris',
            'geoplugin_regionName'    => 'Ile-de-France',
            'geoplugin_countryName'   => 'France',
            'geoplugin_countryCode'   => 'FR',
            'geoplugin_continentCode' => 'EU',
            'geoplugin_region'        => 'IDF',
        ];
        foreach (['city' => 'Paris', 'state' => 'Ile-de-France', 'region' => 'Ile-de-France', 'country name' => 'France', 'country_code' => 'FR', 'address' => 'Paris, Ile-de-France, France'] as $purpose => $expected) {
            $this->assertSame($expected, FakeIpDetails::checkIP('203.0.113.1', $purpose));
        }
        $location = FakeIpDetails::checkIP('2001:db8::1');
        $this->assertSame('Europe', $location['continent']);
        $this->assertSame('IDF', $location['region']);
        $this->assertNull($location['latitude']);
    }

    public function test_invalid_and_unavailable_responses_return_no_location(): void
    {
        foreach ([null, false, [], (object) [], (object) ['geoplugin_countryCode' => '']] as $response) {
            FakeIpDetails::$response = $response;
            $this->assertNull(FakeIpDetails::checkIP('203.0.113.1'));
        }
        $this->assertNull(FakeIpDetails::checkIP('203.0.113.1', 'unsupported'));
        config(['laravelblocker.geolocationUrl' => 'file:///etc/passwd']);
        $this->assertNull(RealIpDetails::checkIP('203.0.113.1'));
    }

    public function test_ip_detection_preserves_header_precedence_and_explicit_addresses(): void
    {
        $server = $_SERVER;
        FakeIpDetails::$response = (object) ['geoplugin_countryCode' => 'FR'];
        $_SERVER['REMOTE_ADDR'] = '203.0.113.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '203.0.113.2';
        $_SERVER['HTTP_CLIENT_IP'] = '203.0.113.3';

        try {
            FakeIpDetails::checkIP(null);
            $this->assertSame('203.0.113.3', FakeIpDetails::$ip);
            FakeIpDetails::checkIP(null, 'location', false);
            $this->assertSame('203.0.113.1', FakeIpDetails::$ip);
            FakeIpDetails::checkIP('2001:db8::1');
            $this->assertSame('2001:db8::1', FakeIpDetails::$ip);
            $_SERVER['HTTP_CLIENT_IP'] = 'invalid';
            FakeIpDetails::checkIP(null);
            $this->assertSame('203.0.113.2', FakeIpDetails::$ip);
            $_SERVER['HTTP_X_FORWARDED_FOR'] = '203.0.113.2, 203.0.113.4';
            FakeIpDetails::checkIP(null);
            $this->assertSame('203.0.113.1', FakeIpDetails::$ip);
            unset($_SERVER['REMOTE_ADDR']);
            $this->assertNull(FakeIpDetails::checkIP(null));
        } finally {
            $_SERVER = $server;
        }
    }

    public function test_partial_location_responses_keep_missing_fields_and_zero_values(): void
    {
        FakeIpDetails::$response = (object) ['geoplugin_countryCode' => 'FR', 'geoplugin_countryName' => 'France', 'geoplugin_city' => '0'];
        $location = FakeIpDetails::checkIP('203.0.113.1');
        $this->assertSame(['city', 'state', 'country', 'countryCode', 'continent', 'continent_code', 'latitude', 'longitude', 'currencyCode', 'areaCode', 'dmaCode', 'region'], array_keys($location));
        $this->assertNull($location['continent']);
        $this->assertNull($location['state']);
        $this->assertSame('0', $location['city']);
        $this->assertSame('0, France', FakeIpDetails::checkIP('203.0.113.1', 'address'));
    }
}

class FakeIpDetails
{
    use IpAddressDetails;

    public static $response;

    public static $ip;

    protected static function lookupIpAddress($ip)
    {
        static::$ip = $ip;

        return static::$response;
    }
}

class RealIpDetails
{
    use IpAddressDetails;
}
