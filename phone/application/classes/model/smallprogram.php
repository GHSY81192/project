<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Smallprogram extends ORM {

	private static $smallprogramfield ='id,title,author,content,litpic,piclist,sponsor,start_time,end_time,share_title,joinnum';
	private static $smallprogramquestionfield ='id,question,answer,correct,ques_type,litpic';

     /*
      * С������� ��ģ��
      *
      * ishidden = 0 Ϊ��ʾ
      */

   public  static  function getActInfo(){

      $sql ="SELECT ".self::$smallprogramfield." FROM `sline_smallprogram` where ishidden=0 ORDER by addtime desc limit 0,1";

      $arr = self::execute($sql);
	  $actid = $arr[0]['id'];
	  self::updateShowNum($actid);
	  return $arr;
   }
	/*
	 *  ��ȡ ����
	 */
	public static function  getQuestion(){

		$sql ="SELECT ".self::$smallprogramquestionfield." FROM `sline_smallprogram_question` where ishidden=0 ORDER by rand() limit 10";

		$arr = self::execute($sql);

		return $arr;
	}
	/*
	 *   ���� �ôδ����ķ����ܴ���
	 *   $actid   ���id
	 */
	public static function  updateShareNum($actid){

		if(!empty($actid)){
			$sql = "UPDATE `sline_smallprogram` SET `sharenum`=sharenum+1 WHERE id=".$actid;
			DB::query(1,$sql)->execute();
		}
	}
	/*
	 *   ������ҳ���Ķ�����
	 *   $actid   ���id
	 */
	public static function  updateShowNum($actid){

		if(!empty($actid)){
			$sql = "UPDATE `sline_smallprogram` SET `shownum`=shownum+1 WHERE id=".$actid;
			DB::query(1,$sql)->execute();
		}
	}
	/*
	 *   �Ա� ��ȷ��
	 */
	public static function compareAnswer($id){

		$sql ="SELECT correct FROM `sline_smallprogram_question` where id=".$id;

		$arr = self::execute($sql);

		return $arr[0]['id'];
	}
	/*
	 *  ���ӷ���ôλ����ϸ��¼
	 *  $openid ,$actid
	 */
    public static  function addShareDetail($openid,$actid){

		$time  = time();
		$sql = "INSERT INTO `sline_smallprogram_share`(`openid`,`act_id`,`share_time`) VALUES ('".$openid."',$actid,$time)";
		DB::query(1,$sql)->execute();
	}
	/*
	 *  ���� �û�������Ϣ
     *  $openid
     *  $taketime ����ʱ��
     *  $all_correct_num ��ȷ�ĸ���
	 */
	public static function addUserAnswerInfo($openid,$taketime,$all_correct_num){
		 $time  = time();
		 $sql ="INSERT INTO `sline_smallprogram_user`(`openid`,`taketime`,`all_correct_num`,`addtime`) VALUES ('".$openid."','".$taketime."',$all_correct_num,$time)";
		 DB::query(1,$sql)->execute();
	}
	/*
	 * ���²���������
	 */
    public static function setJoinUser($actid){
		$sql ="UPDATE `sline_smallprogram` SET `joinnum`=joinnum+1 WHERE id=".$actid;
		$re =DB::query(1,$sql)->execute();
		return $re;
	}
	/*
   * ִ��sql���
   * */
	private static function execute($sql)
	{
		$arr = DB::query(1,$sql)->execute()->as_array();

		return $arr;
	}
}