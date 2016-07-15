<?php

namespace Fuel\Twig;

use Behaviour\trans as Trans;
use Fuel\Core\Config;
use Fuel\Core\Lang;
use Fuel\Core\Log;
use \Twig_Extension;
use \Exception;

class ExtranetI18nExtension extends Twig_Extension
{
    /**
     * Sets up all of the functions this extension makes available.
     *
     * @return  array
     */
    public function getFunctions()
    {
        return array(
            'trans' => new \Twig_Function_Method($this, 'translate'),
        );
    }

    /**
     * getName
     * @return Twig_Extension name
     */
    public function getName()
    {
        return 'ExtranetI18nExtension';
    }

    public function translate()
    {
        $argList = func_get_args();

        if (0 === count($argList)) {
            throw new Exception("ExtranetI18nExtension::translate invoked with no argument", 1);
        }

        $key = $argList[0];
        $dictionary = null;
        $data = null;


        if (isset($argList[2])) {
            if (!is_string($argList[2])) {
                throw new Exception("ExtranetI18nExtension::translate argument 2 must be a string", 1);
            }

            $dictionary = $argList[2];
        }

        if (isset($argList[1])) {
            if (is_array($argList[1])) {
                $data = $argList[1];
            } elseif (is_string($argList[1])) {
                $dictionary = $argList[1];
            } else {
                throw new Exception("ExtranetI18nExtension::translate argument 2 must be an array or a string", 1);
            }
        }

        if (null !== $dictionary) {
            Lang::load($dictionary);
        }

        $translation = Trans::get($key);

        if (null !== $data) {
            return $this->replace($translation, $data);
        } else {
            return $translation;
        }
    }

    public function replace($text, $data)
    {
        foreach ($data as $key => $value) {
            $text = str_replace(':' . $key, $value, $text);
        }

        return $text;
    }
}
