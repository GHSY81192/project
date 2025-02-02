<?php defined('SYSPATH') or die('No direct script access.');



class Controller_Distributor extends Stourweb_Controller

{

    /*

     * 分销商总控制器

     *

     */

    public function before()

    {

        parent::before();

        $action = $this->request->action();

        if ($action == 'index')

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

                Common::getUserRight('distributor', $user_action);

        }

        if ($action == 'add')

        {

            Common::getUserRight('distributor', 'sadd');

        }

        if ($action == 'edit')

        {

            Common::getUserRight('distributor', 'smodify');

        }

        if ($action == 'ajax_save')

        {

            Common::getUserRight('distributor', 'smodify');

        }

        $this->assign('parentkey', $this->params['parentkey']);

        $this->assign('itemid', $this->params['itemid']);

    }



    public function action_index()

    {

        $action = $this->params['action'];

        if (empty($action))  //显示列表

        {

            $kindmenu= ORM::factory("distributor_kind")->get_all();

            $this->assign('kindmenu',$kindmenu);

            $this->display('stourtravel/distributor/list');

        }

        else if ($action == 'read')    //读取列表

        {

            $start = Arr::get($_GET, 'start');

            $limit = Arr::get($_GET, 'limit');

            $keyword = Arr::get($_GET, 'keyword');

            $sort = json_decode(Arr::get($_GET, 'sort'), true);

            if ($sort[0]['property'])

            {

                $order = 'order by a.'.$sort[0]['property'] . ' ' . $sort[0]['direction'] . ',a.addtime desc';

            }



            if (!empty($keyword))

            {

                $w = "where (a.distributorname like '%{$keyword}%' or a.telephone like '%{$keyword}%' or a.mobile like '%{$keyword}%')";

            }

            $sql = "select a.*  from sline_distributor as a $w $order limit $start,$limit";

            //echo $sql;

            $totalcount_arr = DB::query(Database::SELECT, "select count(*) as num from sline_distributor a ")->execute()->as_array();

            $list = DB::query(Database::SELECT, $sql)->execute()->as_array();

            $new_list = array();

            foreach ($list as $k => $v)

            {

                $new_list[] = $v;

            }

            $result['total'] = $totalcount_arr[0]['num'];

            $result['lists'] = $new_list;

            $result['success'] = true;

            echo json_encode($result);

        }

        else if ($action == 'save')   //保存字段

        {

        }

        else if ($action == 'delete') //删除某个记录

        {

            $rawdata = file_get_contents('php://input');

            $data = json_decode($rawdata);

            $id = $data->id;

            if (is_numeric($id)) //

            {

                $model = ORM::factory('distributor', $id);

                $model->delete();

            }

        }

        else if ($action == 'update')//更新某个字段

        {

            $id = Arr::get($_POST, 'id');

            $field = Arr::get($_POST, 'field');

            $val = Arr::get($_POST, 'val');

            if (is_numeric($id))

            {

                $model = ORM::factory('distributor')->where('id', '=', $id)->find();

            }

            if ($model->id)

            {

                if ($field == 'displayorder')  //如果是排序

                {

                    $val = empty($val) ? 9999 : $val;

                }

                $model->$field = $val;

                $model->update();

                if ($model->saved())

                    echo 'ok';

                else

                    echo 'no';

            }

        }

    }



    /*

     * 添加

     * */

    public function action_add()

    {

        $this->assign('action', 'add');

        $this->assign('kind',$this->_distributor_kind());

        $this->display('stourtravel/distributor/edit');

    }

    /*

       * 分销商订单详情   07.12 fengjishu

       * */

    public function action_sup_order()

    {

        $this ->assign("supid",$_GET['supid']);

        $this->display('stourtravel/distributor/sup_order');

    }


    /*

     * 修改

     * */

    public function action_edit()

    {

        $id = $this->params['id'];//会员id.

        $this->assign('action', 'edit');

        $info = ORM::factory('distributor', $id)->as_array();

        $info['piclist_arr'] =  json_encode(Common::getUploadPicture($info['piclist']));//图片数组

        $info['kindlist_arr'] =  Model_Destinations::getKindlistArr($info['kindlist']);//目的地数组

        $qua = unserialize($info['qualification']);

        //$qua['kindtype'] = ORM::factory('distributor_kind',$qua['kindid'])->get('kindname');

        $product_list =  ORM::factory('model')->where('isopen=1 and id not in(4,6,7,10,11,14)')->get_all();

        if(!empty($qua['apply_kind']))

        {

            $apply_product = ORM::factory('distributor_kind')->where("id in(".$qua['apply_kind'].")")->get_all();

            $this->assign('apply_product',$apply_product);

        }



        $this->assign('product_list',$product_list);



        $this->assign('info', $info);

        $this->assign('qua',$qua);

        $this->assign('kind',$this->_distributor_kind());

        $this->display('stourtravel/distributor/edit');

    }



    /**

     * 分销商分类

     */

    private function _distributor_kind()

    {

        $kind = DB::query(Database::SELECT, "select *,concat(path,'-',id) as level from sline_distributor_kind where isopen=1 order by level asc,displayorder asc")->execute()->as_array();

        return $kind;

    }

    /*

     * 保存

     * */

    public function action_ajax_save()

    {

        $action = ARR::get($_POST, 'action');//当前操作

        $id = ARR::get($_POST, 'id');

        $status = false;

        $kindlist = Arr::get($_POST,'kindlist');





        //添加操作

        if ($action == 'add' && empty($id))

        {

            $model = ORM::factory('distributor');

            $model->addtime = time();

        }

        else

        {

            $model = ORM::factory('distributor')->where('id', '=', $id)->find();

        }

        if(!empty($model->account))

        {

            $tempModel=ORM::factory('distributor')->where('account','=',$_POST['account'])->find();

            if($tempModel->loaded()&&$tempModel->id!=$id)

            {

                echo json_encode(array('status' => false, 'msg'=>'账号已经存在'));

                return;

            }

            $model->account=$_POST['account'];

        }

        if(!empty($_POST['password']))

        {

            $model->password = md5($_POST['password']);

        }



        $imagestitle = Arr::get($_POST,'imagestitle');

        $images = Arr::get($_POST,'images');

        $imgheadindex = Arr::get($_POST,'imgheadindex');



        //图片处理

        $piclist ='';

        $litpic = $images[$imgheadindex];

        for($i=1;isset($images[$i]);$i++)

        {

            $desc = isset($imagestitle[$i]) ? $imagestitle[$i] : '';

            $pic = !empty($desc) ? $images[$i].'||'.$desc : $images[$i];

            $piclist .= $pic.',';



        }

        $piclist =strlen($piclist)>0 ? substr($piclist,0,strlen($piclist)-1) : '';//图片



        $model->distributorname = ARR::get($_POST, 'distributorname');

        $model->linkman = ARR::get($_POST, 'linkman');

        $model->mobile = ARR::get($_POST, 'mobile');

        $model->telephone = ARR::get($_POST, 'telephone');

        $model->address = ARR::get($_POST, 'address');

        $model->litpic = $litpic;

        $model->piclist = $piclist;

        $model->fax = ARR::get($_POST, 'fax');

        $model->qq = ARR::get($_POST, 'qq');

        $model->kindid = ARR::get($_POST, 'kindid');

        $model->modtime = time();



        $model->verifystatus = Arr::get($_POST,'verifystatus');

        $model->content = Arr::get($_POST,'content');

        $model->lng = Arr::get($_POST,'lng');

        $model->lat = Arr::get($_POST,'lat');

        $model->payee = Arr::get($_POST,'payee');
        $model->openingbank = Arr::get($_POST,'openingbank');
        $model->bankroom = Arr::get($_POST,'bankroom');
        $model->bankname = Arr::get($_POST,'bankname');
        $model->bankaccount = Arr::get($_POST,'bankaccount');

        $model->kindlist = implode(',',$kindlist);//所属目的地

        $model->content = Arr::get($_POST,'content');//分销商介绍

        $model->finaldestid=empty($_POST['finaldestid'])?Model_Destinations::getFinaldestId(explode(',',$model->kindlist)):$_POST['finaldestid'];



        if ($action == 'add' && empty($id))

        {

            $model->create();

        }

        else

        {

            //这里添加分销商审核功能

            $vstatus =Arr::get($_POST,'vstatus');

            //通过审核

            if($vstatus==3||$vstatus==2)

            {

                $qua = unserialize($model->qualification);

                if(!empty($qua)&&$vstatus==3)

                {

                    $model->verifystatus = $vstatus;

                    $model->reprent = $qua['reprent'];

                    $model->address = $qua['address'];

                    $model->distributorname = $qua['distributorname'];

                    $model->kindid = Arr::get($_POST, 'kind_right');



                }

                else if($vstatus==2)

                {

                    $model->verifystatus = $vstatus;

                    $model->reason = Arr::get($_POST,'reason');

                }







            }



            $model->update();

        }

        if ($model->saved())

        {

            if ($action == 'add')

            {

                $productid = $model->id; //插入的产品id

            }

            else

            {

                $productid = $model->id;

            }

            $status = true;

        }

        echo json_encode(array('status' => $status, 'productid' => $productid));

    }



    /*

      以json方式返回分销商列表

   */

    public function action_ajax_distributor_list()

    {

        $model =ORM::factory('distributor');

        $list = $model->get_all();

        echo json_encode($list);

    }

    /*

          以json方式返回分销商列表

       */

    public function action_ajax_distributor_kindid()

    {

        $sql= "select * from sline_distributor order by  convert(distributorname using gbk) asc";

        $list =DB::query(Database::SELECT,$sql)->execute()->as_array();;

        echo json_encode(array('nextlist'=>$list));

    }

    /*

      设置产品分销商add

    */

    public function action_ajax_set_supplier()

    {

        $product_arr = array(

            1 => 'line',

            2 => 'hotel',

            3 => 'car',

            4 => 'article',

            5 => 'spot',

            6 => 'photo',

            8 => 'visa',

            13 => 'tuan'

        );

        $typeid = ARR::get($_POST, 'typeid');

        $productid = ARR::get($_POST, 'productid');

        $supplierids = ARR::get($_POST, 'supplierids');

        $model = ORM::factory($product_arr[$typeid], $productid);

        $is_success = 'ok';

        $productid_arr = explode('_', $productid);

        foreach ($productid_arr as $k => $v)

        {

            $model = ORM::factory($product_arr[$typeid], $v);

            if ($model->id)

            {

                $model->supplierlist = $supplierids;

                $model->save();

                if (!$model->saved())

                    $is_success = 'no';

            }

        }

        echo $is_success;

    }



    /*

     * ajax检测是否存在

     * */

    public function action_ajax_check()

    {

        $field = $this->params['type'];

        $val = ARR::get($_POST, 'val');//值

        $mid = ARR::get($_POST, 'mid');//会员id

        $flag = Model_Member::checkExist($field, $val, $mid);

        echo $flag;

    }



    public function action_dialog_set()

    {

        $suppliers = $_GET['suppliers'];

        $id = $_GET['id'];

        $typeid = $_GET['typeid'];

        $selector = urldecode($_GET['selector']);

        $supplierArr = explode(',', $suppliers);

        $supplierList = ORM::factory('distributor')->get_all();

        $kind=$this->_distributor_kind();

        array_unshift($kind,array('id'=>0,'kindname'=>'默认'));

        $column=array();

        foreach($supplierList as $v){

            array_push($column,$v['kindid']);

        }

        $count=array_count_values($column);

        foreach($kind as &$v){

            if(!empty($count[$v['id']])){

                $v['count']=$count[$v['id']];

            }

        }

        $this->assign('supplierArr', implode(',',$supplierArr));

        $this->assign('selector', $selector);

        $this->assign('kind',$kind);

        $this->display('stourtravel/distributor/dialog_set');

    }



    /**

     * 分类列表视图

     */

    public function action_kind()

    {

        //栏目深度

        $level = 0;

        $parent = ($node = Arr::get($_GET, 'node')) == 'root' ? 0 : $node;

        $table = 'distributor_kind';

        $action = $this->params['action'];

        $model = ORM::factory($table);

        switch ($action)

        {

            case 'read':

                $path = 0;

                $list = $model->where("pid={$parent}")->get_all();

                foreach ($list as $k => $v)

                {

                    $list[$k]['allowDrag'] = false;

                    $list[$k]['leaf'] = substr_count($list[$k]['path'], '-') < $level ? false : true;

                }

                $list[] = array(

                    'leaf' => true,

                    'id' => "{$parent}add",

                    'kindname' => "<button class=\"dest-add-btn df-add-btn\" onclick=\"addSub('{$parent}','{$path}')\">添加</button>",

                    'allowDrag' => false,

                    'allowDrop' => false,

                    'displayorder' => 'add'

                );

                echo json_encode(array('success' => true, 'text' => '', 'children' => $list));

                break;

            case 'addsub':

                $pid = Arr::get($_POST, 'pid');

                $model->pid = $pid;

                $model->kindname = "未命名";

                $model->path = Arr::get($_POST, 'path');

                $model->save();

                if ($model->saved())

                {

                    $model->reload();

                    $data = $model->as_array();

                    $data['leaf'] = true;

                    echo json_encode($data);

                }

                break;

            case 'save':

                $rawdata = file_get_contents('php://input');

                $field = Arr::get($_GET, 'field');

                $data = json_decode($rawdata);

                $id = $data->id;

                if ($field)

                {

                    $model = ORM::factory($table, $id);

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

                break;

            case 'update':

                $id = Arr::get($_POST, 'id');

                $field = Arr::get($_POST, 'field');

                $val = Arr::get($_POST, 'val');

                $model = ORM::factory($table, $id);

                if ($model->id)

                {

                    $model->$field = $val;

                    $model->save();

                    if ($model->saved())

                        echo 'ok';

                    else

                        echo 'no';

                }

                break;

            case 'delete':

                $rawdata = file_get_contents('php://input');

                $data = json_decode($rawdata);

                $id = $data->id;

                if (!is_numeric($id))

                {

                    echo json_encode(array('success' => false));

                    exit;

                }

                $model = ORM::factory($table, $id);

                $model->delete();

                break;

            default:

                $this->display('stourtravel/distributor/kind');

        }

    }
    /*
     *  分销商的 收入 统计
     *
     */
    public  function  action_distributorIncome(){

        $pagesize = 20;

        $page     = $_GET['page'] ? $_GET['page'] : 1;

        $offset   = ceil($page-1)*$pagesize;

        $distributor_id  = $_GET['distributor_id'];//

        $timetype  = !empty($_GET['day']) ? $_GET['day']: 1;


        $searchKey  = $_GET['searchKey'] ;

        $order = 'order by a.addtime desc';

        $w = "where a.typeid >0 ";

        if(!empty($distributor_id)){

            $w.=" and find_in_set($distributor_id,a.distributor)";
        }

        switch ($timetype)

        {

            case 1:

                $time_arr = Common::getTimeRange(1);//今日

                break;

            case 2:

                $time_arr = Common::getTimeRange(2);//昨日

                break;

            case 3:

                $time_arr = Common::getTimeRange(3);//本周

                break;

            case 5:

                $time_arr = Common::getTimeRange(5); //本月

                break;
            case 6:

                $time_arr = Common::getTimeRange(6); // 上月

                break;

        }

        if($timetype!=999) {

            $w .= " and addtime>=$time_arr[0] and addtime<=$time_arr[1] ";
        }

        $w .= empty($webid) ? ' and a.webid=0' : " and a.webid=$webid";

        $sql = "select a.*  from sline_member_order as a $w $order limit $offset,$pagesize";


        $re = DB::query(Database::SELECT, $sql)->execute()->as_array();

        $totalN =  DB::query(Database::SELECT, "select count(*) as num from sline_member_order a $w ")->execute()->as_array();


        $totalNum = $totalN[0]['num'] ? $totalN[0]['num'] : 0;

        $total  =  $totalNum;

        $totalPage = ceil($total/$pagesize)*1;

        foreach ($re as &$v){

              $num = intval($v['dingnum'])+intval($v['childnum'])+intval($v['oldnum']);

              $v['price'] = $num*$v['price'];

              //$v['distributorprice'] =
              $v['ratio'] =round($v['distributorprice']*100/$v['price']);

              $dis_mod =ORM::factory('distributor')->where('id', '=', $v['distributor'])->find()->as_array();

              $v['distributorlist'] =!empty($dis_mod['distributorname'])?$dis_mod['distributorname']:'';


        }

        $dayArr  = array(
                       1=>'今天',
                       2=>"昨天",
                       3=>"本周",
                       5=>"本月",
                       6=>"上月",
                       999=>"全部"
        );

        $this  ->assign("dayArr",$dayArr);
        $this  ->assign('get',$_GET);
        $this  ->assign('dayss',$timetype);
        $this  ->assign('distributor_id',$distributor_id);
        $this ->assign('page',$page);
        $this  ->assign('totalPage',$totalPage);
        $this->assign('info', $re);

        $this->display("stourtravel/distributor/income");

    }

}