<?php

namespace Zenter\Api\v1
{

	class ListEndpoint
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
		 * Add a recipient to a list
		 *
		 * @param int $listId
		 * @param int $recipientId
		 *
		 * @return bool
		 */
		public function AddRecipient($listId, $recipientId)
		{
			$action = "/lists/" . $listId . "/recipients/add/" . $recipientId;
			$this->restClient->call($action);

			return $this->restClient->GetStatusCode() === 200;
		}
	}
}
