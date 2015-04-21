<?php
$this->breadcrumbs=array(
	'Settings'=>array('/setting'),
	'NFS Settings'=>array('/setting/nfs'),
	'Add NFS Share',
);
include('_sidemenu.php');
?>

<h1>Add NFS Share</h1>

<?php echo $this->renderPartial('_formnfs', array('model'=>$model)); ?>