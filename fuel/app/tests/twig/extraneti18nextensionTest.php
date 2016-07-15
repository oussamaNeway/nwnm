<?php

namespace Fuel\Core;

use \Fuel\Twig\extraneti18nextension as ExtranetI18nExtension;

class Lang
{
    public static $dictionaryLoaded = false;

    public static function get($value)
    {
        if (preg_match('/NOTRANSLATION/', $value)) {
            return '';
        } elseif (self::$dictionaryLoaded) {
            return $value . ' ' . self::$dictionaryLoaded;
        } else {
            return $value . ' translated';
        }
    }

    public static function load ($dictionary)
    {
        self::$dictionaryLoaded = $dictionary;
    }
}

class ExtranetI18nExtensionTest extends TestCase
{
    public $extranetI18nExtension = null;

    public function setUp()
    {
        $this->extranetI18nExtension = new ExtranetI18nExtension();
    }

    public function testGetName()
    {
        $twigExtensionName = 'ExtranetI18nExtension';

        $this->assertEquals($this->extranetI18nExtension->getName(), $twigExtensionName);
    }

    public function testGetFunctions()
    {
        $functions = array(
            'trans' => new \Twig_Function_Method($this->extranetI18nExtension, 'translate')
        );

        $this->assertEquals($this->extranetI18nExtension->getFunctions(), $functions);
    }

    /**
     * @dataProvider translateProvider
     */
    public function testTranslate($arg1, $arg2, $arg3, $translation)
    {
        Lang::$dictionaryLoaded = false;

        $this->assertEquals($translation, $this->extranetI18nExtension->translate($arg1, $arg2, $arg3));
    }

    public function translateProvider()
    {
        return array(
            array('key', null, null, 'key translated'),
            array('key NOTRANSLATION', null, null, 'key NOTRANSLATION'),
            array('key :test :approved', array('test' => 'testé', 'approved' => 'approuvé'), null, 'key testé approuvé translated'),
            array('key', null, 'dico', 'key dico'),
            array('key', 'NewDico', null, 'key NewDico'),
        );
    }

    public function testTranslateExceptionNoArgument()
    {
        $this->setExpectedException('Exception', 'ExtranetI18nExtension::translate invoked with no argument');
        $this->extranetI18nExtension->translate();
    }

    /**
     * @dataProvider translateExceptionWrongArgument2Provider
     */
    public function testTranslateExceptionWrongArgument2($arg2)
    {
        $this->setExpectedException('Exception', 'ExtranetI18nExtension::translate argument 2 must be a string');
        $this->extranetI18nExtension->translate('key', array(), $arg2);
    }

    public function translateExceptionWrongArgument2Provider()
    {
        return array(
            array(array()),
            array(1),
            array(new Lang()),
        );
    }

    /**
     * @dataProvider translateExceptionWrongArgument1Provider
     */
    public function testTranslateExceptionWrongArgument1($arg1)
    {
        $this->setExpectedException('Exception', 'ExtranetI18nExtension::translate argument 2 must be an array or a string');
        $this->extranetI18nExtension->translate('key', $arg1);
    }

    public function translateExceptionWrongArgument1Provider()
    {
        return array(
            array(1),
            array(new Lang()),
        );
    }

    /**
     * @dataProvider replaceProvider
     */
    public function testReplace($text, $data, $expectedData)
    {
        $this->assertEquals($expectedData, $this->extranetI18nExtension->replace($text, $data));
    }

    public function replaceProvider()
    {
        return array(
            array('text :r1', array('r1' => 'r1replaced'), 'text r1replaced'),
            array('text :r1 :r2', array('r1' => 'r1replaced', 'r2' => 'newr2'), 'text r1replaced newr2'),
            array('text :r1 :r2', array(), 'text :r1 :r2'),
            array('text :r1 :r2', array(), 'text :r1 :r2'),
        );
    }
}