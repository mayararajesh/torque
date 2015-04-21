<?php

echo Yii::app()->createUrl(Yii::app()->baseUrl);
$this->widget('ext.elFinder.ServerFileInput', array(
        'model' => $model,
        'attribute' => 'serverFile',
        'connectorRoute' => 'elfinder/connector',
        )
);
 
// ElFinder widget
$this->widget('ext.elFinder.ElFinderWidget', array(
        'connectorRoute' => 'elfinder/connector',
      ));
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

