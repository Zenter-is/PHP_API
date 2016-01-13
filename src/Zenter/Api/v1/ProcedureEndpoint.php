<?php

namespace Zenter\Api\v1
{

	class ProcedureEndpoint
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
		public function AddRecipient($procedureId, $recipientId)
		{
			$data = [
					'recipient' => $recipientId
				];

			$action = "/procedures/" . $procedureId . "/recipients/add_recipient";
			$this->restClient->call($action, $data, 'POST');

			return $this->restClient->GetStatusCode() === 200;
		}
	}
}
