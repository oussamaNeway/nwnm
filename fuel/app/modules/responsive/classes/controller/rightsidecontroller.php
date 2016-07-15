<?php
namespace Responsive\Controller;

use \Fuel\Core\Controller_Template;


class RightsideController extends Controller_Template
{
	public $template = 'rightside.twig';
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

		$this->template->contenu = \View::forge('rightsidecontenu.twig');
		$this->template->style = \View::forge('rightsidestyle.twig');
		$this->template->preview = \View::forge('rightsidepreview.twig');
		$this->template->galerie = \View::forge('rightsidegalerie.twig');

	}



}
