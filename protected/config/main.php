<?php

/**
 * Application Configuration Details
 * 
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version 2.0
 * @since  2.0
 */
# uncomment the following to define a path alias
# Yii::setPathOfAlias('local','path/to/local-folder');
# This is the main Web application configuration. Any writable
# CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Ganana portal',
    'timeZone' => 'Asia/Calcutta',
    #preloading 'log' component
    'preload' => array('fontawesome', 'log', 'jquery'),
    #autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        #uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'gii123',
            'ipFilters' => array('127.0.0.1', '::1', '10.129.*.*'),
        ),
    ),
    #application components
    'components' => array(
        'user' => array(
            #enable cookie-based authentication
            'allowAutoLogin' => TRUE,
            'autoUpdateFlash' => FALSE,
        ),
        // ...
        'viewRenderer' => array(
            'class' => 'CPradoViewRenderer',
        ),
        'fontawesome' => array(
            'class' => 'ext.fontawesome.components.FontAwesome',
        ),
        #uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        #database settings are configured in database.php
//        'db' => require(dirname(__FILE__) . '/database.php'),
//        'errorHandler' => array(
//            #use 'site/error' action to display errors
//            'errorAction' => 'site/error',
//        ),
        'db' => array(
#'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
# uncomment the following lines to use a MySQL database
#'connectionString' => 'mysql:host=localhost;dbname=torque',
            'connectionString' => 'pgsql:host=localhost;dbname=torque;',
            #'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'root123',
            'charset' => 'utf8',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
            /* array(
              'class' => 'CFileLogRoute',
              'levels' => 'trace, info, error, warning',
              ),
              #uncomment the following to show log messages on web pages
              array(
              'class' => 'CWebLogRoute',
              'enabled' => YII_DEBUG,
              'levels' => 'error, warning, trace, notice',
              'categories' => 'application',
              'showInFireBug' => TRUE,
              ), */
            ),
        ),
    ),
    #application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        #this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        /**
         * Host Configuration details for the SSH Connection
         * 
         * @author Rajesh Mayara<rajesh.mayara@locuz.com>
         * @since 2.0
         */
        'hostDetails' => array(
            'host' => '10.129.154.83',
            'port' => 22
        ),
        'torque' => array(
            'serverPriv' => '/var/spool/torque/server_priv',
            'outputDir' => '/opt/results',
            'qsubBin' =>'/opt/torque/4.2.9/bin',
        ),
        'mpi' => array(
            'binPath' => '',
        ),
    ),
);
