<?php defined('SYSPATH') or die('No direct script access.');



class Controller_Notes extends Stourweb_Controller{







    private  $_typeid = 101;



    private  $_cache_key = '';



    public   $admin;



    public function before()



    {



        parent::before();



        //��黺��



        $this->_cache_key = Common::get_current_url();



        $html = Common::cache('get',$this->_cache_key);



        $genpage = Common::remove_xss(Arr::get($_GET,'genpage'));



        $this->admin  = Common::remove_xss(Arr::get($_GET,'feng'));



        if(!empty($html) && empty($genpage))



        {



            echo $html;



            exit;



        }



        $this->assign('typeid',$this->_typeid);



        $channelname = Model_Nav::get_channel_name($this->_typeid);



        $this->assign('channelname', $channelname);



    }



    //��ҳ



    public function action_index()



    {



        $seoinfo = Model_Nav::get_channel_seo($this->_typeid);



        $this->assign('seoinfo', $seoinfo);





        //�μ�����



        $total = Model_Notes::get_total_notes();



        $this->assign('total_notes',$total);



        //��ҳģ��



        $templet = Product::get_use_templet('notes_index');



        // if($this->admin  =='feng'){

        $templet = $templet ? $templet : 'notes/TravelNotes-index';

        //  }else{

        //     $templet = $templet ? $templet : 'notes/index';



        //  }





        $this->display($templet);





    }







    //��ʾ�μ�



    public function action_show()



    {



        $noteid = intval(Common::remove_xss($this->request->param('id')));







        if(!empty($noteid))



        {



            $info = ORM::factory('notes',$noteid)->as_array();



            //δͨ����˵��μ�



            if($info['status']!=1&&$info['status']!=0)



            {



                $msg = array(



                    'status'=>0,



                    'msg'=>'�μ�δͨ�����,��ʱ�������',



                    'jumpUrl'=>$this->request->referrer()



                );




                Common::message($msg);



                exit;



            }else{//fengjishu �����ж�



                //��ȡ �μ�Ŀ¼ ����Ϣ



                $note_list_A2  = Model_Notes::get_list_A2($noteid);



                //��ȡ���� A2 ����Ϣ

                $note_list     = Model_Notes::get_list($noteid);

                $note_list     =array_merge($note_list_A2,$note_list);



                $this ->assign('notelist',$note_list);

            }

            //==================fengjishu 08.22

            //��ȡ�����μ�

            $hot_notes  =Model_Notes::get_hot_notes();

            $this ->assign("hot_notes",$hot_notes);

            $recent_activities =Model_Article::get_recent_activities();//���ڻ

            $this ->assign("recent_activities",$recent_activities);



            //==================fengjishu 08.22



            $member = ORM::factory('member',$info['memberid'])->as_array();

            // ��ȡ ���� �м�ƪ�μ�

            $info['noteNum']  =  Model_Notes::get_notenum($info['memberid']);

            // ��ȡ���ߵȼ�

            $info['greate']   =  Model_Notes::get_author_greate($info['memberid']);

            //��ȡ�μ� ���μ�

            $info['allnotes'] =  Model_Notes::get_all_notesNum();

            // var_dump($member);exit;

            $is_phone =   preg_match('/^1([0-9]{9})/',$member['nickname']);

            if($is_phone){

                $member['nickname']  = substr($member['nickname'],0,3)."****".substr($member['nickname'],7,11);

            }

            //��� mdd

            $info['mdd']  =ORM::factory('destinations',$info['finaldestid'])->as_array();

            $info['mdd']  =$info['mdd']['kindname'];

            // �ж��Ƿ��ղ� ���μ�

            $memberId  = Cookie::get('st_userid');

            if($memberId) {

                $info['islike'] = Model_Article::islike($info['id'], $memberId , '101');

                // ��ȡ��¼ҳ����û� ��Ϣ

                $yemianuser = ORM::factory('member',$memberId)->as_array();

                //$this ->assign('user',$yemianuser[0]);
                $this ->assign('user',$yemianuser);


            }

            Product::update_click_rate($info['id'], $this->_typeid);


            $this->assign('info',$info);



            $this->assign('member',$member);



            //  $templet = Product::get_use_templet('notes_show');

//

            $templet = $templet ? $templet : 'notes/show';



            $this->display($templet);




            //��������

            $content = $this->response->body();

            Common::cache('set',$this->_cache_key,$content);


        }







    }



    //�б�ҳ

    public function action_list()

    {

        //����ֵ��ȡ

        $destPy = $this->request->param('destpy');// ���� Ŀ�ĵ�

        $day = $this->request->param('day');//. ��������

        $way = $this->request->param('way');//  ���з�ʽ

        $p = intval($this->request->param('p'));

        $destPy = $destPy&&$destPy!='list'&&$destPy!='write'? $destPy : 'all';

        $day =   $day ? $day : 0;

        $way= $way ? $way : 0;

        $pagesize = 10;

        $keyword = Common::remove_xss(Arr::get($_GET,'keyword'));

        $route_array = array(

            'controller' => $this->request->controller(),

            'action' => $this->request->action(),

            'destpy' => $destPy,

            'day' => $day,

            'way' => $way
        );

        $out = Model_Notes::search_result($route_array,$keyword,$p,$pagesize);

        $pager = Pagination::factory(

            array(

                'current_page'      => array('source' => 'route', 'key' => 'p'),

                'view'              =>'default/pagination/search1',

                'total_items'       => $out['total'],

                'items_per_page'    => $pagesize,

                'first_page_in_url' => false,

            )

        );

        //���÷��ʵ�ַ ��ǰ����������

        $pager->route_params($route_array);

        $destId = $destPy=='all' ? 0 : ORM::factory('destinations')->where("pinyin='$destPy'")->find()->get('id');

        $destId = $destId ? $destId : 0;

        //Ŀ�ĵ���Ϣ

        $destInfo = array();

        $preDest = array();

        if($destId)

        {

            $destInfo = ORM::factory('destinations',$destId)->as_array();

            $preDest = Model_Destinations::get_prev_dest($destId);

        }

        $chooseitem = Model_Notes::get_selected_item($route_array);


        $seoinfo = Model_Nav::get_channel_seo($this->_typeid);



        $this->assign('seoinfo', $seoinfo);

        //$searchTitle = Model_Article::gen_seotitle($route_array);
        //===================fengjishu 06.17 hotRaiders

        $hotRaiders = Model_Article::get_hot_article(10);

        $this->assign("hotRaiders",$hotRaiders);

        $this->assign('destid',$destId);

        $this->assign('destinfo',$destInfo);

        $this->assign('predest',$preDest);

        $this->assign('list',$out['list']);

        $this ->assign('total',$out['total']);

        $this->assign('chooseitem',$chooseitem);

        $this->assign('searchtitle',$searchTitle);

        $this->assign('param',$route_array);

        $this->assign('currentpage',$p);

        $this->assign('pageinfo',$pager);

        //$templet = Product::get_use_templet('article_list');



        $templet = $templet ? $templet : 'notes/Trategy-list';





        $this->display($templet);

        //��������

        $content = $this->response->body();

        Common::cache('set',$this->_cache_key,$content);

    }



    //д�μ�



    public function action_write()
        
    {


        $noteid = intval(Common::remove_xss(Arr::get($_GET,'noteid')));


        $memberid = intval(Common::remove_xss(Arr::get($_GET,'memberid')));


        $commonnoteid =Common::remove_xss( Common::session('noteid'));

        // echo $commonnoteid;exit;

        //��Ա��Ϣintval()

        $userInfo = Product::get_login_user_info();

        $feng  = '';

        //Ҫ��д�μǱ����½

        if(empty($userInfo['mid']))

        {

            $this->request->redirect('/member/login/?redirecturl='.urlencode(Common::get_current_url()));

        }

        //���ڻ�Ա��Ϣ�޸�

        if(!empty($noteid) && !empty($memberid))

        {


            $info = ORM::factory('notes')

                ->where("id=$noteid and memberid=$memberid")

                ->find()

                ->as_array();

            if(!empty($info))

            {

                $this->assign('info',$info);

                //��ȡ A2 ��Ϣ

                $note_list_A2  = Model_Notes::get_list_A2($noteid);


                //��ȡ���� A2 ����Ϣ

                $note_list     = Model_Notes::get_list($noteid);

                $note_list     =array_merge($note_list_A2,$note_list);
               //  �� ��������ַ��� �е�˫���� ��ɵ�����
//                foreach ($note_list as $k=>&$v){
//
//                    $v['content'] =  str_replace('"',"'",$v['content']);
//
//                    foreach ($v['child'] as $ke=>$va){
//
//                        $va['content'] = str_replace('"',"'",$va['content']);
//                    }
//
//                }

                //var_dump($note_list);exit;

                //  ���� ���� $edit  �ж��Ƿ����޸�

                $edit    =  1;

                $this ->assign('notelist',$note_list);

                $this ->assign("noteid",$noteid);

                $this ->assign('edit',$edit);

            }

        }elseif(!empty($commonnoteid) && !empty($userInfo['mid'])&&$feng=='��ֹ������ж�'){


            $info = ORM::factory('notes')


                ->where("id=$commonnoteid and memberid=$userInfo[mid]")


                ->find()


                ->as_array();


            if(!empty($info))

            {


                $this->assign('info',$info);


                $first   = array('title'=>'','con'=>'','noteid'=>$commonnoteid,'mid'=>$userInfo['mid']) ;


                //�����һ�� ������


                $first_muluid  = Model_Notes::insert_mian_first($first);


                // �������е�һ�� ��Ŀ¼ �ĸ�id

                Model_Notes::update_firstmulu($first_muluid,$commonnoteid);


                //��ȡ A2 ��Ϣ


                $note_list_A2  = Model_Notes::get_list_A2($commonnoteid);


                //��ȡ���� A2 ����Ϣ

                $note_list     = Model_Notes::get_list($commonnoteid);

                $note_list     =array_merge($note_list_A2,$note_list);

                $this ->assign('notelist',$note_list);

                $this ->assign("noteid",$commonnoteid);

                $this  ->assign('commonnoteid',$commonnoteid);

            }

        }


        //=========fengjishu  // 08.22


        $pic['mem_litpic'] = ORM::factory('member',$userInfo['mid'])->get('litpic');//��ȡ��Աͷ��;


        $this ->assign("pic",$pic['mem_litpic']);

        // ��ȡĿ��

        $mdd        = Model_Notes::get_mdd();

        $this->assign("mdd",$mdd);

        //=========fengjishu end

        //=============================fengjishu end 08.26

        $code = time();

        Common::session('code',$code);

        $this->assign('frmcode',$code);


        //y�õ�ģ���ļ�

        $templet = Product::get_use_templet('notes_release');

        $templet = $templet ? $templet : 'notes/write';

        $this->display($templet);


        //��������

//

//        $content = $this->response->body();
//
//
//
//        Common::cache('set',$this->_cache_key,$content);

    }



    /*

     *  ������Ŀ¼

     */

    public  function action_ajax_cookie()

    {



        $userInfo = Product::get_login_user_info();



        $main_id = Common::remove_xss(Arr::get($_GET, 'main_id'));



        $ajax_noteid = Common::remove_xss(Arr::get($_GET, 'ajax_noteid'));



        $dis = str_replace("A", "", $main_id);



        $dis = $dis * 1 - 1;//���� �½� ����

        if ($ajax_noteid == '-1') {



            $m = ORM::factory('notes');



            $m->memberid = $userInfo['mid'];



            $m->save();



            $notes_id = $m->reload();

            $dis = Model_Notes::insert_main_lu($notes_id, $main_id, $dis,$userInfo['mid']);

            //д�� session  ����ˢ��ҳ���

            Common::session("noteid",$notes_id);

            $data=array();

            $data=array('note'=>"$notes_id",'pid'=>$dis);



            echo json_encode($data);

            exit;

        }else{

            $note  =Common::session('noteid');



            if($note) {

                $dis = Model_Notes::insert_main_lu($note, $main_id, $dis, $userInfo['mid']);

            }else{

                $dis = Model_Notes::insert_main_lu($ajax_noteid, $main_id, $dis, $userInfo['mid']);

            }



            echo json_encode(array('noteid'=>$ajax_noteid,'pid'=>$dis));exit;

        }



    }

    /*

 *  ������Ŀ¼

 */

    public  function action_ajax_zid(){



        $userInfo = Product::get_login_user_info();



        $child_id = Common::remove_xss(Arr::get($_GET, 'child_id'));



        $ajax_noteid = Common::remove_xss(Arr::get($_GET, 'ajax_noteid'));



        $parentid    = Common::remove_xss(Arr::get($_GET, 'parentid'));



        //$dis     =str_replace("A","",$child_id);



        $dis     = explode("-",$child_id);



        $disorder = $dis[1];

        if($ajax_noteid=='-1'){

            $m = ORM::factory('notes');



            $m ->memberid =$userInfo['mid'];



            $m->save();



            $notes_id  = $m->reload();



            $dis =  Model_Notes::insert_zi_lu($notes_id,$child_id,$disorder,$dis[0],$userInfo['mid'],$parentid);

            Common::session("noteid",$notes_id);

            echo  $notes_id;exit;

        }else{



            $note  =Common::session('noteid');

            if($note){

                $dis =  Model_Notes::insert_zi_lu($note,$child_id,$disorder,$dis[0],$userInfo['mid'],$parentid);

            }else{

                $dis =  Model_Notes::insert_zi_lu($ajax_noteid,$child_id,$disorder,$dis[0],$userInfo['mid'],$parentid);

            }





            echo $ajax_noteid;exit;

        }











    }

    /*

     *  ɾ�� ��Ŀ¼

     */

    public  function action_ajax_del_cookie(){



        $userInfo = Product::get_login_user_info();



        $delid = Common::remove_xss(Arr::get($_GET, 'del'));



        $noteid = Common::remove_xss(Arr::get($_GET, 'noteid'));



        $re =Model_Notes::del_mainlu($noteid,$delid);

        echo  $re;





    }

    /*

     *  ɾ�� ��Ŀ¼

     */

    public  function action_ajax_del_zimulu(){



        $userInfo = Product::get_login_user_info();



        $delid = Common::remove_xss(Arr::get($_GET, 'del'));



        $noteid = Common::remove_xss(Arr::get($_GET, 'noteid'));



        $re =Model_Notes::del_zimulu($userInfo['mid'],$noteid,$delid);



        echo  $re;





    }

    /**



     * �μǱ���



     */



    public function action_ajax_save()
        
    {

        //��Ա��Ϣ

        $userInfo = Product::get_login_user_info();

        //Ҫ��д�μǱ����½

        if(empty($userInfo['mid']))
        {

            $this->request->redirect('/member/login/?redirecturl='.urlencode(Common::get_current_url()));

        }

        $frmCode = Common::remove_xss(Arr::get($_POST,'frmcode'));

        $title = Common::remove_xss(Arr::get($_GET,'title'));

        $description = Common::remove_xss(Arr::get($_POST,'description'));

       $content = Common::remove_xss(Arr::get($_POST,'content'));

//$s ="
//Database_Exception [ 1064 ]: You have an error in your SQL syntax;
//check the manual that corresponds to your MySQL server version for the right
// syntax to use near 'Helvetica Neue', Helvetica, 'Hiragino Sans GB', 'Microsoft Y
//aHei', Arial, sans-s' at line 1 [ INSERT INTO `sline_notes_list`  (`title_s`, `notes_id`,
// `addtime`, `content`, `mid`, `m_id`, `displayorder`) VALUES ('dddfdf','161','1490780049',
//'���ϰ˵�׼ʱ�Ӽҳ�����ӭ�ų������������õ���������ˡ��ӻ�կ���ϸ��٣��߾������٣��������������ٿ������Ա�������ĳ���Ҫ���м�飬���ǹ����շѿ��ֱ������ס����һ�����֤�ŵ���ͨ�С�·�ϳ����࣬·��������Լ�ڸ���������40�����ӣ������Ʊ��շѿ��£���ʼ���µ����µ�·������Ը���һЩ�ˣ�����խ�����߻��кö�С��С��С���У�����ʼ�ն��᲻�������λ����ư����Сʱ�ŵ���·���ߵ������Ѿ����࣬�����͵ģ�����ȥ�����˵����鶼���泩������һ��С�򣬾������ֲ��ǵ��ˣ�·����ȫ������¯�ձ��ģ������Ҷ�����ԡ�ż������������ʽ�������������ƺܶ��˶�û�м������������˾Ͳ�Ҫ֨�������ױ�¶������䡣һ·�ķ糾���ͣ����ڿ���ţ��կ��·�꣬�ҹս���ͨ����������·��·����С������ԳԷ����ս�ȥ�ֿ���������Ӳŵ��������ǲ�����Ϊ������ԭ���أ��о����������������ɵģ��������Ʒ�Ҳ�Ե���ô��������������ͣ�����Ƿֽ���״�ֲ��ģ�����·�����ֱߣ����е㶸������ȥ��ҪС�ġ���������·��Ҳ�㿼�����ҵĿ���������ͣ�ó�������һ����������������ˣ�ˡ��������ο��������ڽ����У�Ŀǰû��Ͷ��ʹ�ã���Ʊϵͳ�ǳ��˹�������һ����������Ǯ����Ǯ���ܽ�ȥ�ˣ�Ҳ̫�����ˡ�����20��Ǯ֮�󣬿�ʼ��ʽ���뾰�������ھ������ڿ��������У���֮�������ͣ���������������Щ������?��ɽ���ҹ���һ��·ͨ�����ţ�㳡�����ߵİ�ǽ��д�����й�ţ�ֵĳ���м仹���ȥ��һ����ţ�ƺ�塱���ⲻ�ǳ���ɡ��߹��������ǽ������С�㳡�ˣ���ɽ��λ�ô�����һ�����ţ����ǰ���������֡����µ�һţ��Ҳ���������ˡ���Ȼ�����µ�һţ�ˣ��ǿ϶��ٲ������������ˡ����˺�ţ�ĺ����о��ܿ���ţ�ж��˰ɡ�С�㳡�ϻ���һ��������ţ�����ֵ���Ŀ��ʮ���Ǯһ���ˣ���������������ȥ�϶������������Ӿ������ˡ��ι����ţ��ʼ��ɽ��ɽ·�������һ�����ڽ�������ֳ������ڻ������档��ʼ��ɽ����ɽ��ʱ������û����С·���ߵ�һ��������������ɽ��·���տ�ʼ���ǹ�·������߾ͳ���·�ˡ��ӿ����ݵ���·������Щ��ʯ�ӣ���֮·���߻�δ��ѿ�Ĺ�ľ�ԣ���Ҳ��һ�ֱ��µĲ����С���Ȼ���������ģ���Ϊ�˰����ͣ����Ѵ���һ�������ī����ȷʵ��ЩС�ᡣ���߱��棬���˴�Լʮ�����ӵ�ɽ�����ڿ����˴�˵�еĲ���ջ�����������š����Ա߻��и�Сͤ�ӡ������Ƹ�ר�Ź��ο���Ϣ�ġ������������ջ���������е��е��࣬��������Ů������˵�����ָ߶Ⱥͳ��Ȼ�����һ�㡣�����˻���С������ǰ�е�ʱ�������Ѿ������ϱ��˷����졣Ϊ�˱����ŵ���ң�����һ������һ�����Ƭ������ջ���ĳ������õĻ���ͦ�Ƚ��ģ��Զ���Ӧ���Զ���բ�š���բ�š�վ�ڸߴ���������ջ������������ȥ������΢��һ��̼����ˡ�������ǰ��ɽ·����һ��ʯͷ��������ʯ���ö����¶������������������������ֻ������˵���ǸϽ��߿����ˡ����Сɽ���岢����ߣ�����һЩ�ض��ĽǶȿ���ȥ����ͦ׳�۵ġ���һ��͹���ʯͷ��������Ƕȿ��羰��Ұ��Ϊ��������Ȼ�ˣ�������ʱ�̲��������գ��������Ķ����������ɽ·��ʱ��û�ио����ж����ߣ������߹�֮��ع�ͷһ�����������յġ�������߾ͱȽϺ����ˣ��ǳ�������һ��·������·������·��ͨ�����ŵıؾ�֮·��Զ��Сͤ�ӺͰ�ɫ������֮����ž��ǵ��š�������һ���ܺ��������֣���ȵ�ţ��Ȳ���ջ�������٣��ŵ�������ľ���̳ɵ����棬����������˿���������ˡ��ߵ����м��ʱ���������Ŷ��λ����Ƶģ�ͦ����˼�ġ�����ͼ��λ��û��ʼ���ؾ��Ѿ�Ц�úϲ�£���ˡ��ӵ������������͵�������һ��ɽͷ��ɽ����һ������г縣�£���ǰ���ְ��ָߵĹ�����ʮ�ֵ����ۣ��ھ��뾰������Զ��ʱ��Ϳ�����������������������������Ȥ�����Ծ�Զ����һ�£�û�н�ȥ�ιۡ���������ͨ����һ�����е������ǵ�����·��ɽ�ȽϽ���������ֱ�����˳��е�����˵������·���涸��һ���˿������治��������·���ߵ�ʯ���������������ǡ�����٢�𡱿쵽ɽ�׵�ʱ��̧ͷ�����ڿ��еĲ���ջ���͵��ţ���������һ�ָо����Ӱ�ɽ����ʯ̨����ɽ�����ߵĹ�ľ���ﶪ�����������������ϴ��ӡ���Ȫˮƿ����װֽ�ȣ�̫ɷ�羰�ˡ�ǿ�Һ�����ҳ������Σ�һ��Ҫ��������������һ��Ҫ����������һ��Ҫ����������·��һ��������Ҳ��Ҫ��ɽ���ͷ羰ȥô��̨��ֱ��ͨ��ɽ���µ�ũ��Ժ����ʱ�������Ѿ������ꤡ�����һ�����ɣ�����ȥʯ��ׯ�ܱߵľ������棬����������겻��ķ�ʳ�����ﶼ�д���ˣ���Ȼ��ͬ�ĵط�����˵�ζ��Ҳ��ͬ������һ�˽���12��Ǯ������ˡ���ͷ���������ԡ����Ǻòˣ�ζ��Ҳ������������̫���ˡ�һ�ڲ���ȥ�ÿа�����ͷ������˳���һ������ͷ�������������������ˣ���̫���ˣ���λ��ҪЦ���ҡ���Լ1�����극���������Աߵ��һ���תһת����ν���һ���Ҳ����һ���˹����㣬��߿����Ĺ��Ʋ����һ����ӻ��ɡ������������˵Ļ���������Ŀ�ȸ�ˣ����������ֱ������ֽУ����뿴һ����ȸ���������⼸ֻ��ȸ��̫�����ˣ����ǲ������ǲ��������һ����߶���ȸ�����죬��֪�����͵��������ˣ��뵽���ϻ�Ҫȥ���һ������ѡ����Ǿ������߰ɡ�������ʱ����������һ��·����Ȼ�е��ƣ����������Щӵ����С��С�򣬷�������ȥ��ʱ����ˡ�','1155','A2','1') ] ~ MODPATH/database/classes/kohana/database/mysql.php [ 194 ]
//



//";
        $banner = Common::remove_xss(Arr::get($_POST,'banner'));

        $cover = Common::remove_xss(Arr::get($_GET,'cover'));

        $noteid = intval(Arr::get($_GET,'noteid'));

        $commonnoteid = intval(Arr::get($_GET,'commonnoteid'));

        $edit          = Arr::get($_GET,'eidt');

        //===========

        $ajax_noteid   = Common::remove_xss(Arr::get($_GET,'ajax_noteid'));

        //===========================

        //fengjishu 08.22

        $travel_mdd     = Common::remove_xss(Arr::get($_POST,'travel_mdd'));

        $travel_date    = Common::remove_xss(Arr::get($_POST,'travel_date'));

        $travel_day     = Common::remove_xss(Arr::get($_POST,'travel_day'));

        $travel_consume = Common::remove_xss(Arr::get($_POST,'travel_con'));

        $travel_way     = Common::remove_xss(Arr::get($_POST,'travel_way'));

        $travel_men     = Common::remove_xss(Arr::get($_POST,'travel_men'));

        $title_s        = Common::remove_xss(Arr::get($_POST,'A2'));

        $z_content      = Common::remove_xss(Arr::get($_POST,'A2_con'));

        $z_content      = htmlspecialchars($z_content,ENT_QUOTES);


        $draft          = Common::remove_xss(Arr::get($_GET,'draft'));

        //===========================

        //��ȫУ������֤

        $orgCode = Common::session('code');



//        if($orgCode!=$frmCode)

//

//        {

//

//            exit('frmcode error');

//

//        }

        if(!empty($noteid))

        {

            $m = ORM::factory('notes',$noteid);

        }

        else

        {

            if($ajax_noteid=='-1'){

                $m = ORM::factory('notes');

            }else{

                $m = ORM::factory('notes',$ajax_noteid);

            }

        }

        $m->title = $title;

        $m->memberid = $userInfo['mid'];

        $m->litpic = $cover;

        $m->modtime = time();

        //===========fengjishu 08.22

        $m->finaldestid     =$travel_mdd;

        $m->travel_date     =$travel_date;

        $m->travel_consume  =$travel_consume;

        $m->travel_day       =$travel_day;

        $m->travel_way     =$travel_way;

        $m->status         =0;

        $m->travel_men     =$travel_men;

        $m->addtime        =time();


        if($draft==1){

            $m->is_dra   =$draft;

            $m -> status = 0;

        }else{

            $m->is_dra   =0;

        }

        //===========fengjishu08.22

        $status = 0;

        $m->save();

        if($m->saved())

        {

            $status = 1;

            /*

             * ���ֵ����

             *    д�μ�            �� = k        ��Ϊ�� = nk

             *

             *       noteid   = k    eidt  = k    commonnoteid  = k  ��������� д�μ� �������½�

             *       noteid   = nk   eidt  = k    commonnoteid  = nk ��������� д�μ� �����½�

             *       noteid   = nk   edit  = nk   commonnoteid  = k  ��������� �޸��μ�  �������½�

             *       noteid   = nk   edit  = nk   commonnoteid  = nk ��������� �޸�д�μ� �����½�

             *

             * */

            if(empty($noteid))

            {

                $m->reload();

                $noteid = $m->id;

                $first   = array('title'=>$title_s,'con'=>$z_content,'noteid'=>$noteid,'mid'=>$userInfo['mid']) ;

                //�����һ�� ������

                $first_muluid= Model_Notes::insert_mian_first($first);

                //����  ��Ŀ¼ �� ������Ŀ¼��

                Model_Notes::update_mulu($_POST,$_GET,$first_muluid,$noteid);

                $message   = array('status'=>1,'msg'=>'����ɹ�,��ȴ�����Ա���','noteid'=>$noteid,'sss'=>333);
                //Common::session('noteid',null);
                echo json_encode($message);
                exit;





            }else{

                if(!empty($commonnoteid)&&empty($edit)){



                    $first   = array('title'=>$title_s,'con'=>$z_content,'noteid'=>$noteid,'mid'=>$userInfo['mid']) ;



                    //�����һ�� ������



                    $first_muluid= Model_Notes::insert_mian_first($first);

                    //����  ��Ŀ¼ �� ������Ŀ¼��

                    Model_Notes::update_mulu($_POST,$_GET,$first_muluid,$noteid);

                    $message   = array('status'=>1,'msg'=>'����ɹ�,��ȴ�����Ա���','noteid'=>$noteid,'ss'=>'s');

                }else{



                    Model_Notes::update_mulu2($_POST,$_GET,$noteid);

                    $message   = array('status'=>1,'msg'=>'����ɹ�,��ȴ�����Ա���','noteid'=>$noteid,'ss'=>'mulu2');

                }



            }



        }else{

            $message   = array('status'=>0,'msg'=>'����ʧ��');

        }



        Common::session('noteid',null);

        echo json_encode($message);



        exit();

    }

    /*

     * fengjishu

     * 08.27  ���� �½����ݵĴ洢

     */

    public  function  action_ajax_save_zjcon(){

        echo 1;exit;

    }





    public function action_ajax_get_new_notes()



    {



        $currentpage = intval(Arr::get($_GET,'curr'));//��ǰҳ



        $pagesize = 6;//ÿ����ʾ������Ҫ��js������һ��



        $offset = ($currentpage-1) * $pagesize;



        $list = Model_Notes::get_new_notes($offset,$pagesize);



        foreach($list as &$row)



        {



            $row['pubdate'] = Common::mydate('Y-m-d H:i',$row['modtime']);



        }



        echo json_encode(array('list'=>$list));



        exit;







    }







    /**



     * �ϴ�ͼƬ



     */



    public function action_ajax_upload_picture()



    {



        //if(!$this->request->is_ajax())exit;



        $filedata = Arr::get($_FILES,'filedata');



        $storepath = UPLOADPATH.'/'.date('Y').'/'.date('md').'/';
    //    $storepath .= '2017/';



        if(!file_exists($storepath))



        {



            mkdir($storepath);



        }



        $filename = uniqid();



        $out = array();



        if(move_uploaded_file($filedata['tmp_name'], $storepath.$filename.$filedata['name']))



        {



            $out['status'] = 1;



            $out['litpic'] = '/new_uploads/'.date('Y').'/'.date('md').'/'.$filename.$filedata['name'];



        }



        echo json_encode($out);



    }



































}