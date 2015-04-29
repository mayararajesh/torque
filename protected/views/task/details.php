<style>
    table.detail-view {
        background: none repeat scroll 0 0 white;
        border-collapse: collapse;
        margin: 0;
        width: 100%;
        border-spacing: 0;
        max-width: 100%;
    }
    table.detail-view tr.odd {
        background: none repeat scroll 0 0 #e5f1f4;
    }
    table.detail-view th {
        text-align: right;
        width: 160px;
        padding-right: 5px;
    }
    table.detail-view td {
        border: 1px solid white;
        font-size: 1.0em;
        padding: 0.3em 0.6em;
        vertical-align: top;
    }
    table.detail-view tr.even {
        background: none repeat scroll 0 0 #f8f8f8;
    }
    table.detail-view {
        border-collapse: collapse;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    table.detail-view .null {
        color: pink;
    }
</style>
<?php
$this->breadcrumbs = array(
    'Task' => array('index'),
    'List' => array('list'),
    'Details'
);

$this->menu = array(
    array('label' => 'New', 'url' => array('index')),
    array('label' => 'List', 'url' => array('list')),
);

$taskDetails = json_decode($taskDetails[0]->status, TRUE);
$taskDetails['Job_Id'] = explode('.', $taskDetails['Job_Id']);
$taskDetails['Job_Id'] = $taskDetails['Job_Id'][0];
$taskDetails['sub_state'] = isset($taskDetails['substate'])?$taskDetails['substate']:"";
unset($taskDetails['substate']);
$taskDetails['check_point'] = $taskDetails['Checkpoint'];
unset($taskDetails['Checkpoint']);
$taskDetails['creation_time'] = date('Y-M-d H:i:s',(int)$taskDetails['ctime']);
unset($taskDetails['ctime']);
$taskDetails['m_time'] = date('Y-M-d H:i:s',(int)$taskDetails['mtime']);
unset($taskDetails['mtime']);
$taskDetails['queue_time'] = date('Y-M-d H:i:s',(int)$taskDetails['qtime']);
unset($taskDetails['qtime']);
$taskDetails['elapsed_time'] = date('Y-M-d H:i:s',(int)$taskDetails['etime']);
unset($taskDetails['etime']);
echo createTable($taskDetails);

function createTable($data) {
    $tableStr = "";
    $count = 1;
    foreach ($data as $k => $v) {
        $tableTempStr = "";
        if (is_string($v)) {
            $temp = explode(',', $v);
            if (count($temp) > 1) {
                $v = "<ul>"."\n";
                foreach ($temp as $t) {
                    $v.="<li>" . $t . "</li>"."\n";
                }
                $v .="</ul>"."\n";
            }
        }
        if (is_array($v)) {
            $v = createTable($v);
        }
        if (is_string($v)) {
            $tableTempStr .= "<th>" . ucwords(str_replace("_", " ", $k)) . "</th>"."\n";
            $isValueNull = ($v == NULL || $v == "") ? TRUE : FALSE;
            $tableTempStr .= "<td>" . ($isValueNull ? '<span class="null">Not Set</span>': $v) . "</td>"."\n";
            $tableStr .= "<tr class=" . ($count % 2 == 0 ? "even" : "odd") . ">\n" . $tableTempStr . "</tr>"."\n";
            $count++;
        }
    }
    $tableStr = '<table class="detail-view">'."\n".'<tbody>'."\n". $tableStr . '</tbody>'."\n".'</table>'."\n";
    return $tableStr;
}
