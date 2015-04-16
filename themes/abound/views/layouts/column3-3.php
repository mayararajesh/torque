<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar"> 
            <div class="dashBoard-header" ><a href="<?php echo Yii::app()->createAbsoluteUrl('monitor/monitorParam');?>">Dashboard</a>  </div> 
	<?php
        $this->activeMenu=  isset($this->activeMenu)?$this->activeMenu:0;
                 //$monitor = new Monitor();
		//$monitor->activeMenu;
                $this->widget('zii.widgets.jui.CJuiAccordion',array(
                    'panels'=>$this->dashboard,
    // additional javascript options for the accordion plugin
                    'options'=>array(
                    'animate'=>'bounceslide',
                    'collapsible'=>false,
		    'autoHeight'=>false,
		    'active'=>$this->activeMenu,    
            ),
                'themeUrl'=>Yii::app()->baseUrl.'/css/jq',		
		'htmlOptions'=>array(
		'class'=>'dashBoard',                
		),
));
                //echo $this->dashBoard;
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>