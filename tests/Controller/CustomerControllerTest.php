<?php


namespace App\Tests\Controller;


class CustomerControllerTest extends AuthenticatedClient
{
    public function testListCustomers()
    {
        $client = self::createClient();

        $client = $this->createAuthenticatedClient($client);

        $client->request('GET',"/api/customers");

        $data = json_decode($client->getResponse()->getContent(),'true');

        $this->assertIsArray($data);
        $this->assertJson($client->getResponse()->getContent());
        $this->assertArrayHasKey('_links',$data);
        $this->assertArrayHasKey('_embedded',$data);

        $dataEmbedded = $data["_embedded"];
        $items = $dataEmbedded["items"];

        $this->assertCount(10, $items);
    }
}