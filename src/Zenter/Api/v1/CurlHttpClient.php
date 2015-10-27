<?php

namespace Zenter\Api\v1
{

	class CurlHttpClient implements IHttpClient
	{
		private $username;
		private $password;
		private $baseUrl;
		private $protocol;
		private $apiVersion;

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
			$url = $this->baseUrl . $action;
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
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($handle, CURLOPT_USERPWD, $this->username . ":" . $this->password);

			switch (strtoupper($method))
			{
				case 'GET':
					break;
				case 'POST':
					curl_setopt($handle, CURLOPT_POST, true);
					curl_setopt($handle, CURLOPT_POSTFIELDS, $encodedData);
					break;
				case 'PUT':
					curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
					curl_setopt($handle, CURLOPT_POSTFIELDS, $encodedData);
					break;
				case 'DELETE':
					curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
					break;
			}
			$response = curl_exec($handle);

			$this->responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

			return $response;
		}

		private function getFullBaseUrl()
		{
			return $this->protocol . '://' . $this->baseUrl . $this->getApiUrl();
		}


		/**
		 * @param string $username
		 * @param string $password
		 *
		 * @return void
		 */
		private function setAuth($username, $password)
		{
			$this->username = $username;
			$this->password = $password;
		}

		/**
		 * @param string $url
		 *
		 * @return void
		 */
		private function setBaseUrl($url)
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
		private function setProtocol($protocol)
		{
			$this->protocol = $protocol;
		}
	}
}
