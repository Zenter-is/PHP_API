<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 30.10.2015
 * Time: 10:31
 */

namespace spec\Zenter\Api\v1;

use Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AudienceEndpointSpec extends ObjectBehavior
{
	function let($object)
	{
		$object->beADoubleOf('Zenter\Api\v1\CurlHttpClient');

		$this->beConstructedWith($object);

	}
	function it_is_initializable()
	{
		$this->shouldHaveType('Zenter\Api\v1\AudienceEndpoint');
	}


	function it_throws_error_when_getting_audience_by_in_with_invalid_information()
	{
		$this->shouldThrow(new Exception('Audience id invalid'))->during('GetAudienceById', [""]);
		$this->shouldThrow(new Exception('Audience id invalid'))->during('GetAudienceById', [true]);
		$this->shouldThrow(new Exception('Audience id invalid'))->during('GetAudienceById', ["ble"]);
		$this->shouldThrow(new Exception('Audience id invalid'))->during('GetAudienceById', [-100]);
	}

	function it_throws_error_when_getting_audience_by_name_with_invalid_information()
	{
		$this->shouldThrow(new Exception('Title invalid. Trying to create an audience without title.'))->during('GetAudienceByTitle', ["" , null]);
		$this->shouldThrow(new Exception('Title invalid. Trying to create an audience without title.'))->during('GetAudienceByTitle', [true , null]);
		$this->shouldThrow(new Exception('Category id invalid. Trying to find an audience without category scope.'))->during('GetAudienceByTitle', ["ble", null]);
		$this->shouldThrow(new Exception('Category id invalid. Trying to find an audience without category scope.'))->during('GetAudienceByTitle', ["ble", "boob"]);
		$this->shouldThrow(new Exception('Category id invalid. Trying to find an audience without category scope.'))->during('GetAudienceByTitle', ["ble", -100]);
		$this->shouldThrow(new Exception('Unable to create audience'))->during('GetAudienceByTitle', ["ble", 22]);
	}

	function it_throws_error_when_getting_category_by_in_with_invalid_information()
	{
		$this->shouldThrow(new Exception('Category id invalid'))->during('GetCategoryById', [""]);
		$this->shouldThrow(new Exception('Category id invalid'))->during('GetCategoryById', [true]);
		$this->shouldThrow(new Exception('Category id invalid'))->during('GetCategoryById', ["ble"]);
		$this->shouldThrow(new Exception('Category id invalid'))->during('GetCategoryById', [-100]);
	}

	function it_throws_error_when_getting_category_by_name_with_invalid_information()
	{
		$this->shouldThrow(new Exception('Title invalid. Trying to create an category without title.'))->during('GetCategoryByTitle', ["" , null]);
		$this->shouldThrow(new Exception('Title invalid. Trying to create an category without title.'))->during('GetCategoryByTitle', [true , null]);
		$this->shouldThrow(new Exception('Group id invalid. Trying to find an category without group scope.'))->during('GetCategoryByTitle', ["ble", null]);
		$this->shouldThrow(new Exception('Group id invalid. Trying to find an category without group scope.'))->during('GetCategoryByTitle', ["ble", "boob"]);
		$this->shouldThrow(new Exception('Group id invalid. Trying to find an category without group scope.'))->during('GetCategoryByTitle', ["ble", -100]);
		$this->shouldThrow(new Exception('Unable to create category'))->during('GetCategoryByTitle', ["ble", 22]);
	}


	function it_throws_error_when_getting_group_by_in_with_invalid_information()
	{
		$this->shouldThrow(new Exception('Group id invalid'))->during('GetGroupById', [""]);
		$this->shouldThrow(new Exception('Group id invalid'))->during('GetGroupById', [true]);
		$this->shouldThrow(new Exception('Group id invalid'))->during('GetGroupById', ["ble"]);
		$this->shouldThrow(new Exception('Group id invalid'))->during('GetGroupById', [-100]);
	}


	function it_throws_error_when_getting_group_by_name_with_invalid_information()
	{
		$this->shouldThrow(new Exception('Title invalid. Trying to create an group without title.'))->during('GetGroupByTitle', ["" ]);
		$this->shouldThrow(new Exception('Title invalid. Trying to create an group without title.'))->during('GetGroupByTitle', [true]);
		$this->shouldThrow(new Exception('Unable to create a group'))->during('GetGroupByTitle', ["ble"]);
	}
}
