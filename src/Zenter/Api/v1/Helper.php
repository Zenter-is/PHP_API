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
		if ($jsonInput === '')
			throw new Exception("jsonInput cannot be a empty string");

		$data = json_decode($jsonInput);

		if($data === false)
			return null;

		if(!is_object($data))
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
		if ($jsonInput === '')
			throw new Exception("jsonInput cannot be a empty string");

		$data = json_decode($jsonInput, true);

		if($data === false)
			return null;

		if(!is_array($data))
			throw new Exception("Json does not represent an array");

		return (array)$data;
	}

	/**
	 * @param $jsonInput
	 *
	 * @return array|null
	 * @throws Exception
	 */
	public static function ForceJsonToArray($jsonInput)
	{
		if ($jsonInput === '')
			throw new Exception("jsonInput cannot be a empty string");

		$data = json_decode($jsonInput);

		if($data === false)
			return null;

		return (array)$data;
	}
}
