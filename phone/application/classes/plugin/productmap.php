<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28 0028
 * Time: 9:12
 */

class Plugin_Productmap extends Plugin_Core_Base{
    public static function _is_installed()
    {
        return file_exists(BASEPATH.'/plugins/productmap');
    }
}