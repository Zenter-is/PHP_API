<?php

namespace Zenter\Api\v1
{

	class ZenterListApi extends ZenterApi
	{
		public function __construct(ZenterRestClient $restClient)
		{
			parent::__construct($restClient);
		}

		public function AddRecipient($listId, $recipientId)
		{
			$action = "/lists/" . $listId . "/recipients/add/" . $recipientId;
			$add = $this->restClient->call($action);
		}
	}
}