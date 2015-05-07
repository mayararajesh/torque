<?php
$this->breadcrumbs = array(
    'Task' => array('index'),
    'List'=> array('list'),
    $taskDetails['Job_Id'].".".$taskDetails['Job_Name'],
    "details"
);

$this->menu = array(
    array('label' => 'New', 'url' => array('index')),
    array('label' => 'List', 'url' => array('list')),
);
?>
<h4 style="text-align: center;">Job Details</h4>
<?php
echo REQUIRED::createXDetailView($taskDetails);
?>