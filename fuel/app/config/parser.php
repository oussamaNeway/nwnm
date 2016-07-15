<?php
/**
* Fuel
*
* Fuel is a fast, lightweight, community driven PHP5 framework.
*
* @package    Fuel
* @version    1.7
* @author     Fuel Development Team
* @license    MIT License
* @copyright  2010 - 2013 Fuel Development Team
* @link       http://fuelphp.com
*/
return array(

    // ------------------------------------------------------------------------
    // Register extensions to their parsers, either classname or array config
    // ------------------------------------------------------------------------
    'extensions' => array(
        'twig'      => 'View_Twig',
    ),


    // ------------------------------------------------------------------------
    // Individual class config by classname
    // ------------------------------------------------------------------------

    // TWIG ( http://www.twig-project.org/documentation )
    // ------------------------------------------------------------------------
    'View_Twig' => array(
        'auto_encode' => true,
        'views_paths' => array(APPPATH.'views'),
        'delimiters' => array(
            'tag_block'     => array('left' => '{%', 'right' => '%}'),
            'tag_comment'   => array('left' => '{#', 'right' => '#}'),
            'tag_variable'  => array('left' => '{{', 'right' => '}}'),
        ),
        'environment' => array(
            'debug'                => false,
            'charset'              => 'utf-8',
            'base_template_class'  => 'Twig_Template',
            'cache'                => APPPATH.'cache'.DS.'twig'.DS,
            'auto_reload'          => true,
            'strict_variables'     => false,
            'autoescape'           => false,
            'optimizations'        => -1,
        ),
        'extensions' => array(
            'Twig_Fuel_Extension',
            'Fuel\\Twig\\extraneti18nextension',
            'Fuel\\Twig\\extranetpathextension',
            'Fuel\\Twig\\extranetreaddirectionadaptextension',
            'Fuel\\Twig\\phpextension'
        ),
    ),
);

// end of file parser.php
