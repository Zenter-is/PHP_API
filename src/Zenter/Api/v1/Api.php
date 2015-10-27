<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 27.10.2015
 * Time: 12:47
 */

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
	 * @var IHttpClient
	 */
	private $client;

	/**
	 * Api constructor.
	 */
	public function __construct(IHttpClient $client, $username, $password, $domain = 'samskipti.zenter.is', $protocol = 'https')
	{
		$client->setAuth($username, $password);
		$client->setBaseUrl('/api/v1/');
		$client->setProtocol($protocol);
		$this->client = $client;

		$this->recipients = new RecipientEndpoint($client);
		$this->jobs = new JobEndpoint($client);
		$this->lists = new ListEndpoint($client);
		$this->audiences = new AudienceEndpoint($client);
	}
}
