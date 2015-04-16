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
                'actions' => array('index', 'view', 'getQueueList'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'resource', 'acl'),
                'users' => array('root'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('root'),
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
        $model = $this->loadModel($id);
        $resourceAvailable = new ResourcesAvailable();
        $resourceDefault = new ResourcesDefault();
        $resourceMax = new ResourcesMax();
        $resourceMin = new ResourcesMin();
        $groups = new AclGroup();
        $users = new AclUser();
        $hosts = new AclHost();
        $attributes['id'] = NULL;
        $attributes['name'] = NULL;
        $attributes['queue_id'] = $model->id;
        $hosts->attributes = $attributes;
        $users->attributes = $attributes;
        $groups->attributes = $attributes;
        $this->render('view', array(
            'model' => $model,
            'available' => $resourceAvailable->findByAttributes(array('queue_id' => $model->id)),
            'default' => $resourceDefault->findByAttributes(array('queue_id' => $model->id)),
            'max' => $resourceMax->findByAttributes(array('queue_id' => $model->id)),
            'min' => $resourceMin->findByAttributes(array('queue_id' => $model->id)),
            'groups' => $groups,
            'users' => $users,
            'hosts' => $hosts,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Queue();
        $formModelObj = new QueuesForm();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['QueuesForm'])) {
            $attributes = $_POST['QueuesForm'];
            $commandArray = array();
            $formModelObj->attributes = $attributes;
            if ($formModelObj->validate()) {
                $attributes = $formModelObj->attributes;
                $cmd = 'qmgr -c "create queue ' . $attributes['name'] . ' queue_type=' . $attributes['queue_type'] . '"';
                array_push($commandArray, $cmd);
                $tempStr = "";
                if (isset($attributes['disallowed_types'])) {
                    foreach ($attributes['disallowed_types'] as $attribute) {
                        $tempStr .= $attribute . ",";
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' disallowed_types+=' . $attribute . '"';
                        array_push($commandArray, $cmd);
                    }
                    unset($attributes['disallowed_types']);
                }
                if ($tempStr !== "") {
                    $tempStr = trim($tempStr, ",");
                    $attributes['disallowed_types'] = $tempStr;
                }
                if (isset($attributes['enabled']) && (int) $attributes['enabled'] === 1) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' enabled=true"';
                    $attributes['enabled'] = TRUE;
                } else {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' enabled=false"';
                    $attributes['enabled'] = FALSE;
                }
                array_push($commandArray, $cmd);
                if (!isset($attributes['keep_completed'])) {
                    $attributes['keep_completed'] = 0;
                }
                $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' keep_completed=' . $attributes['keep_completed'] . '"';
                array_push($commandArray, $cmd);
                if (!isset($attributes['kill_delay'])) {
                    $attributes['kill_delay'] = 2;
                }
                $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' kill_delay=' . $attributes['kill_delay'] . '"';
                array_push($commandArray, $cmd);
                if (isset($attributes['max_queuable']) && $attributes['max_queuable'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_queuable=' . $attributes['max_queuable'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['max_running']) && $attributes['max_running'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_running=' . $attributes['max_running'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['max_user_queuable']) && $attributes['max_user_queuable'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_user_queuable=' . $attributes['max_user_queuable'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['max_user_run']) && $attributes['max_user_run'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_user_run=' . $attributes['max_user_run'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (!isset($attributes['priority'])) {
                    $attributes['priority'] = 0;
                }
                $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' priority=' . $attributes['priority'] . '"';
                array_push($commandArray, $cmd);
                if (isset($attributes['require_login_property'])) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' require_login_property=' . $attributes['require_login_property'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['started']) && (int) $attributes['started'] === 1) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' started=true"';
                    $attributes['started'] = TRUE;
                } else {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' started=false"';
                    $attributes['started'] = FALSE;
                }
                array_push($commandArray, $cmd);
                $host = Yii::app()->params->hostDetails['host'];
                $port = Yii::app()->params->hostDetails['port'];
                $user = Yii::app()->user->name;
                $encryptedPassword = Yii::app()->user->password;
                $aes = new AES($encryptedPassword);
                $sshHost = new SSH($host, $port, $user);
                if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                    foreach ($commandArray as $cmd) {
                        echo $sshHost->cmd($cmd);
                    }
                }
                $sshHost->disconnect();
                $model->attributes = $attributes;

                if ($model->save(FALSE)) {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }

        $this->render('create', array(
            'model' => $formModelObj,
            'modelTemp' => $model
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
        $formModelObj = new QueuesForm();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['QueuesForm'])) {
            $commandArray = array();
            $formModelObj->attributes = $_POST['QueuesForm'];

            if ($formModelObj->validate()) {
                $attributes = $formModelObj->attributes;
                $tempStr = NULL;
                $dbDisallowedTypes = split(',', $model->disallowed_types);
                $tempStr = "";
                foreach ($dbDisallowedTypes as $attribute) {
                    if (!empty($attribute)) {
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' disallowed_types-=' . $attribute . '"';
                        array_push($commandArray, $cmd);
                    }
                }
                if (is_array($attributes['disallowed_types'])) {
                    foreach ($attributes['disallowed_types'] as $attribute) {
                        $tempStr .= $attribute . ",";
                        $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' disallowed_types+=' . $attribute . '"';
                        array_push($commandArray, $cmd);
                    }
                    $tempStr = trim($tempStr, ",");
                }
                unset($attributes['disallowed_types']);
                $attributes['disallowed_types'] = $tempStr;
                if (isset($attributes['enabled']) && (int) $attributes['enabled'] === 1) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' enabled=true"';
                    $attributes['enabled'] = TRUE;
                } else {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' enabled=false"';
                    $attributes['enabled'] = FALSE;
                }
                array_push($commandArray, $cmd);
                if (!isset($attributes['keep_completed'])) {
                    $attributes['keep_completed'] = 0;
                }
                $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' keep_completed=' . $attributes['keep_completed'] . '"';
                array_push($commandArray, $cmd);
                if (!isset($attributes['kill_delay'])) {
                    $attributes['kill_delay'] = 2;
                }
                $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' kill_delay=' . $attributes['kill_delay'] . '"';
                array_push($commandArray, $cmd);
                if (isset($attributes['max_queuable']) && $attributes['max_queuable'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_queuable=' . $attributes['max_queuable'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['max_running']) && $attributes['max_running'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_running=' . $attributes['max_running'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['max_user_queuable']) && $attributes['max_user_queuable'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_user_queuable=' . $attributes['max_user_queuable'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['max_user_run']) && $attributes['max_user_run'] !== NULL) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' max_user_run=' . $attributes['max_user_run'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (!isset($attributes['priority'])) {
                    $attributes['priority'] = 0;
                }
                $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' priority=' . $attributes['priority'] . '"';
                array_push($commandArray, $cmd);
                if (isset($attributes['require_login_property'])) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' require_login_property=' . $attributes['require_login_property'] . '"';
                    array_push($commandArray, $cmd);
                }
                if (isset($attributes['started']) && (int) $attributes['started'] === 1) {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' started=true"';
                    $attributes['started'] = TRUE;
                } else {
                    $cmd = 'qmgr -c "set queue ' . $attributes['name'] . ' started=false"';
                    $attributes['started'] = FALSE;
                }
                $host = Yii::app()->params->hostDetails['host'];
                $port = Yii::app()->params->hostDetails['port'];
                $user = Yii::app()->user->name;
                $encryptedPassword = Yii::app()->user->password;
                $aes = new AES($encryptedPassword);
                $sshHost = new SSH($host, $port, $user);
                if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                    foreach ($commandArray as $cmd) {
                        echo $sshHost->cmd($cmd) . "<br />";
                    }
                }
                $sshHost->disconnect();
                $model->attributes = $attributes;
                if ($model->save()) {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        } else {
            $attributes = $model->attributes;
            unset($attributes['id']);
            $attributes['disallowed_types'] = split(',', $attributes['disallowed_types']);
            $formModelObj->attributes = $attributes;
        }
        $this->render('update', array(
            'model' => $formModelObj,
            'modelTemp' => $model,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $user = Yii::app()->user->name;
        $encryptedPassword = Yii::app()->user->password;
        $aes = new AES($encryptedPassword);
        $sshHost = new SSH($host, $port, $user);
        if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
            $response = $sshHost->cmd('qmgr -c "delete queue ' . $model->name . '"');
            $tag = 'success';
            $message = "Queue '" . $model->name . "' successfully deleted.";
            if ($response !== "") {
                $tag = 'error';
                $message = $response;
            } else {
                $model->delete();
            }
            Yii::app()->user->setFlash($tag, $message);
        }
        $sshHost->disconnect();
        # if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
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
            $resourceType = 'resources_available';
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
        $resourceModelObj = new ResorcesForm();
        if (isset($_POST['ResorcesForm'])) {
            $commandArray = array();
            $tempAttirbutes = $_POST['ResorcesForm'];
            $resourceModelObj->attributes = $tempAttirbutes;
            if ($resourceModelObj->validate()) {
                $tempAttirbutes = $resourceModelObj->attributes;
                if ($tempAttirbutes['memNumber'] !== NULL) {
                    $tempAttirbutes['mem'] = $tempAttirbutes['memNumber'] . $tempAttirbutes['mem_multiplier'];
                }
                if ($tempAttirbutes['vmemNumber'] !== NULL) {
                    $tempAttirbutes['vmem'] = $tempAttirbutes['vmemNumber'] . $tempAttirbutes['vmem_multiplier'];
                }
                if ($tempAttirbutes['pvmemNumber'] !== NULL) {
                    $tempAttirbutes['pvmem'] = $tempAttirbutes['pvmemNumber'] . $tempAttirbutes['pvmem_multiplier'];
                }
                unset($tempAttirbutes['memNumber']);
                unset($tempAttirbutes['vmemNumber']);
                unset($tempAttirbutes['pvmemNumber']);
                unset($tempAttirbutes['mem_multiplier']);
                unset($tempAttirbutes['vmem_multiplier']);
                unset($tempAttirbutes['pvmem_multiplier']);
                $commandTime = NULL;
                $dataTime = NULL;

                if ($tempAttirbutes['walltime_hh'] == NULL && $tempAttirbutes['walltime_mm'] == NULL && $tempAttirbutes['walltime_ss'] == NULL) {
                    $commandTime = NULL;
                    $cmd = 'qmgr -c "unset queue ' . $queue->name . ' ' . $resourceType . '.walltime"';
                    #echo $cmd;
                    array_push($commandArray, $cmd);
                } else {
                    if ($tempAttirbutes['walltime_hh'] == NULL) {
                        $tempAttirbutes['walltime_hh'] = '00';
                    }
                    if ($tempAttirbutes['walltime_mm'] == NULL) {
                        $tempAttirbutes['walltime_mm'] = '00';
                    }
                    if ($tempAttirbutes['walltime_ss'] == NULL) {
                        $tempAttirbutes['walltime_ss'] = '00';
                    }
                    $commandTime = $tempAttirbutes['walltime_hh'] . ':' . $tempAttirbutes['walltime_mm'] . ':' . $tempAttirbutes['walltime_ss'];
                }
                unset($tempAttirbutes['walltime_hh']);
                unset($tempAttirbutes['walltime_mm']);
                unset($tempAttirbutes['walltime_ss']);
                $tempAttirbutes['walltime'] = $commandTime;
                foreach ($tempAttirbutes as $key => $value) {
                    if ($value !== NULL) {
                        $cmd = 'qmgr -c "set queue ' . $queue->name . ' ' . $resourceType . '.' . $key . '=' . $value . '"';
                    } else {
                        $cmd = 'qmgr -c "unset queue ' . $queue->name . ' ' . $resourceType . '.' . $key . '"';
                    }
                    array_push($commandArray, $cmd);
                }
                $isAtleastOneFieldSet = FALSE;
                foreach ($tempAttirbutes as $k => $v) {
                    if ($v !== NULL) {
                        $isAtleastOneFieldSet = TRUE;
                        break;
                    }
                }
                if ($isAtleastOneFieldSet) {
                    $tempAttirbutes['queue_id'] = $queue->id;
                    $temp['queue_id'] = $tempAttirbutes['queue_id'];
                    $temp['arch'] = $tempAttirbutes['arch'];
                    $temp['mem'] = isset($tempAttirbutes['mem']) ? $tempAttirbutes['mem'] : NULL;
                    $temp['ncpus'] = $tempAttirbutes['ncpus'];
                    $temp['nodect'] = $tempAttirbutes['nodect'];
                    $temp['procct'] = $tempAttirbutes['procct'];
                    $temp['nodes'] = $tempAttirbutes['nodes'];
                    $temp['pvmem'] = isset($tempAttirbutes['pvmem']) ? $tempAttirbutes['pvmem'] : NULL;
                    $temp['vmem'] = isset($tempAttirbutes['vmem']) ? $tempAttirbutes['vmem'] : NULL;
                    $temp['walltime'] = $tempAttirbutes['walltime'];
                    $model->attributes = $temp;
                    $isNewData = FALSE;
                    $tempId = NULL;
                    if ($modelTemp === NULL) {
                        $isNewData = TRUE;
                    } else {
                        $tempId = $modelTemp->id;
                    }
                    if ($isNewData ? $model->save(FALSE) : $model->updateByPk($tempId, $temp)) {
                        $host = Yii::app()->params->hostDetails['host'];
                        $port = Yii::app()->params->hostDetails['port'];
                        $user = Yii::app()->user->name;
                        $encryptedPassword = Yii::app()->user->password;
                        $aes = new AES($encryptedPassword);
                        $sshHost = new SSH($host, $port, $user);
                        if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                            foreach ($commandArray as $cmd) {
                                Yii::app()->user->setFlash('notice', $sshHost->cmd($cmd));
                            }
                        }
                        $sshHost->disconnect();
                        $this->redirect(array('view', 'id' => $queue->id));
                    } else {
                        Yii::app()->user->setFlash('notice', "Something goes wrong.Please try again.");
                    }
                } else {
                    Yii::app()->user->setFlash('error', "You need to fill atleast one of the following details.");
                }
            }
        } else {
            if ($modelTemp) {
                $attributes['arch'] = $modelTemp->arch;
                $memory = $modelTemp->mem;
                if ($memory) {
                    $attributes['memNumber'] = (int) $memory;
                    $attributes['mem_ultiplier'] = substr($memory, strlen('' . $attributes['memNumber']), strlen($memory));
                }
                $memory = $modelTemp->pvmem;
                if ($memory) {
                    $attributes['pvmemNumber'] = (int) $memory;
                    $attributes['pvmem_multiplier'] = substr($memory, strlen('' . $attributes['pvmemNumber']), strlen($memory));
                }
                $memory = $modelTemp->vmem;
                if ($memory) {
                    $attributes['vmemNumber'] = (int) $memory;
                    $attributes['vmem_multiplier'] = substr($memory, strlen('' . $attributes['vmemNumber']), strlen($memory));
                }
                $attributes['ncpus'] = $modelTemp->ncpus;
                $attributes['nodect'] = $modelTemp->nodect;
                $attributes['nodes'] = $modelTemp->nodes;
                $attributes['procct'] = $modelTemp->procct;
                if ($modelTemp->walltime !== NULL || $modelTemp->walltime === "") {
                    $walltime = split(':', $modelTemp->walltime);
                    $attributes['walltime_hh'] = isset($walltime[0]) ? $walltime[0] : '00';
                    $attributes['walltime_mm'] = isset($walltime[1]) ? $walltime[1] : '00';
                    $attributes['walltime_ss'] = isset($walltime[2]) ? $walltime[2] : '00';
                } else {
                    $attributes['walltime_hh'] = "";
                    $attributes['walltime_mm'] = "";
                    $attributes['walltime_ss'] = "";
                }
                $attributes['queue_id'] = $modelTemp->queue_id;
                $attributes['id'] = $modelTemp->id;
                $resourceModelObj->attributes = $attributes;
            }
        }
        $this->render('resource_form', array(
            'model' => $resourceModelObj,
            'queue' => $queue,
            'type' => ucfirst($type),
            'mtemp' => $modelTemp,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Manages the ACL Controls of Queue like acl_[users|groups|hosts]
     */
    public function actionAcl($type, $id, $action = NULL, $aclId = NULL) {
        if ($type == NULL || $id == NULL) {
            echo "Invalid Request";
            exit;
        }
        $commandArray = array();
        $data = NULL;
        $resourceType = "";
        $modelTemp = new AclForm();
        switch ($type) {
            case 'groups':
                $modelForm = new AclGroup();
                $postString = 'AclGroup';
                $resourceType = 'acl_groups';
                break;
            case 'users':
                $modelForm = new AclUser();
                $postString = 'AclUser';
                $resourceType = 'acl_users';
                break;
            case 'hosts':
                $modelForm = new AclHost();
                $postString = 'AclHost';
                $resourceType = 'acl_hosts';
                break;
        }
        $model = $this->loadModel($id);
        $params = array('model' => $modelTemp, 'queue' => $model, 'data' => $modelForm, 'type' => $type);
        if (isset($_POST['AclForm'])) {
            $attributes['queue_id'] = (int) $model->id;
            $attributes = array_merge($attributes, $_POST['AclForm']);
            $modelTemp->attributes = $attributes;

            #print_r($model->attributes);
            #print_r($modelForm->attributes);
            #exit;
            if ($modelTemp->validate()) {
                // form inputs are valid, do something here

                $temp = array(
                    #'id' => $aclId,
                    'queue_id' => $model->id,
                    'name' => $attributes['name']
                );
                $modelForm->attributes = $temp;
                if ($aclId === NULL) {
                    $aclId = 0;
                }
                $cmd = 'qmgr -c "set queue ' . $model->name . ' ' . $resourceType . '+=' . $temp['name'] . '"';
                array_push($commandArray, $cmd);
                $host = Yii::app()->params->hostDetails['host'];
                $port = Yii::app()->params->hostDetails['port'];
                $user = Yii::app()->user->name;
                $encryptedPassword = Yii::app()->user->password;
                $aes = new AES($encryptedPassword);
                $sshHost = new SSH($host, $port, $user);
                if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                    foreach ($commandArray as $cmd) {
                        Yii::app()->user->setFlash('notice', $sshHost->cmd($cmd));
                    }
                }
                $sshHost->disconnect();
                if ((int) $modelForm->countByAttributes(array(), 'id=' . $aclId) > 0) {
                    #Updation of the ACL Information
                    if ($modelForm->updateAll($temp, 'id=' . $aclId) > 0) {
                        $this->redirect(array('view', 'id' => $model->id));
                    }
                } else {
                    #Insertion of ACL Information
                    if ($modelForm->save(FALSE)) {
                        $this->redirect(array('view', 'id' => $model->id));
                    }
                }
            }
        } else if ($action === "edit" && $aclId !== NULL) {
            unset($modelForm);
            switch ($type) {
                case 'groups':
                    $modelForm = new AclGroup();
                    break;
                case 'users':
                    $modelForm = new AclUser();
                    break;
                case 'hosts':
                    $modelForm = new AclHost();
                    break;
            }
            $modelForm = $modelForm->findByPk($aclId);
            $modelTemp->attributes = $modelForm->attributes;
        } else if ($action === 'delete' && $aclId !== NULL) {
            #
            unset($modelForm);
            switch ($type) {
                case 'groups':
                    $modelForm = new AclGroup();
                    break;
                case 'users':
                    $modelForm = new AclUser();
                    break;
                case 'hosts':
                    $modelForm = new AclHost();
                    break;
            }

            $modelForm = $modelForm->findByPk($aclId);
            $cmd = 'qmgr -c "set queue ' . $model->name . ' ' . $resourceType . '-=' . $modelForm->name . '"';
            array_push($commandArray, $cmd);
            $host = Yii::app()->params->hostDetails['host'];
            $port = Yii::app()->params->hostDetails['port'];
            $user = Yii::app()->user->name;
            $encryptedPassword = Yii::app()->user->password;
            $aes = new AES($encryptedPassword);
            $sshHost = new SSH($host, $port, $user);
            if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                foreach ($commandArray as $cmd) {
                    Yii::app()->user->setFlash('notice', $sshHost->cmd($cmd));
                }
            }
            if ($modelForm->deleteByPk($aclId)) {
                Yii::app()->user->setFlash('success', 'acl item has been deleted successfully.');
            } else {
                Yii::app()->user->setFlash('error', 'something goes wrong.Please try again.');
            }
            #die('here');
            $this->redirect(array('view', 'id' => $model->id));
        }
        $this->render('acl_form', array(
            'model' => $modelTemp,
            'queue' => $model,
            'type' => $type,
            'data' => $modelForm,
            'action' => $action,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * returns queue list which associated with torque via ajax
     * 
     * @since   2.0
     */
    public function actionGetQueueList() {
        $responseArray["status"] = INVALID_REQUEST;
        $responseArray["message"] = 'Invalid Request';
        if (isset($_POST)) {
            $host = Yii::app()->params->hostDetails['host'];
            $port = Yii::app()->params->hostDetails['port'];
            $user = Yii::app()->user->name;
            $encryptedPassword = Yii::app()->user->password;
            $aes = new AES($encryptedPassword);
            $sshHost = new SSH($host, $port, $user);
            if ($sshHost->isConnected() && $sshHost->authenticate_pass($aes->decrypt())) {
                $cmd = 'qstat -Q |awk -F" " \'NR>2 {print $1}\'|sed -r \':a;N;$!ba;s/\n/,/g\'';
                $xmlQueueList = $sshHost->cmd($cmd);
                if ($xmlQueueList !== "") {
                    $xmlQueueList = str_replace("\r\n", "", $xmlQueueList);
                    $xmlQueueList = split(',', $xmlQueueList);
                    sort($xmlQueueList, SORT_STRING);
                    $responseArray["status"] = SUCCESS;
                    $responseArray["message"] = 'Successfully retrieved queue list.';
                    $responseArray['response'] = $xmlQueueList;
                } else {
                    $responseArray["status"] = COMMAND_ERROR;
                    $responseArray["message"] = 'Error with command or its not working properly.';
                    $responseArray['response'] = $xmlQueueList;
                }
                unset($xmlQueueList);
            } else {
                $responseArray["status"] = AUTHENTICATION_ERROR;
                $responseArray["message"] = 'Authentication Error';
            }
            $sshHost->disconnect();
        }
        echo json_encode($responseArray);
    }

}

# End of the QueueController Class
# End of the QueueController.php file