<?php

namespace Zenter\Api\v1;

class Api
{
	/**
	 * @var RecipientEndpoint
	 */
	public $recipients;
	/**
	 * @var JobEndpoint
	 */
	public $jobs;
	/**
	 * @var ListEndpoint
	 */
	public $lists;
	/**
	 * @var AudienceEndpoint
	 */
	public $audiences;

	/**
	 * Api constructor.
	 */
	public function __construct($username, $password, $domain = 'samskipti.zenter.is', $protocol = 'https')
	{
		$client = new CurlHttpClient($username, $password, $domain, $protocol);

		$this->recipients = new RecipientEndpoint($client);
		$this->jobs = new JobEndpoint($client);
		$this->lists = new ListEndpoint($client);
		$this->audiences = new AudienceEndpoint($client);
	}
}
