<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 公共控制器
 */
class Controller_Pc_Pub extends Stourweb_Controller
{
    private $_user_info;
    public function before()
    {
        parent::before();
        //登陆状态判断
        $st_supplier_id = Cookie::get('st_supplier_id');

        if(empty($st_supplier_id))
        {
            //$this->request->redirect($GLOBALS['cfg_basehost'].'/plugins/supplier/pc/login');
        }
        else
        {
            $this->_user_info = Model_Supplier::get_supplier_byid($st_supplier_id);
            $this->assign('userinfo',$this->_user_info);
        }

    }
    /**
     * 网站头部
     */
    public function action_header()
    {
        $sid = Arr::get($_GET,'sid') ? intval(Arr::get($_GET,'sid')): '';
        if(!empty($sid))
        {
            $this->_user_info = Model_Supplier::get_supplier_byid($sid);
            $this->assign('userinfo',$this->_user_info);
        }
        $line_product = file_exists(PLUGINPATH.'supplier_line') && strpos($this->_user_info['kindid'],'1')!==FALSE ? 1: 0;
        $hotel_product = file_exists(PLUGINPATH.'supplier_hotel') && strpos($this->_user_info['kindid'],'2')!==FALSE ? 1 : 0;
        $car_product = file_exists(PLUGINPATH.'supplier_car') && strpos($this->_user_info['kindid'],'3')!==FALSE ? 1 : 0;
        $tuan_product = file_exists(PLUGINPATH.'supplier_tuan') && strpos($this->_user_info['kindid'],'13')!==FALSE ? 1 : 0;
        $spot_product = file_exists(PLUGINPATH.'supplier_spot') && strpos($this->_user_info['kindid'],'5')!==FALSE ? 1 : 0;
        $visa_product = file_exists(PLUGINPATH.'supplier_visa') && strpos($this->_user_info['kindid'],'8')!==FALSE ? 1 : 0;
        $tongyong_product = file_exists(PLUGINPATH.'supplier_tongyong') ? 1 : 0;
        $check_product = file_exists(PLUGINPATH.'supplier_check') ? 1 : 0;
        if($tongyong_product)
        {
            $ty_list = Model_Model::get_tongyong_model();
            $tongyong_list = array();
            foreach($ty_list as $tongyong)
            {
                if(strpos($this->_user_info['kindid'],$tongyong['id'])!==FALSE)
                {
                    array_push($tongyong_list,$tongyong);
                }
            }
            $this->assign('tongyong_list',$tongyong_list);
        }
        $finance_manage = file_exists(PLUGINPATH.'supplier_finance') ? 1 : 0;

        $this->assign('line_product',$line_product);
        $this->assign('hotel_product',$hotel_product);
        $this->assign('car_product',$car_product);
        $this->assign('spot_product',$spot_product);
        $this->assign('tuan_product',$tuan_product);
        $this->assign('visa_product',$visa_product);
        $this->assign('tongyong_product',$tongyong_product);
        $this->assign('check_product',$check_product);
        $this->assign('finance_manage',$finance_manage);
        $this->display("pub/header");
    }

    /**
     * 网站底部
     */
    public function action_footer()
    {
        $this->display("pub/footer");
    }

    /**
     * ajax 验证码验证
     */
    public function action_ajax_do_code()
    {
        $code = Common::remove_xss(Arr::get($_POST,'code'));

        if (!Captcha::valid($code))
        {
            echo json_encode(array('status' => 0));
            exit;
        }

        echo json_encode(array('status' => 1));
    }

    /**
     * ajax 发送验证码
     */
    public function action_ajax_send_message()
    {
        $validataion = Validation::factory($this->request->post());
        $validataion->rule('phone', 'not_empty');
        $validataion->rule('phone', 'phone');
        if (!$validataion->check())
        {
            exit(__('error_user_phone'));
        }
        //检测用户是否存在
        $phone = Arr::get($_POST, 'phone');
        $code = rand(1000, 9999);
        $model = ORM::factory('sms_msg');
        $content = $model->message_template('reg_findpwd');
        $content = str_replace(array('{#CODE#}', '{#WEBNAME#}', '{#PHONE#}'), array($code, Common::get_sys_para('cfg_webname'), Common::get_sys_para('cfg_phone')), $content);
        $status = $model->send_message($phone, $code, $content);
        echo intval($status);
    }

}