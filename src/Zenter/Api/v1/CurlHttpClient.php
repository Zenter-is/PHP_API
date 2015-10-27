<?php

namespace Zenter\Api\v1
{

	class CurlHttpClient implements IHttpClient
	{
		private $clientId;
		private $username;
		private $password;
		private $baseUrl;
		private $protocol;
		private $apiVersion;

		private $responseCode;

		public function __construct($clientId, $username, $password, $baseUrl = 'zenter.is', $apiVersion = 1, $protocol = 'http')
		{
			$this->clientId = $clientId;
			$this->apiVersion = $apiVersion;
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
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($handle, CURLOPT_USERPWD, $this->clientId . '_' . $this->username . ":" . $this->password);

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

		private function getApiUrl()
		{
			return $this->apiUrls[$this->apiVersion];
		}

		/**
		 * @param string $username
		 * @param string $password
		 *
		 * @return void
		 */
		public function setAuth($username, $password)
		{
			$this->username = $username;
			$this->password = $password;
		}

		/**
		 * @param string $url
		 *
		 * @return void
		 */
		public function setBaseUrl($url)
		{
			$this->baseUrl = $url;
		}

		/**
		 * @param string $protocol
		 *
		 * @return mixed
		 */
		public function setProtocol($protocol)
		{
			$this->protocol = $protocol;
		}
	}
}
