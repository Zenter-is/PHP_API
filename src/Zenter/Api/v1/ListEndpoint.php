<?php

namespace Zenter\Api\v1
{

	class ListEndpoint
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
			$this->restClient->call($action);
		}
	}
}