<?php

namespace ChromaX\GoogleGeocode\Lookup;

use ChromaX\CommonException;

/**
 * Class GeoLocationLookupTest
 *
 * @package ChromaX\GoogleGeocode
 */
class GeoLocationLookupTest extends \PHPUnit_Framework_TestCase
{

	public function testLookupSuccess()
	{
		// Perform lookup
		$geoLocationLookup = new GeoLocationLookup();
		$geoLocationLookup->lookup(54.334123, 10.1364007);

		// Validate results
		$this->assertEquals(7, $geoLocationLookup->getResultCount());
		$firstResult = $geoLocationLookup->getFirstResult();
		$this->assertInstanceOf('ChromaX\\GoogleGeocode\\Result\\GeoLookupResult', $firstResult);

		// Address result
		$this->assertTrue($firstResult->hasAddress());
		$addressResult = $firstResult->getAddress();
		$this->assertTrue($addressResult->hasStreetNumber());
		$this->assertEquals('43', $addressResult->getStreetNumber()->getShortName());
		$this->assertEquals('43', $addressResult->getStreetNumber()->getLongName());
		$this->assertTrue($addressResult->hasStreetName());
		$this->assertEquals('Lornsenstraße', $addressResult->getStreetName()->getShortName());
		$this->assertEquals('Lornsenstraße', $addressResult->getStreetName()->getLongName());
		$this->assertTrue($addressResult->hasCity());
		$this->assertEquals('KI', $addressResult->getCity()->getShortName());
		$this->assertEquals('Kiel', $addressResult->getCity()->getLongName());
		$this->assertTrue($addressResult->hasPostalCode());
		$this->assertEquals('24105', $addressResult->getPostalCode()->getShortName());
		$this->assertEquals('24105', $addressResult->getPostalCode()->getLongName());
		$this->assertTrue($addressResult->hasArea());
		$this->assertEquals('Ravensberg - Brunswik - Düsternbrook', $addressResult->getArea()->getShortName());
		$this->assertEquals('Ravensberg - Brunswik - Düsternbrook', $addressResult->getArea()->getLongName());
		$this->assertTrue($addressResult->hasProvince());
		$this->assertEquals('SH', $addressResult->getProvince()->getShortName());
		$this->assertEquals('Schleswig-Holstein', $addressResult->getProvince()->getLongName());
		$this->assertTrue($addressResult->hasCountry());
		$this->assertEquals('DE', $addressResult->getCountry()->getShortName());
		$this->assertEquals('Germany', $addressResult->getCountry()->getLongName());

		// Geometry result
		$this->assertTrue($firstResult->hasGeometry());
		$geometryResult = $firstResult->getGeometry();
		$this->assertTrue($geometryResult->hasLocation());
		$this->assertGreaterThanOrEqual(54.3, $geometryResult->getLocation()->getLatitude());
		$this->assertLessThanOrEqual(54.4, $geometryResult->getLocation()->getLatitude());
		$this->assertGreaterThanOrEqual(10.1, $geometryResult->getLocation()->getLongitude());
		$this->assertLessThanOrEqual(10.2, $geometryResult->getLocation()->getLongitude());
		$this->assertTrue($geometryResult->hasViewport());
		$this->assertGreaterThanOrEqual(54.3, $geometryResult->getViewport()->getNortheast()->getLatitude());
		$this->assertLessThanOrEqual(54.4, $geometryResult->getViewport()->getNortheast()->getLatitude());
		$this->assertGreaterThanOrEqual(10.1, $geometryResult->getViewport()->getNortheast()->getLongitude());
		$this->assertLessThanOrEqual(10.2, $geometryResult->getViewport()->getNortheast()->getLongitude());
		$this->assertGreaterThanOrEqual(54.3, $geometryResult->getViewport()->getSouthwest()->getLatitude());
		$this->assertLessThanOrEqual(54.4, $geometryResult->getViewport()->getSouthwest()->getLatitude());
		$this->assertGreaterThanOrEqual(10.1, $geometryResult->getViewport()->getSouthwest()->getLongitude());
		$this->assertLessThanOrEqual(10.2, $geometryResult->getViewport()->getSouthwest()->getLongitude());
		$this->assertFalse($geometryResult->hasAccessPoints());

		// Google Places ID
		$this->assertTrue($firstResult->hasGooglePlacesId());
		$this->assertEquals('ChIJ_zNzWmpWskcRP8DWT5eX5jQ', $firstResult->getGooglePlacesId());
	}

	public function testLookupFailure()
	{
		$this->setExpectedException(get_class(new CommonException\ApiException\NoResultException()));
		$geoLocationLookup = new GeoLocationLookup();
		$geoLocationLookup->lookup(1000000, 1000000);
	}

}
