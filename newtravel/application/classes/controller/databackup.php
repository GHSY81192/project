<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Databackup extends Stourweb_Controller
{
    public static $db_tables = array(

        'sline_line' => array('table' => 'sline_line', 'name' => '线路', 'beizu' => '线路列表'),
        'sline_line_attr' => array('table' => 'sline_line_attr', 'name' => '线路属性', 'beizu' => '线路分类表，比如自由行，包团游等'),
        'sline_line_jieshao' => array('table' => 'sline_line_jieshao', 'name' => '线路行程', 'beizu' => '保存线路所有的行程内容'),
        'sline_line_suit' => array('table' => 'sline_line_suit', 'name' => '线路套餐', 'beizu' => '线路套餐列表'),
        'sline_line_suit_price' => array('table' => 'sline_line_suit_price', 'name' => '线路套餐报价', 'beizu' => '线路套餐报价'),

        'sline_hotel' => array('table' => 'sline_hotel', 'name' => '酒店', 'beizu' => '酒店列表'),
        'sline_hotel_attr' => array('table' => 'sline_hotel_attr', 'name' => '酒店属性', 'beizu' => '酒店分类'),
        // array('table'=>'sline_hotel_booking','name'=>'酒店订单','beizu'=>'酒店订单'),
        'sline_hotel_rank' => array('table' => 'sline_hotel_rank', 'name' => '酒店等级', 'beizu' => '酒店星级'),
        'sline_hotel_room' => array('table' => 'sline_hotel_room', 'name' => '酒店房间', 'beizu' => '酒店的房间列表，比如单人间，双人间，豪华间'),
        'sline_hotel_pricelist' => array('table' => 'sline_hotel_pricelist', 'name' => '酒店房间报价', 'beizu' => '酒店房间按日报价列表'),

        'sline_car' => array('table' => 'sline_car', 'name' => '租车', 'beizu' => '车辆列表'),
        //    array('table'=>'sline_car_booking','name'=>'租车订单','beizu'=>'租车订单表'),
        'sline_car_kind' => array('table' => 'sline_car_kind', 'name' => '租车类型', 'beizu' => '比如越野车，大巴等'),
        'sline_car_suit' => array('table' => 'sline_car_suit', 'name' => '租车套餐', 'beizu' => '租车套餐列表'),
        'sline_car_suit_price' => array('table' => 'sline_car_suit_price', 'name' => '租车套餐报价', 'beizu' => '租车套餐按日报价列表'),

        'sline_spot' => array('table' => 'sline_spot', 'name' => '景点', 'beizu' => '景点列表'),
        'sline_spot_attr' => array('table' => 'sline_spot_attr', 'name' => '景点属性', 'beizu' => '景点分类'),
        'sline_spot_kindlist' => array('table' => 'sline_spot_kindlist', 'name' => '景点目的地', 'beizu' => '景点目的地分类'),
        'sline_spot_pricelist' => array('table' => 'sline_spot_pricelist', 'name' => '景点门票价格', 'beizu' => '按日或星期等进行的报价'),
        'sline_spot_ticket' => array('table' => 'sline_spot_ticket', 'name' => '景点门票', 'beizu' => '景点门票列表'),

        'sline_tuan' => array('table' => 'sline_tuan', 'name' => '团购', 'beizu' => '团购列表'),
        'sline_tuan_attr' => array('table' => 'sline_tuan_attr', 'name' => '团购属性分类', 'beizu' => '团购分类'),
        'sline_tuan_attr' => array('table' => 'sline_tuan_attr', 'name' => '团购目的地分类', 'beizu' => '团购所在目的地'),

        'sline_visa' => array('table' => 'sline_visa', 'name' => '签证', 'beizu' => '签证列表'),

        'sline_article' => array('table' => 'sline_article', 'name' => '文章', 'beizu' => '即所有攻略，比如新闻，动态，游记等'),
        'sline_article' => array('table' => 'sline_article', 'name' => '文章属性', 'beizu' => '文章分类表'),
        'sline_article_kindlist' => array('table' => 'sline_article_kindlist', 'name' => '文章目的地', 'beizu' => '文章所属目的地，比如九寨沟,大理等'),

        'sline_photo' => array('table' => 'sline_photo', 'name' => '相册', 'beizu' => '相册表'),
        'sline_photo_picture' => array('table' => 'sline_photo_picture', 'name' => '相册图片', 'beizu' => '相册图片列表'),
        'sline_photo_attr' => array('table' => 'sline_photo_attr', 'name' => '相册属性分类', 'beizu' => '相册分类 '),
        'sline_photo_kindlist' => array('table' => 'sline_photo_kindlist', 'name' => '相册目的地列表', 'beizu' => '相册所属目的地'),

        'sline_member' => array('table' => 'sline_member', 'name' => '会员', 'beizu' => '客户会员列表'),
        'sline_member_order' => array('table' => 'sline_member_order', 'name' => '客户订单', 'beizu' => '所有产品订单'),
        'sline_question' => array('table' => 'sline_question', 'name' => '客户咨询', 'beizu' => '客户咨询'),

        'sline_module_list' => array('table' => 'sline_module_list', 'name' => '右侧模块', 'beizu' => '右侧模块列表'),
        'sline_module_config' => array('table' => 'sline_module_config', 'name' => '右侧模块配置', 'beizu' => '右侧模块配置'),

        'sline_weblist' => array('table' => 'sline_weblist', 'name' => '站点列表', 'beizu' => '站点列表，用于保存主站和子站信息'),
        'sline_nav' => array('table' => 'sline_nav', 'name' => '顶部导航', 'beizu' => '顶部菜单'),
        'sline_sysconfig' => array('table' => 'sline_sysconfig', 'name' => '系统配置', 'beizu' => '常用变量的列表，比如电话，logo,底部信息等各种设置等'),
        'sline_icon' => array('table' => 'sline_icon', 'name' => '图标', 'beizu' => '产品图标，通常用于表示推荐,热门,特价等'),
        'sline_keyword' => array('table' => 'sline_keyword', 'name' => '关键词', 'beizu' => '搜索关键词'),
        'sline_kindorderlist' => array('table' => 'sline_kindorderlist', 'name' => '目的地排序', 'beizu' => '产品在某些目的地下的排序列表'),

        'sline_comment' => array('table' => 'sline_comment', 'name' => '评论', 'beizu' => '前台用户评论，包括文章和产品评论等'),
        'sline_customize' => array('table' => 'sline_customize', 'name' => '订制订单', 'beizu' => '客户专门订制的订单'),

        'sline_destinations' => array('table' => 'sline_destinations', 'name' => '目的地', 'beizu' => '目的地表'),
        'sline_help' => array('table' => 'sline_help', 'name' => '帮助', 'beizu' => '帮助文章列表'),
        'sline_help_kind' => array('table' => 'sline_help_kind', 'name' => '帮助分类', 'beizu' => '帮助分类'),

        'sline_admin' => array('table' => 'sline_admin', 'name' => '用户表', 'beizu' => '后台管理用户表，比如系统管理员，普通管理员'),
        'sline_role' => array('table' => 'sline_role', 'name' => '用户权限表', 'beizu' => '用户权限表'),
        'sline_role_module' => array('table' => 'sline_role_module', 'name' => '用户权限定义表', 'beizu' => '保存用权限的具体定义'),

        'sline_advertise' => array('table' => 'sline_advertise', 'name' => '广告', 'beizu' => '所有前台广告，包括首页广告，栏目页广告，自定义广告等'),
        'sline_allorderlist' => array('table' => 'sline_allorderlist', 'name' => '全局排序', 'beizu' => '保存所有产品的全局排序表'),

    );

    public function before()
    {
        parent::before();
        Common::getUserRight('databack', 'smodify');
        $this->assign('cmsurl', URL::site());
        $this->assign('parentkey', $this->params['parentkey']);
        $this->assign('itemid', $this->params['itemid']);
        $this->assign('weblist', Common::getWebList());

    }

    /*
       首页
    */
    public function action_index()
    {
        $list = array();
        foreach (self::$db_tables as $k => $v)
        {
            $row = array();
            $row = $v;
            $row['id'] = $v['table'];
            $row['leaf'] = true;
            $list[] = $row;
        }
        $this->assign('tables', $list);
        $this->display('stourtravel/databackup/list');
    }

    /*
       备份
    */
    public function action_ajax_backup()
    {
        $table = Arr::get($_POST, 'table');
        $timestamp = Arr::get($_POST, 'timestamp');
        if (empty($table))
            echo 'no';
        else
        {
            if (empty($timestamp))
            {
                $timestamp = time();
            }

            $databackModel = new Model_Backup($timestamp);
            $databackModel->backup($table);

            echo $timestamp;
        }

    }


    /*
     * 数据库恢复列表
     */
    public function action_recovery()
    {
        $backuppath = BASEPATH . '/data/backup';
        $back_arr = $this->getDirObjects($backuppath);
        rsort($back_arr);

        $list = array();
        foreach ($back_arr as $k => $v)
        {
            $backdir = $backuppath . '/' . $v;
            if (!is_dir($backdir))
                continue;

            $row = array();
            $temp_path = $backdir . '/tables';
            $table_arr = $this->getDirObjects($temp_path);

            $nlist = array();
            foreach ($table_arr as $key => $val)
            {
                if (is_dir($temp_path . '/' . $val))
                    continue;

                $tablename = substr($val, 0, -4);

                $nrow = array();
                $nrow = self::$db_tables[$tablename];
                if ($nrow != null)
                {
                    $nrow['leaf'] = true;
                    $nrow['pdir'] = $v;
                    $nrow['id'] = 'son_' . $v . '_' . $tablename;
                    $nrow['text'] = $nrow['table'];

                    $nlist[] = $nrow;
                }
            }

            $row['text'] = $v;
            $row['id'] = 'pid_' . $v;
            $row['children'] = $nlist;

            $list[] = $row;
        }

        $this->assign('list', $list);
        $this->display('stourtravel/databackup/recovery');
    }

    private function getDirObjects($dir)
    {
        $objects = array();

        $handler = opendir($dir);
        while ($file = readdir($handler))
        {
            array_push($objects, $file);
        }
        $objects = array_diff($objects, array('.', '..'));

        return $objects;
    }

    /*
     * 恢复表
     */
    public function action_ajax_recover()
    {
        $dir = Arr::get($_POST, 'dir');
        $table = Arr::get($_POST, 'table');
        $backdirpath = BASEPATH . '/data/backup/' . $dir;

        if (!Model_Backup::recover($backdirpath, array($table)) || mysql_error())
            echo 'no';
        else
            echo 'ok';

    }

    public function action_ajax_delpackage()
    {
        $dir = Arr::get($_POST, 'dir');
        if (!empty($dir))
            Common::rrmdir(BASEPATH . '/data/backup/' . $dir);
        echo 'ok';
    }

}