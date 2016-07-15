<?php

namespace Fuel\Core;

use Fuel\Behaviour\savior as Savior;
use Fuel\Extranet\apiclient as ApiClient;
use User\Model\usermodel as UserModel;
use Login\Model\singleemailmodel as SingleEmailModel;

class SaviorTest extends TestCase
{
    public function testConstructor()
    {
        $savior = new Savior(new ApiClient());

        $this->assertInstanceOf('Fuel\\Behaviour\\Savior', $savior);
    }

    public function testAdd()
    {
        $singleEmailModel = new SingleEmailModel();
        $singleEmailModel->email = 'youyohu';

        $apiClientMock = $this->getMock('\\Fuel\\Extranet\\ApiClient', array('post'));
        $apiClientMock->expects($this->once())
            ->method('post')
            ->with('/add', $singleEmailModel)
            ->willReturn(array(200, 'ok'))
        ;

        $savior = new Savior();
        $savior->setApiClient($apiClientMock);

        $response = $savior->add($singleEmailModel);

        $this->assertEquals('ok', $response);
    }

    public function testUpdate()
    {
        $userModel = new \User\Model\UserModel();
        $userModel->email = 'youyohu';
        $userModel->_id = '3R3R3R3R3R3FRGTG3';

        $userModel2 = new \User\Model\UserModel();
        $userModel2->email = 'youyohu';

        $apiClientMock = $this->getMock('\\Fuel\\Extranet\\ApiClient', array('post'));
        $apiClientMock->expects($this->once())
            ->method('post')
            ->with('/update', array('_id' => $userModel->_id, 'update' => $userModel2))
            ->willReturn(array(200, 'ok'))
        ;

        $savior = new Savior();
        $savior->setApiClient($apiClientMock);

        $response = $savior->update($userModel);

        $this->assertEquals('ok', $response);
    }

    public function testDelete()
    {
        $_id = '3R3R3R3R3R3FRGTG3';

        $apiClientMock = $this->getMock('\\Fuel\\Extranet\\ApiClient', array('post'));
        $apiClientMock->expects($this->once())
            ->method('post')
            ->with('/delete', array('_id' => $_id))
            ->willReturn(array(200, 'ok'))
        ;

        $savior = new Savior();
        $savior->setApiClient($apiClientMock);

        $response = $savior->delete($_id);

        $this->assertEquals('ok', $response);
    }
}
