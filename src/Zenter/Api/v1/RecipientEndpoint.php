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
			'byForeignId'	=> 'recipients/by_foreign_id/%s'
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

		public function GetByForeignId($foreignId)
		{
			if(!$foreignId)
			{
				throw new Exception("Foreign Id is invalid");
			}

			$recipient =  $this->restClient->Call(sprintf($this->actions['byForeignId'], $foreignId));

			return Helper::JsonToObject($recipient);
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
				$recipientData = $this->restClient->Call(sprintf($this->actions['byEmail'], $email));
				if ($this->restClient->GetStatusCode() == 200)
				{
					return Helper::JsonToObject($recipientData);
				}

			}
			return null;
		}

		public function GetByKt($kt)
		{
            //Clean up spaces if there are any
			$kt = str_replace(' ', '', (string)$kt);

			//Clean up hyphen if there are any
			$kt = str_replace('-', '', $kt);

			if ($kt !== '')
			{
				$recipientData = $this->restClient->Call(sprintf($this->actions['byKt'], $kt));
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
			$data = $this->restClient->Call($this->actions['global']);
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
			$recipient = $this->restClient->Call(sprintf($this->actions['byEmail'], $email), $data, 'POST');
			if ($this->restClient->GetStatusCode() != 200)
			{
				return $this->CreateRecipient($data);
			}
			return Helper::ForceJsonToObject($recipient);
		}

		public function UpdateRecipientByKt($kt, array $data)
		{
			//Clean up spaces if there are any
			$kt = str_replace(' ', '', $kt);

			//Clean up hyphen if there are any
			$kt = str_replace('-', '', $kt);

			$recipient = $this->restClient->Call(sprintf($this->actions['byKt'], $kt), $data, 'POST');
			if ($this->restClient->GetStatusCode() != 200)
			{
				return $this->CreateRecipient($data);
			}
			return Helper::ForceJsonToObject($recipient);
		}

		public function UpdateRecipientByForeignId($foreignId, array $data)
		{
			if(!is_string($foreignId))
				throw new Exception("Id can only be a string");

			$recipient =  $this->restClient->Call(sprintf($this->actions['byForeignId'], $foreignId), $data, 'POST');
			if($this->restClient->GetStatusCode() != 200)
			{
				return $this->CreateRecipient($data);
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
		public function addNote($id, $content)
		{
			if(!$id || $id < 1 || !is_numeric($id))
			{
				throw new Exception("id invalid");
			}

			if($content === '' || $content === null)
			{
				throw new Exception("content can't be empty string or null");
			}

			$action = '/recipients/' . $id . '/notes/';
			$data = [
				'content' => $content
			];
			$this->restClient->call($action, $data, 'POST');

			return ($this->restClient->GetStatusCode() == 200);
		}

		public function getAllRecipients()
		{
			$recipientsIds = $this->GetAll();

			$data = [
					'id' => $recipientsIds
			];
			$jsonArray = $this->restClient->call('recipients/GetAllByIds', $data);

			return Helper::ForceJsonToArray($jsonArray);
		}

		public function getByIds(array $recipientsIds)
		{
			$data = [
					'id' => $recipientsIds
			];
			$jsonArray = $this->restClient->call('recipients/GetAllByIds', $data);

			return Helper::JsonToArray($jsonArray);
		}

		public function RemoveRecipient($recipientId)
		{
			if($recipientId < 0 || !is_numeric($recipientId))
			{
			    throw new Exception("Recipient Id needs to be numeric");
			}
			$this->restClient->call('recipients/RemoveRecipient/'.$recipientId);
		}
	}
}


