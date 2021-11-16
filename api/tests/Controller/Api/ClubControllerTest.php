<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClubControllerTest extends WebTestCase
{
	public function testCreateClubInvalidData()
	{
		$client = static::createClient();
		$client->request(
			'POST',
			'/api/clubs',
			[],
			[],
			['CONTENT_TYPE' => 'application/json'],
			'{"name":""}'
		);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	public function testCreateClubEmptyData()
	{
		$client = static::createClient();
		$client->request(
			'POST',
			'/api/clubs',
			[],
			[],
			['CONTENT_TYPE' => 'application/json'],
			''
		);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	public function testCreateClubSuccess()
	{
		$client = static::createClient();
		$client->request(
			'POST',
			'/api/clubs',
			[],
			[],
			['CONTENT_TYPE' => 'application/json'],
			'{"name":"Betis"}'
		);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}
