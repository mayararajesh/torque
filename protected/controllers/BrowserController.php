<?php

class BrowserController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated role to perform 'browser' and 'sshbrowser' action
                'actions' => array('getlist', 'index'),
                //'roles'=>array('manager'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        throw new CHttpException(404, 'The requested page does not exist.');
    }

    public function actionGetlist($dironly = false, $remote = false) {
        if (Yii::app()->request->isAjaxRequest) {
            $dironly = (boolean) $dironly;
            $remote = (boolean) $remote;
            $dir = urldecode($_POST['dir']);
            if ($remote) {
                // Browse remote system
                $this->browseremote($dir, $dironly);
            } else {
                // Browse local system
                $this->browselocal($dir, $dironly);
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    private static function browselocal($dir, $dironly = FALSE) {
        $root = '/';
        $dironly = FALSE;
        if (file_exists($root . $dir)) {
            $files = @scandir($root . $dir);
            if (count($files) > 2) {
                natcasesort($files);
                // The 2 accounts for . and ..
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach ($files as $file) {
                    if (file_exists($root . $dir . $file) && $file != '.' && $file != '..' && is_dir($root . $dir . $file)) {
                        echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . "/\">" . htmlentities($file) . "</a></li>";
                    }
                }
                if (!$dironly) {
                    // All files
                    foreach ($files as $file) {
                        if (file_exists($root . $dir . $file) && $file != '.' && $file != '..' && !is_dir($root . $dir . $file)) {
                            $ext = preg_replace('/^.*\./', '', $file);
                            echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . "\">" . htmlentities($file) . "</a></li>";
                        }
                    }
                }
                echo "</ul>";
            }
        }
    }

    private static function browseremote($dir, $dironly) {
        #$dironly = FALSE;
        $host = Yii::app()->params->hostDetails['host'];
        $port = Yii::app()->params->hostDetails['port'];
        $user = Yii::app()->user->name;
        $encryptedPassword = Yii::app()->user->password;
        $aes = new AES($encryptedPassword);
        $ssh = new SSH($host, $port, $user);
        if (!$ssh->authenticate_pass($aes->decrypt()))
            throw new CException(Yii::t('CCM', 'SSH authentication failed!'));
        $root = '/';
        if ($ssh->dirExists($root . $dir)) {
            //$files = scandir($root . $dir);
            $cmdOutput = $ssh->cmdExec("ls -laL \"" . $root . $dir . "\" ;echo -e \"\n\"$?");
            
            if ($cmdOutput !== 'failed') {
                $cmdOutput = trim($cmdOutput);
                $lines = explode("\n", $cmdOutput);
                $files = array();
                $items = array();
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        sscanf($line, "%s %s %s %s %s %s %s %s %[^$]s", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9);
                        $p9 = trim($p9);
                        switch ($p1[0]) {
                            case '-':
                                $files[] = $p9;
                                break;
                            case 's':
                                $files[] = $p9;
                                break;
                            case 'd':
                                $items[] = $p9;
                                break;
                            case 'l':
                                if ($ssh->dirExists($root . $dir . $p9))
                                    $items[] = $p9;
                                else
                                    $files[] = $p9;
                                break;
                        }
                    }
                }
                natcasesort($items);
                natcasesort($files);
                if ((count($files) > 0) || (count($items) > 0)) {
                    // The 2 accounts for . and ..
                    echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                    // All dirs
                    foreach ($items as $item) {
                        if ($item != '.' && $item != '..') {
                            echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($dir . $item) . "/\">" . htmlentities($item) . "</a></li>";
                        }
                    }
                    if (!$dironly) {
                        // All files
                        foreach ($files as $file) {
                            $ext = preg_replace('/^.*\./', '', $file);
                            echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . "\">" . htmlentities($file) . "</a></li>";
                        }
                    }
                    echo "</ul>";
                }
            }
        }
    }

    // TODO: remove this action once the all its referances are updated to new getlist action
    public function actionLocalold($dironly = false) {
        if (Yii::app()->request->isAjaxRequest) {
            $root = '/';
            $_POST['dir'] = urldecode($_POST['dir']);
            if (file_exists($root . $_POST['dir'])) {
                $files = scandir($root . $_POST['dir']);
                natcasesort($files);
                if (count($files) > 2) {
                    // The 2 accounts for . and ..
                    echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                    // All dirs
                    foreach ($files as $file) {
                        if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file)) {
                            echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
                        }
                    }
                    if (!$dironly) {
                        // All files
                        foreach ($files as $file) {
                            if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file)) {
                                $ext = preg_replace('/^.*\./', '', $file);
                                echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
                            }
                        }
                    }
                    echo "</ul>";
                }
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    // TODO: remove this action once the all its referances are updated to new getlist action
    public function actionRemoteold($dironly = false) {
        if (Yii::app()->request->isAjaxRequest) {
            $cblrserver = Ccm::getConfig('cobblerserver');
            $ssh = new Ssh($cblrserver, 22, 'root');
            if (!$ssh->authenticate_pubkey())
                throw new CException(Yii::t('CCM', 'SSH authentication failed!'));
            $root = '/';
            $_POST['dir'] = urldecode($_POST['dir']);
            if ($ssh->dirExists($root . $_POST['dir'])) {
                //$files = scandir($root . $_POST['dir']);
                $cmdOutput = $ssh->cmd("ls -laL \"" . $root . $_POST['dir'] . "\" ;echo -e \"\n\"$?");
                if (Ccm::getExitCode($cmdOutput) == 0) {
                    $cmdOutput = trim($cmdOutput);
                    $lines = explode("\n", $cmdOutput);
                    $files = array();
                    $dirs = array();
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            sscanf($line, "%s %s %s %s %s %s %s %s %[^$]s", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9);
                            $p9 = trim($p9);
                            switch ($p1[0]) {
                                case '-':
                                    $files[] = $p9;
                                    break;
                                case 's':
                                    $files[] = $p9;
                                    break;
                                case 'd':
                                    $dirs[] = $p9;
                                    break;
                                case 'l':
                                    if ($ssh->dirExists($root . $_POST['dir'] . $p9))
                                        $dirs[] = $p9;
                                    else
                                        $files[] = $p9;
                                    break;
                            }
                        }
                    }
                    natcasesort($dirs);
                    natcasesort($files);
                    if ((count($files) > 0) || (count($dirs) > 0)) {
                        // The 2 accounts for . and ..
                        echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                        // All dirs
                        foreach ($dirs as $dir) {
                            if ($dir != '.' && $dir != '..') {
                                echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $dir) . "/\">" . htmlentities($dir) . "</a></li>";
                            }
                        }
                        if (!$dironly) {
                            // All files
                            foreach ($files as $file) {
                                $ext = preg_replace('/^.*\./', '', $file);
                                echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
                            }
                        }
                        echo "</ul>";
                    }
                }
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

}
