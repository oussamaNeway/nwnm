<?php

namespace Fuel\Core;

use Fuel\Twig\extranetrightextension as ExtranetRightExtension;
use Behaviour\rights as Rights;
use Netmessage\Model\armmodel as ArmModel;
use Netmessage\Behaviour\armcortex as ArmCortex;

class ExtranetRightExtentionTest extends TestCase
{
    private $extranetRightExtension;

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

        $this->extranetRightExtension = new ExtranetRightExtension();
    }

    public function testGetFunctions()
    {
        $this->assertTrue(array_key_exists('has_right', $this->extranetRightExtension->getFunctions()));
    }

    public function testGetName()
    {
        $this->assertEquals('ExtranetRightExtension', $this->extranetRightExtension->getName());
    }

    public function testHasRightFalse()
    {
        $url = '/datamart';

        $this->assertFalse($this->extranetRightExtension->hasRight($url));
    }

    public function testHasRightTrue()
    {
        $url = '/netmessage';

        $this->assertTrue($this->extranetRightExtension->hasRight($url));
    }
}
