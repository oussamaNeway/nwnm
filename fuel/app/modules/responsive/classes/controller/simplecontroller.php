<?php
namespace Responsive\Controller;

use \Fuel\Core\Controller_Template;
use Fuel\Core\File;
use Fuel\Core\Response;



class SimpleController extends Controller_Template
{
    public $template = 'simple/layoutSimple.twig';

    public function before()
    {
        \Finder::instance()->add_path(APPPATH . 'modules/responsive/');
        \Finder::instance()->add_path(APPPATH . 'modules/responsive/views/simple');

        // \Config::set('language', 'en_GB');
        \Config::set('language', 'fr_FR');

        \Lang::load('responsive.json');

        parent::before();
    }

    public function action_Simplifie()
    {
        $nomRepertoire = $this->param('name');
        $dir = File::read_dir(DOCROOT.DS.'savedwork/'.$nomRepertoire, 0, array(
            '!^\.', // no hidden files/dirs
            '!^private' => 'dir', // no private dirs
            '\.html$' => 'file', // only get html's
            '!^_', // exclude everything that starts with an underscore.
        ));
        $file_content = File::read(DOCROOT.DS.'savedwork/'.$nomRepertoire.'/'.$dir[0], true);

        $this->template->simpleEditeurBlock = \View::forge('simple/simple.twig',array("html" => $file_content,"root" => DOCROOT.DS.'savedwork\\'.$nomRepertoire));
        $this->template->menuTop = \View::forge('simple/menutopsimple.twig');
        $this->template->rightSide = \View::forge('simple/rightsidesimple.twig');


    }



}
