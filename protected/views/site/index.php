<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div style="right:0px;" class="productDesc">
    <ul>
        <li>GUI (Browser) based HPC Job submission portal</li>
        <li>End users quickly learn to manage and track their jobs, reducing administrative support burden</li>
        <li>Software intelligently applies rules based on the user, group, quality of service, etc. of submitter. (Role Bases Access)</li>
        <li><strong>License Analytic:</strong> Shows the real time Free / Busy License token of Applications</li>
        <li><strong>Monitoring:</strong> Real time Resource monitoring of cluster. As well as individual GUI Job monitoring by users. And viewing historical data of cluster resource usage</li>
        <li><strong>Reporting:</strong> Generate reports on Cluster usage, node usage, job, individual user, Department etc. for specified period</li>
        <li><strong>Scalability:</strong> Jobs can be manage &amp; submit to 1000+ node cluster (GPU &amp; Accelarator based). And to multiple clusters from a single portal</li>
    </ul>
</div>