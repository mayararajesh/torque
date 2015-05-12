<?php

/**
 * SSH Class provides API Calls for libssh2 functions
 * Manages the SSH Connection 
 * Also supports FileManagement for remote filesystem.
 * 
 * @author      Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
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

//--------------------------------------------------------------------------
    /**
     * Initializes the object of SSH Class
     * @param string $host
     * @param integer $port
     * @param string $user
     */
    public function __construct($host, $port, $user, $public_key = '~/.ssh-key/id_rsa.pub') {
        $this->privateKey = '~/.ssh-key/id_rsa_'.$user;
        $this->publicKey = $public_key;
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->connect();
    }

//--------------------------------------------------------------------------
    /**
     * Checks is it connected or not
     * 
     * @return TRUE if successfully connects otherwise FALSE
     */
    public function isConnected() {
        return (boolean) $this->conn;
    }

//--------------------------------------------------------------------------
    /**
     * Connect to remote host using its port
     * 
     * @return SSH2Object if successfully connects otherwise FALSE
     */
    public function connect() {
        if (!($this->conn = @ssh2_connect($this->host, $this->port))) {
            throw new CException(Yii::t('application', "SSH unable to connect to {$this->host}"));
        }
    }

//--------------------------------------------------------------------------
    /**
     * Authenticate using password
     * 
     * @return SSH2Object if successfully authenticated otherwise FALSE
     */
    public function authenticate_pass($pass) {
        $this->pass = $pass;
        return @ssh2_auth_password($this->conn, $this->user, $this->pass);
    }

//--------------------------------------------------------------------------
    /**
     * Authenticate using public key
     * 
     * @return SSH2Object if successfully authenticated otherwise FALSE
     */
    public function authenticate_pubkey() {
        return @ssh2_auth_pubkey_file($this->conn, $this->user, $this->publicKey, $this->privateKey);
    }

//--------------------------------------------------------------------------
    /**
     * Sends file to remote
     * 
     * @param string $localFile
     * @param string $remoteFile
     * @param integer $permision
     * @return TRUE if successfully send otherwise Exception
     * @throws CException
     */
    public function sendFile($localFile, $remoteFile, $permision = 0644) {
        if (!is_file($localFile)) {
            throw new CException(Yii::t('application', "Local file {$localFile} does not exist"));
        }
        $sftp = ssh2_sftp($this->conn);
        $sftpStream = @fopen('ssh2.sftp://' . $sftp . $remoteFile, 'w');
        if (!$sftpStream) {
//  if 1 method fails try the other one
            if (!@ssh2_scp_send($this->conn, $localFile, $remoteFile, $permision)) {
                throw new CException(Yii::t('application', "SSH could not open remote file: $remoteFile"));
            } else {
                return true;
            }
        }
        $data_to_send = @file_get_contents($localFile);
        if (@fwrite($sftpStream, $data_to_send) === false) {
            throw new CException(Yii::t('application', "SSH could not send data from file: $localFile."));
        }
        fclose($sftpStream);
        return true;
    }

//--------------------------------------------------------------------------
    /**
     * Receieves remote file
     * 
     * @param string $remoteFile
     * @param string $localFile
     * @return TRUE if successfully receives otherwise Exception
     * @throws CException
     */
    public function getFile($remoteFile, $localFile) {
        if (@ssh2_scp_recv($this->conn, $remoteFile, $localFile)) {
            return true;
        }
        throw new CException(Yii::t('application', "SSH unable to get remote file {$remoteFile}"));
    }

//--------------------------------------------------------------------------
    /**
     * Deletes remote file
     * 
     * @param string $remoteFile
     * @return TRUE if successfully removed otherwise Exception
     * @throws CException
     */
    public function deleteFile($remoteFile) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_unlink($sftp, $remoteFile)) {
            return true;
        }
        throw new CException(Yii::t('application', "SSH unable to delete remote file {$remoteFile}"));
    }

//--------------------------------------------------------------------------
    /**
     * Creates remote link for the target file
     * 
     * @param string $target
     * @param string $link
     * @return TRUE if successfully created otherwise Exception
     * @throws CException
     */
    public function symLink($target, $link) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_symlink($sftp, $target, $link)) {
            return true;
        }
        throw new CException(Yii::t('application', "SSH unable to create sybbolic link {$link}"));
    }

//--------------------------------------------------------------------------
    /**
     * Removes the remote directory 
     * 
     * @param string $remoteDir
     * @return TRUE if removes the directory successfully otherwise Exception
     * @throws CException
     */
    public function removeDir($remoteDir) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_rmdir($sftp, $remoteDir)) {
            return true;
        }
        throw new CException(Yii::t('application', "SSH unable to remove directory {$remoteDir}"));
    }

//--------------------------------------------------------------------------
    /**
     * Creates the remote directory
     * 
     * @param string $remoteDir
     * @param integer $permision
     * @param boolean $recursive
     * @return TRUE if successfully creates directory otherwise Exception
     * @throws CException
     */
    public function makeDir($remoteDir, $permision = 0755, $recursive = false) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_mkdir($sftp, $remoteDir, $permision, $recursive)) {
            return true;
        }
        throw new CException(Yii::t('application', "SSH unable to create directory {$remoteDir}"));
    }

//--------------------------------------------------------------------------
    /**
     * Renames the remote file from old to new
     * 
     * @param string $oldFilename
     * @param string $newFilename
     * @return TRUE if successfully file renamed otherwise FALSE
     * @throws CException
     */
    public function renameFile($oldFilename, $newFilename) {
        $sftp = ssh2_sftp($this->conn);
        if (@ssh2_sftp_rename($sftp, $oldFilename, $newFilename)) {
            return true;
        }
        throw new CException(Yii::t('application', "SSH unable to rename file {$oldFilename}"));
    }

//--------------------------------------------------------------------------
    /**
     * Executes the single line command
     * 
     * @param string $cmd
     * @return last log information
     * @throws CException
     */
    public function cmd($cmd) {
        $this->stream = ssh2_exec($this->conn, $cmd, 'console');
        if ($this->stream === false) {
            throw new CException(Yii::t('application', "SSH Unable to execute command '$cmd'"));
        }
        stream_set_blocking($this->stream, true);
        stream_set_timeout($this->stream, $this->stream_timeout);
        $this->lastLog = stream_get_contents($this->stream);
        fclose($this->stream);
        return $this->lastLog;
    }

//--------------------------------------------------------------------------
    /**
     * Execute bulk of commands as a stream
     * 
     * @param Array $cmds
     * @return string
     */
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

//--------------------------------------------------------------------------
    /**
     * Gives the last log information
     * 
     * @return string
     */
    public function getLastOutput() {
        return $this->lastLog;
    }

//--------------------------------------------------------------------------
    /**
     * Disconnects the shell connection
     * 
     * @return NULL
     */
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

//--------------------------------------------------------------------------
    /**
     * Checks wethear given file is a file or not 
     * @param string $path
     * @return TRUE if given file is a file otherwise FALSE
     */
    public function fileExists($path) {
        $output = $this->cmd("[ -f $path ] && echo 1 || echo 0");
        return (boolean) trim($output);
    }

//--------------------------------------------------------------------------
    /**
     * Checks wethear given file is directory or not 
     * @param string $path
     * @return TRUE if given file is directory otherwise FALSE
     */
    public function dirExists($path) {
        $output = $this->cmd("[ -d $path ] && echo 1 || echo 0");
        return (boolean) trim($output);
    }

//--------------------------------------------------------------------------
    /**
     * Excutes the provided command and checks for error code
     * 
     * @param strin $cmd
     * @return string 
     */
    public function cmdExec($cmd) {
        $cmdExec = $cmd . ";echo $?";
        $this->stream = ssh2_exec($this->conn, $cmdExec, 'console');
        if ($this->stream === false) {
            throw new CException(Yii::t('application', "SSH Unable to execute command '$cmd'"));
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

//--------------------------------------------------------------------------
    /**
     * Write(s) string to remote file using SSH Stream
     * 
     * @param string $targetFile
     * @param string $content
     * @return TRUE if successfully write otherwise throws Exception
     * @throws CException
     */
    public function writeStringToFile($targetFile, $content = "") {
        $sftp = ssh2_sftp($this->conn);
        $sftpStream = @fopen('ssh2.sftp://' . $sftp . $targetFile, 'w');
        if (@fwrite($sftpStream, $content) === false) {
            throw new CException(Yii::t('application', "SSH could not write data from this editor"));
        }
        fclose($sftpStream);
        return true;
    }

}

# End of the SSH Class
# End of the SSH.php file