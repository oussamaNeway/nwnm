<?php
namespace Responsive\Controller;

use \Fuel\Core\Controller_Template;


class MenutopController extends Controller_Template
{
    public $template = 'menutop.twig';
    public function before()
    {
        \Finder::instance()->add_path(APPPATH . 'modules/responsive/');
        \Finder::instance()->add_path(APPPATH . 'modules/responsive/views/');

        // \Config::set('language', 'en_GB');
        \Config::set('language', 'fr_FR');

        \Lang::load('responsive.json');

        parent::before();
    }
    public function action_Index()
    {

    }



}
