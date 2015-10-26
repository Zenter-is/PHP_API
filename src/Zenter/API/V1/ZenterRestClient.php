<?php

namespace Zenter\API\V1
{

	class ZenterRestClient {
		private $clientId;
		private $username;
		private $password;
		private $baseUrl;
		private $protocol;
		private $apiVersion;

		private $responseCode;

		private $apiUrls = [
			1 => '/api/v1/'
		];

		public function __construct($clientId, $username, $password, $baseUrl = 'zenter.is', $apiVersion = 1, $protocol = 'http') {
			$this->clientId = $clientId;
			$this->username = $username;
			$this->password = $password;
			$this->baseUrl = $baseUrl;
			$this->protocol = $protocol;
			$this->apiVersion = $apiVersion;
		}

		private function getApiUrl() {
			return $this->apiUrls[$this->apiVersion];
		}

		private function getFullBaseUrl() {
			return $this->protocol. '://' . $this->baseUrl . $this->getApiUrl();
		}

		public function GetStatusCode() {
			return $this->responseCode;
		}

		public function Call($action, $data = null, $method = 'GET', $decode = true) {
			$url = $this->getFullBaseUrl() . $action;
			//var_dump($url);
			# headers and data (this is API dependent, some uses XML)
			$headers = array(
				/*'Accept: application/json',
				'Content-Type: application/json',*/
			);
			if(is_array($data))
			{
				$encodedData = '';
				foreach ($data as $key => $value) {
					if(strlen($encodedData) > 0)
						$encodedData.= '&';
					$encodedData .= $key . '=' . urlencode($value);
				}
			}
			else
			{
				$url .= $data;
			}

			$handle = curl_init();
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($handle, CURLOPT_USERPWD, $this->clientId. '_' . $this->username . ":" . $this->password);

			switch(strtoupper($method)) {
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

			if($decode)
				return json_decode($response);
			return $response;
		}
	}
}