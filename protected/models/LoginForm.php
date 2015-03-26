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
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
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
        /*if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect username or password.');
        }*/
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new SSH('localhost', 22, $this->username);
            if ($this->_identity->isConnected() && $this->_identity->authenticate_pass($this->password)) {
                #$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
                Yii::app()->user->setState('username',$this->username);
                Yii::app()->user->setState('password',$this->password);
            } else {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
        if(!$this->hasErrors()){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Adding error to the 
     */
}
