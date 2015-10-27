<?php

namespace Zenter\Api\v1
{

	use Exception;

	class AudienceEndpoint
	{
		/**
		 * @var IHttpClient
		 */
		private $restClient;

		public function __construct(IHttpClient $restClient)
		{
			$this->restClient = $restClient;
		}

		public function GetGroup($title)
		{
			$action = '/audiences/groups/byTitle/' . rawurlencode($title);
			$result = (array)$this->restClient->call($action);
			if (count($result) < 1)
			{
				$action = '/audiences/groups/add/';
				$data = [
					'title' => $title,
				];
				$result = $this->restClient->call($action, $data, 'POST');
				if (strlen($result))
				{
					return $result;
				}
			}

			return current($result)->id;
		}

		public function GetCategory($title, $groupId)
		{
			$action = '/audiences/categories/byTitle/' . rawurlencode($title);
			$result = (array)$this->restClient->call($action);
			if (count($result) < 1)
			{
				$action = '/audiences/categories/add/';
				$data = [
					'title'   => $title,
					'groupId' => $groupId,
				];
				$result = $this->restClient->call($action, $data, 'POST');
				if (strlen($result))
				{
					return $result;
				}
			}

			return current($result)->id;
		}

		public function GetTarget($title, $categoryId)
		{
			$action = '/audiences/byTitle/' . $categoryId . '/' . rawurlencode($title);
			$result = (array)$this->restClient->call($action);
			if (count($result) < 1)
			{
				$action = '/audiences/add/';
				$data = [
					'title'      => $title,
					'categoryId' => $categoryId,
				];
				$result = $this->restClient->call($action, $data, 'POST');
				if (strlen($result))
				{
					return $result;
				}
			}
			else
			{
				return current($result)->id;
			}
		}

		public function AddRecipient($target, $recipient)
		{
			$action = '/audiences/recipients/add/' . $target . '/' . $recipient;
			$this->restClient->call($action);

			return ($this->restClient->GetStatusCode() == 200);
		}

		public function HasRecipient($targetId, $recipientId)
		{
			throw new Exception('Functionality not yet implemented');
		}

		public function RemoveRecipientFromTarget($recipient, $target)
		{
			$action = '/audiences/recipients/remove/' . $target . '/' . $recipient;
			$this->restClient->call($action);

			return ($this->restClient->GetStatusCode() == 200);
		}

		public function FlushTarget($target)
		{
			throw new Exception('Not Yet completed');
			$action = '/audiences/recipients/flush/';
			$this->restClient->call($action);

			return ($this->restClient->GetStatusCode() == 200);
		}

	}
}