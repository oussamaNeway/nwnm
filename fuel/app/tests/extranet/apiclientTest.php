<?php

namespace Fuel\Core;

use Fuel\Extranet\apiclient as ApiClient;
use \Exception;
use Netmessage\Model\armmodel as ArmModel;

class Response
{
    private $return = 'ok';

    public function json()
    {
        return json_encode($this->return);
    }

    public function setReturn($return)
    {
        $this->return = $return;
    }

    public function redirect()
    {

    }
}

class ClientMock
{
    public $exception;

    public function get($arg1, $arg2)
    {
        throw $this->exception;
    }
}

class ApiClientTest extends TestCase
{
    private $apiClient;

    public function setUp()
    {
        $this->apiClient = new ApiClient();
    }

    public function testConstructor()
    {
        $client = $this->apiClient->getClient();

        $this->assertEquals(Config::get('api_base_url'), $client->getBaseUrl());
        $this->assertInstanceOf('\\GuzzleHttp\\Client', $client);
        $this->assertInstanceOf('\\Login\\Behaviour\\SessionUpdater', $this->apiClient->getSessionUpdater());

        $user = array(
            '_id' => 'superID'
        );

        Session::set('user', $user);
        Session::set('session_id', 'session_id');

        $this->apiClient = new ApiClient();
        $client = $this->apiClient->getClient();

        $this->assertEquals($user['_id'], $client->getDefaultOption()['headers']['id_user']);
        $this->assertArrayHasKey('id_check', $client->getDefaultOption()['headers']);

        Session::destroy();
        Session::set('user_id', 'superId');
        Session::set('session_id', 'session_id');

        $this->apiClient = new ApiClient();
        $client = $this->apiClient->getClient();

        $this->assertEquals('superId', $client->getDefaultOption()['headers']['id_user']);
        $this->assertArrayHasKey('id_check', $client->getDefaultOption()['headers']);
    }

    public function test_call()
    {
        $url = 'user/';
        $options = array();

        $clientMock = $this->getMock('\\GuzzleHttp\\Client', array('get'));

        $clientMock->expects($this->once())
            ->method('get')
            ->with($url, array('body' => json_encode($options)))
            ->willReturn(new Response())
        ;

        $this->apiClient->setClient($clientMock);

        $expectedReturnArray = array(
            200,
            'ok'
        );

        $returnArray = $this->apiClient->get($url, $options);

        $this->assertEquals($expectedReturnArray, $returnArray);
    }

    public function test_callSendUser()
    {
        $url = 'user/';
        $options = array();

        $response = new Response();
        
        $userModel = new \User\Model\UserModel();
        $userModel->email = 'email@toto.com';
        
        $returnResponse = array('ok', 'sendUser' => json_encode($userModel));
        $response->setReturn($returnResponse);

        $clientMock = $this->getMock('\\GuzzleHttp\\Client', array('get'));

        $clientMock->expects($this->once())
            ->method('get')
            ->with($url, array('body' => json_encode($options)))
            ->willReturn($response)
        ;

        $this->apiClient->setClient($clientMock);

        $sessionUpdaterMock = $this->getMock('\\Login\\Behaviour\\SessionUpdater', array('updateSession'));
        $sessionUpdaterMock->expects($this->once())
            ->method('updateSession')
            ->with($userModel)
        ;

        $this->apiClient->setSessionUpdater($sessionUpdaterMock);

        $expectedReturnArray = array(
            200,
            array('ok')
        );

        $returnArray = $this->apiClient->get($url, $options);

        $this->assertEquals($expectedReturnArray, $returnArray);
    }

    public function test_callSendArm()
    {
        $url = 'user/';
        $options = array();

        $response = new Response();

        $armModel = new ArmModel();
        $armModel->name = 'God';
        $armModel->allow = array('/netmessage');
        $armModel->forbid = array('/');

        $returnResponse = array('ok', 'sendArm' => json_encode($armModel));
        $response->setReturn($returnResponse);

        $clientMock = $this->getMock('\\GuzzleHttp\\Client', array('get'));

        $clientMock->expects($this->once())
            ->method('get')
            ->with($url, array('body' => json_encode($options)))
            ->willReturn($response)
        ;

        $this->apiClient->setClient($clientMock);

        $sessionUpdaterMock = $this->getMock('\\Login\\Behaviour\\SessionUpdater', array('updateSessionArm'));
        $sessionUpdaterMock->expects($this->once())
            ->method('updateSessionArm')
            ->with($armModel)
        ;

        $this->apiClient->setSessionUpdater($sessionUpdaterMock);

        $expectedReturnArray = array(
            200,
            array('ok')
        );

        $returnArray = $this->apiClient->get($url, $options);

        $this->assertEquals($expectedReturnArray, $returnArray);
    }
}
