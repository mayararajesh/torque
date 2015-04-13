<?php

/**
 * Task Controller manages to submit the jobs into torque sytsem queue
 * 
 * @author  Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version 2.0
 * @since   2.0
 * 
 */
class TaskController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

//--------------------------------------------------------------------------
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'edit', 'submit'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'resource', 'acl'),
                'users' => array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $model = new TaskForm();
        $params = array('model' => $model);
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $sshHost = new SSH($host, $port, 'compute1');
        if ($sshHost->isConnected() && $sshHost->authenticate_pass('redhat')) {
            $cmd = 'qstat -Q |awk -F" " \'NR>2 {print $1}\'|sed -r \':a;N;$!ba;s/\n/,/g\'';
            $xmlQueueList = $sshHost->cmd($cmd);
            if ($xmlQueueList !== "") {
                $xmlQueueList = split(',', $xmlQueueList);
                $params['queues'] = $xmlQueueList;
            }
            unset($xmlQueueList);
        } else {
            echo "Authentication Error";
        }
        $sshHost->disconnect();
        $params['show'] = 'form';
        if (isset($_POST['TaskForm'])) {
            $attributes = $_POST['TaskForm'];
            $model->attributes = $attributes;
            $outPutDir = Yii::app()->params['torque']['outputDir'];
            if ($model->validate()) {
                $script = "#!/bin/sh" . "\n" .
                        "#PBS -N {$attributes['name']}" . "\n" .
                        "#PBS -S /bin/bash" . "\n" .
                        "#PBS -e $outPutDir/\$PBS_JOBID.\$PBS_JOBNAME.err" . "\n" .
                        "#PBS -o $outPutDir/\$PBS_JOBID.\$PBS_JOBNAME.out" . "\n" .
                        "#PBS -q {$attributes['queue']}" . "\n" .
                        "#PBS -l nodes={$attributes['nodes']}:ppn={$attributes['ppn']}" . "\n" .
                        "cd \$PBS_O_WORKDIR" . "\n" .
                        "cd /home/locuz/gromacs/rnase_cubic/" . "\n" .
                        "date" . "\n" .
                        Yii::app()->params['mpi']['binPath'] . "/mpirun -np \$NSLOTS -hostfile \$TMPDIR/machines mdrun_mpi -s rnase_cubic.tpr" . "\n" .
                        "date";
                $outPutDir = Yii::app()->params['torque']['outputDir'];
                $params['content'] = $script;
                $params['show'] = 'editor';
            }
        }
        $this->render('index', $params);
    }

    //--------------------------------------------------------------------------
    /**
     * 
     */
    public function actionSubmit() {
        if (isset($_POST)) {
            $scriptName = $_POST['script-name'] . time() . '.sh';
            $content = $_POST['codemirror-text'];
            $content = str_replace("\r", "", $content);
            $outPutDir = Yii::app()->params['torque']['outputDir'];
            $filePath = $outPutDir . '/' . $scriptName;
            $commandArray = array();
            $host = Yii::app()->params->hostDetails['host'];
            $port = Yii::app()->params->hostDetails['port'];
            $sshHost = new SSH($host, $port, 'compute1');
            if ($sshHost->isConnected() && $sshHost->authenticate_pass('redhat')) {
                if ($sshHost->writeStringToFile($filePath, $content)) {
                    $sshHost->cmd("chmod 0744 {$filePath}");
                    echo $sshHost->cmd(Yii::app()->params['torque']['qsubBin'] . "/qsub {$filePath}") . "<br />";
                }
            } else {
                echo "Authentication Error";
            }
            $sshHost->disconnect();
        }
    }

}

# End of the TaskController Class
# End of the TaskController.php file
