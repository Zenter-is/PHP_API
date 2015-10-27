<?php

namespace Zenter\Api\v1
{

	interface IHttpClient
	{
		/**
		 * @param string     $action The API endpoint to call
		 * @param array|null $data   The data that is supposed to be sent
		 * @param string     $method What method to use (GET|POST|PUT...)
		 *
		 * @return string    The response
		 */
		function call($action, array $data = null, $method = 'GET');

		/**
		 * @param string $username
		 * @param string $password
		 *
		 * @return void
		 */
		function setAuth($username,$password);

		/**
		 * @param string $url
		 *
		 * @return void
		 */
		function setBaseUrl($url);

		/**
		 * @param string $protocol
		 *
		 * @return mixed
		 */
		function setProtocol($protocol);
	}
}
