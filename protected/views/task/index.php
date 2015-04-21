<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tasks/tasks.css" />
<div class="task-area">
    <?php
    /* @var $this TaskController */

    $this->breadcrumbs = array(
        'task',
    );
    ?>
    <div class="messages">
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            ?>
            <div class="alert alert-<?php echo $key;?>">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong> <?php echo $message;?></strong>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    if ($show == "editor") {
        $this->renderPartial('editor', array('content' => $content));
    } else {
        $params['model'] = $model;
        if (isset($queues)) {
            $params['queues'] = $queues;
        }
        $this->renderPartial('_form', $params);
    }
    ?>

</div>