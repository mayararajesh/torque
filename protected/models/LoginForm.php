<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {

    public $username;
    public $password;
    public $rememberMe;
    private $errorCode;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username, password', 'required'),
                // rememberMe needs to be a boolean
                #array('rememberMe', 'boolean'),
                // password needs to be authenticated
                #array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'rememberMe' => 'Remember me next time',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect username or password.');
        } /**/
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        /* $host = Yii::app()->params['hostDetails']['host'];
          $port = Yii::app()->params['hostDetails']['port'];
          $sshHost = new SSH($host, $port, $this->username);
          if (!Yii::app()->user->getState('name')) {
          if ($sshHost->isConnected() && $sshHost->authenticate_pass($this->password)) {
          $aes = new AES($this->password);
          $encryptedPassword = $aes->encrypt();
          Yii::app()->user->setState('name', $this->username);
          Yii::app()->user->setState('password', $encryptedPassword);
          Yii::app()->user->setState('role', 'admin');
          Yii::app()->authManager->save();
          $this->errorCode = CUserIdentity::ERROR_NONE;
          $sshHost->disconnect();
          } else {
          $this->addError('password', 'Incorrect username or password.');
          }
          } */
        if ($this->_identity === NULL) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            Yii::app()->user->login($this->_identity, 1800);
            return TRUE;
        }
        $this->addError('password','Invalid Username or Password.');
        return FALSE;
    }

    /**
     * Adding error to the 
     */
}
