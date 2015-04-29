<?php

/**
 * Reads and stores torque information which is running as backround process
 * using cron jobs
 * 
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class CrawlerController extends Controller {

    //--------------------------------------------------------------------------
    /**
     * Calls required function based on specified id
     * 
     * @param string $id
     */
    public function actionIndex($id = NULL) {
        if ($id == "jobs") {
            $this->updateJobInfo();
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Updates the jobs table with qstat in torque
     */
    private function updateJobInfo() {
        $hostDetails = Yii::app()->params['hostDetails'];
        $sshHost = new SSH($hostDetails['host'], $hostDetails['port'], 'root');
        if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
            $cmd = "qstat -x";
            $response = $sshHost->cmd($cmd);
            if ($response !== "") {
                try {
                    $xml = simplexml_load_string($response);
                    $json = json_encode($xml);
                    $taskDetails = json_decode($json, TRUE);
                    # Making job array as uniform to access
                    if (!isset($taskDetails['Job'][0])) {
                        $temp = $taskDetails['Job'];
                        $taskDetails['Job'] = array($temp);
                    }
                    foreach ($taskDetails['Job'] as $task) {
                        $jobDetails = array();
                        $jobId = explode('.', $task['Job_Id']);
                        $jobDetails['job_id'] = (int) $jobId[0];
                        $jobDetails['status'] = json_encode($task);
                        $jobDetails['submitted_by'] = explode('@', $task['Job_Owner']);
                        $jobDetails['submitted_by'] = $jobDetails['submitted_by'][0];
                        $job_state = $task['job_state'];
                        if ($job_state == "C") {
                            $jobDetails['is_deleted'] = TRUE;
                        } else {
                            $jobDetails['is_deleted'] = FALSE;
                        }
                        $job = new Job();
                        $tempObj = new Job();
                        if (!$tempObj->exists('job_id=:job_id', array(':job_id' => $jobDetails['job_id']))) {
                            $job->attributes = $jobDetails;
                            $job->save();
                        } else {
                            $job->updateAll(array(
                                'is_deleted' => $jobDetails['is_deleted'],
                                'status' => $jobDetails['status'],
                                'submitted_by' => $jobDetails['submitted_by']
                                    ), 'job_id=:job_id', array(':job_id' => $jobDetails['job_id']));
                        }
                    }
                } catch (Exception $e) {
                    print_r($e);
                }
            }
        }
    }

}

# End of the CrawlerController Class
# End of the CrawlerController.php file