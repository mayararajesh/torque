<?php

/**
 * Manages the PBS Node(cretes,deletes,updtaes and other)
 * 
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class NodeController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
                'actions' => array('create', 'update', 'online', 'offline'),
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Node;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Node'])) {
            $model->attributes = $_POST['Node'];
            $host = Yii::app()->params->hostDetails['host'];
            $port = Yii::app()->params->hostDetails['port'];
            $sshHost = new SSH($host, $port, 'root');
            if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
                $error = array();
                $cmd = 'qmgr -c "create node ' . $model->attributes['name'] . '"';
                $cmd = $sshHost->cmd($cmd);
                if ($cmd === "") {
                    if ($model->attributes['np'] === "") {
                        $model->attributes['np'] = 1;
                    }
                    $this->setNodeProps($sshHost, $model);
                } else {
                    array_push($error, $cmd);
                }
                $sshHost->disconnect();
                if (count($error) > 0) {
                    var_dump($error);
                    exit;
                }
                if ($model->save()) {
                    $this->redirect(array('view', 'id' => $model->id));
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
        $node = $model->attributes['name'];
        #$error = array();
        if (isset($_POST['Node'])) {
            $_POST['Node']['name'] = $node;
            $model->attributes = $_POST['Node'];
            $host = Yii::app()->params->hostDetails['host'];
            $port = Yii::app()->params->hostDetails['port'];
            $sshHost = new SSH($host, $port, 'root');
            if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
                $this->setNodeProps($sshHost, $model);
                if ($model->save()) {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
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
        $node = $this->loadModel($id);
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $sshHost = new SSH($host, $port, 'root');
        if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
            $sshHost->cmd('qmgr -c "delete node ' . $node->name . '"');
            $sshHost->disconnect();
            $this->loadModel($id)->delete();
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Node');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Node('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Node'])) {
            $model->attributes = $_GET['Node'];
        }
        #var_dump($model);exit;
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    //--------------------------------------------------------------------------
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Node the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Node::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    //--------------------------------------------------------------------------
    /**
     * Performs the AJAX validation.
     * @param Node $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'node-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Updates/Assign the node properties
     * 
     * @author Rajesh Mayara<rajesh.mayara@locuz.com>
     * @since       2.0
     * @param CHANNEL $sshHost
     * @param OBJECT $model
     */
    private function setNodeProps($sshHost, $model) {
        $error = array();
        # Number of processors per node
        if ($model->attributes['np'] !== "") {

            $cmd = 'qmgr -c "set node ' . $model->attributes['name'] . ' np=' . $model->attributes['np'] . '"';
            $cmd = $sshHost->cmd($cmd);
            if ($cmd !== "") {
                array_push($error, $cmd);
                $cmd = "";
            }
        }
        # Number of gpus per node
        if ($model->attributes['gpus'] !== "" && (int) $model->attributes['gpus'] !== 0) {
            $cmd = 'qmgr -c "set node ' . $model->attributes['name'] . ' gpus=' . $model->attributes['gpus'] . '"';
            $cmd = $sshHost->cmd($cmd);
            if ($cmd !== "") {
                array_push($error, $cmd);
                $cmd = "";
            }
        } else {
            #var_dump($sshHost->isConnected());
            $model->attributes['gpus'] = "";
            $command = $sshHost->cmd("pbsnodes -x " . $model->attributes['name']);
            #var_dump($command);
            $commandSyntax = split(':', $command);
            if ($commandSyntax[0] !== "pbsnodes") {
                $xmlstring = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n" . $command;
                #echo '<pre>'.$xmlstring."</pre>";exit;
                $xml = simplexml_load_string($xmlstring);
                $json = json_encode($xml);
                $nodeDetails = json_decode($json, TRUE);
                $nodeDetails = $nodeDetails['Node'];
                $position = 3;
                if ((int) $nodeDetails['np'] === 1) {
                    $position = 2;
                }

                $cmd = "cat -n " . Yii::app()->params['torque']['serverPriv'] . "/nodes | grep -i " . $model->name . " | awk -F \" \" '{print $1}'";
                $line = $sshHost->cmd($cmd);
                if ($line = (int) $line) {
                    $cmd = "cat " . Yii::app()->params['torque']['serverPriv'] . "/nodes | grep -i " . $model->name . " | awk -F \" \" '{print \$$position}'";
                    $current = $sshHost->cmd($cmd);
                    $cusrrentArr = split('=', $current);
                    if ($cusrrentArr[0] == 'gpus') {
                        $cmd = "sed -i \"" . $line . "s/" . trim($current) . "/ /\" " . Yii::app()->params['torque']['serverPriv'] . "/nodes";

                        if ($sshHost->cmd($cmd) === "") {
                            $cmd = "qterm -t quick";
                            if ($sshHost->cmd($cmd) === "") {
                                $cmd = "/etc/init.d/pbs_server start";
                                $sshHost->cmd($cmd);
                                sleep(10);
                            }
                        }
                    }
                }
            }
        }
        # Number of mics per node
        if ($model->attributes['mics'] !== "") {
            $cmd = 'qmgr -c "set node ' . $model->attributes['name'] . ' mics=' . $model->attributes['mics'] . '"';
            $cmd = $sshHost->cmd($cmd);
            if ($cmd !== "") {
                array_push($error, $cmd);
                $cmd = "";
            }
        }
        if (count($error) > 0) {
            var_dump($error);
            exit;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Make PBS node Online
     * 
     * @since       2.0
     */
    public function actionOnline($id) {
        $node = $this->loadModel($id);
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $sshHost = new SSH($host, $port, 'root');
        if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123') && $node) {
            $sshHost->cmd('pbsnodes -c ' . $node->name . '');
            $sshHost->disconnect();
        }
        $this->redirect(array('admin'));
    }

    //--------------------------------------------------------------------------
    /**
     * Make PBS node Offline
     * 
     * @since       2.0
     */
    public function actionOffline($id) {
        $node = $this->loadModel($id);
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $sshHost = new SSH($host, $port, 'root');
        if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123') && $node) {
            $sshHost->cmd('pbsnodes -o ' . $node->name . '');
            $sshHost->disconnect();
        }
        $this->redirect(array('admin'));
    }

}

# End of the NodeController Class
#End of the NodeController.php file
