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

		/**
		 * @param IHttpClient $restClient
		 */
		public function __construct(IHttpClient $restClient)
		{
			$this->restClient = $restClient;
		}

		/**
		 * @param string $title
		 *
		 * @return string
		 * @throws Exception
		 */
		public function GetGroup($title)
		{
			$action = '/audiences/groups/byTitle/' . rawurlencode($title);

			$data = $this->restClient->call($action);

			if ($this->restClient->GetStatusCode() === 404)
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
				throw new Exception('Unable to create a group');
			}

			$groups = Helper::JsonToArray($data);

			return current($groups)->id;
		}

		/**
		 * @param string $title
		 * @param int    $groupId
		 *
		 * @return string
		 * @throws Exception
		 */
		public function GetCategory($title, $groupId)
		{
			$action = '/audiences/categories/byTitle/';

			$getData = [
				'title'   => $title,
				'groupId' => $groupId,
			];

			$apiResult = $this->restClient->call($action, $getData);

			if ($this->restClient->GetStatusCode() === 404)
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

			$categories = Helper::JsonToArray($apiResult);

			foreach($categories as $category)
			{
				if($category->groupId == $groupId)
					return $category->id;
			}

			throw new Exception('Unable to create category');
		}

		/**
		 * @param string $title
		 * @param int    $categoryId
		 *
		 * @return string
		 * @throws Exception
		 */
		public function GetAudience($title, $categoryId)
		{
			$action = '/audiences/byTitle/' . $categoryId . '/' . rawurlencode($title);

			$data = $this->restClient->call($action);
			$audiences = Helper::JsonToArray($data);

			if (count($audiences) < 1)
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
				throw new Exception('Unable to create audience');
			}


			return current($audiences)->id;
		}

		/**
		 * @param int $target
		 * @param int $recipient
		 *
		 * @return bool
		 */
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

		/**
		 * @param int $recipient
		 * @param int $target
		 *
		 * @return bool
		 */
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