<?php

namespace Zenter\API\V1
{

	class ZenterJobApi extends ZenterApi
	{
		public function __construct(ZenterRestClient $restClient)
		{
			parent::__construct($restClient);
		}

		public function GetAll()
		{
			$action = '/jobs';
			$job = $this->restClient->call($action);
			//var_dump($job);
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
			$responce = $this->restClient->call($action, $data, 'POST', false);

			return $responce;
		}

		public function UpdateById($id, $data)
		{
			$action = '/jobs/email/' . $id;
			$responce = $this->restClient->call($action, $data, 'POST', false);

			return $responce;
		}

		public function SendJob($id)
		{
			$action = '/jobs/email/' . $id . '/send';
			$job = $this->restClient->call($action);
		}
	}
}