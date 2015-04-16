<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        /*
          $users=array(
          // username => password
          'demo'=>'demo',
          'admin'=>'admin',
          );
          if(!isset($users[$this->username]))
          $this->errorCode=self::ERROR_USERNAME_INVALID;
          elseif($users[$this->username]!==$this->password)
          $this->errorCode=self::ERROR_PASSWORD_INVALID;
          else
          $this->errorCode=self::ERROR_NONE;
          return !$this->errorCode; */
        $host = Yii::app()->params['hostDetails']['host'];
        $port = Yii::app()->params['hostDetails']['port'];
        $sshHost = new SSH($host, $port, $this->username);

        if ($sshHost->isConnected() && $sshHost->authenticate_pass($this->password)) {
            $aes = new AES($this->password);
            $encryptedPassword = $aes->encrypt();
            $this->setState('name', $this->username);
            $this->setState('password', $encryptedPassword);
            $this->setState('role', 'admin');
            Yii::app()->authManager->save();
            $sshHost->disconnect();
            $this->errorCode =self::ERROR_NONE;
            //return TRUE;
        } else {
            //return FALSE;
            $this->errorCode =self::ERROR_PASSWORD_INVALID;
            #$this->addError('password', 'Incorrect username or password.');
        }
        return !$this->errorCode;
    }

}
