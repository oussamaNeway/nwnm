<?php

namespace Fuel\Twig;

use Fuel\Core\Router;
use \Twig_Extension;

class ExtranetReadDirectionAdaptExtension extends Twig_Extension
{

    const ENCLOSURE_START = 0;
    const ENCLOSURE_END = 1;

    protected $rtl = null;
    protected $readDirection = null;
    protected $readDirectionInverse = null;

    public function __construct()
    {
        $this->rtl = \Session::get('user')['rtl'];
        $this->readDirection = $this->rtl ? 'rtl':'ltr';
        $this->readDirectionInverse = !$this->rtl ? 'rtl':'ltr';
    }

    /**
     * Sets up all of the functions this extension makes available.
     *
     * @return  array
     */
    public function getFunctions()
    {
        return array(
            'rtl_adapt' => new \Twig_Function_Method($this, 'rtl_adapt'),
            'ltr_adapt' => new \Twig_Function_Method($this, 'ltr_adapt')
        );
    }

    /**
     * getName
     * @return Twig_Extension name
     */
    public function getName()
    {
        return 'ExtranetReadDirectionAdaptExtension';
    }

    protected function _adapt($elements, $enclosure = null, $glue = ' ')
    {
        $start = null === $enclosure || !isset($enclosure[self::ENCLOSURE_START]) ? '':$enclosure[self::ENCLOSURE_START];
        $end = null === $enclosure || !isset($enclosure[self::ENCLOSURE_END]) ? '':$enclosure[self::ENCLOSURE_END];

        foreach ($elements as $key => $element) {
            $elements[$key] = str_replace('readDirection', $this->readDirection, $element);
            $elements[$key] = str_replace('readDirectionInverse', $this->readDirectionInverse, $element);
        }

        $adapted = !$this->rtl ? $elements:array_reverse($elements);

        return $start . implode($glue, $adapted) . $end;
    }

    public function ltr_adapt($elements, $enclosure = null, $glue = ' ')
    {
        return $this->_adapt($elements, $enclosure, $glue);
    }

    public function rtl_adapt($elements, $enclosure = null, $glue = ' ')
    {
        return $this->_adapt($elements, $enclosure, $glue);
    }
}
