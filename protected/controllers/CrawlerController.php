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
        $this->updateNodeInfo();
        $this->updateQueueInfo();
        $this->updateJobInfo();
    }

    //--------------------------------------------------------------------------
    /**
     * Updates the jobs table with qstat from torque
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

    //--------------------------------------------------------------------------
    /**
     * Updates queue information from torque
     */
    private function updateQueueInfo() {
        $hostDetails = Yii::app()->params['hostDetails'];
        $sshHost = new SSH($hostDetails['host'], $hostDetails['port'], 'root');
        if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
            $cmd = "qstat -Qf";
            $response = $sshHost->cmd($cmd);
            $response = str_replace("\r\n\t", "", $response);
            $queueArray = explode("Queue:", $response);
            unset($queueArray[0]);
            $queueArray = array_filter($queueArray);
            foreach ($queueArray as $kq => $queue) {
                $queue = explode("\r\n", $queue);
                foreach ($queue as $k => $q) {
                    $q = explode("=", $q);
                    if (!isset($q[1]) && !empty($q[0])) {
                        $q[1] = trim($q[0]);
                        $q[0] = "name";
                    }
                    if (!empty($q[0])) {
                        $queue[trim($q[0])] = !is_array($q[1]) ? trim($q[1]) : $q[1];
                    }
                    unset($queue[$k]);
                }
                $queue = array_filter($queue);
                $stateCount = explode(" ", trim($queue['state_count']));
                $stateCount = array_filter($stateCount);
                $tempArr = array();
                foreach ($stateCount as $sc) {
                    $sc = explode(':', $sc);
                    $tempArr[trim($sc[0])] = trim($sc[1]);
                }
                $queue['state_count'] = $tempArr;
                $queueArray[$kq] = array_filter($queue);
            }
            $queueArray = array_values($queueArray);
            foreach ($queueArray as $key => $queue) {
                $resource = array();
                foreach ($queue as $k => $q) {
                    $tkq = explode('.', $k);
                    if (count($tkq) > 1) {
                        $tkq[0] = trim($tkq[0]);
                        $tkq[1] = trim($tkq[1]);
                        $queue[$tkq[0]] = isset($queue[$tkq[0]]) ? $queue[$tkq[0]] : array();
                        $queue[$tkq[0]][$tkq[1]] = $queue[$k];
                        unset($queue[$k]);
                        #$queue[$tkq[0]] = $queue[$tkq[0]];
                        if (!in_array($tkq[0], $resource)) {
                            array_push($resource, $tkq[0]);
                        }
                    }
                    $queueArray[$key] = array_filter($queue);
                }
                $queueArray = array_values($queueArray);
                foreach ($resource as $r) {
                    $resourceObj = NULL;
                    switch ($r) {
                        case "resources_available":
                            $resourceObj = new ResourcesAvailable();
                            break;
                        case "resources_default":
                            $resourceObj = new ResourcesDefault();
                            break;
                        case "resources_max":
                            $resourceObj = new ResourcesMax();
                            break;
                        case "resources_min":
                            $resourceObj = new ResourcesMin();
                            break;
                    }
                    if ($resourceObj !== NULL) {
                        $resourceObj->attributes = $queueArray[$key][$r];
                        $model = new Queue();
                        $queue = $model->findByAttributes(array('name' => $queueArray[$key]['name']));
                        if (isset($queue->id)) {
                            $queue = $resourceObj->findByAttributes(array('queue_id' => $queue->id));
                            if (isset($queue->id)) {
                                $resourceObj->updateAll($queueArray[$key][$r], 'queue_id=:queue_id', array(':queue_id' => $queue->queue_id));
                            } else {
                                $resourceObj->save(FALSE);
                            }
                        }
                    }
                    unset($resourceObj);
                }
            }
            $queueNames = "";
            foreach ($queueArray as $queue) {
                $queueNames .= "'" . $queue['name'] . "',";
                $model = new Queue();
                $q = $model->findByAttributes(array('name' => $queue['name']));
                if (isset($q->id)) {
                    $model->updateAll(array('status' => json_encode($queue)), 'id=:id', array(':id' => $q->id));
                } else {
                    if (isset($queue['enabled']) && $queue['enabled'] === "True") {
                        $queue['enabled'] = TRUE;
                    } else {
                        $queue['enabled'] = FALSE;
                    }
                    if (isset($queue['started']) && $queue['started'] === "True") {
                        $queue['started'] = TRUE;
                    } else {
                        $queue['started'] = FALSE;
                    }
                    $attributes = array(
                        'name' => $queue['name'],
                        'priority' => isset($queue['Priority']) ? $queue['Priority'] : 0,
                        'enabled' => isset($queue['enabled']) ? $queue['enabled'] : FALSE,
                        'queue_type' => 'execution',
                        'started' => isset($queue['started']) ? $queue['started'] : FALSE,
                        'status' => json_encode($queue)
                    );
                    $model->attributes = $attributes;
                    $model->save(FALSE);
                }
            }
            $queueNames = "(" . trim($queueNames, ',') . ")";
            $model = new Queue();
            $model->deleteAll('name NOT IN' . $queueNames);
        }
        $sshHost->disconnect();
    }

    //--------------------------------------------------------------------------
    public function updateNodeInfo() {
        $hostDetails = Yii::app()->params['hostDetails'];
        $sshHost = new SSH($hostDetails['host'], $hostDetails['port'], 'root');
        if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
            $cmd = "pbsnodes -x";
            $responseXML = $sshHost->cmd($cmd);
            if ($responseXML !== "") {
                try {
                    $xml = simplexml_load_string($responseXML);
                    $json = json_encode($xml);
                    $nodeDetails = json_decode($json, TRUE);
                    if (!isset($nodeDetails['Node'][0])) {
                        $temp = $nodeDetails['Node'];
                        $nodeDetails['Node'] = array($temp);
                    }
                    $nodeNames = "";
                    foreach ($nodeDetails['Node'] as $key => $node) {
                        $nodeNames .= "'" . $node['name'] . "',";
                        if (isset($node['status'])) {
                            $status = explode(",", $node['status']);
                            foreach ($status as $k => $s) {
                                $tempStatus = explode("=", $s);
                                $status[$tempStatus[0]] = isset($tempStatus[1]) ? $tempStatus[1] : "";
                                unset($status[$k]);
                            }
                            $node['status'] = $status;
                        }
                        $nodeDetails['Node'][$key] = $node;
                        $nodeObj = new Node();
                        $nodeObj->updateAll(array(
                            'status' => json_encode($node),
                            'np' => isset($node['np']) ? $node['np'] : 1,
                            'gpus' => isset($node['gpus']) ? $node['gpus'] : NULL,
                        ),'name=:name',array(':name'=>$node['name']));
                    }
                    $nodeNames = "(" . trim($nodeNames, ",") . ")";
                    $node = new Node();
                    $node->deleteAll("name NOT IN" . $nodeNames);
                } catch (Exception $ex) {
                    print_r($ex);
                }
            }
        }
        $sshHost->disconnect();
    }

}

# End of the CrawlerController Class
# End of the CrawlerController.php file