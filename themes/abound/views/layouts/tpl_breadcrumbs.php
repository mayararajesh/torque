<?php if(isset($this->breadcrumbs)):?>
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
        'homeLink'=>isset($this->homeLink) && isset($this->homeLinkText) ? CHtml::link($this->homeLinkText, $this->homeLink) : NULL,
        'htmlOptions'=>array('class'=>'breadcrumb')
    )); ?>
<?php endif?>

