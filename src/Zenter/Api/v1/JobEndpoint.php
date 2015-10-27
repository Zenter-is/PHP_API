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

		/**
		 * Get a list of all jobs
		 *
		 * @return array|null
		 * @throws \Exception
		 */
		public function GetAll()
		{
			$action = '/jobs';
			$data = $this->restClient->call($action);

			return Helper::JsonToArray($data);
		}

		/**
		 * Get a job by id
		 *
		 * @param int $id
		 *
		 * @return null|object
		 * @throws \Exception
		 */
		public function GetById($id)
		{
			$action = '/jobs/email/' . $id;
			$job = $this->restClient->call($action);

			return Helper::JsonToObject($job);
		}

		/**
		 * Create a job
		 *
		 * @param array $data
		 *
		 * @return null|object
		 * @throws \Exception
		 */
		public function CreateJob(array $data)
		{
			$action = '/jobs/email/';
			$response = $this->restClient->call($action, $data, 'POST');

			return Helper::JsonToObject($response);
		}

		/**
		 * Update job by id
		 *
		 * @param int   $id
		 * @param array $data
		 *
		 * @return null|object
		 * @throws \Exception
		 */
		public function UpdateById($id, array $data)
		{
			$action = '/jobs/email/' . $id;
			$response = $this->restClient->call($action, $data, 'POST');

			return Helper::JsonToObject($response);
		}

		/**
		 * Send a Job
		 * This will trigger the job to start sending
		 *
		 * @param int $id
		 *
		 * @return bool
		 */
		public function SendJob($id)
		{
			$action = '/jobs/email/' . $id . '/send';
			$this->restClient->call($action);

			return $this->restClient->GetStatusCode() === 200;
		}
	}
}
