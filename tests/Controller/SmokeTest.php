<?php


namespace App\Tests\Controller;


class SmokeTest extends AuthenticatedClient
{
    /**
     * @dataProvider provideUrls
     * @param $pageName
     * @param $url
     * @param string $method
     * @param int $expectedStatusCode
     * @param bool $withLogin
     */
    public function testPageIsSuccessful($pageName,
                                         $url,
                                         $method = "GET",
                                         $expectedStatusCode = 200,
                                         $withLogin = true)
    {
        $client = self::createClient();

        if ($withLogin) {
            $client = $this->createAuthenticatedClient($client);
        }

        $client->request($method, $url);
        $response = $client->getResponse();

        self::assertSame(
            $response->getStatusCode(),
            $expectedStatusCode,
            sprintf(
                'La page "%s" devrait être accessible, mais le code HTTP est "%s".',
                $pageName,
                $response->getStatusCode()
            )
        );
    }

    public function provideUrls()
    {
        yield ['app_list_phones', '/api/phones'];
        yield ['app_list_phones', '/api/phones', 'GET', 401, false];
        yield ['app_phone_show', '/api/phones/1'];
        yield ['app_phone_show', '/api/phones/1', 'GET', 401 , false];
        yield ['app_list_customers', '/api/customers'];
        yield ['app_list_customers', '/api/customers', 'GET', 401 , false];
        yield ['app_customer_show', '/api/customers/1', 'GET', 401 , false];
        yield ['app_customer_show', '/api/customers/16', 'GET', 401 , false];
        yield ['app_customer_show', '/api/customers/16'];
    }
}