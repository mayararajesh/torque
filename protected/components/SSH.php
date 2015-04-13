<?php

/*
 * SSH2 helper class
 */

class SSH extends CUserIdentity {

    private $host;
    private $user;
    private $pass;
    private $port;
    private $conn = false;
    private $privateKey;
    private $publicKey;
    private $stream;
    private $stream_timeout = 100;
    private $lastLog;
    public $errorCode;

    public function __construct($host, $port, $user) {
        $this->privateKey = Yii::app()->basePath . '/.ssh-key/id_rsa';
        $this->publicKey = Yii::app()->basePath . '/.ssh-key/id_rsa.pub';
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->connect();
    }

    public function isConnected() {
        return (boolean) $this->conn;
    }

    public function connect() {
        if (!($this->conn = @ssh2_connect($this->host, $this->port))) {
            throw new CException(Yii::t('application', "Ssh unable to connect to {$this->host}"));
        }
    }

    public function authenticate_pass($pass) {
        $this->pass = $pass;
        return @ssh2_auth_password($this->conn, $this->user, $this->pass);
    }

    public function authenticate_pubkey() {
        return @ssh2_auth_pubkey_file($this->conn, $this->user, $this->publicKey, $this->privateKey);
    }

    public function sendFile($localFile, $remoteFile, $permision = 0644) {
        if (!is_file($localFile)) {
            throw new CException(Yii::t('application', "Local file {$localFile} does not exist"));
        }
        $sftp = ssh2_sftp($this->conn);
        $sftpStream = @fopen('ssh2.sftp://' . $sftp . $remoteFile, 'w');
        if (!$sftpStream) {
            //  if 1 method fails try the other one
            if (!@ssh2_scp_send($this->conn, $localFile, $remoteFile, $permision)) {
                throw new CException(Yii::t('application', "Ssh could not open remote file: $remoteFile"));
            } else {
                return true;
            }
        }
        $data_to_send = @file_get_contents($localFile);
        if (@fwrite($sftpStream, $data_to_send) === false) {
            throw new CException(Yii::t('application', "Ssh could not send data from file: $localFile."));
        }
        fclose($sftpStream);
        return true;
    }

    public function getFile($remoteFile, $localFile) {
        if (@ssh2_scp_recv($this->conn, $remoteFile, $localFile)) {
            return true;
        }
        throw new CException(Yii::t('application', "Ssh unable to get remote file {$remoteFile}"));
    }

    public function deleteFile($remoteFile) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_unlink($sftp, $remoteFile)) {
            return true;
        }
        throw new CException(Yii::t('application', "Ssh unable to delete remote file {$remoteFile}"));
    }

    public function symLink($target, $link) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_symlink($sftp, $target, $link)) {
            return true;
        }
        throw new CException(Yii::t('application', "Ssh unable to create sybbolic link {$link}"));
    }

    public function removeDir($remoteDir) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_rmdir($sftp, $remoteDir)) {
            return true;
        }
        throw new CException(Yii::t('application', "Ssh unable to remove directory {$remoteDir}"));
    }

    public function makeDir($remoteDir, $permision = 0755, $recursive = false) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_mkdir($sftp, $remoteDir, $permision, $recursive)) {
            return true;
        }
        throw new CException(Yii::t('application', "Ssh unable to create directory {$remoteDir}"));
    }

    public function renameFile($oldFilename, $newFilename) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_rename($sftp, $oldFilename, $newFile)) {
            return true;
        }
        throw new CException(Yii::t('application', "Ssh unable to rename file {$oldFilename}"));
    }

    public function cmd($cmd) {
        $this->stream = ssh2_exec($this->conn, $cmd, 'console');
        if ($this->stream === false) {
            throw new CException(Yii::t('application', "Ssh Unable to execute command '$cmd'"));
        }
        stream_set_blocking($this->stream, true);
        stream_set_timeout($this->stream, $this->stream_timeout);
        $this->lastLog = stream_get_contents($this->stream);
        fclose($this->stream);
        return $this->lastLog;
    }

    public function shellCmd($cmds = array()) {
        $this->shellStream = ssh2_shell($this->conn);
        sleep(1);
        $out = '';
        while ($line = fgets($this->shellStream)) {
            $out .= $line;
        }
        foreach ($cmds as $cmd) {
            $out = '';
            fwrite($this->shellStream, "$cmd" . PHP_EOL);
            sleep(1);
            while ($line = fgets($this->shellStream)) {
                $out .= $line;
                sleep(1);
            }
        }
        fclose($this->shellStream);
        return $out;
    }

    public function getLastOutput() {
        return $this->lastLog;
    }

    public function disconnect() {
        // if disconnect function is available call it..
        if (function_exists('ssh2_disconnect')) {
            ssh2_disconnect($this->conn);
        } else { // if no disconnect func is available, close conn, unset var
            @fclose($this->conn);
            $this->conn = false;
        }
        // return null always
        return null;
    }

    public function fileExists($path) {
        $output = $this->cmd("[ -f $path ] && echo 1 || echo 0");
        return (boolean) trim($output);
    }

    public function dirExists($path) {
        $output = $this->cmd("[ -d $path ] && echo 1 || echo 0");
        return (boolean) trim($output);
    }

    public function cmdExec($cmd) {
        $cmdExec = $cmd . ";echo $?";
        $this->stream = ssh2_exec($this->conn, $cmdExec, 'console');
        if ($this->stream === false) {
            throw new CException(Yii::t('application', "Ssh Unable to execute command '$cmd'"));
        }
        stream_set_blocking($this->stream, true);
        stream_set_timeout($this->stream, $this->stream_timeout);

        $this->lastLog = stream_get_contents($this->stream);
        fclose($this->stream);
        $returnVal = explode("\n", trim($this->lastLog));
        //print_r($returnVal);
        if (end($returnVal) == '0') {
            return rtrim(trim($this->lastLog), "\n0");
        } else {
            //return rtrim(trim($this->lastLog), "\n0");
            return 'failed';
        }
        //return $this->lastLog;
    }

    public function writeStringToFile($targetFile, $content = "") {
        $sftp = ssh2_sftp($this->conn);
        $sftpStream = @fopen('ssh2.sftp://' . $sftp . $targetFile, 'w');
        if (@fwrite($sftpStream, $content) === false) {
            throw new CException(Yii::t('application', "Ssh could not write data from this editor"));
        }
        fclose($sftpStream);
        return true;
    }

}
