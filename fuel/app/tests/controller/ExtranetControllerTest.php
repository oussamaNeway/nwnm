<?php

//namespace Fuel\Core;

use \Controller\extranetcontroller as ExtranetController;

class myTemplate
{
    public $active_request;
    public $global = array();
    public $content;

    public function __construct()
    {
        $this->active_request = new myActiveRequest();
    }

    public function set_global($name, $value)
    {
        $this->global[$name] = $value;
    }

    public function set($name, $value)
    {
        $this->set_global($name, $value);
    }
}

class myActiveRequest
{
    public $route;
    public $action = '';

    public function __construct()
    {
        $this->route = new myRoute();
    }
}

class myRoute
{
    public $translation = 'User/UserManager/List';
}

class ExtranetControllerTest extends \Fuel\Core\TestCase
{
    private $requestMock;
    private $extranetController;

    public function setUp()
    {
        $this->requestMock = $this->getMockBuilder('Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $this->requestMock->route = new myRoute();
        
        $this->extranetController = new ExtranetController($this->requestMock);
    }

    public function testConstructor()
    {
        $this->assertEquals('template.twig', $this->extranetController->template);
        $this->assertEquals('', $this->extranetController->moduleName);
        $this->assertEquals(array(), $this->extranetController->datas);
        $this->assertInstanceOf('Fuel\\Extranet\\ExtranetResponse', $this->extranetController->getExtranetResponse());
        $this->assertInstanceOf('Fuel\\Extranet\\SessionFlash', $this->extranetController->getSessionFlash());
    }

    /**
     * @dataProvider beforeProvider
     */
    public function testBefore($locale, $timezone, $uid, $template, $moduleName,$expectedLocale, $expectedTimezone, $expectedTemplateToForge, $user, $countRedirect)
    {
        Session::destroy();
        $this->extranetController->template = $template;
        $this->extranetController->moduleName = $moduleName;

        $armCortex = new \Netmessage\Behaviour\ArmCortex();
        $armModel = new \Netmessage\Model\ArmModel();
        $armModel->allow = array('/');
        $armModel->forbid = array();

        $armCortex->cortexBind($armModel);

        $extranetResponseMock = $this->getMock('Fuel\\Extranet\\ExtranetResponse', array('redirect'));
        $extranetResponseMock->expects($this->exactly($countRedirect))
            ->method('redirect')
            ->with('user_login')
        ;
        $this->extranetController->setExtranetResponse($extranetResponseMock);

        Session::set('cortex', $armCortex->cortex);
        Session::set('locale', $locale);
        Session::set('timezone', $timezone);

        if (null !== $uid) {
            Session::set('uid', $uid);
        }

        if (null !== $user) {
            Session::set('user', $user);
        }

        $this->extranetController->before();

        $this->assertEquals(Config::get('language'), $expectedLocale);
        $this->assertEquals(setlocale(LC_ALL, 0), $expectedLocale);
        $this->assertEquals(date_default_timezone_get(), $expectedTimezone);
        $this->assertEquals($expectedTemplateToForge, $this->extranetController->templateToForge);

        if (null === $uid) {
            $expectedGlobal = array();
        } else {
            $expectedGlobal = array(
                'ltr' => (null !== $user['rtl']) ? !$user['rtl'] : true,
                'rtl' => (null !== $user['rtl']) ? $user['rtl'] : false,
                'readDirection' => ($user['rtl'] ? 'right':'left'),
                'readDirectionInverse' => (!$user['rtl'] ? 'right':'left'),
                'lang' => $expectedLocale,
                'navbar_list' => array(
                    '/user' => array(
                        'icon' => 'fa-user',
                        'list' => array(
                            array(
                                'icon' => 'fa-user',
                                'name' => 'navbar_user_account',
                                'url' => 'user_account'
                            ),
                            array(
                                'icon' => 'fa-plus',
                                'name' => 'navbar_user_new',
                                'url' => 'user_new'
                            ),
                            array(
                                'icon' => 'fa-list',
                                'name' => 'navbar_user_list',
                                'url' => 'user_list'
                            )
                        ),
                    ),
                    '/datamart' => array(
                        'icon' => 'fa-tasks',
                        'list' => array(
                            0 => array(
                                'icon' => 'fa-upload',
                                'name' => 'navbar_datamart_import',
                                'url' => 'datamart_import'
                            ),
                            array(
                                'icon' => 'fa-list',
                                'name' => 'navbar_datamart_import_lists',
                                'url' => 'import_list_list'
                            )
                        )
                    ),
                    '/company/manage' => array(
                        'icon' => 'fa-building-o',
                        'list' => array(
                            array(
                                'icon' => 'fa-building-o',
                                'name' => 'navbar_company_mycompany',
                                'url' => 'company_manager_mycompany'
                            ),
                            array(
                                'icon' => 'fa-plus',
                                'name' => 'navbar_company_add',
                                'url' => 'company_manager_add'
                            ),
                            array(
                                'icon' => 'fa-list',
                                'name' => 'navbar_company_list',
                                'url' => 'company_manager_list'
                            ),
                        ),
                    ),
                    '/campaign' => array(
                        'icon' => 'fa-share-alt',
                        'list' => array(
                            array(
                                'icon' => 'fa-plus',
                                'name' => 'navbar_campaign_new',
                                'url' => 'campaign_new'
                            ),
                            array(
                                'icon' => 'fa-list',
                                'name' => 'navbar_campaign_list',
                                'url' => 'campaign_list'
                            ),
                        ),
                    ),
                    '/netmessage' => array(
                        'icon' => 'fa-cogs',
                        'list' => array(
                            array(
                                'icon' => 'fa-users',
                                'name' => 'navbar_netmessage_arm',
                                'url' => 'netmessage_arm_list'
                            )
                        )
                    )
                ),
                'navbar' => \View::forge('navbar.twig'),
                'auth' => $uid,
                'action' => $template->active_request->action,
                'template_list' => 'list/template_list.twig',
                'is_ajax' => false,
                'timezone' => $expectedTimezone,
                'navbar_list_active' => str_replace('.twig', '', $expectedTemplateToForge)
            );
            
            if (null !== $user) {
                $expectedGlobal['hid'] = 'http://127.0.0.1:1337';
                $expectedGlobal['exid'] = false;
                $expectedGlobal['moid'] = $user['_id'];
            }
        }

        if (null !== $user && null !== $uid) {
            $expectedGlobal['user_session'] = $user;
        }

        $this->assertEquals($expectedGlobal, $template->global);
    }

    public function beforeProvider()
    {
        $user_ltr = array('rtl' => false, '_id' => 'id_user_ltr');
        $user_rtl = array('rtl' => true, '_id' => 'id_user_rtl');
        
        $locale_en_US = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'French_France.1252':'en_US';
        $locale_fr_CH = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'French_France.1252':'fr_CH';
        $locale_fr_FR = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'French_France.1252':'fr_FR';

        return array(
            array($locale_en_US, 'Europe/Berlin', 'uid', new myTemplate(), '', $locale_en_US, 'Europe/Berlin', 'user/usermanager/list.twig', null, 0),
            array($locale_fr_CH, 'Europe/Lisbon', 'uid', new myTemplate(), 'User', $locale_fr_CH, 'Europe/Lisbon', 'usermanager/list.twig', null, 0),
            array(null, 'Europe/Lisbon', 'uid', new myTemplate(), '', $locale_fr_FR, 'Europe/Lisbon', 'user/usermanager/list.twig', null, 0),
            array(null, null, 'uid', new myTemplate(), '', $locale_fr_FR, 'Europe/Paris', 'user/usermanager/list.twig', null, 0),
            array(null, null, 'uid', new myTemplate(), '', $locale_fr_FR, 'Europe/Paris', 'user/usermanager/list.twig', null, 0),
            array(null, null, 'uid', new myTemplate(), '', $locale_fr_FR, 'Europe/Paris', 'user/usermanager/list.twig', $user_ltr, 0),
            array(null, null, 'uid', new myTemplate(), '', $locale_fr_FR, 'Europe/Paris', 'user/usermanager/list.twig', $user_rtl, 0),
            array(null, null, null, new myTemplate(), '', $locale_fr_FR, 'Europe/Paris', null, null, 1),
        );
    }

    public function testAfter()
    {
        $datas = array(
            '1' => 'ok',
            '2' => 'youhou'
        );
        $template = new myTemplate();
        $this->extranetController->template = $template;
        $this->extranetController->templateToForge = 'template.twig';
        $this->extranetController->datas = $datas;
        $response = new \Fuel\Core\Response();

        $this->extranetController->after($response);

        $expectedView = \View::forge($this->extranetController->templateToForge);

        foreach($datas as $key => $value){
            $expectedView->set($key, $value, false);
        }

        $this->assertEquals($template->content, $expectedView);
    }

    public function testBeforeright()
    {
        Session::destroy();
        Session::set('cortex', array(
            array(
                'name' => '/',
                'has_right' => false
            )
        ));
        Session::set('uid', 'fefe');

        $extranetResponseMock = $this->getMock('Fuel\\Extranet\\ExtranetResponse', array('redirect'));
        $extranetResponseMock->expects($this->once())
            ->method('redirect')
            ->with('403')
        ;
        $this->extranetController->setExtranetResponse($extranetResponseMock);

        $this->extranetController->template = new myTemplate();

        $response = new \Fuel\Core\Response();
        $this->extranetController->before($response);
    }
}
