<?php

namespace spec\Zenter\Api\v1;

use Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RecipientEndpointSpec extends ObjectBehavior
{
	function let($object)
	{
		$object->beADoubleOf('Zenter\Api\v1\CurlHttpClient');

		$this->beConstructedWith($object);

	}
	function it_is_initializable()
	{
		$this->shouldHaveType('Zenter\Api\v1\RecipientEndpoint');
	}


	public function it_throws_error_if_invalid_data_is_passed_into_GetByTrackingId()
	{

		$this->shouldThrow(new Exception('Tracking id invalid'))->during('GetByTrackingId', [""]);
		$this->shouldThrow(new Exception('Tracking id invalid'))->during('GetByTrackingId', [true]);
		$this->shouldThrow(new Exception('Tracking id invalid'))->during('GetByTrackingId', ["ble"]);
		$this->shouldThrow(new Exception('Tracking id invalid'))->during('GetByTrackingId', [-100]);
	}
}