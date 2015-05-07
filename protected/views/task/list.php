<style>
    .font-icon{
        font-size: 14px;
    }
    .queue-status-icon{
        font-size: 24px;
    }
    .queue-status-icon-border{
        border: 0.08em solid #888;
        border-radius: 0.1em;
        padding: 0px 4px 0px 4px;
    }
    .grid-view td{
        text-align: center !important;
    }
    .queue-status-completed{
        color: green;
    }
    .queue-status-ban{
        color:red;
    }
    .grid-view .pager{
        vertical-align: text-top !important;
    }
    .null{
        color: red !important;
    }
</style>
<?php
/* @var $this TaskController */

$this->breadcrumbs = array(
    'Task' => array('index'),
    "List"
);
$this->menu = array(
    array('label' => 'New', 'url' => array('index')),
    array('label' => 'List', 'url' => array('list')),
);
?>
<div class="messages">
    <?php
    foreach (Yii::app()->user->getFlashes() as $key => $message) {
        ?>
        <div class="alert alert-<?php echo $key; ?>">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong> <?php echo $message; ?></strong>
        </div>
        <?php
    }
    ?>
</div>
<script type="text/javascript">
    function initilizeGridView() {
        /* deletes the links for which are having hold status  and delte links hold 
         * & delete links for having status Completed */
        var jobListTableBody = $('#task-list').find('table').find('tbody');
        $.each(jobListTableBody.find('tr'), function (k, v) {
            var jobStateObj = jobListTableBody.find('tr:eq(' + k + ')').find('td:eq(5)');
            var jobState = jobStateObj.text();
            var actionTD = jobListTableBody.find('tr:eq(' + k + ')').find('td:eq(7)');
            switch (jobState) {
                case "C":
                    var showDetails = actionTD.find('a:eq(0)');
                    showDetails = jQuery("<p>").append(showDetails.clone()).html();
                    actionTD.html(showDetails);
                    delete showDetails;
                    jobStateObj.html('<i class="queue-status-completed queue-status-icon fa fa-check-square"></i>');
                    break;
                case "H":
                    var action = actionTD.find('a:eq(1)').find('i');
                    action.removeClass('fa-pause');
                    action.addClass('fa-play');
                    jobStateObj.html('<i class="queue-status-completed queue-status-icon fa fa-check-square"></i>');
                    break;
                case "Q":
                    jobStateObj.html('<i class="fa fa-arrow-right fa-2x"><i class="queue-status-icon queue-status-icon-border fa fa-ellipsis-h fa-3x"></i></i>');
                    break;
                case "R":
                    jobStateObj.html('<i class="queue-status-icon fa fa-spinner fa-pulse fa-3x"></i>');
                    actionTD.find('a:eq(1)').remove();
                    actionTD.find('a:eq(1)').remove();
                    break;
                case "E":
                    jobStateObj.html('<i class="queue-status-icon fa fa-sign-out fa-3x"></i>');
                    break;
                case "T":
                    jobStateObj.html('<i class="queue-status-icon fa fa-external-link fa-3x"></i>');
                    break;
                case "S":
                    jobStateObj.html('<i class="queue-status-icon fa fa-external-link fa-3x"></i>');
                    break;
                case "W":
                    jobStateObj.html('<span class="fa-stack fa-3x">'
                            + '<i class="fa fa-spinner fa-pulse fa-stack-1x"></i>'
                            + '<i class="fa fa-ban fa-stack-2x"></i>'
                            + '</span>');
                    break;
            }

        });
    }
    var REQUEST_URL = "<?php echo Yii::app()->createAbsoluteUrl("task/delete"); ?>";
    $(document).ready(function () {
        $('body').on('click', '.delete-job', function () {
            var deleteJob = confirm("Are sure to delete this job?");
            if (deleteJob) {
                var jobId = $(this).closest('tr').find('td:eq(0)').text();
                $.post(REQUEST_URL, {
                    job_id: jobId
                },
                function (response) {
                    if (response.status == 100) {
                        location.reload();
                    } else {
                        var html = '<div class="alert alert-danger">' +
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                '<strong> ' + response.message + '</strong></div>';
                        $('.messages').html(html);
                    }
                },
                        'json');
            }
        });
        initilizeGridView();
    });
</script>
<?php
$this->renderPartial('list_search', array(
    'model' => $model
));
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/fontawesome/css/font-awesome.min.css');
?>