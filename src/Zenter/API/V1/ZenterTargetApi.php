<?php

namespace Zenter\API\V1
{

	class ZenterTargetApi extends ZenterApi
	{
		public function __construct(ZenterRestClient $restClient) {
			parent::__construct($restClient);
		}

		public function GetGroup($title)
		{
			$action = '/targets/groups/byTitle/'.rawurlencode($title);
			$result = $this->restClient->call($action);
			if(count($result) < 1)
			{
				$action = '/targets/groups/add/';
				$data = [
					'title' => $title
				];
				$result = $this->restClient->call($action, $data, 'POST', false);
				if(strlen($result))
				{
					return $result;
				}
			}
			return current($result)->id;
		}
		public function GetCategory($title, $groupId)
		{
			$action = '/targets/categories/byTitle/'.rawurlencode($title);
			$result = (array)$this->restClient->call($action);
			if(count($result) < 1)
			{
				$action = '/targets/categories/add/';
				$data = [
					'title' => $title,
					'groupId' => $groupId
				];
				$result = $this->restClient->call($action, $data, 'POST', false);
				if(strlen($result))
				{
					return $result;
				}
			}

			return current($result)->id;
		}

		public function GetTarget($title, $categoryId)
		{
			$action = '/targets/byTitle/'.$categoryId.'/'.rawurlencode($title);
			$result = $this->restClient->call($action);
			if(count($result) < 1)
			{
				$action = '/targets/add/';
				$data = [
					'title' => $title,
					'categoryId' => $categoryId
				];
				$result = $this->restClient->call($action, $data, 'POST', false);
				if(strlen($result))
					return $result;
			}
			else
			{
				return current($result)->id;
			}
		}

		public function AddRecipient($target,$recipient)
		{
			$action = '/targets/recipients/add/'.$target.'/'.$recipient;
			$result = $this->restClient->call($action);
			return ($this->restClient->GetStatusCode() == 200);
		}

		public function HasRecipient($targetId, $recipientId){
			throw new Exception('Functionality not yet implemented');
		}
		public function RemoveRecipientFromTarget($recipient, $target)
		{
			$action = '/targets/recipients/remove/'.$target.'/'.$recipient;
			$result = $this->restClient->call($action);
			return ($this->restClient->GetStatusCode() == 200);
		}

		public function FlushTarget($target)
		{
			$action = '/targets/recipients/flush/';
			$result = $this->restClient->call($action);
			return ($this->restClient->GetStatusCode() == 200);
		}

	}
}