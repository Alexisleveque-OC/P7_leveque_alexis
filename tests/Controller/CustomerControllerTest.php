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

        $this->assertCount(10, $dataEmbedded["items"]);
    }
    public function testShowCustomer()
    {
        $client = self::createClient();

        $client = $this->createAuthenticatedClient($client);

        $client->request('GET',"/api/customers/16");

        $data = json_decode($client->getResponse()->getContent(),'true');

        $this->assertIsArray($data);
        $this->assertJson($client->getResponse()->getContent());
        $this->assertArrayHasKey('_links',$data);
        $this->assertArrayHasKey('full_name',$data);

    }

//    public function testCreateCustomer()
//    {
//        $data = [
//            'full_name' => 'test',
//            'email' => 'toto@test.com',
//            'street' => '1 rue des tests',
//            'city' => 'test',
//            'country' => 'France'
//        ];
//        $json = json_encode($data);
//
//        $client = self::createClient();
//
//
//        $client = $this->createAuthenticatedClient($client);
//
//        $client->request('POST',"/api/customers",[],[],[],$json);
//        $this->assertJson($client->getResponse()->getContent());
//    }

    public function testDeleteCustomer()
    {
        $client = self::createClient();

        $client = $this->createAuthenticatedClient($client);

        $client->request('DELETE',"/api/customers/17");
        $this->assertEquals(204,$client->getResponse()->getStatusCode());

    }

}