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
                'actions' => array('index', 'submit', 'list', 'details', 'hold', 'release', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    //--------------------------------------------------------------------------
    /**
     * Shows the editor/form to generate/submit the job script
     */
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
     * Submit(s) the job by user using generated script
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
                unset($aes);
                if ($sshHost->writeStringToFile($filePath, $content)) {
                    $sshHost->cmd("chmod 0744 {$filePath}");
                    $message = $sshHost->cmd(Yii::app()->params['torque']['qsubBin'] . "/qsub {$filePath}");
                    $messages = split('.', $message);
                    if (count($messages) == 2) {
                        Yii::app()->user->setFlash('success', $message . " Job suceessfully submitted.");
                    } else {
                        Yii::app()->user->setFlash('error', $message);
                    }
                    $sshHost->disconnect();
                }
            } else {
                unset($aes);
                Yii::app()->user->setFlash('info', "Problem with authentication.Unable to submit the Job.");
            }
            unset($sshHost);
            $this->redirect(Yii::app()->createUrl('task/index'));
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
        return $script;
    }

    //--------------------------------------------------------------------------
    /**
     * Lists all job details which are submitted by the user(s).
     */
    public function actionList() {
        $tasks = new Job();
        $stringJSON = array();
        $user = Yii::app()->user->name;
        $params = array();
        if ($user !== "root") {
            $params['condition'] = "submitted_by='" . $user . "'";
        }
        $params['order'] = "job_id DESC";
        foreach ($tasks->findAll($params) as $key => $value) {
            array_push($stringJSON, json_decode($value->status, TRUE));
            $stringJSON[$key]['id'] = explode('.', $stringJSON[$key]['Job_Id']);
            $stringJSON[$key]['host'] = $stringJSON[$key]['id'][1];
            $stringJSON[$key]['id'] = $stringJSON[$key]['id'][0];
            unset($stringJSON[$key]['Job_Id']);
        }
        $this->render('list', array('model' => $stringJSON));
//        }
    }

    //--------------------------------------------------------------------------
    /**
     * Shows specified job details
     * 
     * @param Integer $id
     */
    public function actionDetails($id = NULL) {
        $attributes = array();
        $taskDetails = array();
        $task = new Job();
        $taskDetails = $task->findAllByAttributes(array('job_id' => $id));
        $this->render('details', array(
            'attributes' => $attributes,
            'taskDetails' => $taskDetails,
        ));
    }

    //--------------------------------------------------------------------------
    /*
      private function convertResponseToJSON($response, $taskDetails, &$attributes) {
      $xml = simplexml_load_string($response);
      $json = json_encode($xml);
      $taskDetails = json_decode($json, TRUE);
      $taskDetails = $taskDetails['Job'];
      $jobDetails = explode('.', $taskDetails['Job_Id']);
      $taskDetails['Job_Id'] = $jobDetails[0];
      foreach ($taskDetails as $k => $task) {
      $tmp = preg_replace('!_+!', ' ', $k);
      $tmp = ucwords(strtolower($tmp));
      $attributes[$k] = $tmp;
      }
      return $taskDetails;
      }
     */
    //--------------------------------------------------------------------------
    /**
     * Holds the specified job in torque(in specified queue)
     * 
     * @param integer $id
     */
    public function actionHold($id = NULL) {
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $user = Yii::app()->user->name;
        $accessJob = TRUE;
        if ($user !== "root") {
            if (!$this->isValidJob($id,$user)) {
                Yii::app()->user->setFlash('danger', "Unable to access the job.Seems it does not belongs to you.");
                $accessJob = FALSE;
            }
        }
        $encryptedPassword = Yii::app()->user->password;
        $aes = new AES($encryptedPassword);
        $sshHost = new SSH($host, $port, $user);
        if ($accessJob && $sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
            unset($aes);
            $cmd = "qhold " . $id;
            $response = $sshHost->cmd($cmd);
            if ($response !== '') {
                Yii::app()->user->setFlash('danger', $response);
            } else {
                Yii::app()->user->setFlash('success', "Successfully holds job #" . $id);
            }
            $this->updateJobsDB();
        }
        $sshHost->disconnect();
        $this->redirect(Yii::app()->createUrl('task/list'));
    }

    //--------------------------------------------------------------------------
    /**
     * Releases the specified job in torque(in specified queue)
     * @param integer $id
     */
    public function actionRelease($id = NULL) {
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $user = Yii::app()->user->name;
        $accessJob = TRUE;
        if ($user !== "root") {
            if (!$this->isValidJob($id,$user)) {
                Yii::app()->user->setFlash('danger', "Unable to access the job.Seems it does not belongs to you.");
                $accessJob = FALSE;
            }
        }
        $encryptedPassword = Yii::app()->user->password;
        $aes = new AES($encryptedPassword);
        $sshHost = new SSH($host, $port, $user);
        if ($accessJob && $sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
            unset($aes);
            $cmd = "qrls " . $id;
            $response = $sshHost->cmd($cmd);
            if ($response !== '') {
                Yii::app()->user->setFlash('danger', $response);
            } else {
                Yii::app()->user->setFlash('success', "Successfully releases job #" . $id);
            }
            $this->updateJobsDB();
        }
        $sshHost->disconnect();

        $this->redirect(Yii::app()->createUrl('task/list'));
    }

    //--------------------------------------------------------------------------
    /**
     * Updates the database with torque whenever is required.
     */
    private function updateJobsDB() {
        # create curl resource
        $ch = curl_init();
        # set url
        curl_setopt($ch, CURLOPT_URL, Yii::app()->createAbsoluteUrl('crawler/index?id=jobs'));
        # return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        # $output contains the output string
        $output = curl_exec($ch);
        # close curl resource to free up system resources
        curl_close($ch);
    }

    //--------------------------------------------------------------------------
    /**
     * Deletes the specified job in torque(in specified queue) via AJAX Call only
     * 
     * @param integer $id
     */
    public function actionDelete($id = NULL) {
        $responseArray['status'] = INVALID_REQUEST;
        $responseArray['message'] = "Invalid Request";
        if (isset($_POST)) {
            $host = Yii::app()->params->hostDetails['host'];
            $port = Yii::app()->params->hostDetails['port'];
            $user = Yii::app()->user->name;
            $accessJob = TRUE;
            if ($user !== "root") {
                if (!$this->isValidJob($id,$user)) {
                    $responseArray['status'] = INVALID_ACCESS;
                    $responseArray['message'] = "Unable to access the job.Seems it does not belongs to you.";
                    $accessJob = FALSE;
                }
            }
            $encryptedPassword = Yii::app()->user->password;
            $aes = new AES($encryptedPassword);
            $sshHost = new SSH($host, $port, $user);
            if ($accessJob && $sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                unset($aes);
                $cmd = "qdel " . $_POST['job_id'];
                $response = $sshHost->cmd($cmd);
                if ($response !== "") {
                    $responseArray['status'] = COMMAND_ERROR;
                    $responseArray['message'] = $response;
                } else {
                    $responseArray['status'] = SUCCESS;
                    $responseArray['message'] = "Successfully deleted job #" . $_POST['job_id'];
                    Yii::app()->user->setFlash('info', $responseArray['message']);
                }
                $this->updateJobsDB();
            }
            $sshHost->disconnect();
        }
        echo json_encode($responseArray);
    }

    //--------------------------------------------------------------------------
    /**
     * Validates wether job belongs to specified user or not
     * 
     * @param integer $id
     * @param string $user
     * @return boolean TRUE if job belongs to specified user otherwise FALSE
     */
    private function isValidJob($id, $user) {
        $task = new Job();
        return $task->exists('job_id=:job_id AND submitted_by=:submitted_by', array(
            ':job_id' => $id, 
            ':submitted_by' => $user));
    }

}

# End of the TaskController Class
# End of the TaskController.php file


    