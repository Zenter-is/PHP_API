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
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $domain
	 * @param string $protocol
	 */
	public function __construct($username, $password, $domain = 'samskipti.zenter.is', $protocol = 'https')
	{
		$client = new CurlHttpClient($username, $password, $domain .'/api/v1/', $protocol);

		$this->recipients = new RecipientEndpoint($client);
		$this->jobs = new JobEndpoint($client);
		$this->lists = new ListEndpoint($client);
		$this->audiences = new AudienceEndpoint($client);
	}
}
