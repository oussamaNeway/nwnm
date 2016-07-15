<?php

namespace Fuel\Core;

use Fuel\Behaviour\provider as Provider;
use User\Model\usermodel as UserModel;

class ProviderTest extends TestCase
{
    public $apiClientMock;
    public $provider;
    public $baseUrl;
    public $resultMock;

    public function setUp()
    {
        $this->baseUrl = 'user';
        $this->apiClientMock = $this->getMock('Fuel\\Extranet\\ApiClient', array('__construct', 'get'));
        $this->provider = new Provider($this->baseUrl);
        $this->provider->setApiClient($this->apiClientMock);
    }

    public function testConstruct()
    {
        $this->assertEquals($this->provider->getBaseUrl(), $this->baseUrl);
        $this->assertEquals($this->provider->getApiClient(), $this->apiClientMock);
    }

    public function testGetAll()
    {
        $docs = array(
            array(
                "_id" => "53a9460ee21938b94166b22d",
                "civility" => "M",
                "first_name" => "Freeman",
                "last_name" => "Gordon",
                "email" => "fgordon@blackmesa.com",
                "id_company" => "Blackmesa",
                "locale" => "en_US",
                "timezone" => "America/Phoenix",
                "creation_date" => 1403602446,
                "is_active" => true
            ),
            array(
                "_id" => "53a94644e21938b94166b237",
                "civility" => "M",
                "first_name" => "Alain",
                "last_name" => "Soap",
                "email" => "soapalain@internet.com",
                "id_company" => "Cogip",
                "locale" => "fr_FR",
                "timezone" => "Europe/Paris",
                "creation_date" => 1403602500,
                "is_active" => false
            )
        );

        $doc1 = new UserModel();
        $doc1->_id = "53a9460ee21938b94166b22d";
        $doc1->civility = "M";
        $doc1->first_name = "Freeman";
        $doc1->last_name = "Gordon";
        $doc1->email = "fgordon@blackmesa.com";
        $doc1->id_company = "Blackmesa";
        $doc1->locale = "en_US";
        $doc1->timezone = "America/Phoenix";
        $doc1->creation_date = 1403602446;
        $doc1->is_active = true;

        $doc2 = new UserModel();
        $doc2->_id = "53a94644e21938b94166b237";
        $doc2->civility = "M";
        $doc2->first_name = "Alain";
        $doc2->last_name = "Soap";
        $doc2->email = "soapalain@internet.com";
        $doc2->id_company = "Cogip";
        $doc2->locale = "fr_FR";
        $doc2->timezone = "Europe/Paris";
        $doc2->creation_date = 1403602500;
        $doc2->is_active = false;

        $expectedDocs = array(
            '53a9460ee21938b94166b22d' => $doc1, 
            '53a94644e21938b94166b237' => $doc2
        );

        $result = array(
            0 => 'httpCode',
            1 => $docs
        );

        $this->apiClientMock->expects($this->once())
            ->method('get')
            ->with($this->baseUrl . '/list')
            ->willReturn($result)
        ;

        $this->provider->setModel('\\User\\Model\\usermodel');

        $this->assertEquals($expectedDocs, $this->provider->getAll());
    }
}
