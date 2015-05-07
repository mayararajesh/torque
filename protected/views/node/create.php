<?php
/* @var $this NodeController */
/* @var $model Node */

$this->breadcrumbs=array(
	'Nodes'=>array('index'),
	'Create',
);
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    if (Yii::app()->user->hasFlash($key)) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>";
    }
}
$this->menu=array(
	array('label'=>'List Node', 'url'=>array('index')),
	array('label'=>'Manage Node', 'url'=>array('admin')),
);

?>

<h1>Create Node</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>