<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ElfinderController
 *
 * @author Rajesh
 */
// controller to host connector action
class ElfinderController extends CController {

    public function actions() {
        return array(
            'connector' => array(
                'class' => 'ext.elFinder.ElFinderConnectorAction',
                'settings' => array(
                    'driver'=> 'LocalFileSystem',
                    'root' => '/opt',
                    'URL' => Yii::app()->baseUrl . '/uploads',
                    'rootAlias' => 'Home',
                    'mimeDetect' => 'none'
                )
            ),
        );
    }
    
    public function actionIndex() {
        $this->actions();
        $model=new Elfinder();
        $this->render('index',array('model'=>$model));
    }
    
    public function actionElfinder($param) {
        
    }

}


