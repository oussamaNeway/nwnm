<?php

namespace Fuel\Core;

use Fuel\Extranet\sessionflash as SessionFlash;

class SessionFlashTest extends TestCase
{
    private $sessionFlash;

    public function setUp()
    {
        Session::destroy();
        $this->sessionFlash = new SessionFlash();
    }

    public function testConstructor()
    {
        $this->assertEquals(SessionFlash::FLASH_LEVEL_CONTEXTUAL, $this->sessionFlash->getLevel());
        $this->assertEquals('contextual', SessionFlash::FLASH_LEVEL_CONTEXTUAL);
        $this->assertEquals('global', SessionFlash::FLASH_LEVEL_GLOBAL);
        $this->assertEquals('danger', SessionFlash::FLASH_TYPE_DANGER);
        $this->assertEquals('info', SessionFlash::FLASH_TYPE_INFO);
        $this->assertEquals('error', SessionFlash::FLASH_TYPE_ERROR);
        $this->assertEquals('success', SessionFlash::FLASH_TYPE_SUCCESS);
        $this->assertEquals('warning', SessionFlash::FLASH_TYPE_WARNING);
        $this->assertTrue($this->sessionFlash->getTranslate());
    }

    public function testLevelContextual()
    {
        $this->assertEquals(SessionFlash::FLASH_LEVEL_CONTEXTUAL, $this->sessionFlash->levelContextual()->getLevel());
    }

    public function testLevelGlobal()
    {
        $this->assertEquals(SessionFlash::FLASH_LEVEL_GLOBAL, $this->sessionFlash->levelGlobal()->getLevel());
    }

    public function testAddDanger()
    {
        $msg = 'message_to_show';
        $expectedData = array(
            'message_to_show'
        );

        $flashName = SessionFlash::FLASH_LEVEL_CONTEXTUAL . '_' . SessionFlash::FLASH_TYPE_DANGER;
        $this->sessionFlash->noTranslation()->addDanger($msg);

        $this->assertEquals($expectedData, Session::get_flash($flashName));
    }

    public function testAddInfo()
    {
        $msg = 'message_to_show';
        $expectedData = array(
            'message_to_show'
        );

        $flashName = SessionFlash::FLASH_LEVEL_CONTEXTUAL . '_' . SessionFlash::FLASH_TYPE_INFO;
        $this->sessionFlash->noTranslation()->addInfo($msg);

        $this->assertEquals($expectedData, Session::get_flash($flashName));
    }

    public function testAddError()
    {
        $msg = 'message_to_show';
        $expectedData = array(
            'message_to_show'
        );

        $flashName = SessionFlash::FLASH_LEVEL_CONTEXTUAL . '_' . SessionFlash::FLASH_TYPE_ERROR;
        $this->sessionFlash->noTranslation()->addError($msg);

        $this->assertEquals($expectedData, Session::get_flash($flashName));
    }

    public function testAddSuccess()
    {
        $msg = 'message_to_show';
        $expectedData = array(
            'message_to_show'
        );

        $flashName = SessionFlash::FLASH_LEVEL_CONTEXTUAL . '_' . SessionFlash::FLASH_TYPE_SUCCESS;
        $this->sessionFlash->noTranslation()->addSuccess($msg);

        $this->assertEquals($expectedData, Session::get_flash($flashName));
    }

    public function testAddWarning()
    {
        $msg = 'message_to_show';
        $expectedData = array(
            'message_to_show'
        );

        $flashName = SessionFlash::FLASH_LEVEL_CONTEXTUAL . '_' . SessionFlash::FLASH_TYPE_WARNING;
        $this->sessionFlash->noTranslation()->addWarning($msg);

        $this->assertEquals($expectedData, Session::get_flash($flashName));
    }

    public function testaddMessageEmptyMsg()
    {
        $this->setExpectedException('Exception', 'msg is empty');
        $this->sessionFlash->noTranslation()->addInfo('');
    }

    public function testaddMessageEmptyLevel()
    {
        $this->sessionFlash->setLevel('');
        $this->setExpectedException('Exception', 'level is empty');
        $this->sessionFlash->noTranslation()->addInfo('msg');
    }

    public function testMultipleMessage()
    {
        $msg = $expectedData = array(
            'message_to_show 1',
            'message_to_show 2'
        );

        $flashName = SessionFlash::FLASH_LEVEL_CONTEXTUAL . '_' . SessionFlash::FLASH_TYPE_DANGER;
        $this->sessionFlash->noTranslation()->addDanger($msg);

        $this->assertEquals($expectedData, Session::get_flash($flashName));
    }

    public function testTranslation()
    {
        $this->assertFalse($this->sessionFlash->noTranslation()->getTranslate());
        $this->assertTrue($this->sessionFlash->translation()->getTranslate());
        $this->assertFalse($this->sessionFlash->noTranslation()->getTranslate());
        $this->assertTrue($this->sessionFlash->translation()->getTranslate());
    }
}
