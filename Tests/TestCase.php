<?php

namespace Siciarek\SymfonyCommonBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BasicTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TestCase extends BasicTestCase {

    /**
     * @var Container
     */
    protected $container;

    /**
     * @return Container
     */
    public function getContainer() {

        if(false === $this->container instanceof Container) {
            $this->container = self::createClient()->getContainer();
        }
        return $this->container;
    }


    /**
     * @param $username
     */
    public function logInUser($username)
    {
        $user = $this->getContainer()->get('fos_user.user_manager')->findOneBy(['username' => $username]);
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $this->getContainer()
            ->get('security.token_storage')
            ->setToken($token);
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'colak', $password = 'pass')
    {
        $router = $this->getContainer()->get('router');
        $authUrl = $router->generate('auth_check', [], $router::ABSOLUTE_URL);

        $client = static::createClient();
        $client->request(
            'POST',
            $authUrl,
            [
                'username' => $username,
                'password' => $password,
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function getAuthHeaders()
    {
        $router = $this->getContainer()->get('router');
        $authUrl = $router->generate('auth_check', [], $router::ABSOLUTE_URL);
        $authData = [
            'username' => 'colak',
            'password' => 'pass',
        ];
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        list($resp, $info) = $this->getResponse('POST', $authUrl, http_build_query($authData), $headers);

        $data = json_decode($resp, true);

        return [
            sprintf('Authorization: Bearer %s', $data['token']),
        ];
    }

    public function getResponse($method, $url, $data = [], $headers = null)
    {
        $method = strtoupper($method);
        $ch = curl_init();

        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_VERBOSE, false);

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_PUT, true);
                break;
            case 'DELETE':
                curl_setopt($ch,  CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [$resp, $info];
    }
}