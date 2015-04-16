<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

  <div class="row-fluid">
	<div class="span3">
		<div class="sidebar-nav">        
		  <?php $this->widget('zii.widgets.CMenu', array(
			/*'type'=>'list',*/
			'encodeLabel'=>false,
			'items'=>array(
				//array('label'=>'<i class="icon icon-home"></i>  Dashboard <span class="label label-info pull-right">BETA</span>', 'url'=>array("monitor/monitorParam"),'itemOptions'=>array('class'=>'')),				
				
				// Include the operations menu
				//array('label'=>'OPERATIONS','items'=>$this->menu),
			),
			));?>		
	<?php
        $this->activeMenu=  isset($this->activeMenu)?$this->activeMenu:0;
                $this->widget('zii.widgets.jui.CJuiAccordion',array(
                    'panels'=>$this->monitordashboard,
    // additional javascript options for the accordion plugin
                    'options'=>array(
				'collapsible'=>false,
				'autoHeight'=>false,
				'active'=>$this->activeMenu,
		),
                //'themeUrl'=>Yii::app()->baseUrl.'/css/jq',		
		//'htmlOptions'=>array(
		//'class'=>'dashBoard',                
		//),
));
	?>
		</div>
        <br>        	
		
    </div><!--/span-->
    <div class="span9">
    
    <?php if(isset($this->breadcrumbs)):
		require_once('tpl_breadcrumbs.php');
?>
		<!-- breadcrumbs -->
    <?php endif?>
    
    <!-- Include content pages -->
    <?php echo $content; ?>

	</div><!--/span-->
  </div><!--/row-->


<?php $this->endContent(); ?>
