<?php defined('SYSPATH') or die('No direct script access.');



class Controller_Line extends Stourweb_Controller

{

    private $jiaotong = array('飞机', '汽车', '火车', '轮船');



    public function before()

    {

        parent::before();

        //$this->request->controller,

        $action = $this->request->action();

        if ($action == 'line')

        {

            $param = $this->params['action'];

            $right = array(

                'read' => 'slook',

                'save' => 'smodify',

                'delete' => 'sdelete',

                'update' => 'smodify'

            );

            $user_action = $right[$param];

            if (!empty($user_action))

                Common::getUserRight('line', $user_action);

        }

        else if ($action == 'add')

        {

            Common::getUserRight('line', 'sadd');

        }

        else if ($action == 'edit')

        {

            Common::getUserRight('line', 'smodify');

        }

        else if ($action == 'day')

        {

            $param = $this->params['action'];

            $right = array(

                'add' => 'slook',

                'save' => 'smodify',

                'del' => 'sdelete'

            );

            $user_action = $right[$param] ? $right[$param] : 'slook';

            if (!empty($user_action))

                Common::getUserRight('lineday', $user_action);

        }

        else if ($action == 'price')

        {

            $param = $this->params['action'];

            $right = array(

                'add' => 'slook',

                'save' => 'smodify',

                'del' => 'sdelete'

            );

            $user_action = $right[$param] ? $right[$param] : 'slook';

            if (!empty($user_action))

                Common::getUserRight('lineprice', $user_action);

        }

        else if ($action == 'content') //线路行程

        {

            $param = $this->params['action'];

            $right = array(

                'save' => 'smodify',

            );

            $user_action = $right[$param] ? $right[$param] : 'slook';

            if (!empty($user_action))

                Common::getUserRight('linejieshao', $user_action);

        }

        else if ($action == 'ajax_linesave')

        {

            Common::getUserRight('line', 'smodify');

        }

        $this->assign('parentkey', $this->params['parentkey']);

        $this->assign('itemid', $this->params['itemid']);

        $this->assign('weblist', Common::getWebList());

        $this->assign('sysjiaotong', $this->jiaotong);//交通

        $this->assign('templetlist', Common::getUserTemplteList('line_show'));

    }
  /*
   * 创建订单 页面  book
   * 对于线路的套餐只取 第一个套餐
   */
    public  function action_create_order(){

        $productId  = Arr::get($_GET, 'lineid');
        //产品信息
        $info = ORM::factory('line',$productId)->as_array();

        //套餐信息  只能做一个 套餐
        $suitInfo = Model_Line::suit_info($productId);

        //套餐按出发日期价格
        $suitPrice = Model_Line::suit_price($suitInfo['id'],$info['linedate']);

        //产品编号

        $info['series'] =  Model_Line::product_number($info['id'], '01');
        $info['usedate'] = $info['linedate'];
        $this->assign('info',$info);

        $this->assign('suitInfo',$suitInfo);

        $this->assign('suitPrice',$suitPrice);

        $this->display('stourtravel/line/book');

    }

    /*

 * 创建订单

 * */



    public function action_create()

    {

        //会员信息

       // $userInfo = Product::get_login_user_info();

        $memberId = $userInfo ? $userInfo['mid'] : 0;//会员id

        $webid = intval(Arr::get($_POST,'webid'));//网站id

        $adultNum = intval(Arr::get($_POST,'adult_num'));//成人数量

        $childNum = intval(Arr::get($_POST,'child_num'));//小孩数量

        $oldNum = intval(Arr::get($_POST,'old_num'));//老人数量

        $suitId = intval(Arr::get($_POST,'suitid'));//套餐id

        $lineId = intval(Arr::get($_POST,'lineid'));//线路id

        $useDate = Arr::get($_POST,'usedate');//出发日期

        $linkMan = Arr::get($_POST,'linkman');//联系人姓名

        $linkTel = Arr::get($_POST,'linktel');//联系人电话

        $linkEmail = Arr::get($_POST,'linkemail');//联系人邮箱

        $remark = Arr::get($_POST,'remark');//订单备注

        $roomBalanceNum = intval(Arr::get($_POST,'roombalance_num'));//单房差数量

        $roomBalance_Paytype = intval(Arr::get($_POST,'roombalance_paytype'));//单房差支付方式.

        $isneedBill = intval(Arr::get($_POST,'isneedbill'));//是否需要发票



        $billTitle = Arr::get($_POST,'bill_title');//发票抬头

        $billReceiver = Arr::get($_POST,'bill_receiver');//发票接收人

        $billPhone = Arr::get($_POST,'bill_phone');//发票联系人电话

        $billProv = Arr::get($_POST,'bill_prov');//发票联系人省份

        $billCity = Arr::get($_POST,'bill_city');//发票联系人城市+

        $billAddress = Arr::get($_POST,'bill_address');//发票联系人地址

        $payType = Arr::get($_POST,'paytype');//支付方式

        $insuranceCode = Arr::get($_POST,'insurance_code');//选择的保险

        $useJifen = intval(Arr::get($_POST,'usejifen'));//是否使用积分



        //发票信息

        $billInfo = array(

            'title'=>$billTitle,

            'receiver'=>$billReceiver,

            'mobile'=>$billPhone,

            'province'=>$billProv,

            'city'=>$billCity,

            'address'=>$billAddress

        );



        //游客信息读取

        $t_name = Arr::get($_POST,'t_name');

        $t_cardtype = Arr::get($_POST,'t_cardtype');

        $t_cardno = Arr::get($_POST,'t_cardno');

        $tourer = array();

        $totalNum = $adultNum + $childNum + $oldNum;

        for($i=0;$i<$totalNum;$i++)

        {

            $tourer[] = array(

                'name'=>$t_name[$i],

                'cardtype'=>$t_cardtype[$i],

                'cardno'=>$t_cardno[$i]

            );

        }

        //套餐信息

        $suitInfo = Model_Line::suit_info($suitId);

        //产品信息

        $info = ORM::factory('line',$lineId)->as_array();

        //套餐按出发日期价格

        $suitPrice = Model_Line::suit_price($suitId,$info['linedate']);

        $orderSn   = Model_Line::get_ordersn('01');

        //判断积分使用是否满足需求.

        $needJifen = 0;

        if($useJifen)

        {

            $needJifen = $GLOBALS['cfg_exchange_jifen'] * $suitInfo['jifentprice']; //所需积分

            if($userInfo['jifen']<$needJifen)

            {

                $useJifen = 0;

            }

        }
        //====================== 车辆信息
        $carbrand    = Arr::get($_POST,'carbrand');//  品牌
        $cartype     = Arr::get($_POST,'cartype');//车型
        $carage      = Arr::get($_POST,'carage');//年份
        $insuranceDate = Arr::get($_POST,'insuranceDate');//保险日期
        $cargrade    = Arr::get($_POST,'cargrade');//档次
        $carhost     = Arr::get($_POST,'carhost');//车主出生年月
        $hostwork    = Arr::get($_POST,'hostwork');//车主职业
        $driveRate   = Arr::get($_POST,'driveRate');//自驾频次
        $goneSeason  = Arr::get($_POST,'goneSeason');//去过的季节
        $goneSpot    = Arr::get($_POST,'goneSpot');//去过的景点
        $wantSeason  = Arr::get($_POST,'wantSeason');//想去 的季节
        $wantSpot    = Arr::get($_POST,'wantSpot');//想去的 景点
        $usertype    = Arr::get($_POST,'usertype');//用户的类型 亲子 ，聚会 等

        //=====接受微信用户的id 昵称
        $wxuserid    = Arr::get($_POST,'wxuser');

        //=======================
        //订单状态(全款支付,定金支付,二次确认)

        $status = $suitInfo['paytype'] != 3 ? 1 : 0;

       $carinfo = array(

           'carbrand'      =>$carbrand,
           'cartype'       =>$cartype,
           'carage'        =>$carage,
           'insuranceDate' =>$insuranceDate,
           'cargrade'      =>$cargrade,
           'carhost'       =>$carhost,
           'hostwork'      =>$hostwork,
           'driveRate'     =>$driveRate,
           'goneSeason'    =>$goneSeason,
           'goneSpot'      =>$goneSpot,
           'wantSeason'    =>$wantSeason,
           'wantSpot'      =>$wantSpot
       );

        $arr = array(

            'ordersn'=>$orderSn,

            'webid'=>$webid,

            'typeid'=>1,

            'productautoid'=>$info['id'],

            'productaid'=>$info['aid'],

            'productname'=>$info['title'],

            'price'=>$suitPrice['adultprice'],

            'childprice'=>$suitPrice['childprice'],

            'oldprice'=>$suitPrice['oldprice'],

            'usedate'=>$useDate,

            'dingnum'=>$adultNum,

            'childnum'=>$childNum,

            'oldnum'=>$oldNum,

            'linkman'=>$linkMan,

            'linktel'=>$linkTel,

            'linkemail'=>$linkEmail,

            'jifentprice'=>$suitInfo['jifentprice'],

            'jifenbook'=>$suitInfo['jifenbook'],

            'jifencomment'=>$suitInfo['jifencomment'],

            'addtime'=>time(),

            'memberid'=>$memberId,

            'dingjin'=>$suitInfo['dingjin'],

            'paytype'=>$suitInfo['paytype'],

            'suitid'=>$suitId,

            'usejifen'=>$useJifen,

            'needjifen'=>$needJifen,

            'roombalance'=>$suitPrice['roombalance'],

            'roombalancenum'=>$roomBalanceNum,

            'roombalance_paytype'=>$roomBalance_Paytype,

            'status'=>$status,

            'remark'=>$remark,

            'isneedpiao'=>$isneedBill,

            'usertype'  =>$usertype//  订单的 用户类型

        );

        //添加订单

        if(Model_Line::add_order($arr, $carinfo, $wxuserid))

        {

           echo "创建成功";
           exit;

            $orderInfo = Model_Member_Order::get_order_by_ordersn($orderSn);

            Model_Member_Order::add_tourer_pc($orderInfo['id'],$tourer,$memberId);

            //发票信息存储

            if($isneedBill)

            {
                Model_Member_Order::add_bill_info($orderInfo['id'],$billInfo);

            }

            //保险信息存储

            if($insuranceCode)

            {
                Model_Member_Order::add_insurance_order(

                    $insuranceCode,

                    $orderInfo['ordersn'],

                    $totalNum,

                    $orderInfo['memberid'],

                    $orderInfo['usedate'],

                    $info['lineday'],

                    $tourer

                );

            }

            //这里作判断是跳转到会员中心还是支付页面

            if($suitInfo['paytype']!=3)

            {
                $payurl = "{$GLOBALS['cfg_basehost']}/payment/?ordersn=".$orderSn;

                $this->request->redirect($payurl);

            }

            else

            {
                $this->request->redirect("/member");

            }
        }


    }

 /*
  *   搜索微信昵称用户
  *   $nickname 微信昵称
  */
    public  function  action_get_wxuser(){

        $nickname  = Arr::get($_POST, 'nickname');
        $arr =array();
        if(!empty($nickname)){

            $sql  =  "select id,nickname from `bbs_hjbox_users` where `nickname` like '%$nickname%' ";
            $arr  = DB::query(1,$sql)->execute()->as_array();
        }
        echo json_encode($arr);

    }

    /*

    线路列表操作



    */

    public function action_line()

    {

        $action = $this->params['action'];

        if (empty($action))  //显示线路列表页

        {

            $this->assign('kindmenu', Common::getConfig('menu_sub.linekind'));//分类设置项

            $this->display('stourtravel/line/list');

        }

        else if ($action == 'read')    //读取列表

        {

            $start = Arr::get($_GET, 'start');

            $limit = Arr::get($_GET, 'limit');

            $serial = $keyword = Arr::get($_GET, 'keyword');

            $kindid = Arr::get($_GET, 'kindid');

            $attrid = Arr::get($_GET, 'attrid');

            $startcity = Arr::get($_GET, 'startcity');

            $sort = json_decode(Arr::get($_GET, 'sort'), true);

            $webid = Arr::get($_GET, 'webid');

            $webid = empty($webid) ? '-1' : $webid;

            $keyword = Common::getKeyword($keyword);

            $specOrders = array('attrid', 'kindlist', 'iconlist', 'themelist');

            $order = 'order by a.modtime desc';

            if ($sort[0]['property'])

            {

                if ($sort[0]['property'] == 'displayorder')

                    $prefix = '';

                else if ($sort[0]['property'] == 'ishidden')

                {

                    $prefix = 'a.';

                }

                else if ($sort[0]['property'] == 'suitday')

                {

                    $prefix = 'd.';

                }

                else if (in_array($sort[0]['property'], $specOrders))

                {

                    $prefix = 'order_';

                }

                $order = 'order by ' . $prefix . $sort[0]['property'] . ' ' . $sort[0]['direction'] . ',a.modtime desc';

            }

            $w = "a.id is not null";

            if (!empty($keyword))

            {

                $w .= preg_match('`^\d+$`', $keyword) && preg_match('`^[A-Za-z]\d+`', $serial) ? " and a.id={$keyword}" : " and (a.title like '%{$serial}%' or a.supplierlist in(select id from sline_supplier where suppliername like '%{$serial}%'))";

            }

            $w .= empty($kindid) ? '' : " and find_in_set($kindid,a.kindlist)";

            $w .= empty($attrid) ? '' : " and find_in_set($attrid,a.attrid)";

            $w .= empty($startcity) ? '' : " and a.startcity='$startcity'";

            $w .= $webid == '-1' ? '' : " and a.webid=$webid";

            if ($kindid != 0)

            {

                $sql = "select a.id,a.aid,a.title,a.iconlist,a.linedate,a.finaldestid,a.price,a.startcity,if(length(ifnull(a.attrid,''))=0,0,1) as order_attrid,if(length(ifnull(a.kindlist,''))=0,0,1) as order_kindlist,if(length(ifnull(a.iconlist,''))=0,0,1) as order_iconlist,if(length(ifnull(a.themelist,''))=0,0,1) as order_themelist,

a.attrid,a.webid,a.kindlist,a.ishidden,a.piclist,a.themelist,a.supplierlist,b.isjian,IFNULL(b.displayorder,9999) as displayorder,b.isding,b.istejia,ifnull(d.suitday,0) as suitday from sline_line as a left join sline_kindorderlist b on (a.id=b.aid and b.typeid=1 and b.classid=$kindid)  left join (select

c.lineid,c.id,min(c.suitday) as suitday from(select a.lineid,a.id,max(ifnull(b.day,0)) as suitday from sline_line_suit a left join sline_line_suit_price b on a.id=b.suitid group by a.id) c group by c.lineid) d on a.id=d.lineid where $w $order limit  $start,$limit";

            }

            else

            {
                 //  加入 linedate  值
                $sql = "select a.id,a.aid,a.title,a.supplierlist,a.linedate,a.iconlist,a.finaldestid,a.price,a.startcity,a.attrid,if(length(ifnull(a.attrid,''))=0,0,1) as order_attrid,if(length(ifnull(a.kindlist,''))=0,0,1) as order_kindlist,if(length(ifnull(a.iconlist,''))=0,0,1) as order_iconlist,if(length(ifnull(a.themelist,''))=0,0,1) as order_themelist,a.webid,a.kindlist,a.ishidden,a.piclist,a.themelist,b.isjian,IFNULL(b.displayorder,9999) as displayorder,b.isding,b.istejia,ifnull(d.suitday,0) as suitday from sline_line as a left join sline_allorderlist b on (a.id=b.aid and b.typeid=1)   left join (select

c.lineid,c.id,min(c.suitday) as suitday from(select a.lineid,a.id,max(ifnull(b.day,0)) as suitday from sline_line_suit a left join sline_line_suit_price b on a.id=b.suitid group by a.id) c group by c.lineid) d on a.id=d.lineid where $w $order  limit $start,$limit";

            }

            $totalcount_arr = DB::query(Database::SELECT, "select count(*) as num from sline_line a where $w")->execute()->as_array();

            $list = DB::query(Database::SELECT, $sql)->execute()->as_array();

            $new_list = array();

            foreach ($list as $k => $v)

            {

                $v['kindname'] = Model_Destinations::getKindnameList($v['kindlist']);



                $finalDestModel = ORM::factory('destinations', $v['finaldestid']);

                if ($finalDestModel->loaded())

                    $v['finaldestname'] = $finalDestModel->kindname;

                $v['attrname'] = Model_Line_Attr::getAttrnameList($v['attrid']);

                $v['url'] = Common::getBaseUrl($v['webid']) . '/lines/show_' . $v['aid'] . '.html';

                $iconname = Model_Icon::getIconName($v['iconlist']);

                $name = '';

                foreach ($iconname as $icon)

                {

                    if (!empty($icon))

                        $name .= '<span style="color:red">[' . $icon . ']</span>';

                }

                $v['iconname'] = $name;

                $v['lineseries'] = Common::getSeries($v['id'], '01');//线路编号

                //供应商信息

                $supplier = ORM::factory('supplier')->where("id='{$v['supplierlist']}'")->find()->as_array();

                $v['suppliername'] = $supplier['suppliername'];

                $v['linkman'] = $supplier['linkman'];

                $v['mobile'] = $supplier['mobile'];

                $v['address'] = $supplier['address'];

                $v['qq'] = $supplier['qq'];

                /*foreach($supplier as $key=>$v)

                {

                    $v[$key] = $v;

                }*/

                //$suit=ORM::factory('line_suit')->where("lineid={$v['id']}")->get_all();

                $suitOrder = $sort[0]['property'] == 'suitday' ? 'order by suitday ' . $sort[0]['direction'] : '';

                $suitSql = "select a.*,max(b.day) as suitday from sline_line_suit a left join sline_line_suit_price b on a.id=b.suitid where a.lineid={$v['id']}  group by a.id $suitOrder";

                $suit = DB::query(Database::SELECT, $suitSql)->execute()->as_array();

                if (!empty($suit))

                    $v['tr_class'] = 'parent-line-tr';

                $new_list[] = $v;

                foreach ($suit as $key => $val)

                {

                    $val['title'] = $val['suitname'];

                    $val['minprice'] = Model_Line_Suit_Price::getMinPrice($val['id']);

                    $val['minprofit'] = Model_Line_Suit_Price::getMinPrice($val['id'], 'adultprofit');

                    $val['id'] = 'suit_' . $val['id'];

                    if ($key != count($suit) - 1)

                        $val['tr_class'] = 'suit-tr';

                    $new_list[] = $val;

                }

            }

            $result['total'] = $totalcount_arr[0]['num'];

            $result['lines'] = $new_list;

            $result['success'] = true;

            echo json_encode($result);

        }

        else if ($action == 'save')   //保存字段

        {

            $rawdata = file_get_contents('php://input');

            $data = json_decode($rawdata);

            $field = Arr::get($_GET, 'field');

            $kindid = Arr::get($_GET, 'kindid');

            $id = $data->id;

            if (is_numeric($id))   //如果是线路

            {

                if ($field == 'displayorder')

                {

                    $displayorder = $data->displayorder;

                    if (empty($kindid))  //全局排序

                    {

                        $order = ORM::factory('allorderlist');

                        $order_mod = $order->where("aid='$id' and typeid=1 and webid=0")->find();

                        $displayorder = empty($displayorder) ? 9999 : $displayorder;

                        if ($order_mod->id)  //如果已经存在

                        {

                            $order_mod->displayorder = $displayorder;

                        }

                        else   //如果这个排序不存在

                        {

                            $order_mod->displayorder = $displayorder;

                            $order_mod->aid = $id;

                            $order_mod->webid = 0;

                            $order_mod->typeid = 1;

                        }

                        $order_mod->save();

                    }

                    else  //按目的地排序

                    {

                        $kindorder = ORM::factory('kindorderlist');

                        $kindorder_mod = $kindorder->where("aid='$id' and typeid=1 and classid=$kindid")->find();

                        $displayorder = empty($displayorder) ? 9999 : $displayorder;

                        if ($kindorder_mod->id)

                        {

                            $kindorder_mod->displayorder = $displayorder;

                        }

                        else

                        {

                            $kindorder_mod->displayorder = $displayorder;

                            $kindorder_mod->aid = $id;

                            $kindorder_mod->classid = $kindid;

                            $kindorder_mod->typeid = 1;

                        }

                        $kindorder_mod->save();

                    }

                }

            }

            else if (strpos($id, 'suit') !== FALSE)//如果是套餐

            {

                $suitid = substr($id, strpos($id, '_') + 1);

                $suit = ORM::factory('line_suit', $suitid);

                if ($field == 'displayorder')

                {

                    $displayorder = $data->displayorder;

                    $displayorder = empty($displayorder) ? 999999 : $displayorder;

                    $suit->displayorder = $displayorder;

                    $suit->save();

                }

                else

                {

                    $suit->$field = $data->$field;

                    $suit->save();

                }

            }

        }

        else if ($action == 'delete')

        {

            $rawdata = file_get_contents('php://input');

            $data = json_decode($rawdata);

            $id = $data->id;

            if (is_numeric($id)) //线路删除

            {

                $line = ORM::factory('line', $id);

                $line->deleteClear();

                $jieshao_model = new Model_Line_Jieshao();

                $jieshao_model->deleteByLineId($id);

            }

            else if (strpos($id, 'suit') !== FALSE)

            {

                $suitid = substr($id, strpos($id, '_') + 1);

                $suit = ORM::factory('line_suit', $suitid);

                $lineid = $suit->lineid;

                $suit->deleteClear();

                Model_Line::updateMinPrice($lineid);

            }

        }

        else if ($action == 'update')

        {

            $id = Arr::get($_POST, 'id');

            $field = Arr::get($_POST, 'field');

            $val = Arr::get($_POST, 'val');

            $kindid = Arr::get($_POST, 'kindid');

            if ($field == 'displayorder')

            {

                if (is_numeric($id))   //如果是线路

                {

                    $displayorder = $val;

                    if (empty($kindid))  //全局排序

                    {

                        $order = ORM::factory('allorderlist');

                        $order_mod = $order->where("aid='$id' and typeid=1 and webid=0")->find();

                        $displayorder = empty($displayorder) ? 9999 : $displayorder;

                        if ($order_mod->id)  //如果已经存在

                        {

                            $order_mod->displayorder = $displayorder;

                        }

                        else   //如果这个排序不存在

                        {

                            $order_mod->displayorder = $displayorder;

                            $order_mod->aid = $id;

                            $order_mod->webid = 0;

                            $order_mod->typeid = 1;

                        }

                        $order_mod->save();

                        if ($order_mod->saved())

                            echo 'ok';

                        else

                            echo 'no';

                    }

                    else  //按目的地排序

                    {

                        $kindorder = ORM::factory('kindorderlist');

                        $kindorder_mod = $kindorder->where("aid='$id' and typeid=1 and classid=$kindid")->find();

                        $displayorder = empty($displayorder) ? 9999 : $displayorder;

                        if ($kindorder_mod->id)

                        {

                            $kindorder_mod->displayorder = $displayorder;

                        }

                        else

                        {

                            $kindorder_mod->displayorder = $displayorder;

                            $kindorder_mod->aid = $id;

                            $kindorder_mod->classid = $kindid;

                            $kindorder_mod->typeid = 1;

                        }

                        $kindorder_mod->save();

                        if ($kindorder->saved())

                            echo 'ok';

                        else

                            echo 'no';

                    }

                }

                else if (strpos($id, 'suit') !== FALSE)//如果是套餐

                {

                    $suitid = substr($id, strpos($id, '_') + 1);

                    $suit = ORM::factory('line_suit', $suitid);

                    $displayorder = $val;

                    $displayorder = empty($displayorder) ? 999999 : $displayorder;

                    if ($suit->id)

                    {

                        $suit->displayorder = $displayorder;

                        $suit->save();

                        if ($suit->saved())

                            echo 'ok';

                        else

                            echo 'no';

                    }

                }

            }

            else //如果不是排序

            {

                if (is_numeric($id))

                {

                    $model = ORM::factory('line', $id);

                }

                else if (strpos($id, 'suit') !== FALSE)

                {

                    $suitid = substr($id, strpos($id, '_') + 1);

                    $model = ORM::factory('line_suit', $suitid);

                }

                if ($model->id)

                {

                    $model->$field = $val;

                    if ($field == 'kindlist')

                    {

                        $model->$field = implode(',', Model_Destinations::getParentsStr($val));

                    }

                    else if ($field == 'attrid')

                    {

                        $model->$field = implode(',', Model_Attrlist::getParentsStr($val, 1));

                    }

                    $model->save();

                    if ($model->saved())

                        echo 'ok';

                    else

                        echo 'no';

                }

            }

        }

    }



    /*

     * 添加线路

     */

    public function action_add()

    {

        $webid = 0;

        $this->assign('webid', 0);

        $columns = ORM::factory('line_content')->where("(webid=" . $webid . " and isopen=1 and isline=0 and columnname!='linespot') or columnname='tupian' ")->order_by('displayorder', 'asc')->get_all();

        $startplacelist = ORM::factory('startplace')->where("pid!=0")->get_all();

        $this->assign('startplacelist', $startplacelist);

        $this->assign('columns', $columns);

        $this->assign('usertransport', array());

        $this->assign('position', '添加线路');

        $this->assign('action', 'add');

        $this->assign('hasinsurance', Model_Insurance::hasInsurance());

        $this->display('stourtravel/line/edit');

    }



    /*

     * 编辑线路

     */

    public function action_edit()

    {

        $lineid = $this->params['lineid'];

        $model = ORM::factory('line', $lineid);

        $this->assign('action', 'edit');

        $startplacelist = ORM::factory('startplace')->where("pid!=0")->get_all();

        $this->assign('startplacelist', $startplacelist);

        $this->assign('hasinsurance', Model_Insurance::hasInsurance());

        if ($model->id)

        {

            $info = $model->as_array();

            $extendinfo = Common::getExtendInfo(1, $model->id);

            $info['kindlist_arr'] = Model_Destinations::getKindlistArr($info['kindlist']);

            $info['attrlist_arr'] = Common::getSelectedAttr(1, $info['attrid']);

            $info['iconlist_arr'] = Common::getSelectedIcon($info['iconlist']);

            $info['supplier_arr'] = ORM::factory('supplier', $info['supplierlist'])->as_array();

            $info['insurance_arr'] = Model_Insurance::getNamePaires($info['insuranceids']);

            $day_arr = array_chunk(ORM::factory('line_jieshao')->where("lineid='" . $info['id'] . "'")->order_by('day', 'asc')->get_all(), $info['lineday']);

            $info['linejieshao_arr'] = $day_arr[0];

            $columns = ORM::factory('line_content')->where("(webid=0 and isopen=1 and isline=0 and columnname!='linespot') or (columnname='tupian' and webid=0)")->order_by('displayorder', 'asc')->get_all();

            /* foreach($columns as $key => $c)

             {

                 if(preg_match('/^e_/',$c['columnname']))

                 {

                     unset($columns[$key]);

                 }

             }*/

            $info['linedoc']=unserialize($info['linedoc']);

            $this->assign('columns', $columns);

            $this->assign('webid', $info['webid']);

            $this->assign('info', $info);

            $this->assign('extendinfo', $extendinfo);//扩展信息

            $this->assign('position', '修改' . $info['title']);

            $this->assign('usertransport', explode(',', $info['transport']));

            $this->display('stourtravel/line/edit');

        }

        else

            echo 'URL地址错误，请重新选择线路';

    }



    /*

       通过ajax保存线路

    */

    public function action_ajax_linesave()

    {

        $attrids = implode(',', Arr::get($_POST, 'attrlist'));//属性

        if (!empty($attrids))

        {

            $attrids = implode(',', Model_Attrlist::getParentsStr($attrids, 1));

        }

        $lineid = Arr::get($_POST, 'lineid');

        $data_arr = array();

        $data_arr['webid'] = Arr::get($_POST, 'webid');

        $data_arr['webid'] = empty($data_arr['webid']) ? 0 : $data_arr['webid'];

        $webid = $data_arr['webid'];

        $kindlist = Arr::get($_POST, 'kindlist');

        if ($webid != 0)//自动添加子站目的地属性

        {

            if (is_array($kindlist))

            {

                if (!in_array($webid, $kindlist))

                {

                    array_push($kindlist, $webid);

                }

            }

            else

            {

                $kindlist = array($webid);//如果为空则直接加webid

            }

        }

        $data_arr['title'] = Arr::get($_POST, 'title');

        $data_arr['sellpoint'] = Arr::get($_POST, 'sellpoint');
        //=========fengjishu
        $data_arr['selfDriMdd'] = Arr::get($_POST, 'self_driving_mdd');
        $data_arr['linedate'] = Arr::get($_POST, 'time');
        //=========fengjishu  end
        $data_arr['kindlist'] = implode(',', Model_Destinations::getParentsStr(implode(',', $kindlist)));

        $data_arr['finaldestid'] = empty($_POST['finaldestid']) ? Model_Destinations::getFinaldestId(explode(',', $data_arr['kindlist'])) : $_POST['finaldestid'];

        $data_arr['attrid'] = $attrids;

        $data_arr['lineday'] = Arr::get($_POST, 'lineday') ? Arr::get($_POST, 'lineday') : 1;

        $data_arr['linenight'] = Arr::get($_POST, 'linenight') ? Arr::get($_POST, 'linenight') : 0;

        $data_arr['islinebefore'] = $_POST['islinebefore'] ? 1 : 0;

        $data_arr['recommendnum'] = $_POST['recommendnum'];

        $data_arr['supplierlist'] = implode(',', Arr::get($_POST, 'supplierlist'));

        $data_arr['linebefore'] = Arr::get($_POST, 'linebefore') ? Arr::get($_POST, 'linebefore') : 0;

        $data_arr['storeprice'] = Arr::get($_POST, 'storeprice') ? Arr::get($_POST, 'storeprice') : 0;

        $data_arr['childrule'] = Arr::get($_POST, 'childrule');

        $data_arr['templet'] = Arr::get($_POST, 'templet');

        $data_arr['templet'] = empty($data_arr['templet']) ? 'line_show.htm' : $data_arr['templet'];

        $data_arr['color'] = Arr::get($_POST, 'color');

        $data_arr['satisfyscore'] = Arr::get($_POST, 'satisfyscore') ? Arr::get($_POST, 'satisfyscore') : 0;

        $data_arr['bookcount'] = Arr::get($_POST, 'bookcount') ? Arr::get($_POST, 'bookcount') : 0;

        $data_arr['ishidden'] = Arr::get($_POST, 'ishidden') ? Arr::get($_POST, 'ishidden') : 0;//显示隐藏

        $data_arr['seotitle'] = Arr::get($_POST, 'seotitle');

        $data_arr['keyword'] = Arr::get($_POST, 'keyword');

        $data_arr['tagword'] = Arr::get($_POST, 'tagword');

        $data_arr['description'] = Arr::get($_POST, 'description');

        $data_arr['modtime'] = time();

        $data_arr['isstyle'] = Arr::get($_POST, 'isstyle') ? Arr::get($_POST, 'isstyle') : 2; //默认标准版

        $data_arr['showrepast'] = Arr::get($_POST, 'showrepast');

        $data_arr['jieshao'] = Arr::get($_POST, 'jieshao');

        $data_arr['biaozhun'] = Arr::get($_POST, 'biaozhun');

        $data_arr['beizu'] = Arr::get($_POST, 'beizu');

        $data_arr['payment'] = Arr::get($_POST, 'payment');

        $data_arr['feeinclude'] = Arr::get($_POST, 'feeinclude');

        $data_arr['features'] = Arr::get($_POST, 'features');

        $data_arr['reserved1'] = Arr::get($_POST, 'reserved1');

        $data_arr['reserved2'] = Arr::get($_POST, 'reserved2');

        $data_arr['reserved3'] = Arr::get($_POST, 'reserved3');

        $data_arr['startcity'] = Arr::get($_POST, 'startcity');

        $data_arr['transport'] = Arr::get($_POST, 'transport_pub') ? implode(',', Arr::get($_POST, 'transport_pub')) : '';

        $data_arr['iconlist'] = Arr::get($_POST, 'iconlist') ? implode(',', Arr::get($_POST, 'iconlist')) : '';

        $data_arr['insuranceids'] = Arr::get($_POST, 'insuranceids') ? implode(',', Arr::get($_POST, 'insuranceids')) : '';



        //图片处理

        $images_arr = Arr::get($_POST, 'images');

        $imagestitle_arr = Arr::get($_POST, 'imagestitle');

        $headimgindex = Arr::get($_POST, 'imgheadindex');

        $imgstr = "";

        foreach ($images_arr as $k => $v)

        {

            $imgstr .= $v . '||' . $imagestitle_arr[$k] . ',';

            if ($headimgindex == $k)

            {

                $data_arr['litpic'] = $v;

            }

        }

        $imgstr = trim($imgstr, ',');

        $data_arr['piclist'] = $imgstr;

        $data_arr['linedoc'] = serialize(Arr::get($_POST, 'linedoc'));

        if ($lineid == 0)

        {

            $data_arr['addtime'] = $data_arr['modtime'];

            $model = ORM::factory('line');

            $model->aid = Common::getLastAid('sline_line', $data_arr['webid']);

            $model->addtime = time();

            $data_arr['adminid']=Session::instance()->get('userid');

        }

        else

        {

            $data_arr['modtime'] = time();

            $model = ORM::factory('line', $lineid);

            if ($model->webid != $data_arr['webid']) //如果更改了webid重新生成aid

            {

                $aid = Common::getLastAid('sline_line', $data_arr['webid']);

                $model->aid = $aid;

            }

            $model->modtime = time();

        }

        foreach ($data_arr as $k => $v)

        {

            $model->$k = $v;

        }

        $model->save();

        if ($model->saved())

        {

            $model->reload();

            $lineid = $model->id;

            $this->savejieshao($lineid);

            Common::saveExtendData(1, $lineid, $_POST);//扩展信息保存

            echo $lineid;

        }

        else

            echo 'no';

    }



    //保存介绍

    public function savejieshao($lineid)

    {

        $title_arr = Arr::get($_POST, 'jieshaotitle');

        $breakfirsthas_arr = Arr::get($_POST, 'breakfirsthas');

        $breakfirst_arr = Arr::get($_POST, 'breakfirst');

        $lunchhas_arr = Arr::get($_POST, 'lunchhas');

        $lunch_arr = Arr::get($_POST, 'lunch');

        $supperhas_arr = Arr::get($_POST, 'supperhas');

        $supper_arr = Arr::get($_POST, 'supper');

        $hotel_arr = Arr::get($_POST, 'hotel');

        $transport_arr = Arr::get($_POST, 'transport');

        $jieshao_arr = Arr::get($_POST, 'txtjieshao');

        // $beforemodels=ORM::factory('line_jieshao')->where("lineid='$lineid'")->find_all()->as_array();

        foreach ($title_arr as $k => $v)

        {

            $model = ORM::factory('line_jieshao')->where("lineid='$lineid' and day='$k'")->find();

            if (empty($model->id))

                $model = ORM::factory('line_jieshao');

            $model->lineid = $lineid;

            $model->day = $k;

            $model->hotel = $hotel_arr[$k];

            $model->breakfirst = $breakfirst_arr[$k];

            $model->lunch = $lunch_arr[$k];

            $model->supper = $supper_arr[$k];

            $model->title = $v;

            $superhas_arr[$k] = empty($superhas_arr[$k]) ? 0 : $superhas_arr[$k];

            $lunchhas_arr[$k] = empty($lunchhas_arr[$k]) ? 0 : $lunchhas_arr[$k];

            $breakfirsthas_arr[$k] = empty($breakfirsthas_arr[$k]) ? 0 : $breakfirsthas_arr[$k];

            $model->supperhas = $supperhas_arr[$k];

            $model->lunchhas = $lunchhas_arr[$k];

            $model->breakfirsthas = $breakfirsthas_arr[$k];

            $model->transport = implode(',', $transport_arr[$k]);

            $link = new Model_Tool_Link();

            $model->jieshao = $link->keywordReplaceBody($jieshao_arr[$k], 1);

            $model->save();

        }

    }



    /*

     * 线路天数

     */

    public function action_day()

    {

        $action = $this->params['action'];

        if (empty($action))

        {

            $list = ORM::factory('line_day')->get_all();

            $this->assign('list', $list);

            $this->display('stourtravel/line/day');

        }

        else if ($action == 'add')

        {

            $model = ORM::factory('line_day');

            $model->create();

            echo $model->id;

        }

        else if ($action == 'save')

        {

            $word = Arr::get($_POST, 'dayword');

            foreach ($word as $k => $v)

            {

                $model = ORM::factory('line_day', $k);

                if ($model->id)

                {

                    $model->word = $v;

                    $model->save();

                }

            }

            echo 'ok';

        }

        else if ($action == 'del')

        {

            $id = Arr::get($_POST, 'id');

            $model = ORM::factory('line_day', $id);

            $model->delete();

            echo 'ok';

        }

    }



    /*

     * 线路价格列表

     */

    public function action_price()

    {

        $action = $this->params['action'];

        if (empty($action))

        {

            $list = ORM::factory('line_pricelist')->get_all();

            $this->assign('list', $list);

            $this->display('stourtravel/line/price');

        }

        else if ($action == 'add')

        {

            $model = ORM::factory('line_pricelist');

            $model->create();

            echo $model->id;

        }

        else if ($action == 'save')

        {

            $lowerprice = Arr::get($_POST, 'lowerprice');

            $highprice = Arr::get($_POST, 'highprice');

            $newlowerprice = Arr::get($_POST, 'newlowerprice');

            $newhighprice = Arr::get($_POST, 'newhighprice');

            foreach ($lowerprice as $k => $v)

            {

                $model = ORM::factory('line_pricelist', $k);

                if ($model->id)

                {

                    $model->lowerprice = $v;

                    $model->highprice = $highprice[$k];

                    $model->save();

                }

            }

            echo 'ok';

        }

        else if ($action == 'del')

        {

            $id = Arr::get($_POST, 'id');

            $model = ORM::factory('line_pricelist', $id);

            $model->delete();

            echo 'ok';

        }

    }



    /*

     * 线路属性操作

     */

    public function action_attr()

    {

        $action = $this->params['action'];

        if (empty($action))

        {

            $this->display('stourtravel/line/attr');

        }

        else if ($action == 'read')

        {

            $node = Arr::get($_GET, 'node');

            $list = array();

            if ($node == 'root')

            {

                $list = ORM::factory('line_attr')->where('pid=0')->get_all();

                foreach ($list as $k => $v)

                {

                    $list[$k]['kindname'] = Model_Destinations::getKindnameList($v['destid']);

                    $list[$k]['allowDrag'] = false;

                }

                $list[] = array('leaf' => true, 'id' => '0add', 'attrname' => '<button class="dest-add-btn" onclick="addSub(0)">添加</button>', 'allowDrag' => false, 'allowDrop' => false, 'displayorder' => 'add');

            }

            else

            {

                $list = ORM::factory('line_attr')->where('pid=' . $node)->get_all();

                foreach ($list as $k => $v)

                {

                    $list[$k]['kindname'] = Model_Destinations::getKindnameList($v['destid']);

                    $list[$k]['leaf'] = true;

                }

                $list[] = array('leaf' => true, 'id' => $node . 'add', 'attrname' => '<button class="dest-add-btn" onclick="addSub(\'' . $node . '\')">添加</button>', 'allowDrag' => false, 'allowDrop' => false, 'displayorder' => 'add');

            }

            echo json_encode(array('success' => true, 'text' => '', 'children' => $list));

        }

        else if ($action == 'addsub')

        {

            $pid = Arr::get($_POST, 'pid');

            $model = ORM::factory('line_attr');

            $model->pid = $pid;

            $model->attrname = "未命名";

            $model->save();

            if ($model->saved())

            {

                $model->reload();

                //  $dest->updateSibling('add');

                echo json_encode($model->as_array());

            }

        }

        else if ($action == 'save')

        {

            $rawdata = file_get_contents('php://input');

            $field = Arr::get($_GET, 'field');

            $data = json_decode($rawdata);

            $id = $data->id;

            if ($field)

            {

                $model = ORM::factory('line_attr', $id);

                if ($model->id)

                {

                    $model->$field = $data->$field;

                    $model->save();

                    if ($model->saved())

                        echo 'ok';

                    else

                        echo 'no';

                }

            }

        }

        else if ($action == 'drag')

        {

            $moveid = Arr::get($_POST, 'moveid');

            $overid = Arr::get($_POST, 'overid');

            $position = Arr::get($_POST, 'position');

            $movemodel = ORM::factory('line_attr', $moveid);

            $overmodel = ORM::factory('line_attr', $overid);

            if ($position == 'append')

            {

                $movemodel->pid = $overid;

            }

            else

            {

                $movemodel->pid = $overmodel->pid;

            }

            $movemodel->save();

            if ($movemodel->saved())

                echo 'ok';

            else

                echo 'no';

        }

        else if ($action == 'delete')

        {

            $rawdata = file_get_contents('php://input');

            $data = json_decode($rawdata);

            $id = $data->id;

            if (!is_numeric($id))

            {

                echo json_encode(array('success' => false));

                exit;

            }

            $model = ORM::factory('line_attr', $id);

            $model->deleteClear();

        }

        else if ($action == 'update')

        {

            $id = Arr::get($_POST, 'id');

            $field = Arr::get($_POST, 'field');

            $val = Arr::get($_POST, 'val');

            $model = ORM::factory('line_attr', $id);

            if ($model->id)

            {

                $model->$field = $val;

                $model->save();

                if ($model->saved())

                    echo 'ok';

                else

                    echo 'no';

            }

        }

    }



    /*

      线路行程

    */

    public function action_content()

    {

        $action = $this->params['action'];

        if (empty($action))

        {

            $linecontent = ORM::factory('line_content')->where('webid=0')->order_by('displayorder')->get_all();

            $this->assign('list', $linecontent);

            $this->display('stourtravel/line/content');

        }

        else if ($action == 'save')

        {

            $displayorder = Arr::get($_POST, 'displayorder');

            $chinesename = Arr::get($_POST, 'chinesename');

            $isopen = Arr::get($_POST, 'isopen');

            $newdisplayorder = Arr::get($_POST, 'newdisplayorder');

            $newchinesename = Arr::get($_POST, 'newchinesename');

            $newisopen = Arr::get($_POST, 'newisopen');

            foreach ($displayorder as $k => $v)

            {

                $model = ORM::factory('line_content', $k);

                if ($model->id)

                {

                    $open = $isopen[$k] ? 1 : 0;

                    $model->chinesename = $chinesename[$k];

                    $model->displayorder = $v;

                    $model->isopen = $open;

                    $model->save();

                }

            }

            /*foreach($newchinesename as $k=>$v)

            {

                $open=$newisopen[$k]?1:0;

                $model=ORM::factory('line_content');

                $model->chinesename=$v;

                $model->isopen=$open;

                $model->displayorder=$newdisplayorder[$k];

                $model->save();

            }*/

            echo 'ok';

        }

    }



    /*

     * 添加套餐

     */

    public function action_addsuit()

    {

        $lineid = $this->params['lineid'];

        $lineinfo = ORM::factory('line', $lineid)->as_array();

        $info = array('lastoffer' => array('pricerule' => 'all'));

        $this->assign('lineinfo', $lineinfo);

        $this->assign('info', $info);

        $this->assign('action', 'add');

        $this->assign('position', '添加套餐');

        $this->display('stourtravel/line/suit_edit');

    }



    /*

   * 修改套餐

   */

    public function action_editsuit()

    {

        $suitid = $this->params['suitid'];

        $info = ORM::factory('line_suit', $suitid)->as_array();

        $info['lastoffer'] = unserialize($info['lastoffer']);

        if (empty($info['lastoffer']))

        {

            $info['lastoffer'] = array('pricerule' => 'all');

        }

        $lineinfo = ORM::factory('line', $info['lineid'])->as_array();

        $this->assign('action', 'edit');

        $this->assign('lineinfo', $lineinfo);

        $this->assign('info', $info);

        $this->assign('position', '修改套餐');

        $this->display('stourtravel/line/suit_edit');

    }



    /*

     * 保存套餐

     */

    public function action_ajax_suitsave()

    {

        $lineid = Arr::get($_POST, 'lineid');

        $suitid = $_POST['suitid'];

        $data_arr = array();

        $data_arr['suitname'] = Arr::get($_POST, 'suitname');

        $data_arr['lineid'] = Arr::get($_POST, 'lineid');

        $data_arr['description'] = Arr::get($_POST, 'description');

        $data_arr['propgroup'] = implode(',', Arr::get($_POST, 'propgroup'));

        $data_arr['jifentprice'] = Arr::get($_POST, 'jifentprice') ? Arr::get($_POST, 'jifentprice') : 0;

        $data_arr['jifenbook'] = Arr::get($_POST, 'jifenbook') ? Arr::get($_POST, 'jifenbook') : 0;

        $data_arr['jifencomment'] = Arr::get($_POST, 'jifencomment') ? Arr::get($_POST, 'jifencomment') : 0;

        $data_arr['paytype'] = Arr::get($_POST, 'paytype');

        $data_arr['dingjin'] = $data_arr['paytype'] == 2 ? Arr::get($_POST, 'dingjin') : '';

        $data_arr['lastoffer'] = Common::last_offer(1, $_POST);

        if ($suitid)

        {

            $model = ORM::factory('line_suit', $suitid);

        }

        else

            $model = ORM::factory('line_suit');

        foreach ($data_arr as $k => $v)

        {

            $model->$k = $v;

        }

        $model->save();

        if ($model->saved())

        {

            $model->reload();

            $this->saveBaoJia($lineid, $model->id, $_POST);

            echo $model->id;

        }

        else

            echo 'no';

    }



    public function saveBaoJia($lineid, $suitid, $arr)

    {

        //$pricerule,$starttime,$endtime,$hotelid,$roomid,$basicprice,$profit,$description

        $pricerule = Arr::get($arr, 'pricerule');

        $starttime = Arr::get($arr, 'starttime');

        $endtime = Arr::get($arr, 'endtime');

        if (empty($starttime) || empty($endtime))

        {

            return false;

        }

        //儿童

        $childbasicprice = $childprofit = 0;

        if (in_array(1, Arr::get($arr, 'propgroup')))

        {

            $childbasicprice = Arr::get($arr, 'childbasicprice') ? Arr::get($arr, 'childbasicprice') : $childbasicprice;

            $childprofit = Arr::get($arr, 'childprofit') ? Arr::get($arr, 'childprofit') : $childprofit;

        }

        //成人

        $adultbasicprice = $adultprofit = 0;

        if (in_array(2, Arr::get($arr, 'propgroup')))

        {

            $adultbasicprice = Arr::get($arr, 'adultbasicprice') ? Arr::get($arr, 'adultbasicprice') : $adultbasicprice;

            $adultprofit = Arr::get($arr, 'adultprofit') ? Arr::get($arr, 'adultprofit') : $adultprofit;

        }

        //老人

        $oldbasicprice = $oldprofit = 0;

        if (in_array(3, Arr::get($arr, 'propgroup')))

        {

            $oldbasicprice = Arr::get($arr, 'oldbasicprice') ? Arr::get($arr, 'oldbasicprice') : $oldbasicprice;

            $oldprofit = Arr::get($arr, 'oldprofit') ? Arr::get($arr, 'oldprofit') : $oldprofit;

        }

        $roombalance = $arr['roombalance'];

        $description = Arr::get($arr, 'description'); //描述

        $number = Arr::get($arr, 'number'); //库存

        $monthval = Arr::get($arr, 'monthval');

        $weekval = Arr::get($arr, 'weekval');

        $stime = strtotime($starttime);

        $etime = strtotime($endtime);

        $adultprice = (int)$adultbasicprice + (int)$adultprofit;

        $childprice = (int)$childbasicprice + (int)$childprofit;

        $oldprice = (int)$oldbasicprice + (int)$oldprofit;

        //按日期范围报价

        if ($pricerule == 'all')

        {

            $begintime = $stime;

            while (true)

            {

                $model = ORM::factory('line_suit_price')->where("suitid=$suitid and day='$begintime'")->find();

                $data_arr = array();

                $data_arr['lineid'] = $lineid;

                $data_arr['suitid'] = $suitid;

                $data_arr['adultbasicprice'] = $adultbasicprice;

                $data_arr['adultprofit'] = $adultprofit;

                $data_arr['adultprice'] = $adultprice;

                $data_arr['childbasicprice'] = $childbasicprice;

                $data_arr['childprofit'] = $childprofit;

                $data_arr['childprice'] = $childprice;

                $data_arr['oldbasicprice'] = $oldbasicprice;

                $data_arr['oldprofit'] = $oldprofit;

                $data_arr['oldprice'] = $oldprice;

                $data_arr['day'] = $begintime;

                $data_arr['description'] = $description;

                $data_arr['roombalance'] = empty($roombalance) ? 0 : $roombalance;

                $data_arr['number'] = $number;

                if ($model->suitid)

                {

                    $query = DB::update('line_suit_price')->set($data_arr)->where("suitid=$suitid and day='$begintime'");

                    $query->execute();

                }

                else

                {

                    foreach ($data_arr as $k => $v)

                    {

                        $model->$k = $v;

                    }

                    $model->save();

                }

                $begintime = $begintime + 86400;

                if ($begintime > $etime)

                    break;

            }

        }

        //按号进行报价

        else if ($pricerule == 'month')

        {

            $syear = date('Y', $stime);

            $smonth = date('m', $stime);

            $sday = date('d', $stime);

            $eyear = date('Y', $etime);

            $emonth = date('m', $etime);

            $eday = date('d', $etime);

            $beginyear = $syear;

            $beginmonth = $smonth;

            while (true)

            {

                $daynum = date('t', strtotime($beginyear . '-' . $beginmonth . '-' . '01'));

                foreach ($monthval as $v)

                {

                    if ((int)$v < 10)

                        $v = '0' . $v;

                    $newtime = strtotime($beginyear . '-' . $beginmonth . '-' . $v);

                    if ((int)$v > (int)$daynum || $newtime < $stime || $newtime > $etime)

                        continue;

                    $model = ORM::factory('line_suit_price')->where("suitid=$suitid and day='$newtime'")->find();

                    $data_arr['lineid'] = $lineid;

                    $data_arr['suitid'] = $suitid;

                    $data_arr['adultbasicprice'] = $adultbasicprice;

                    $data_arr['adultprofit'] = $adultprofit;

                    $data_arr['adultprice'] = $adultprice;

                    $data_arr['childbasicprice'] = $childbasicprice;

                    $data_arr['childprofit'] = $childprofit;

                    $data_arr['childprice'] = $childprice;

                    $data_arr['oldbasicprice'] = $oldbasicprice;

                    $data_arr['oldprofit'] = $oldprofit;

                    $data_arr['oldprice'] = $oldprice;

                    $data_arr['day'] = $newtime;

                    $data_arr['description'] = $description;

                    $data_arr['roombalance'] = empty($roombalance) ? 0 : $roombalance;

                    $data_arr['number'] = $number;

                    if ($model->suitid)

                    {

                        $query = DB::update('line_suit_price')->set($data_arr)->where("suitid=$suitid and day='$newtime'");

                        $query->execute();

                    }

                    else

                    {

                        foreach ($data_arr as $k => $v)

                        {

                            $model->$k = $v;

                        }

                        $model->save();

                    }

                }

                $beginmonth = (int)$beginmonth + 1;

                if ($beginmonth > 12)

                {

                    $beginmonth = $beginmonth - 12;

                    $beginyear++;

                }

                if (($beginmonth > $emonth && $beginyear >= $eyear) || ($beginmonth <= $emonth && $beginyear > $eyear))

                    break;

                $beginmonth = $beginmonth < 10 ? '0' . $beginmonth : $beginmonth;

            }

        }

        //按星期进行报价

        else if ($pricerule == 'week')

        {

            $begintime = $stime;

            while (true)

            {

                $cur_week = date('w', $begintime);

                $cur_week = $cur_week == 0 ? 7 : $cur_week;

                if (in_array($cur_week, $weekval))

                {

                    $model = ORM::factory('line_suit_price')->where("suitid=$suitid and day='$begintime'")->find();

                    $data_arr['lineid'] = $lineid;

                    $data_arr['suitid'] = $suitid;

                    $data_arr['adultbasicprice'] = $adultbasicprice;

                    $data_arr['adultprofit'] = $adultprofit;

                    $data_arr['adultprice'] = $adultprice;

                    $data_arr['childbasicprice'] = $childbasicprice;

                    $data_arr['childprofit'] = $childprofit;

                    $data_arr['childprice'] = $childprice;

                    $data_arr['oldbasicprice'] = $oldbasicprice;

                    $data_arr['oldprofit'] = $oldprofit;

                    $data_arr['oldprice'] = $oldprice;

                    $data_arr['day'] = $begintime;

                    $data_arr['description'] = $description;

                    $data_arr['roombalance'] = empty($roombalance) ? 0 : $roombalance;

                    $data_arr['number'] = $number;

                    if ($model->suitid)

                    {

                        $query = DB::update('line_suit_price')->set($data_arr)->where("suitid=$suitid and day='$begintime'");

                        $query->execute();

                    }

                    else

                    {

                        foreach ($data_arr as $k => $v)

                        {

                            $model->$k = $v;

                        }

                        $model->save();

                    }

                }

                $begintime = $begintime + 86400;

                if ($begintime > $etime)

                    break;

            }

        }

        Model_Line::updateMinPrice($lineid);

    }



    /*

     * 删除行程附件

     * */

    public function action_ajax_del_doc()

    {

        $bool = false;

        if (isset($_POST['file']))

        {

            //删除文件

            $file = realpath(BASEPATH . $_POST['file']);

            $bool = unlink($file);

            if ($bool && isset($_POST['id']))

            {

                $data=DB::select()->from('line')->where('id', '=', $_POST['id'])->execute()->current();

                if(!empty($data)){

                    $attach=unserialize($data['linedoc']);

                    foreach($attach['path'] as $k=>$v){

                        if($v==$_POST['file']){

                            unset($attach['path'][$k]);

                            unset($attach['name'][$k]);

                            break;

                        }

                    }

                    DB::update('line')->set(array('linedoc' => serialize($attach)))->where('id', '=', $_POST['id'])->execute();

                }

            }

        }

        echo json_encode(array('status' => $bool));

    }



    //获取景点

    public function action_ajax_getspot()

    {

        $content = Arr::get($_POST, 'content');

        $lineid = Arr::get($_POST, 'lineid');

        $day = Arr::get($_POST, 'day');

        $model = new Model_Line();

        $out = $model->autoGetSpot($content, $lineid, $day);

        echo json_encode($out);

    }



    //删除提取的景点

    public function action_ajax_del_dayspot()

    {

        $autoid = Arr::get($_POST, 'autoid');

        $model = new Model_Line();

        $flag = $model->delDaySpot($autoid);

        echo json_encode(array('status' => $flag));

    }



    //克隆线路

    public function action_ajax_clone()

    {

        $num = Arr::get($_POST, 'num');

        $lineid = Arr::get($_POST, 'id');

        $model = new Model_Line();

        $flag = $model->cloneLine($lineid, $num);

        echo json_encode(array('status' => $flag));

    }

}

