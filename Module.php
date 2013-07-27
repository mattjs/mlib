<?php

namespace Mlib;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
	public function getAutoloaderConfig() {
        return array(
            /*'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),*/
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
	
	public function getServiceConfig() {
        return array(
        	'factories' => array(
	       		'Mlib\Model\User' => function($sm) {
	            	if(!$sm->has('User')) {
	                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	                    $user = new Model\User($dbAdapter);
						$sm->setService('User', $user);
					} else {
						$user = $sm->get('User');
					}
	                return $user;
	            },
			)
		);
	}
}
