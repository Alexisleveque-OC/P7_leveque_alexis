<?php


namespace App\Tests\Controller;


class PhoneControllerTest extends AuthenticatedClient
{
    public function testListPhones()
    {
        $client = self::createClient();

        $client = $this->createAuthenticatedClient($client);

        $client->request('GET',"/api/phones");

        $data = json_decode($client->getResponse()->getContent(),'true');

        $this->assertIsArray($data);
        $this->assertJson($client->getResponse()->getContent());
        $this->assertArrayHasKey('_links',$data);
        $this->assertArrayHasKey('_embedded',$data);

        $this->assertEquals(3600, $client->getResponse()->sendHeaders()->getMaxAge());

        $this->assertEquals(0, $client->getResponse()->sendHeaders()->getAge());
    }
    public function testShowPhone()
    {
        $client = self::createClient();

        $client = $this->createAuthenticatedClient($client);

        $client->request('GET',"/api/phones/16");

        $data = json_decode($client->getResponse()->getContent(),'true');

        $this->assertIsArray($data);
        $this->assertJson($client->getResponse()->getContent());

        $this->assertEquals('Phone 16',$data["name"]);

        $this->assertEquals(3600, $client->getResponse()->sendHeaders()->getMaxAge());

        $this->assertEquals(0, $client->getResponse()->sendHeaders()->getAge());
    }
}