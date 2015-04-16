<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class  NavMenu extends Controller
{
    public function topNavMenuItems() {
        
        return array(
                        array('label' => 'Home', 'url' => array('/site/index')),
                        array('label' => 'Task Manager', 'url' => array('/task'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Queue', 'url' => array('/queue'), 'visible' => Yii::app()->user->name == "root" ? TRUE : FALSE),
                        array('label' => 'Node', 'url' => array('/node'), 'visible' => Yii::app()->user->name == "root" ? TRUE : FALSE),
                        array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
                        array('label' => 'Contact', 'url' => array('/site/contact')),
                        array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                        array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                    );
        
    }
}
