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
}

class FakeIpDetails
{
    use IpAddressDetails;

    public static $response;

    protected static function lookupIpAddress($ip)
    {
        return static::$response;
    }
}

class RealIpDetails
{
    use IpAddressDetails;
}
