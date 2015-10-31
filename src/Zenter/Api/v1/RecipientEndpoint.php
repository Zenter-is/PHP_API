<?php

namespace Zenter\Api\v1
{

	use Exception;

	class RecipientEndpoint
	{
		private $actions = [
			'doc'     		=> 'doc',
			'global'  		=> 'recipients/',
			'byId'    		=> 'recipients/%s',
			'byEmail' 		=> 'recipients/by_email/%s',
			'byKt' 			=> 'recipients/by_kt/%s',
			'getContacts' 	=> 'recipients/get_contacts/%s',
			'AddContact' 	=> 'recipients/add_contact/%s',
		];
		/**
		 * @var IHttpClient
		 */
		private $restClient;

		public function __construct(IHttpClient $restClient)
		{
			$this->restClient = $restClient;
		}

		/**
		 * @param $id
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function GetById($id)
		{
			if (!is_numeric($id))
			{
				throw new Exception("Id can only be numeric");
			}

			$action = "/recipients/" . $id;
			$data = $this->restClient->Call($action);

			return Helper::JsonToObject($data);
		}

		/**
		 * @param string $email
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function GetByEmail($email)
		{
			if (filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$recipientData = $this->restClient->Call(sprintf($this->actions['byEmail'], urlencode($email)));
				if ($this->restClient->GetStatusCode() == 200)
				{
					return Helper::JsonToObject($recipientData);
				}

			}
			return null;
		}

		public function GetByKt($kt)
		{
			if ($kt > 0)
			{
				$recipientData = $this->restClient->Call(sprintf($this->actions['byKt'], urlencode($kt)));
				if ($this->restClient->GetStatusCode() == 200)
				{
					return Helper::JsonToObject($recipientData);
				}

			}
			return null;
		}

		/**
		 * @return array|null
		 * @throws Exception
		 */
		public function GetAll()
		{
			$data = json_decode($this->restClient->Call($this->actions['global']));
			return Helper::JsonToArray($data);
		}

		/**
		 * @param array $data
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function CreateRecipient(array $data)
		{
			$recipient = $this->restClient->Call(sprintf($this->actions['global']), $data, 'POST');

			return Helper::JsonToObject($recipient);
		}

		/**
		 * @param       $id
		 * @param array $data
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function UpdateRecipientById($id, array $data)
		{
			if (!is_numeric($id))
			{
				throw new Exception("Id can only be numeric");
			}

			$data = $this->restClient->Call(sprintf($this->actions['byId'], $id), $data, 'POST');
			return Helper::JsonToObject($data);
		}

		/**
		 * @param string $email
		 * @param array $data
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function UpdateRecipientByEmail($email, array $data)
		{
			$recipient = $this->restClient->Call(sprintf($this->actions['byEmail'], urlencode($email)), $data, 'POST');
			if ($this->restClient->GetStatusCode() != 200)
			{
				$recipient = $this->CreateRecipient($data);
			}
			return Helper::JsonToObject($recipient);
		}

		/**
		 * @param $id
		 *
		 * @return array|null
		 * @throws Exception
		 */
		public function GetAllContacts($id)
		{
			$data = $this->restClient->Call(sprintf($this->actions['getContacts'], $id));
			return Helper::JsonToArray($data);
		}

		/**
		 * @param $company_id
		 * @param $contact_id
		 * @param string $position
		 *
		 * @return bool
		 * @throws Exception
		 */
		public function AddContact($company_id, $contact_id, $position = 'null')
		{
			$data = [
					'recipient_id' => $contact_id,
					'position' => $position,
				];
			$this->restClient->Call(sprintf($this->actions['AddContact'], $company_id), $data, 'POST');

			return ($this->restClient->GetStatusCode() == 200);
		}

		public function GetByTrackingId($trackingId)
		{
			if(!$trackingId || $trackingId < 1 || !is_numeric($trackingId))
			{
				throw new Exception("Tracking id invalid");
			}

			$action = '/recipients/byTrackingId/' . $trackingId;
			$rawRecipient = $this->restClient->call($action);

			if(!$rawRecipient)
			{
				return null;
			}
			return Helper::JsonToObject($rawRecipient);

		}

		/**
		 * @param $id
		 *
		 * @return array|null
		 * @throws Exception
		 */
		public function getNotes($id)
		{
			if(!$id || $id < 1 || !is_numeric($id))
			{
				throw new Exception("id invalid");
			}

			$action = '/recipients/' . $id . '/notes/';
			$jsonArray = $this->restClient->call($action);

			return Helper::JsonToArray($jsonArray);
		}

		/**
		 * @param $id
		 *
		 * @return bool
		 * @throws Exception
		 */
		public function addNote($id)
		{
			if(!$id || $id < 1 || !is_numeric($id))
			{
				throw new Exception("id invalid");
			}

			$action = '/recipients/' . $id . '/notes/';
			$this->restClient->call($action);

			return ($this->restClient->GetStatusCode() == 200);
		}
	}
}


