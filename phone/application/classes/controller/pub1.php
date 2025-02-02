<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pub extends Stourweb_Controller
{
    /*
     * 公共请求控制器,此控制器不能删除.
     *
     * */

    public function before()
    {
        parent::before();

    }

    //请求CSS资源,合并输出
    public function action_css()
    {
        $this->request->headers('Content-Type', 'text/css');
        $this->request->headers('charset', 'utf-8');
        if (isset($_GET['file']))
        {
            $files = explode(",", $_GET['file']);
            $fc = '';
            foreach ($files as $val)
            {
                $fc .= file_get_contents(DOCROOT . $val);
            }
            $fc = self::replace_note($fc);
            $fc = str_replace("\/t", "", $fc);
            $fc = str_replace("\/n", "", $fc);
            $fc = str_replace("\/r\/n", "", $fc);
            echo $fc;
        }
    }

    //请求js资源,合并输出
    public function action_js()
    {
        //输出JS
        header("Content-type:application/x-javascript; Charset: utf-8");
        if (isset($_GET['file']))
        {
            $files = explode(",", $_GET['file']);
            $str = '';
            foreach ($files as $val)
            {
                $str .= file_get_contents(DOCROOT . $val);
            }
            $str = self::replace_note($str);
            $str = str_replace("\/t", "", $str);
            $str = str_replace("\/n", "", $str);
            //$str = preg_replace('#\/\/[^\n]*#','',$str);//行注释
            echo $str;
        }
    }

    /*
     * 网站头部
     * */
    public function action_header()
    {
        Common::before_header();
        //默认显示logo
        $showlogo = 1;
        $typeid = $this->params['typeid'];
        $definetitle = $this->params['definetitle'];
        $definetitle = urldecode($definetitle);
        foreach ($this->params as $k => $v)
        {
            $this->assign($k, $v);
        }
        $isshowpage = $this->params['isshowpage'] ? $this->params['isshowpage'] : 0;
        $isplpage = $this->params['isplpage'] ? $this->params['isplpage'] : 0;
        $isbookpage = $this->params['isbookpage'] ? $this->params['isbookpage'] : 0;
        $isorder = $this->params['isorder'] ? $this->params['isorder'] : 0;
        $islinkman = $this->params['islinkman'] ? $this->params['islinkman'] : 0;
        $ispaypage = $this->params['ispaypage'] ? $this->params['ispaypage'] : 0;
        $iscommontitle = $this->params['iscommontitle'] ? $this->params['iscommontitle'] : 0;
        $isparam = $this->params['isparam'] ? $this->params['isparam'] : 0;
        if (!empty($typeid))
        {
            $channelname = Model_Nav::get_channel_name($typeid);
        }

        if ($isshowpage == 1 || $isplpage == 1 || $isbookpage == 1)
        {
            $showlogo = 0;
        }
        if ($isplpage == 1)
        {
            $channelname = '评论';
        }
        if ($isbookpage == 1)
        {
            $channelname = '预订产品';
        }
        if ($isbookpage == 1)
        {
            $channelname = '预订产品';
        }
        if ($isorder == 1)
        {
            $channelname = '我的订单';
        }
        if ($islinkman == 1)
        {
            $channelname = '常用联系人';
        }
        if ($ispaypage == 1)
        {
            $channelname = '确认订单';
        }
        if ($iscommontitle == 1)
        {
            $showlogo = 0;
            $channelname = '<stourweb_title/>';
        }
        if (!empty($isparam))
        {
            $showlogo = 0;
            $channelname = $isparam;
        }
        $member = Common::session('member');
        if (empty($member))
        {
            $member = array(
                'litpic' => Common::member_nopic()
            );
        }
        else
        {
            //订单数量
            $this->assign('orderNum', count(Model_Member_Order::unpay($member['mid'])));
        }
        $backUrl = 'href="javascript:;" onclick="javascript:history.go(-1);"';
        if (stripos($_SERVER['HTTP_REFERER'], Common::cookie_domain()) === false)
        {
            $conf = require dirname(DOCROOT) . '/data/mobile.php';
            $url = Common::get_main_host() . '/phone/';
            if (stripos($conf['domain']['mobile'], $url) === false)
            {
                $url = $conf['domain']['mobile'];
            }
            $backUrl = 'href="' . $url . '"';
        }
        $channelname = empty($definetitle) ? $channelname : $definetitle;
        $this->assign('showlogo', $showlogo);
        $this->assign('member', $member);
        $this->assign('isshowpage', $isshowpage);
        $this->assign('backurl', $backUrl);
        $this->assign('channelname', $channelname);
        $this->display('pub/header');
    }

    /*
     * 网站底部
     * */
    public function action_footer()
    {
        $sql = "select weburl from sline_weblist where webid=0";
        $ar = DB::query(1, $sql)->execute()->as_array();
        $this->assign('weburl', $ar[0]['weburl']);
        $this->display('pub/footer');
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
        $content = $model->message_template('reg_msgcode');
        $content = str_replace(array('{#CODE#}', '{#WEBNAME#}', '{#PHONE#}'), array($code, $GLOBALS['cfg_webname'], $GLOBALS['cfg_phone']), $content);
        $status = $model->send_message($phone, $code, $content);
        echo intval($status);
    }

    /**
     * 验证手机短信验证码
     */
    public function action_ajax_check_msg()
    {
        //验证码检测
        if (isset($_POST['msg']))
        {
            $msg = Arr::get($_POST, 'msg');

            //短信验证是否开启
            $sms_msg = DB::select()->from('sms_msg')->where("msgtype='reg_findpwd'")->execute()->current();
            if ($sms_msg['isopen'])
            {
                $result = (bool)Common::session('msg_code') == $msg;
            }
            else
            {
                $result = (bool)(sha1(utf8::strtoupper($msg)) === Session::instance()->get('captcha_response'));
            }
            if ($result)
            {
                print_r('true');
                exit;
            }
            else
            {
                print_r('false');
            }
        }
        else
        {
            print_r('false');
        }
    }

    /*
     * 搜索选择页面
     *
     * */
    public function action_select()
    {
        $typeid = Common::remove_xss($this->params['typeid']);
        $destid = Common::remove_xss($this->params['destid']);
        //判断目的地是否设置,未设置则使用顶级目的地
        $destid = $destid ? $destid : 0;
        $model = ORM::factory('model', $typeid);
        if (!$model->loaded())
            return;
        $pinyin = $model->pinyin;

        //排除通用模块和签证模块(读取属性)
        if (!empty($pinyin) && $typeid != 8 && $model->maintable != 'model_archive')
        {
            $table = "sline_{$pinyin}_attr";
            $sql = "SELECT * FROM `{$table}` ";
            $sql .= "WHERE isopen=1 AND pid=0 ";
            $sql .= "ORDER BY displayorder ASC";
            $arr = DB::query(1, $sql)->execute()->as_array();

            $attrlist = $arr;
        }
        else if ($model->maintable == 'model_archive')
        {
            $table = 'sline_model_attr';
            $sql = "SELECT * FROM `{$table}` ";
            $sql .= "WHERE isopen=1 AND pid=0 and typeid=$typeid ";
            $sql .= "ORDER BY displayorder ASC";
            $arr = DB::query(1, $sql)->execute()->as_array();

            $attrlist = $arr;
        }

        $dest_list = ORM::factory('destinations')->where("pid='$destid' AND isopen=1")->get_all();

        $this->assign('hotdest', $hot_dest);
        $this->assign('attrlist', $attrlist);
        $this->assign('destlist', $dest_list);
        $this->assign('typeid', $typeid);
        $this->display('pub/select');

    }
    /**

     * 添加浏览次数

     */

    public function action_ajax_add_shownum()

    {

        $typeid = intval(Arr::get($_GET,'typeid'));

        $aid = Common::remove_xss(Arr::get($_GET,'productid'));

        if($typeid)

        {

            Product::update_click_rate($aid,$typeid);

        }

    }

    /*由子级目的地获取上级目的地*/
    public function action_ajax_get_destall()
    {
        $destpy = Arr::get($_GET, 'destpy');
        $typeid = Arr::get($_GET, 'typeid');
        //根据拼音获取PID
        $sql = "select * from sline_destinations where pinyin='{$destpy}' and isopen=1";
        $arr = DB::query(1, $sql)->execute()->as_array();
        $sql = "select * from sline_destinations where id='{$arr[0]['pid']}' and isopen=1";
        $arr2 = DB::query(1, $sql)->execute()->as_array();
        //根据PID获取ID 判断是第二级还是第一级
        if ($arr2[0]['pid'] != 0)
        {
            //第二级
            $sql = "select * from sline_destinations where id='{$arr2[0]['pid']}' and isopen=1";
            $arr3 = DB::query(1, $sql)->execute()->as_array();
            $out = array(
                'destid' => $arr3[0]['id'],//1级ID
                'destidtwo' => $arr[0]['pid'], //2级ID
                'addcss' => 'ok',
                'destthree' => $arr[0]['id'] //3级ID
            );
        }
        else
        {
            //第一级
            $out = array(
                'destid' => $arr2[0]['id'],
                'destidtwo' => $arr[0]['id']
            );
        }
        echo json_encode($out);
    }

    /*
     * 评论页面
     *
     * */
    public function action_comment()
    {
        $articleid = Common::remove_xss($this->params['id']);
        $typeid = Common::remove_xss($this->params['typeid']);
        $pinyin = ORM::factory('model', $typeid)->get('pinyin');
        if (!empty($pinyin))
        {
            //线路显示评论id=$articleid 换成aid=$articleid
            $row = ORM::factory($pinyin)->where("aid=$articleid")->find()->as_array();
            //查询数量$articleid 换成$row['id']
            $row['commentnum'] = Model_Comment::get_comment_num($row['id'], $typeid);
            $row['score'] = Model_Comment::get_score($articleid, $typeid, $row['$satisfyscore'], $row['commentnum']);
        }
        $this->assign('info', $row);
        $this->assign('typeid', $typeid);
        $this->display('pub/comment');
    }
    /*
     * 新评论 页面  封 09.13
     */
    public function action_New_comment()
    {
        $articleid = Common::remove_xss($this->params['id']);//文章  aid
        $typeid = Common::remove_xss($this->params['typeid']);
        $pinyin = ORM::factory('model', $typeid)->get('pinyin');
        //获取点前页面的的地址
        $url    = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        //  获取 微信用用户 信息
         if(isset($_GET['code'])){
           $code  = isset($_GET['code'])?$_GET['code']:"no code";
         }else{

           $redirect_url = urlencode($url);
           header('Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxdf8497303505ae0d&redirect_uri='.$redirect_url.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect');
        }
        if (!empty($pinyin))
        {
            //线路显示评论id=$articleid 换成aid=$articleid
            if($typeid=='101'){
                $row = ORM::factory($pinyin)->where("id=$articleid")->find()->as_array();
                $row['url'] = Common::get_web_url($row['webid']) . "/notes/show_{$row['id']}.html";
                // 获取 对应游记的 会员信息
                $member = ORM::factory('member',$row['memberid'])->as_array();
                $row['authorhead']  = $member['litpic'];
                $row ['author']     = $member['nickname'];
            }else{
                $row = ORM::factory($pinyin)->where("aid=$articleid")->find()->as_array();
                $row['img_count'] = substr_count($row['content'],'<img')*1-3;
                $row['dayTour'] = Model_Article::get_Several_day_tour($row['attrid']);//几日游
                $row['url'] = Common::get_web_url($row['webid']) . "/raiders/show_{$row['aid']}.html";
                $row['authorhead']    = Model_Article::get_authorHead($row['author']);
            }
            //查询数量$articleid 换成$row['id']
            $row['commentnum'] = Model_Comment::get_comment_num($row['id'],$typeid); //评论次数
            $row['mdd']     = Model_Article::get_mdds($row['finaldestid']);//最终目的地
        }
        $this->assign('info', $row);
        $this ->assign('code',$code);

        $this->assign('typeid', $typeid);
        $this->display('pub/discuss');
    }
    /*
     * 付款
     * */

    public function action_pay()
    {

        $this->display('pub/pay');
    }

    /**
     * 通用头底部html
     */
    public function action_commonhd()
    {
        $this->display('pub/commonhd');
    }

    /**
     * 积分抵现
     */
    public function action_integral()
    {
        $jifen = array();
        $bool = true;
        $member = Common::session('member');
        if ($member)
        {
            $userInfo = Model_Member::get_member_byid($member['mid']);
            $jifen['isopen'] = 1;
            $jifen['exchange'] = $GLOBALS['cfg_exchange_jifen'];
            $jifen['userjifen'] = $userInfo['jifen'];
            if (empty($jifen['exchange']))
            {
                $bool = false;
            }
        }
        $this->assign('jifen', $jifen);
        if ($bool)
        {
            $this->display('pub/integral');
        }

    }

    public function action_code()
    {
        $this->display('pub/code');
    }


    /*
     * 进行第三方支付页面
     * */
    public function action_dopay()
    {
        $orderid = Arr::get($_POST, 'orderid');
        $paytype = Arr::get($_POST, 'paytype');
        $usejifen = Arr::get($_POST, 'usejifen');

        $info = ORM::factory('member_order', $orderid)->as_array();

        //使用积分抵现,且当前订单没有使用过积分抵现功能.
        if (intval($usejifen) == 1 && $info['usejifen'] == 0)
        {
            $needjifen = $GLOBALS['cfg_exchange_jifen'] * $info['jifentprice']; //所需积分
            //当前登陆会员
            $member = Common::session('member');
            if ($member)
            {
                $memberid = $member['mid'];
                $flag = Model_Member::operate_jifen($memberid, $needjifen, 1);
                if ($flag)
                {
                    Product::add_jifen_log($memberid, '预订产品' . $info['productname'] . '积分抵现消费积分' . $needjifen, $needjifen, 1);
                    //如果会员积分扣除成功,则写订单信息,使用积分抵现.
                    $m = ORM::factory('member_order', $orderid);
                    $m->usejifen = 1;
                    $m->save();
                }

            }

        }
        if (intval($info['dingjin']) > 0)
        {
            $totalprice = $info['dingjin'] * ($info['dingnum'] + $info['childnum'] + $info['oldnum']);
        }
        else if ($info['typeid'] == 2)
        {
            $totalprice = Model_Hotel::suit_range_price($info['suitid'], $info['usedate'], $info['departdate'], $info['dingnum']);
            $totalprice = $totalprice * $info['dingnum'];
        }
        else if ($info['typeid'] == 3)
        {
            $totalprice = Model_Car::suit_range_price($info['suitid'], $info['usedate'], $info['departdate'], $info['dingnum']);
        }
        else
        {
            $totalprice = $info['price'] * $info['dingnum'] + $info['childnum'] * $info['childprice'] + $info['oldnum'] * $info['oldprice'];
        }

        //再次查询数据库,判断是否使用积分成功,如果成功则重新计算价格.
        $ujifen = ORM::factory('member_order', $orderid)->get('usejifen');
        if (intval($ujifen) == 1)
        {
            $jifentprice = intval($info['jifentprice']);
            $totalprice = intval($totalprice) - $jifentprice;
        }

        if ($paytype == 0)
        {
            exit('error request');
        }
        echo Product::pay_online($info['ordersn'], $info['productname'], $totalprice, $paytype);

    }


    /*
     * 日历筛选
     * */
    public function action_calendar()
    {
        $typeid = $this->params['typeid'];
        $suitid = $this->params['suitid'];
        $productid = $this->params['productid'];
        $startdate = $this->params['startdate'];
        $containdiv = $this->params['containdiv'];

        $year = date("Y"); //当前月
        $month = date("m");//当前年
        $out = '';
        for ($i = 1; $i <= 24; $i++)
        {
            if ($month == 13)
            {
                $year = $year + 1;
                $month = 1;
            }
            $priceArr = self::get_suit_price($year, $month, $suitid, $typeid, $startdate);
            $out .= empty($priceArr) ? '' : self::calender($year, $month, $priceArr, $containdiv);
            $month++;
        }

        $this->assign('calendar', $out);
        $this->assign('typeid', $typeid);
        $this->display('pub/calendar');
    }

    /**
     * 生成格式化的数据
     * 用于日历中进行呈现
     * @param $arr
     */
    public function get_suit_price($year, $month, $suitid, $typeid, $startdate)
    {
        $priceTable = array(
            '1' => 'line_suit_price',
            '2' => 'hotel_room_price',
            '3' => 'car_suit_price',
            '106'=>'mitao_suit_price'
        );
        $start = !empty($startdate) ? strtotime($startdate) : strtotime("$year-$month-1");
        $end = strtotime("$year-$month-31");
        $table = $priceTable[$typeid];
        $arr = ORM::factory($table)
            ->where("suitid=$suitid")
            ->and_where('day', '>=', $start)
            ->and_where('day', '<=', $end)
            ->and_where('number', '!=', 0)
            ->get_all();

        $price = array();
        foreach ($arr as $row)
        {
            if ($row)
            {
                $day = $row['day'];
                $price[$day]['date'] = Common::mydate('Y-m-d', $row['day']);
                $price[$day]['basicprice'] = isset($row['adultbasicprice']) ? $row['adultbasicprice'] : $row['basicprice'];
                $price[$day]['basicprice'] = Currency_Tool::price($price[$day]['basicprice']);

                $price[$day]['profit'] = isset($row['adultprofit']) ? $row['adultprofit'] : $row['profit'];
                $price[$day]['profit'] = Currency_Tool::price($price[$day]['profit']);

                $price[$day]['price'] = isset($row['adultprice']) ? $row['adultprice'] : $row['price'];
                $price[$day]['price'] = Currency_Tool::price($price[$day]['price']);


                $price[$day]['child_basicprice'] = isset($row['childbasicprice']) ? $row['childbasicprice'] : 0;
                $price[$day]['child_basicprice'] = Currency_Tool::price($price[$day]['child_basicprice']);

                $price[$day]['child_profit'] = isset($row['childprofit']) ? $row['childprofit'] : 0;
                $price[$day]['child_profit'] = Currency_Tool::price($price[$day]['child_profit']);

                $price[$day]['child_price'] = isset($row['childprice']) ? $row['childprice'] : 0;
                $price[$day]['child_price'] = Currency_Tool::price($price[$day]['child_price']);


                $price[$day]['old_basicprice'] = isset($row['oldbasicprice']) ? $row['oldbasicprice'] : 0;
                $price[$day]['old_basicprice'] = Currency_Tool::price($price[$day]['old_basicprice']);

                $price[$day]['old_profit'] = isset($row['oldprofit']) ? $row['oldprofit'] : 0;
                $price[$day]['old_profit'] = Currency_Tool::price($price[$day]['old_profit']);

                $price[$day]['old_price'] = isset($row['oldprice']) ? $row['oldprice'] : 0;
                $price[$day]['old_profit'] = Currency_Tool::price($price[$day]['old_profit']);

                $price[$day]['suitid'] = $suitid;
                $price[$day]['number'] = $row['number'];//库存
                $price[$day]['description'] = $row['description'];//描述
            }

        }


        return $price;
    }

    /**
     *
     * 我的日历(DateTime版本)
     * date_default_timezone_set date mktime
     * @param int $year
     * @param int $month
     * @priceArr array 成人,儿童,老人报价
     * @param string $timezone
     */


    public function calender($year = '', $month = '', $priceArr = NULL, $contain)
    {

        date_default_timezone_set('Asia/Shanghai');
        $year = abs(intval($year));
        $month = abs(intval($month));
        $tmonth = $month < 10 ? "0" . $month : $month;
        $defaultYM = $year . '-' . $tmonth;
        $nowDate = new DateTime();

        if ($year <= 0)
        {
            $year = $nowDate->format('Y');
        }

        if ($month <= 0 or $month > 12)
        {
            $month = $nowDate->format('m');
        }

        //上一年
        $pretYear = $year - 1;
        //上一月
        $mpYear = $year;
        $preMonth = $month - 1;
        if ($preMonth <= 0)
        {
            $preMonth = 1;
            $mpYear = $pretYear;
        }
        $preMonth = $preMonth < 10 ? '0' . $preMonth : $preMonth;

        //下一年
        $nextYear = $year + 1;
        //下一月
        $mnYear = $year;
        $nextMonth = $month + 1;
        if ($nextMonth > 12)
        {
            $nextMonth = 1;
            $mnYear = $nextYear;
        }
        $nextMonth = $nextMonth < 10 ? '0' . $nextMonth : $nextMonth;


        //日历头
        $html = '<div class="tab">
<table width="100%" border="1" style="border-collapse: collapse;">

  <tr align="center" >
    <td colspan="3" class="top_title" style="height:50px;">' . $year . '年' . $month . '月</td>

  </tr>
  <tr>
  	<td colspan="5">
		<table width="100%" border="1" >
			<tr align="center">
				<td style="background-color:#DAF0DD;height:25px;">星期一</td>
				<td style="background-color:#DAF0DD;height:25px;">星期二</td>
				<td style="background-color:#DAF0DD;height:25px;">星期三</td>
				<td style="background-color:#DAF0DD;height:25px;">星期四</td>
				<td style="background-color:#DAF0DD;height:25px;">星期五</td>
				<td style="background-color:#F60;color:#fff;font-weight: bold;">星期六</td>
				<td style="background-color:#F60;color:#fff;font-weight: bold;">星期天</td>
			</tr>
';

        $currentDay = $nowDate->format('Y-m-j');

        //当月最后一天
        $creatDate = new DateTime("$year-$nextMonth-0");
        $lastday = $creatDate->format('j');
        $creatDate = NULL;

        //循环输出天数
        $day = 1;
        $line = '';
        while ($day <= $lastday)
        {
            $cday = $year . '-' . $month . '-' . $day;

            //当前星期几
            $creatDate = new DateTime("$year-$month-$day");
            $nowWeek = $creatDate->format('N');
            $creatDate = NULL;

            if ($day == 1)
            {
                $line = '<tr align="center">';
                $line .= str_repeat('<td>&nbsp;</td>', $nowWeek - 1);
            }
            if ($cday == $currentDay)
            {
                $style = 'style="font-size:16px; font-family:微软雅黑,Arial,Helvetica,sans-serif;color:#FF6600;line-height:22px;"';
            }
            else
            {
                $style = 'style=" font-size:16px; font-family:微软雅黑,Arial,Helvetica,sans-serif;line-height:22px;"';
            }
            //判断当前的日期是否小于今天
            $defaultmktime = mktime(1, 1, 1, $month, $day, $year);

            $currentmktime = mktime(1, 1, 1, date("m"), date("j"), date("Y"));
            //echo '<hr>';
            $tday = ($day < 10) ? '0' . $day : $day;
            $cdaydate = $defaultYM . '-' . $tday;
            $cdayme = strtotime($cdaydate);
            //单价
            $dayPrice = $priceArr[$cdayme]['price'];
            /* //成本
             $daybasicprice = $priceArr[$cdayme]['basicprice'];
             //利润
             $dayprofitprice = $priceArr[$cdayme]['profit'];

             //老人
             $day_old_price = $priceArr[$cdayme]['old_price'];
             $day_old_basicprice = $priceArr[$cdayme]['old_basicprice'];
             $day_old_profit = $priceArr[$cdayme]['old_profit'];
             //儿童
             $day_child_price = $priceArr[$cdayme]['child_price'];
             $day_child_basicprice = $priceArr[$cdayme]['child_basicprice'];
             $day_child_profit = $priceArr[$cdayme]['child_profit'];*/


            //库存
            $priceArr[$cdayme]['number'] = $priceArr[$cdayme]['number'] < -1 ? 0 : $priceArr[$cdayme]['number'];
            $number = $priceArr[$cdayme]['number'] != -1 ? $priceArr[$cdayme]['number'] : '不限';


            //suitid
            // $daysuitid = $suitid;

            //定义单元格样式，高，宽
            $tdStyle = "height='80'";
            //判断当前的日期是否小于今天
            $tdcontent = '<span class="num">' . $day . '</span>';
            if ($defaultmktime >= $currentmktime)
            {


                if ($dayPrice)
                {

                    $dayPriceStrs = '¥' . $dayPrice . '<br>';
                    $ydCls = '';
                    $balanceStr = '';
                    $tdcontent .= '<b class="yes_yd ' . $ydCls . '">' . $dayPriceStrs . '</b>' . $balanceStr;
                    if ($number === 0)
                    {
                        $onclick = '';
                    }
                    else
                    {
                        $onclick = 'onclick="choose_day(\'' . $cday . '\',\'' . $contain . '\')"';
                    }
                    $numberinfo = "<span class='kucun'>库存:$number</span>";
                }
                else
                {
                    $dayPriceStrs = '';
                    $tdcontent .= '<b class="no_yd">' . $dayPriceStrs . '</b>' . '<b class="roombalance_b"></b>';
                    $onclick = '';
                    $numberinfo = "<span class='kucun'></span>";

                }
                if ($onclick == '')
                {

                    $line .= "<td $tdStyle class='nouseable' onclick=\"choose_null_day('" . $cday . "','" . $contain . "')\" >" . $tdcontent . $numberinfo . "</td>";
                }
                else
                {
                    $line .= "<td $tdStyle $onclick style='cursor:pointer;' class='useable' >" . $tdcontent . $numberinfo . "</td>";
                }
            }
            else
            {
                $dayPriceStrs = '&nbsp;&nbsp;';
                $tdcontent .= '<b class="no_yd">' . $dayPriceStrs . '</b>';
                $line .= "<td $tdStyle class='nouseable' >" . $tdcontent . "</td>";
            }


            //$line .= "<td $style>$day <div>不可订</div></td>";

            //一周结束
            if ($nowWeek == 7)
            {
                $line .= '</tr>';
                $html .= $line;
                $line = '<tr align="center">';
            }

            //全月结束
            if ($day == $lastday)
            {
                if ($nowWeek != 7)
                {
                    $line .= str_repeat('<td>&nbsp;</td>', 7 - $nowWeek);
                }
                $line .= '</tr>';
                $html .= $line;

                break;
            }

            $day++;
        }

        $html .= '
		</table>
	</td>
  </tr>
</table>
</div>
';
        return $html;

    }

    /**
     * @param $str
     * @return mixed
     * @desc 替换注释
     */
    public function replace_note($str)
    {


        $pos0 = strpos($str, '/*');
        while ($pos0 !== false)
        {
            $pos1 = strpos($str, '*/');
            if ($pos1 === false)
            {
                $pos0 += 2;
            }
            else
            {
                $rp = substr($str, $pos0, $pos1 - $pos0 + 2);
                $str = str_replace($rp, '', $str);
                $pos0 = strpos($str, '/*');
            }
        }

        return $str;

    }


    /*ajax请求*/
    /**
     * 获取目的地
     * @return string
     */

    public function action_ajax_get_dest()
    {
        if (!$this->request->is_ajax())
            return '';
        $flag = Arr::get($_GET, 'flag');
        $destid = Arr::get($_GET, 'destid');
        $typeid = Arr::get($_GET, 'typeid');
        if ($flag == 'desthot')
        {
            $dest = Model_Destinations::get_hot_dest($typeid, 0, 20);
            $flag = '';
            $liclass = 'hotdest';
        }
        else
        {
            $sql = "SELECT id,kindname,pinyin FROM `sline_destinations` ";
            $sql .= "WHERE isopen=1 AND pid='$destid' AND FIND_IN_SET($typeid,opentypeids) ";
            $dest = DB::query(1, $sql)->execute()->as_array();
            $ajaxdiv = 'list-spot';
            $flag = 'dest';
            $liclass = 'hasnext';
        }
        $out = array(
            'ajaxdiv' => $ajaxdiv,
            'list' => $dest,
            'flag' => $flag,
            'liclass' => $liclass
        );
        echo json_encode($out);

    }

    /**
     * 异步获取属性组下级
     *
     */
    public function action_ajax_get_attr()
    {

        $typeid = Arr::get($_GET, 'typeid');
        $pid = Arr::get($_GET, 'attrid');
        $pinyin = ORM::factory('model', $typeid)->get('pinyin');
        $flag = '';
        $liclass = '';
        $arr = array();


        //排除通用模块和签证模块(读取属性)
        if (!empty($pinyin) && $typeid != 8 && $typeid < 17)
        {
            $table = "sline_{$pinyin}_attr";
            $sql = "SELECT *,attrname AS kindname FROM `{$table}` ";
            $sql .= "WHERE isopen=1 AND pid=$pid ";
            $sql .= "ORDER BY displayorder ASC";
            $arr = DB::query(1, $sql)->execute()->as_array();

        }
        $out = array(
            'ajaxdiv' => '',
            'list' => $arr,
            'flag' => $flag,
            'liclass' => $liclass,
            'type' => 'attrid'

        );
        echo json_encode($out);
    }

    public function action_ajax_calendar()
    {
        $typeid = $_POST['typeid'];
        $suitid = $_POST['suitid'];
        $productid = $_POST['productid'];
        $containdiv = Arr::get($_POST, 'containdiv');
        $startdate = Arr::get($_POST, 'startdate');//开始日期
        $url = "pub/calendar/typeid/$typeid/suitid/$suitid/productid/$productid/containdiv/$containdiv/startdate/$startdate";
        $content = Request::factory($url)->execute()->body();
        echo $content;
    }

    /*
 * 异步获取评论
 *
 * */
    public function action_ajax_comment()
    {

        if (!$this->request->is_ajax())
            return '';
        $articleid = Arr::get($_GET, 'articleid');
        $typeid = Arr::get($_GET, 'typeid');
        $page = Arr::get($_GET, 'page');
        $pinyin = ORM::factory('model', $typeid)->get('pinyin');
        $row = ORM::factory($pinyin)->where("id=$articleid")->find()->as_array();
        $row['commentnum'] = Model_Comment::get_comment_num($articleid, $typeid);
        $row['score'] = Model_Comment::get_score($articleid, $typeid, $row['$satisfyscore'], $row['commentnum']);
        $page = $page ? $page : 1;
        $pagesize = 5;
        $offset = ($page - 1) * $pagesize;
        $sql = "SELECT * FROM `sline_comment` WHERE articleid='$articleid' AND typeid='$typeid' LIMIT {$offset},{$pagesize}";
        $pl = DB::query(1, $sql)->execute()->as_array();
        foreach ($pl as $key => &$v)
        {
            $score = $pl[$key]['score1'] * 20;
            $memberinfo = Model_Member::get_member_byid($v['memberid']);
            $pl[$key]['addtime']= date("Y-m-d H:i",$v['addtime']);
            $pl[$key]['litpic'] = $memberinfo['litpic'] ? $memberinfo['litpic'] : Common::member_nopic();
            $pl[$key]['nickname'] = $memberinfo['nickname'];
            //==========start  fengjishu 07.25
            $pl[$key]['tel'] = substr($memberinfo['mobile'],0,3)."***".substr($memberinfo['mobile'],7,4);
            // /=========end fengjishu
            $pl[$key]['score'] = $score . '%';
            $pl[$key]['content'] = $v['content'];
        }
        echo json_encode(array('list' => $pl));
    }

    public function action_paystatus()
    {
        $info = array('status' => false, 'url' => $this->cmsurl);
        $this->assign('info', $info);
        $this->display('pub/paystatus');
        if (!isset($_GET['sign']))
        {
            die('No direct script access.');
        }
        $sign = $_GET['sign'];
        switch ($sign)
        {
            //支付失败
            case '00':
                $info = array('sign' => 0, 'title' => '支付失败', 'msg' => '对不起，支付失败！');
                break;
            case '01':
                $info = array('sign' => 1, 'title' => '支宝付支付异常', 'msg' => '支付宝支付异常中断！');
                break;
            //支付成功
            case '11':
                $info = array('sign' => 11, 'title' => '支付成功', 'msg' => '恭喜，购买成功!');
                break;
            case '12':
                $info = array('sign' => 12, 'title' => '线下支付', 'msg' => '您的订单已提交成功，我们会尽快为您确认！');
                break;
            case '13':
                $info = array('sign' => 13, 'title' => '等待确认', 'msg' => '你的订单已提交成功，请等待确认！');
                break;
        }
        $this->assign('info', $info);
        $this->display('pub/paystatus');
    }

    /**
     * 404
     */
    public function action_404()
    {
        $this->display('pub/404');
    }

    /**
     * 第三方登陆跳转
     */
    public function action_thirdlogin()
    {
        $type = Arr::get($_GET, 'type');
        $refer = Arr::get($_GET, 'refer');
        if (empty($type) || empty($refer))
        {
            $this->request->redirect('pub/404');
        }
        Cookie::set('_version', 'mobile_5.0');
        header("location:" . Common::get_main_host() . "/plugins/login_{$type}/index/index/?refer={$refer}");
        exit;
    }

}