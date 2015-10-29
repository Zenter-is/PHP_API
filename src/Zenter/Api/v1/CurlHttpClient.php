<?php

namespace Zenter\Api\v1
{

	class CurlHttpClient implements IHttpClient
	{
		private $username;
		private $password;
		private $baseUrl;
		private $protocol;

		private $responseCode;

		public function __construct($username, $password, $baseUrl, $protocol)
		{
			$this->setAuth($username,$password);
			$this->setBaseUrl($baseUrl);
			$this->setProtocol($protocol);
		}

		public function GetStatusCode()
		{
			return $this->responseCode;
		}

		public function Call($action, array $data = null, $method = 'GET')
		{
			$url = $this->getFullBaseUrl() . $action;
			$encodedData = '';

			$headers = [
				//'Accept: application/json',
				//'Content-Type: application/json',
			];

			if (is_array($data))
			{
				foreach ($data as $key => $value)
				{
					if (strlen($encodedData) > 0)
						$encodedData .= '&';
					$encodedData .= $key . '=' . urlencode($value);
				}
			}

			$handle = curl_init();

			switch (strtoupper($method))
			{
				case 'GET':
					curl_setopt($handle, CURLOPT_URL, $url . '?' . $encodedData);
					break;
				case 'POST':
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_POST, true);
					curl_setopt($handle, CURLOPT_POSTFIELDS, $encodedData);
					break;
				case 'PUT':
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
					curl_setopt($handle, CURLOPT_POSTFIELDS, $encodedData);
					break;
				case 'DELETE':
					curl_setopt($handle, CURLOPT_URL, $url);
					curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
					break;
			}

			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($handle, CURLOPT_USERPWD, $this->username . ":" . $this->password);

			$response = curl_exec($handle);

			$this->responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

			return $response;
		}

		private function getFullBaseUrl()
		{
			return $this->protocol . '://' . $this->baseUrl;
		}


		/**
		 * @param string $username
		 * @param string $password
		 *
		 * @return void
		 */
		protected function setAuth($username, $password)
		{
			$this->username = $username;
			$this->password = $password;
		}

		/**
		 * @param string $url
		 *
		 * @return void
		 */
		protected function setBaseUrl($url)
		{
			if(substr($url,strlen($url) -1) !== '/')
			{
				$url .= '/';
			}
			$this->baseUrl = $url;
		}

		/**
		 * @param string $protocol
		 *
		 * @return mixed
		 */
		protected function setProtocol($protocol)
		{
			$this->protocol = $protocol;
		}
	}
}
