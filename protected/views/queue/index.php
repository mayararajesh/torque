<?php
/* @var $this QueueController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Queues',
);
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '>' . $message . '</div>';
}
$this->menu = array(
    array('label' => 'Create Queue', 'url' => array('create')),
    array('label' => 'Manage Queue', 'url' => array('admin')),
);
?>

<h1>Queues</h1>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'summaryText' => 'Page {page} of {pages}',
    'itemView' => '_view',
));
?>
