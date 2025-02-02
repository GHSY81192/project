<?php defined('SYSPATH') or die('No direct script access.');



/**

 * Class Controller_Member_Login

 * 会员登陆

 */

class Controller_Member_Login extends Stourweb_Controller

{



    private $_mid;



    public function before()

    {

        parent::before();

        $this->_mid = Cookie::get('st_userid') ? Cookie::get('st_userid') : 0;



    }



    //登录首页

    public function action_index()

    {

        //判断是否是登陆状态

        if (!empty($this->_mid))

        {

            $this->request->redirect('member');

        }

        $redirect_url = Arr::get($_GET, 'redirecturl');



        if (empty($redirect_url))

        {

            /*$fromurl = $_SERVER['HTTP_REFERER'];//来源地址

            $fromurl = strpos($fromurl, 'register') || strpos($fromurl, 'login') || strpos($fromurl, 'findpwd') ? $GLOBALS['cfg_basehost'] : $fromurl;

            $fromurl = $fromurl ? $fromurl : $GLOBALS['cfg_basehost'];*/

            $fromurl=rtrim($GLOBALS['cfg_basehost'],'/').'/member/';

        }

        else

        {

            $fromurl = $redirect_url;

        }



        Common::session('login_referer', $fromurl);



        //token

        $token = md5(time());

        Common::session('crsf_code', $token);

        $this->assign('frmcode', $token);

        $this->assign('fromurl', $fromurl);

        $this->display('member/login');

    }



    //退出登陆

    public function action_loginout()

    {

        $referer = $_SERVER['HTTP_REFERER'];//来源地址

        Model_Member::login_out();

        $this->request->redirect($referer);



    }



    //ajax登陆

    public function action_ajax_login()

    {

        $ucsynlogin = '';

        $loginName = Arr::get($_POST, 'loginname');

        $loginPwd = empty(Arr::get($_POST, 'loginpwd'))?'':Arr::get($_POST, 'loginpwd');

        $frmCode = Arr::get($_POST, 'frmcode');

        $msg     = Arr::get($_POST, 'msg');

        //安全校验码验证

        $orgCode = Common::session('crsf_code');

        if ($orgCode != $frmCode && !Captcha::valid($frmCode))

        {

            $out['status'] = 0;

            $out['msg'] = '校验码错误';

            echo json_encode($out);

            exit;

        }
      
        $user = Model_Member::login($loginName, $loginPwd, 1);

        $status = !empty($user) ? 1 : 0;



        #api{{

        if (defined('UC_API') && include_once BASEPATH . '/uc_client/client.php')

        {



            //检查帐号

            list($uid, $loginname, $password, $email) = uc_user_login($loginName, $loginPwd);



            if ($uid > 0)

            {



                //同步登录的代码

                $ucsynlogin = uc_user_synlogin($uid);

            }

            else if ($uid == -1)

            {

                $uid = uc_user_register($loginname, md5($password), '');

                if ($uid > 0)

                {

                    $ucsynlogin = uc_user_synlogin($uid);

                }

            }

        }

        #/aip}}



        $out = array();

        $out['status'] = $status;

        $out['js'] = $ucsynlogin;

        echo json_encode($out);



    }



    /**

     * ajax判断是否登陆

     */

    public function action_ajax_is_login()

    {

        //检测用户是否存在

        $mid = Cookie::get('st_userid');

        if (!isset($mid))

        {



            exit(json_encode(array('status' => 0)));

        }

        else

        {

            $member = Model_Member::get_member_byid($mid);

            $minfo = array(

                'mid' => $member['mid'],

                'nickname' => $member['nickname'],

                'litpic' => $member['litpic'],

                'last_logintime' => $member['last_logintime']

            );

            exit(json_encode(array('status' => 1, 'user' => $minfo)));

        }

    }



    //注销登录

    public function action_ajax_out()

    {

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->cmsurl;

        $fromurl = strpos($referer, 'member') || strpos($referer, 'login') || strpos($fromurl, 'register') ? $GLOBALS['cfg_basehost'] : $referer;

        Common::session('member', NULL);

        Cookie::delete('st_userid');

        header("Location:{$fromurl}");

    }

}