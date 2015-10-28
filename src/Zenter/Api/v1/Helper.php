<?php

namespace Zenter\Api\v1;

use Exception;

final class Helper
{
	/**
	 * @param $jsonInput
	 *
	 * @return null|object
	 * @throws Exception
	 */
	public static function JsonToObject($jsonInput)
	{
		$data = json_decode($jsonInput);
		if($data === false)
			return null;

		if(!is_array($data) && !is_object($data))
			throw new Exception("Json does not represent a object");

		return (object)$data;
	}

	/**
	 * @param $jsonInput
	 *
	 * @return array|null
	 * @throws Exception
	 */
	public static function JsonToArray($jsonInput)
	{
		$data = json_decode($jsonInput);
		if($data === false)
			return null;

		if(!is_array($data) && !is_object($data))
			throw new Exception("Json does not represent an array");

		return (array)$data;
	}
}
