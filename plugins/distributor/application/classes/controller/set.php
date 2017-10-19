<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ���ӿ�����
 */
class Controller_Set extends Stourweb_Controller
{
    public function before()
    {
        parent::before();
        $this->assign('parentkey', $this->params['parentkey']);
        $this->assign('itemid', $this->params['itemid']);
    }
    /**
     * LOGO����
     */
    public function action_logo()
    {
        $this->display('set_logo');
    }

    /**
     *  ��Ӧ�̵ײ�����
     */
    public function action_footer()
    {
        $configinfo = DB::select()->from('sysconfig')->where('webid','=',0)->and_where('varname','=','cfg_supplier_footer')->execute()->current();
        $this->assign('configinfo', $configinfo);//var_dump($configinfo);exit;
        $this->display('set_footer');
    }
}