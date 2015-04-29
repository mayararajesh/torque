<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of REQUIERED
 *
 * @author Rajesh
 */
class REQUIRED {
    //put your code here
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

}
