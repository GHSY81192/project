<<<<<<< .mine
<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-04-15 16:31:22 --- ERROR: Database_Exception [  ]:  ~ MODPATH\database\classes\kohana\database\mysql.php [ 108 ]
2016-04-15 16:31:22 --- STRACE: Database_Exception [  ]:  ~ MODPATH\database\classes\kohana\database\mysql.php [ 108 ]
--
#0 D:\www\lvyou\core\modules\database\classes\kohana\database\mysql.php(75): Kohana_Database_MySQL->_select_db('stourwebcms')
#1 D:\www\lvyou\core\modules\database\classes\kohana\database\mysql.php(171): Kohana_Database_MySQL->connect()
#2 D:\www\lvyou\core\modules\database\classes\kohana\database\mysql.php(359): Kohana_Database_MySQL->query(1, 'SHOW FULL COLUM...', false)
#3 D:\www\lvyou\core\modules\orm\classes\kohana\orm.php(1800): Kohana_Database_MySQL->list_columns('admin')
#4 D:\www\lvyou\core\modules\orm\classes\kohana\orm.php(455): Kohana_ORM->list_columns()
#5 D:\www\lvyou\core\modules\orm\classes\kohana\orm.php(400): Kohana_ORM->reload_columns()
#6 D:\www\lvyou\core\modules\orm\classes\kohana\orm.php(265): Kohana_ORM->_initialize()
#7 D:\www\lvyou\core\modules\orm\classes\kohana\orm.php(46): Kohana_ORM->__construct(NULL)
#8 D:\www\lvyou\newtravel\application\classes\common.php(774): Kohana_ORM::factory('admin')
#9 D:\www\lvyou\newtravel\application\classes\stourweb\controller.php(46): Common::checkLogin('9c4aS34IWcijtyZ...')
#10 D:\www\lvyou\newtravel\application\classes\controller\line.php(9): Stourweb_Controller->before()
#11 [internal function]: Controller_Line->before()
#12 D:\www\lvyou\core\system\classes\kohana\request\client\internal.php(103): ReflectionMethod->invoke(Object(Controller_Line))
#13 D:\www\lvyou\core\system\classes\kohana\request\client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#14 D:\www\lvyou\core\system\classes\kohana\request.php(1160): Kohana_Request_Client->execute(Object(Request))
#15 D:\www\lvyou\newtravel\index.php(121): Kohana_Request->execute()
#16 {main}||||||| .r0
=======
<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-04-15 17:06:18 --- ERROR: HTTP_Exception_404 [ 404 ]: The requested URL line/line/parentkey/product/itemid was not found on this server. ~ SYSPATH\classes\kohana\request\client\internal.php [ 87 ]
2016-04-15 17:06:18 --- STRACE: HTTP_Exception_404 [ 404 ]: The requested URL line/line/parentkey/product/itemid was not found on this server. ~ SYSPATH\classes\kohana\request\client\internal.php [ 87 ]
--
#0 D:\www\lvyou\core\system\classes\kohana\request\client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#1 D:\www\lvyou\core\system\classes\kohana\request.php(1160): Kohana_Request_Client->execute(Object(Request))
#2 D:\www\lvyou\newtravel\index.php(121): Kohana_Request->execute()
#3 {main}>>>>>>> .r54
