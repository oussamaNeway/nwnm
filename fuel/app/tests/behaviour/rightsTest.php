<?php

namespace Fuel\Core;

use Behaviour\rights as Rights;
use Netmessage\Behaviour\armcortex as ArmCortex;
use Netmessage\Model\armmodel as ArmModel;

class RightsTest extends TestCase
{
    private $rights;

    public function setUp()
    {
        Session::destroy();
        $this->rights = new Rights();

        $armModel = new ArmModel();
        $armModel->name = 'God';
        $armModel->allow = array('/netmessage');
        $armModel->forbid = array('/');

        $armCortex = new ArmCortex();
        $armCortex->cortexBind($armModel);

        Session::set('cortex', $armCortex->cortex);
    }

    /**
     * @dataProvider hasRightProvider
     */
    public function testHasRight($url, $expectedReturn)
    {
        $this->assertEquals($expectedReturn, $this->rights->hasRight($url));
    }

    public function hasRightProvider()
    {
        return array(
            array('/netmessage', true),
            array('/netmessage/import', true),
            array('/netmessage/page inexistante', true),
            array('/user', false),
            array('/arm', false),
            array('/resrefefefdfegfe', true)
        );
    }
}
