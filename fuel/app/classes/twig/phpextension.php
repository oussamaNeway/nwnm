<?php

namespace Fuel\Twig;

use Twig_Extension;

class PhpExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('addslashes', 'addslashes'),
            new \Twig_SimpleFilter('dump', 'var_dump'),
            new \Twig_SimpleFilter('to_array', 'get_object_vars')
        );
    }
    
    public function getFunctions() {
        return array(
            'json_decode' => new \Twig_Function_Method($this, 'jsonDecode'),
            'json_encode' => new \Twig_Function_Method($this, 'jsonEncode'),
            'strftime' => new \Twig_Function_Method($this, 'strftime'),
            'is_ajax' => \Input::is_ajax(),
            'in_array' => new \Twig_Function_Method($this, 'in_array'),
            'pow' => new \Twig_Function_Method($this, 'pow')
        );
    }
    
    public function getName()
    {
        return 'PhpExtension';
    }
    
    public function jsonDecode($value)
    {
        return json_decode($value, true);
    }
    
    public function jsonEncode($value)
    {
        return json_encode($value);
    }
    
    public function strftime($format, $value)
    {
        return strftime($format, $value);
    }

    public function in_array($needle, $haystack)
    {
        return in_array($needle, $haystack);
    }

    public function pow($base, $exp)
    {
        return pow($base, $exp);
    }
}