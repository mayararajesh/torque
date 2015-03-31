<?php

/**
 * Queue Controller manages the torque sytsem queue
 * Manages queue properties,resources, user groups etc.,
 * 
 * @author  Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version 2.0
 * @since   2.0
 * 
 */
class QueueController extends Controller {

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
            'postOnly + delete', // we only allow deletion via POST request
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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'resource'),
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

    //--------------------------------------------------------------------------
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $resourceAvailable = new ResourcesAvailable();
        $resourceDefault = new ResourcesDefault();
        $resourceMax = new ResourcesMax();
        $resourceMin = new ResourcesMin();
        $model = $this->loadModel($id);
        $this->render('view', array(
            'model' => $model,
            'available' => $resourceAvailable->findByAttributes(array('queue_id' => $model->id)),
            'default' => $resourceDefault->findByAttributes(array('queue_id' => $model->id)),
            'max' => $resourceMax->findByAttributes(array('queue_id' => $model->id)),
            'min' => $resourceMin->findByAttributes(array('queue_id' => $model->id)),
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Queue;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Queue'])) {
            $attributes = $_POST['Queue'];
            $arrayCmds = array();
            $host = Yii::app()->params->hostDetails['host'];
            $port = Yii::app()->params->hostDetails['port'];
            $sshHost = new SSH($host, $port, 'root');
            if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
                if ($attributes['name'] !== "" && $attributes['queue_type'] !== "") {
                    $cmd = 'qmgr -c "create queue' . $attributes['name'] . ' queue_type=' . $attributes['queue_type'] . '"';
                    array_push($arrayCmds, $cmd);
                    if (isset($attributes['disallowed_types'])) {
                        $tempStr = "";
                        foreach ($attributes['disallowed_types'] as $attribute) {
                            $tempStr .= $attribute . ",";
                            $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' disallowed_types+=' . $attribute . '"';
                            array_push($arrayCmds, $cmd);
                        }
                        $tempStr = trim($tempStr, ",");
                        unset($attributes['disallowed_types']);
                        $attributes['disallowed_types'] = $tempStr;
                    }
                    if (isset($attributes['enabled']) && (int) $attributes['enabled'] === 1) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' enabled=true"';
                    } else {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' enabled=false"';
                    }
                    array_push($arrayCmds, $cmd);
                    if (!isset($attributes['keep_completed'])) {
                        $attributes['keep_completed'] = 0;
                    }
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' keep_completed=' . $attributes['keep_completed'] . '"';
                    array_push($arrayCmds, $cmd);
                    if (!isset($attributes['kill_delay'])) {
                        $attributes['kill_delay'] = 2;
                    }
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' kill_delay=' . $attributes['kill_delay'] . '"';
                    array_push($arrayCmds, $cmd);
                    if (isset($attributes['max_queuable'])) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_queuable=' . $attributes['max_queuable'] . '"';
                        array_push($arrayCmds, $cmd);
                    }
                    if (isset($attributes['max_running'])) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_running=' . $attributes['max_running'] . '"';
                        array_push($arrayCmds, $cmd);
                    }
                    if (isset($attributes['max_user_queuable'])) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_user_queuable=' . $attributes['max_user_queuable'] . '"';
                        array_push($arrayCmds, $cmd);
                    }
                    if (isset($attributes['max_user_run'])) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_user_run=' . $attributes['max_user_run'] . '"';
                        array_push($arrayCmds, $cmd);
                    }
                    if (!isset($attributes['priority'])) {
                        $attributes['priority'] = 0;
                    }
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' priority=' . $attributes['priority'] . '"';
                    array_push($arrayCmds, $cmd);
                    if (isset($attributes['require_login_property'])) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' require_login_property=' . $attributes['require_login_property'] . '"';
                        array_push($arrayCmds, $cmd);
                    }
                    if (isset($attributes['started']) && (int) $attributes['started'] === 1) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' started=true"';
                    } else {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' started=false"';
                    }
                    array_push($arrayCmds, $cmd);
                    foreach ($arrayCmds as $cmd) {
                        $sshHost->cmd($cmd);
                    }
                    $sshHost->disconnect();
                    $model->attributes = $attributes;
                    if ($model->save()) {
                        $this->redirect(array('view', 'id' => $model->id));
                    }
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Queue'])) {
            $model->attributes = $_POST['Queue'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    //--------------------------------------------------------------------------
    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Queue');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Queue('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Queue']))
            $model->attributes = $_GET['Queue'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Queue the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Queue::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    //--------------------------------------------------------------------------
    /**
     * Performs the AJAX validation.
     * @param Queue $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'queue-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Manages the resources like (resources_[available|default|max|min]) 
     * this function creates/updates the above features for the queue
     * @since       2.0
     */
    public function actionResource($type, $id) {
        if ($type == NULL || $id == NULL) {
            echo "Invalid Request";
            exit;
        }
        $model = NULL;
        $queue = $this->loadModel($id);
        $postString = "";
        $resourceType = "";
        if ($type == 'available') {
            $model = new ResourcesAvailable();
            $postString = 'ResourcesAvailable';
            $resourceType = 'resources_availble';
        } else if ($type == 'default') {
            $model = new ResourcesDefault();
            $postString = 'ResourcesDefault';
            $resourceType = 'resources_default';
        } else if ($type == 'max') {
            $model = new ResourcesMax();
            $postString = 'ResourcesMax';
            $resourceType = 'resources_max';
        } else if ($type == 'min') {
            $model = new ResourcesMin();
            $postString = 'ResourcesMin';
            $resourceType = 'resources_min';
        }
        $data = $model->findByAttributes(array('queue_id' => $queue->id));
        $modelTemp = $data ? $data : NULL;
        if (isset($_POST[$postString])) {
            $commandArray = array();
            $attributes = $_POST[$postString];
            if (isset($attributes['arch']) && $attributes['arch'] !== "") {
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.arch=' . $attributes['arch'] . '"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['mem']) && $attributes['mem']['number'] !== "" && $attributes['mem']['multiplier'] !== "") {
                #$multiplier = 1;
                if ($attributes['mem']['multiplier'] === "mb") {
                    $attributes['mem'] = (int) $attributes['mem']['number'] . 'mb';
                } else if ($attributes['mem']['multiplier'] === "gb") {
                    $attributes['mem'] = (int) $attributes['mem']['number'] . 'gb';
                } else if ($attributes['mem']['multiplier'] === "tb") {
                    $attributes['mem'] = (int) $attributes['mem']['number'] . 'tb';
                }
                #$attributes['mem'] = (int) $attributes['mem']['number'] * $multiplier;
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.mem=' . $attributes['mem'] . 'b"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['pvmem']) && $attributes['pvmem']['number'] !== "" && $attributes['pvmem']['multiplier'] !== "") {
                if ($attributes['pvmem']['multiplier'] === "MB") {
                    $attributes['pvmem'] = (int) $attributes['pvmem']['number'] . 'mb';
                } else if ($attributes['pvmem']['multiplier'] === "GB") {
                    $attributes['pvmem'] = (int) $attributes['pvmem']['number'] . 'gb';
                } else if ($attributes['pvmem']['multiplier'] === "TB") {
                    $attributes['pvmem'] = (int) $attributes['pvmem']['number'] . 'tb';
                }
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.pvmem=' . $attributes['pvmem'] . 'b"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['vmem']) && $attributes['vmem']['number'] !== "" && $attributes['vmem']['multiplier'] !== "") {
                if ($attributes['vmem']['multiplier'] === "MB") {
                    $attributes['vmem'] = (int) $attributes['vmem']['number'] . 'mb';
                } else if ($attributes['vmem']['multiplier'] === "GB") {
                    $attributes['vmem'] = (int) $attributes['vmem']['number'] . 'gb';
                } else if ($attributes['vmem']['multiplier'] === "TB") {
                    $attributes['vmem'] = (int) $attributes['vmem']['number'] . 'tb';
                }
                #$attributes['vmem'] = (int) $attributes['vmem']['number'] * $multiplier;
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.vmem=' . $attributes['vmem'] . 'b"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['ncpus']) && $attributes['ncpus'] !== "") {
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.ncpus=' . $attributes['ncpus'] . '"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['nodect']) && $attributes['nodect'] !== "") {
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.nodect=' . $attributes['nodect'] . '"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['nodes']) && $attributes['nodes'] !== "") {
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.nodes=' . $attributes['nodes'] . '"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['procct']) && $attributes['procct'] !== "") {
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.procct=' . $attributes['procct'] . '"';
                array_push($commandArray, $cmd);
            }
            if (isset($attributes['walltime']) && ($attributes['walltime']['hh'] !== "" || $attributes['walltime']['mm'] !== "" || $attributes['walltime']['ss'] !== "")) {
                $hoursInSeconds = ($attributes['walltime']['hh'] === "" ? 0 : (int) $attributes['walltime']['hh'] * 60 * 60);
                $minsInSeconds = ($attributes['walltime']['mm'] === "" ? 0 : (int) $attributes['walltime']['mm'] * 60);
                $seconds = ($attributes['walltime']['ss'] === "" ? 0 : (int) $attributes['walltime']['ss']);
                $totalSeconds = $hoursInSeconds + $minsInSeconds + $seconds;
                $attributes['walltime'] = $totalSeconds;
                $commandTime = (($attributes['walltime']['hh'] === "") ? '00' : $attributes['walltime']['hh'])
                        . ':' . (($attributes['walltime']['mm'] === "") ? '00' : $attributes['walltime']['mm'])
                        . ':' . (($attributes['walltime']['ss'] === "") ? '00' : $attributes['walltime']['ss']);
                $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.walltime=' . $commandTime . '"';
                array_push($commandArray, $cmd);
            }
            $attributes['queue_id'] = $queue->id;
            $model->attributes = $attributes;
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $queue->id));
            }
        } else {
            if ($modelTemp) {
                $attributes['arch'] = $modelTemp->arch;
                $memory = $modelTemp->mem;
                if ($memory) {
                    $attributes['mem']['number'] = (int) $memory;
                    $attributes['mem']['multiplier'] = substr($memory, strlen('' . $attributes['mem']['number']), strlen($memory));
                }
                $memory = $modelTemp->pvmem;
                if ($memory) {
                    $attributes['pvmem']['number'] = (int) $memory;
                    $attributes['pvmem']['multiplier'] = substr($memory, strlen('' . $attributes['pvmem']['number']), strlen($memory));
                }
                $memory = $modelTemp->vmem;
                if ($memory) {
                    $attributes['vmem']['number'] = (int) $memory;
                    $attributes['vmem']['multiplier'] = substr($memory, strlen('' . $attributes['vmem']['number']), strlen($memory));
                }
                $attributes['ncpus'] = $modelTemp->ncpus;
                $attributes['nodect'] = $modelTemp->nodect;
                $attributes['nodes'] = $modelTemp->nodes;
                $attributes['procct'] = $modelTemp->procct;
                $walltime = (int) $modelTemp->walltime;
                $attributes['walltime']['hh'] = floor($walltime / 3600);
                $attributes['walltime']['mm'] = floor(($walltime - (int) $attributes['walltime']['hh']) / 60);
                $attributes['walltime']['ss'] = $walltime - ((int) $attributes['walltime']['hh'] * 3600) - ((int) $attributes['walltime']['mm'] * 60);
                $model->attributes = $attributes;
            }
        }
        $this->render('resource_form', array(
            'model' => $model,
            'queue' => $queue,
            'type' => ucfirst($type),
            'mtemp' => $modelTemp,
        ));
    }

}

# End of the QueueController Class
# End of the QueueController.php file