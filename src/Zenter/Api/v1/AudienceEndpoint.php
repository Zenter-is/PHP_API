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
		 * @param $categoryId
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function GetCategoryById($categoryId)
		{
			if(!$categoryId || !is_numeric($categoryId) || $categoryId < 1)
			{
				throw new Exception('Category id invalid');
			}

			$action = '/audiences/categories/byId/' . $categoryId;

			$categoryRaw = $this->restClient->call($action);
			if(!$categoryRaw)
			{
				return null;
			}

			return Helper::JsonToObject($categoryRaw);
		}

		public function GetAllCategoriesByGroupId($groupId)
		{
			if(!$groupId || !is_numeric($groupId) || $groupId < 1)
			{
				throw new Exception('Category id invalid');
			}

			$action = '/audiences/categories/all/' . $groupId;
			$data = $this->restClient->call($action);
			return Helper::JsonToArray($data);
		}

		/**
		 * @param $title
		 * @param $groupId
		 *
		 * @return string
		 * @throws Exception
		 */
		public function GetCategoryByTitle($title, $groupId)
		{
			if(!$title || !is_string($title))
			{
				throw new Exception('Title invalid. Trying to create an category without title.');
			}

			if(!$groupId || $groupId < 1)
			{
				throw new Exception('Group id invalid. Trying to find an category without group scope.');
			}

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

			$categories = Helper::ForceJsonToArray($apiResult);

			foreach($categories as $category)
			{
				if($category->groupId == $groupId)
					return $category->id;
			}

			throw new Exception('Unable to create category');
		}

		/**
		 * @param $audienceId
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function GetAudienceById($audienceId)
		{
			if(!$audienceId || !is_numeric($audienceId) || $audienceId < 1)
			{
				throw new Exception('Audience id invalid');
			}

			$action = '/audiences/byId/'.$audienceId;

			$rawData = $this->restClient->call($action);

			if(!$rawData || $this->restClient->GetStatusCode() !== 200)
			{
				return null;
			}
			return Helper::JsonToObject($rawData);
		}

		/**
		 * @param		   $title
		 * @param		   $categoryId
		 * @param bool|true $createOnFailure
		 *
		 * @return string
		 * @throws Exception
		 */
		public function GetAudienceByTitle($title, $categoryId, $createOnFailure = true)
		{
			if(!$title || !is_string($title))
			{
				throw new Exception('Title invalid. Trying to create an audience without title.');
			}
			if(!$categoryId || !is_numeric($categoryId) || $categoryId < 1)
			{
				throw new Exception('Category id invalid. Trying to find an audience without category scope.');
			}

			$action = '/audiences/byTitle/' . $categoryId . '/' . rawurlencode($title);

			$data = $this->restClient->call($action);
			$audiences = Helper::ForceJsonToArray($data);

			if (count($audiences) < 1 && $createOnFailure)
			{
				$action = '/audiences/add/';
				$data = [
					'title'	  => $title,
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
		 * @param int $audienceId
		 * @param int $recipientId
		 *
		 * @return bool
		 */
		public function AddRecipient($audienceId, $recipientId)
		{
			if (!$recipientId)
				throw new Exception("No recipientId passed into function");

			if (!$audienceId)
				throw new Exception("No audienceId passed into function");

			$action = '/audiences/recipients/add/' . $audienceId . '/' . $recipientId;
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

		/**
		 * @param $groupId
		 *
		 * @return null|object
		 * @throws Exception
		 */
		public function GetGroupById($groupId)
		{
			if(!$groupId || !is_numeric($groupId) || $groupId < 1)
			{
				throw new Exception('Group id invalid');
			}

			$action = '/audiences/groups/ById/'.$groupId;
			$groupRaw = $this->restClient->call($action);

			if(!$groupRaw)
			{
				return null;
			}

			return Helper::JsonToObject($groupRaw);
		}

		/**
		 * @param $title
		 *
		 * @return string
		 * @throws Exception
		 */
		public function GetGroupByTitle($title)
		{
			if(!$title || !is_string($title))
			{
				throw new Exception('Title invalid. Trying to create an group without title.');
			}

			$action = '/audiences/groups/byTitle/' . rawurlencode($title);

			$data = $this->restClient->call($action);
			if (!$data || $this->restClient->GetStatusCode() === 404)
			{
				$action = '/audiences/groups/add/';
				$incomingData = [
					'title' => $title,
				];
				$result = $this->restClient->call($action, $incomingData, 'POST');
				if (strlen($result))
				{
					return $result;
				}
				throw new Exception('Unable to create a group');
			}

			$groups = Helper::ForceJsonToArray($data);

			return current($groups)->id;
		}

		public function flushRecipientFromCategory($categoryId, $recipientId)
		{
			if (!$categoryId || !$recipientId)
			{
				throw new Exception('Flush Category error.');
			}
			$action = '/audiences/categories/flushRecipient/' . $categoryId;
			$this->restClient->call($action);

			return ($this->restClient->GetStatusCode() == 200);
		}
	}
}