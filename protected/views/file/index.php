<?php
/* @var $this FileController */

$this->breadcrumbs = array(
    'File',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<?php
$this->widget('widgets.elFinder.FinderWidget', array(
    'path' => '/files', // path to your uploads directory, must be writeable 
    'url' => Yii::app()->request->baseUrl.'/torque/files', // url to uploads directory 
    'action' => $this->createUrl('site/elfinder.connector') // the connector action (we assume we are pasting this code in the sitecontroller view file)
));
?>
