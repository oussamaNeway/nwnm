<?php
namespace Responsive\Controller;

use \Fuel\Core\Controller_Template;
use Fuel\Core\File;
use Fuel\Core\Finder;
use Fuel\Core\Input;
use Fuel\Core\Request;
use Fuel\Core\Response;
use Fuel\Core\Upload;
use Fuel\Core\View;


class ResponsiveController extends Controller_Template
{
    public $template = 'layout.twig';

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

        $this->template->menuTop = Request::forge('responsive/MenutopController/Index',false)->execute()->response()->body;
        $this->template->rightSide = Request::forge('responsive/RightsideController/Index',false)->execute()->response()->body;
        $this->template->centerBlock = Request::forge('responsive/CenterblockController/Index',false)->execute()->response()->body;

    }
    public function action_Upload()
    {

        $nomRepertoire = Input::post('nomHtml');
        if( File::exists(DOCROOT.DS.'savedwork/'.$nomRepertoire))
            File::create_dir(DOCROOT.DS.'savedwork/', $nomRepertoire, 0755);

        $configHtml = array(
            'path' =>  DOCROOT.DS.'savedwork/'.$nomRepertoire,
            'randomize' => false,
            'ext_whitelist' => array('html','img', 'jpg', 'jpeg', 'gif', 'png','zip','rar'),
        );
        Upload::process($configHtml);
        if (Upload::is_valid()) {
            Upload::register('before', function (&$file) {
                $file['saved_as']= $file['name'];
            });
            Upload::save();


            foreach(Upload::get_files() as $value){
                if($value['extension']=="zip" || $value['extension']=="rar"){
                    $zip = new \ZipArchive();
                    $pathFile = str_replace("\\","/",$value['saved_to'].$value['saved_as']);
                    echo $pathFile."<br />";
                    echo file."<br />";
                    if ($zip->open($pathFile) == true){
                        echo $zip->numFiles."<br />";
                        $path = str_replace("\\","/",$value['saved_to']);
                        echo $path."<br />";
                        var_dump($zip);
die();
                        $zip->extractTo($path);
                        $zip->close();
                        echo 'Done.';
                    } else {
                        echo "Error";
                    }


                }
            }

            die();

        }

        return Response::redirect('http://netmessage.neway-si.com/responsive/simplifie/'.$nomRepertoire);
    }




}
