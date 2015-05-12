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
        $host = Yii::app()->params['hostDetails']['host'];
        $port = Yii::app()->params['hostDetails']['port'];
        $sshHost = new SSH($host, $port, $this->username);
        $keyFile = "";
        if ($sshHost->isConnected() && $sshHost->authenticate_pass($this->password)) {
            /*
//            echo "<pre>";
//            $user = new User();
//            $details = $user->findByAttributes(array('username' => $this->username));
//            if ($details === NULL) {
//                if ($this->username === "root") {
//                    $tmpFile = "/{$this->username}/.ssh/id_rsa_{$this->username}.pub";
//                }else{
//                    $tmpFile = "/home/{$this->username}/.ssh/id_rsa_{$this->username}.pub";
//                }
//                if ((bool) $sshHost->cmd("[ -f {$tmpFile} ] && echo 1 || echo 0")) {
//                    $keyFile = $tmpFile;
//                } else if (($keyFile = $this->generateSSHKeyPair($sshHost, $this->username, $tmpFile)) !== FALSE) {
//                    Yii::app()->user->setFlash('danger', '</strong>Unable to generate ssh key pair.</strong>' . $response);
//                }
//                if ($keyFile) {
//                    $time = date('Y-m-d H:i:s', time());
//                    $attributes = array(
//                        'username' => $this->username,
//                        'pub_key_path' => $keyFile,
//                        'created_at' => $time,
//                        'updated_at' => $time,
//                    );
//                    #print_r($attributes);
//                    $user->attributes = $attributes;
//                    if (!$user->save()) {
//                        Yii::app()->user->setFlash('info', 'Unable to store user details.');
//                    } else {
//                        $details = $user->findByAttributes(array('username' => $this->username));
//                    }
//                }
//            }
//            if ($details !== NULL) {
             * 
             */
                $aes = new AES($this->password);
                $encryptedPassword = $aes->encrypt();
                $this->setState('name', $this->username);
                $this->setState('password', $encryptedPassword);
                #$this->setState('public_key_path', $details->pub_key_path);
                $this->setState('role', 'admin');
                Yii::app()->authManager->save();
                $this->errorCode = self::ERROR_NONE;
                $sshHost->disconnect();
            
            //return TRUE;
        } else {
            //return FALSE;
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            #$this->addError('password', 'Incorrect username or password.');
        }
        return !$this->errorCode;
    }

    //--------------------------------------------------------------------------
    /**
     * 
    private function generateSSHKeyPair($sshHost, $user, $file) {
        $response = $sshHost->cmd("ssh-keygen -q -N '' -f {$file}" . $user);
        if (trim($response) !== "") {
            return FALSE;
        }
        return "/{$user}/.ssh/id_rsa_{$user}.pub";
    }**/

}
