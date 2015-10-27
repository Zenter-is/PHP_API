<?php

namespace Zenter\Api\v1
{

	class JobEndpoint
	{
		/**
		 * @var IHttpClient
		 */
		private $restClient;

		public function __construct(IHttpClient $restClient)
		{
			$this->restClient = $restClient;
		}

		public function GetAll()
		{
			$action = '/jobs';
			$this->restClient->call($action);
		}

		public function GetById($id)
		{
			$action = '/jobs/email/' . $id;
			$job = $this->restClient->call($action);

			return $job;
		}

		public function CreateJob($data)
		{
			$action = '/jobs/email/';
			$response = $this->restClient->call($action, $data, 'POST');

			return $response;
		}

		public function UpdateById($id, $data)
		{
			$action = '/jobs/email/' . $id;
			$response = $this->restClient->call($action, $data, 'POST');

			return $response;
		}

		public function SendJob($id)
		{
			$action = '/jobs/email/' . $id . '/send';
			$this->restClient->call($action);
		}
	}
}
