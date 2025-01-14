<?php defined('SYSPATH') or die('No direct script access.');



//会员中心

Route::set('member', 'member(/<controller>(/<action>(/<query>)))', array('query' => '.*'))

    ->defaults(array(

        'action' => 'index',

        'controller' => 'index',

        'directory' => 'member'

    ));



//消息提示

Route::set('message', 'message/<param>', array(

    'param' => '[a-zA-Z0-9]+'

))->defaults(array(

    'action' => 'index',

    'controller' => 'message'

));


// 增加车主服务 详情

Route::set('che_show', 'che(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => '(print|show)'

))->defaults(array(

    'action' => 'index',

    'controller' => 'che'

));

//线路详情

Route::set('line', 'lines(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => '(print|show)'

))->defaults(array(

    'action' => 'index',

    'controller' => 'line'

));



//线路列表

Route::set('line_list', 'lines/<params>', array(

    'params' => '[a-zA-Z0-9]+\/?((-0-0)?(-\d+){3}-[^-]+(-\d+)?-[\d+_]+(-\d+)?)?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'line'

));
//   线上活动 ，
Route::set('photoshow', 'photos(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => '(print|show)'

))->defaults(array(

    'action' => 'index',

    'controller' => 'photoshow'

));
//米淘详情

Route::set('mitao', 'mitaos(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => '(print|show)'

))->defaults(array(

    'action' => 'index',

    'controller' => 'mitao'

));
//米淘列表

Route::set('mitao_list', 'mitaos/<params>', array(

    'params' => '[a-zA-Z0-9]+\/?((-0-0)?(-\d+){3}-[^-]+(-\d+)?-[\d+_]+(-\d+)?)?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'mitao'

));
//米淘会员

Route::set('mitao_member', 'mitaos/member/index', array(

    'action' => '(print|show)'

))->defaults(array(

    'action' => 'member',

    'controller' => 'mitao'

));


//酒店详细

Route::set('hotel', 'hotels(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'hotel'

));



//酒店列表

Route::set('hotel_list', 'hotels/<params>', array(

    'params' => '[a-zA-Z0-9]+/?((-\d+){3}-[^-]+-[\d_]+(-\d+)?)?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'hotel'

));



//签证详细

Route::set('visa', 'visa(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'visa'

));



//签证列表

Route::set('visa_list', 'visa/<params>', array(

    'params' => '((?!search|create)[a-zA-Z0-9])+/?(-[\d_]+(-\d+){0,2})?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'visa'

));



//租车详情

Route::set('car', 'cars(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'car'

));



//租车列表

Route::set('car_list', 'cars/<params>', array(

    'params' => '[a-zA-Z0-9]+((-[\d_]+){4}+(-\d+)?+(?))?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'car'

));



//攻略详情

Route::set('article', 'raiders(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'article'

));
//notes游记 详情
//游记版块

Route::set('notes', 'notes(/<action>_<id>.html)', array(

    'id' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'notes'

));



//攻略列表

Route::set('article_list', 'raiders/<params>', array(

    'params' => '[a-zA-Z0-9]+/?(-[\d+_]+(-\d+)?)?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'article'

));



//添加问答

Route::set('question', 'questions/<action>', array(

    'action' => '\w+'

))->defaults(array(

    'action' => 'add',

    'controller' => 'question'

));

//问答列表

Route::set('question_list', 'questions', array(

))->defaults(array(

    'action' => 'index',

    'controller' => 'question'

));



//相册详情

Route::set('photo', 'photos(/<action>_<aid>(_<num>).html)', array(

    'aid' => '\d+',

    'num' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'photo'

));



//相册列表

Route::set('photo_list', 'photos/<params>', array(

    'params' => '[a-zA-Z0-9]+(-[\d+_]+(-\d+)?(-\d+)?)?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'photo'

));



//景点门票

Route::set('spot', 'spots(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'spot'

));



//景点列表

Route::set('spot_list', 'spots/<params>', array(

    'params' => '[a-zA-Z0-9]+/?((-\d+){2}-([^-]+)(-[\d+_]+)?(-\d+)?)?',

))->defaults(array(

    'action' => 'list',

    'controller' => 'spot'

));



//团购

Route::set('tuan', 'tuan(/<action>(_<aid>.html))', array(

    'aid' => '\d+',

    'action' => '(show|create)'

))->defaults(array(

    'action' => 'index',

    'controller' => 'tuan'

));

//团购搜索

Route::set('tuan_search', 'tuan/search')->defaults(array(

    'action' => 'search',

    'controller' => 'tuan'

));

//团购列表

Route::set('tuan_list', 'tuan/(<params>)',array(

    'params'=>'[a-zA-Z0-9]+/?(-\d(-[\d_]+)?(-\d+)?)?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'tuan'

));





//结伴

Route::set('jieban', 'jieban(/<action>_<aid>.html)', array(

    'aid' => '\d+',

    'action' => 'show'

))->defaults(array(

    'action' => 'index',

    'controller' => 'jieban'

));



//发布结伴

Route::set('jieban_add', 'jieban/<action>', array(

    'action' => 'add|ajax_get_more|ajax_check_msg'

))->defaults(array(

    'action' => 'index',

    'controller' => 'jieban'

));



//结伴列表

Route::set('jieban_list', 'jieban/<params>', array(

    'params' => '[a-zA-Z0-9]+/?(-\d+(-[\d_]){1,2}(-\d+)?)?'

))->defaults(array(

    'action' => 'list',

    'controller' => 'jieban'

));



//私人定制

Route::set('customize', 'customize(/<action>)', array(

    'action' => 'index|ajax_get_more|ajax_check_msg'

))->defaults(array(

    'action' => 'index',

    'controller' => 'customize'

));



//帮助

Route::set('help', 'help/<action>_<aid>.html', array(

    'aid' => '\d+',

    'action' => '(index|show)'

))->defaults(array(

    'action' => 'index',

    'controller' => 'help'

));



//服务选项

Route::set('servers','servers/index_<aid>.html',array(

    'aid'=>'\d+'

))->defaults(array(

    'action' => 'index',

    'controller' => 'server'

));



//全局搜索

Route::set('search', 'search')->defaults(array(

    'action' => 'index',

    'controller' => 'search'

));

//通用模块路由

include_once(APPPATH.'tyroute.php');

include_once(APPPATH.'userroute.php');



//目的地列表

Route::set('destlist', 'destination')->defaults(array(

    'action' => 'index',

    'controller' => 'destination'

));

//404错误页

Route::set('404', '404')->defaults(array(

    'action' => '404',

    'controller' => 'pub'

));


//单目的地主页

Route::set('desthome', '<pinyin>/des-<param>', array(

    'pinyin' => '[a-zA-Z0-9]+',
    'param' => '[a-zA-Z0-9]+'

))->defaults(array(

    'action' => 'main',

    'controller' => 'destination'

));
//单目的地 搜索页面

Route::set('destsearch', '<pinyin>/search-<param>', array(

    'pinyin' => '[a-zA-Z0-9]+',
    'param' => '[a-zA-Z0-9]+'

))->defaults(array(

    'action' => 'search',

    'controller' => 'destination'

));


//子站内容显示

Route::set('substation', '<pinyin>/<model>/show_<aid>.html', array(

    'pinyin' => '[a-zA-Z0-9]+',

    'model' => '[a-zA-Z0-9]+',

    'aid' => '\d+'

))->defaults(array(

    'action' => 'sub_station',

    'controller' => 'destination'

));

//错误页面

Route::set('error', 'error/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))

    ->defaults(array(

        'controller' => 'pub'

    ));

//默认

Route::set('default', '(<controller>(/<action>(/<params>)))', array(

    'params' => '.*'

))->defaults(array(

    'controller' => 'index',

    'action' => 'index',

));