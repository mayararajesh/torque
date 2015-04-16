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
    public $layout = '//layouts/main';

    //--------------------------------------------------------------------------
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    //--------------------------------------------------------------------------
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated users to perform 'index' and 'submit' actions
                'actions' => array('index', 'submit'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $model = new TaskForm();
        $params = array('model' => $model);
        $params['show'] = 'form';
        if (isset($_POST['TaskForm'])) {
            $attributes = $_POST['TaskForm'];
            $model->attributes = $attributes;
            if ($model->validate()) {
                $script = $this->generateScript($attributes);
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
            $user = Yii::app()->user->name;
            $encryptedPassword = Yii::app()->user->password;
            $aes = new AES($encryptedPassword);
            $sshHost = new SSH($host, $port, $user);
            if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                if ($sshHost->writeStringToFile($filePath, $content)) {
                    $sshHost->cmd("chmod 0744 {$filePath}");
                    echo $sshHost->cmd(Yii::app()->params['torque']['qsubBin'] .
                            "/qsub {$filePath}") . "<br />";
                }
            } else {
                echo "Authentication Error";
            }
            $sshHost->disconnect();
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Returns the required script to submit the job
     * 
     * @param Array $attributes
     * @return string
     * @since  2.0
     */
    private function generateScript($attributes) {
        $outPutDir = Yii::app()->params['torque']['outputDir'];
        $script = "#!/bin/sh" . "\n" .
                "#PBS -N {$attributes['name']}" . "\n" .
                "#PBS -S /bin/bash" . "\n" .
                "#PBS -e $outPutDir/\$PBS_JOBID.\$PBS_JOBNAME.err" . "\n" .
                "#PBS -o $outPutDir/\$PBS_JOBID.\$PBS_JOBNAME.out" . "\n" .
                "#PBS -q {$attributes['queue']}" . "\n" .
                "#PBS -l nodes={$attributes['nodes']}:ppn={$attributes['ppn']}" . "\n" .
                "cd \$PBS_O_WORKDIR" . "\n" .
                "echo \"job started at `date`\"" . "\n" .
                Yii::app()->params['mpi']['binPath'] . "/mpirun -np \$NSLOTS -hostfile \$TMPDIR/machines mdrun_mpi -s rnase_cubic.tpr" . "\n" .
                "echo \"job completed at `date`\"";
        /*
          $script = "#PBS -N myjob" . "\n" .
          "#PBS -q batch" . "\n" .
          "#PBS -S /bin/sh" . "\n" .
          "cd \$PBS_O_WORKDIR" . "\n" .
          "echo \"Working directory is \$PBS_O_WORKDIR\"" . "\n" .
          "NPROCS=`wc -l < \$PBS_NODEFILE`" . "\n" .
          "NNODES=`uniq \$PBS_NODEFILE | wc -l`" . "\n" .
          "echo \"Running on host `hostname`\"" . "\n" .
          "echo \"Time is `date`\"" . "\n" .
          "echo \"Directory is `pwd`\"" . "\n" .
          "echo \"Using \${NPROCS} processors across \${NNODES} nodes\"";
         */
        return $script;
    }

}

# End of the TaskController Class
# End of the TaskController.php file
