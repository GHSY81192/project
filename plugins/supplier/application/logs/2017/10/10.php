<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-10-10 14:08:43 --- ERROR: View_Exception [ 0 ]: The requested view /default/mobile/login/login could not be found ~ APPPATH/classes/stourweb/view.php [ 324 ]
2017-10-10 14:08:43 --- STRACE: View_Exception [ 0 ]: The requested view /default/mobile/login/login could not be found ~ APPPATH/classes/stourweb/view.php [ 324 ]
--
#0 /home/wwwroot/www.aitto.net/plugins/supplier/application/classes/stourweb/view.php(187): Stourweb_View->set_filename('/default/mobile...')
#1 /home/wwwroot/www.aitto.net/plugins/supplier/application/classes/stourweb/view.php(30): Stourweb_View->__construct('/default/mobile...', NULL, NULL, NULL)
#2 /home/wwwroot/www.aitto.net/plugins/supplier/application/classes/stourweb/controller.php(46): Stourweb_View::factory('/default/mobile...')
#3 /home/wwwroot/www.aitto.net/plugins/supplier/application/classes/controller/pc/login.php(29): Stourweb_Controller->display('login/login')
#4 [internal function]: Controller_Pc_Login->action_index()
#5 /home/wwwroot/www.aitto.net/core/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Pc_Login))
#6 /home/wwwroot/www.aitto.net/core/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#7 /home/wwwroot/www.aitto.net/core/system/classes/kohana/request.php(1160): Kohana_Request_Client->execute(Object(Request))
#8 /home/wwwroot/www.aitto.net/plugins/supplier/index.php(133): Kohana_Request->execute()
#9 {main}