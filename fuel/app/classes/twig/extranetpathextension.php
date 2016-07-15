<?php

namespace Fuel\Twig;

use Fuel\Core\Router;
use Fuel\Core\Uri;
use \Twig_Extension;

class ExtranetPathExtension extends Twig_Extension
{
    /**
     * Sets up all of the functions this extension makes available.
     *
     * @return  array
     */
    public function getFunctions()
    {
        return array(
            'path' => new \Twig_Function_Method($this, 'path'),
            'uri' => new \Twig_Function_Method($this, 'uri'),
        );
    }

    /**
     * getName
     * @return Twig_Extension name
     */
    public function getName()
    {
        return 'ExtranetPathExtension';
    }

    public function path($wanted, $params = array())
    {
        return Router::get($wanted, $params);
    }

    public function uri($wanted, $params = array())
    {
        $path = $this->path($wanted, $params);

        return str_replace(Uri::base(), '/', $path);
    }
}
