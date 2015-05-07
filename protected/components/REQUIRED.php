<?php

/**
 * All required functions are kept here
 *
 * @author Rajesh
 */
class REQUIRED {

    /**
     * Converts Object To Array recursively
     * 
     * @param type $obj
     * @return type
     */
    public static function objectToArray($obj) {
        if (is_object($obj))
            $obj = (array) $obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = self::objectToArray($val);
            }
        } else
            $new = $obj;
        return $new;
    }

    //--------------------------------------------------------------------------
    /**
     * Creates Table for XDetailView is alternate and improved version of 
     * CDetailView
     * 
     * @param type $data
     * @return string
     */
    private static function createXDetailViewTable($data) {
        $tableStr = "";
        $count = 1;
        foreach ($data as $k => $v) {
            $tableTempStr = "";
            if (is_string($v)) {
                $temp = explode(',', $v);
                if (count($temp) > 1) {
                    $tempArr = array();
                    foreach ($temp as $t) {
                        $t = explode('=', $t);
                        if (isset($t[1])) {
                            $tempArr[$t[0]] = $t[1];
                        } else {
                            $tempArr = $t;
                        }
                    }
                    $v = $tempArr;
                }
                unset($temp);
            }
            if (is_array($v)) {
                $v = self::createXDetailView($v);
            }
            if (is_string($v)) {
                if (!is_integer($k)) {
                    $tableTempStr .= '<td class="th">' . ucwords(str_replace("_", " ", $k)) . "</td>" . "\n";
                }
                $isValueNull = ($v == NULL || $v == "") ? TRUE : FALSE;
                $tableTempStr .= '<td>' . ($isValueNull ? '<span class="null">Not Set</span>' : $v) . "</td>" . "\n";
                $tableStr .= "<tr class=" . ($count % 2 == 0 ? "even" : "odd") . ">\n" . $tableTempStr . "</tr>" . "\n";
                $count++;
            }
        }
        $tableStr = '<table class="xdetail-view">' . "\n" . '<tbody>' . "\n" . $tableStr . '</tbody>' . "\n" . '</table>' . "\n";
        return $tableStr;
    }

    //--------------------------------------------------------------------------
    /**
     * Returns generated XDetailView with CSS and Table
     * 
     * @param type $data
     * @return string
     */
    public static function createXDetailView($data) {
        $tableStr = "<style>
    table.xdetail-view {
        background: none repeat scroll 0 0 white;
        border-collapse: collapse;
        margin: 0;
        width: 100%;
        border-spacing: 0;
        max-width: 100%;
    }
    table.xdetail-view tr.odd {
        background: none repeat scroll 0 0 #e5f1f4;
    }
    table.xdetail-view .th {
        text-align: right;
        width: 160px;
        border: 1px solid white;
        font-weight: bold;
        padding: 0.3em 0.6em;
        font-family: \"Carrois Gothic\",sans-serif;
    }
    table.xdetail-view td {
        border: 1px solid white;
        font-size: 1.0em;
        padding: 0.3em 0.6em;
        vertical-align: auto;
    }
    table.xdetail-view tr.even {
        background: none repeat scroll 0 0 #f8f8f8;
    }
    table.xdetail-view {
        border-collapse: collapse;
        border-spacing: 0;
    }
    table.xdetail-view .null {
        color: red;
    }
</style>\n";
        return $tableStr . self::createXDetailViewTable($data);
    }

    //--------------------------------------------------------------------------
    /**
     * Updates the database with torque whenever is required.
     */
    public static function updateTorqueWithDB() {
        # create curl resource
        $ch = curl_init();
        # set url
        curl_setopt($ch, CURLOPT_URL, Yii::app()->createAbsoluteUrl('crawler/index'));
        # return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        # $output contains the output string
        $output = curl_exec($ch);
        # close curl resource to free up system resources
        curl_close($ch);
    }

}
