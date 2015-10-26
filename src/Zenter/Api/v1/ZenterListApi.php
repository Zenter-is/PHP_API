<?php

namespace Zenter\Api\v1
{

	class ZenterListApi
	{
		/**
		 * @var IHttpClient
		 */
		private $restClient;

		public function __construct(IHttpClient $restClient)
		{
			$this->restClient = $restClient;
		}

		public function AddRecipient($listId, $recipientId)
		{
			$action = "/lists/" . $listId . "/recipients/add/" . $recipientId;
			$add = $this->restClient->call($action);
		}
	}
}