<?php

namespace Zenter\Api\v1
{

	class ZenterApi
	{
		protected $restClient;

		public function __construct(ZenterRestClient $restClient)
		{
			$this->restClient = $restClient;
		}
	}
}


