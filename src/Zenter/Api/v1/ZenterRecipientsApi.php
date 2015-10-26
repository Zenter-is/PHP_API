<?php

namespace Zenter\Api\v1
{

	use Exception;

	class ZenterRecipientsApi extends ZenterApi
	{
		private $actions = [
			'doc'     => 'doc',
			'global'  => 'recipients/',
			'byId'    => 'recipients/%s',
			'byEmail' => 'recipients/by_email/%s',
		];

		public function __construct(ZenterRestClient $restClient)
		{
			parent::__construct($restClient);
		}

		public function GetById($id)
		{
			if (!is_numeric($id))
			{
				throw new Exception("Id can only be numeric");
			}

			$action = "/recipients/" . $id;

			return $this->restClient->Call($action);
		}

		public function GetByEmail($email)
		{
			if (filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$recipient = $this->restClient->Call(sprintf($this->actions['byEmail'], urlencode($email)));
				if ($this->restClient->GetStatusCode() == 200)
				{
					return $recipient;
				}

				return null;
			}
		}

		public function GetDoc()
		{
			return $this->restClient->Call($this->actions['doc'], null, 'GET', false);
		}

		public function GetAll()
		{
			return $this->restClient->Call($this->actions['global']);
		}

		public function CreateRecipient($data)
		{
			$recipient = $this->restClient->Call(sprintf($this->actions['global']), $data, 'POST');

			return $recipient;
		}

		public function UpdateRecipientById($id, $data)
		{
			if (!is_numeric($id))
			{
				throw new Exception("Id can only be numeric");
			}

			return $this->restClient->Call(sprintf($this->actions['byId'], $id), $data, 'POST');
		}

		public function UpdateRecipientByEmail($email, $data)
		{
			$recipient = $this->restClient->Call(sprintf($this->actions['byEmail'], urlencode($email)), $data, 'POST');
			if ($this->restClient->GetStatusCode() != 200)
			{
				$recipient = $this->CreateRecipient($data);
			}

			return $recipient;
		}

	}
}


