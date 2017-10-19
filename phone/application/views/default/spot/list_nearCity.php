<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="telephone=no" name="format-detection" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>景区门票</title>
<link rel="stylesheet" type="text/css" href="/res/css/phone/global.css" />
<!-- css库 -->
<link rel="stylesheet" type="text/css" href="/res/css/phone/serch-phone.css" />
<!-- 布局版面 CSS 文件-->
<script src="/res/js/phone/jquery-1.8.3.min.js"></script><!-- jQuery库 -->
<script src="/res/js/phone/rosestrap-min.js"></script><!-- 常用jQuery库 -->
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=Nv7ePdYOGgT28WZqsGZOeBuH"></script>
{Common::js('jquery.min.js,amazeui.js,template.js')}
    {Common::js('layer/layer.m.js')}
<style>
.tuan_more {
	display: block;
	width: 100%;
	margin: 1.5rem 0;
	text-align: center;
}
.cursor {
	padding: 10px;
	border: 1px solid #666;
	cursor: pointer;
}
.show-more {
	width: 100%;
	text-align: center;
	height: 40px;
	line-height: 40px;
	margin: 10px 0;
	background: url(/res/images/phone/back3.png) no-repeat center 10px;
	margin: 0 auto;
	border-top: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
}
.show-more a {
	display: block;
	color: #666;
}
.on {
	background: #B5CE6B;
}
.cursor:hover {
}
</style>
</head><body style="background:#f0f0f0;">
<header>
  <div class="order-head1"><a class="back-home" href="/phone/spots/"></a>
    <input name="textfield" type="text" class="seach-input" id="textfield" value="目的地/景区/酒店">
  </div>
</header>
<section>
  <div class="hot-seach">
    <ul>
      {st:dest action="query" flag="dest" pid="0" typeid="$typeid" row="99"}
        <?php $all  = count($data);?>
      {loop $data $dest}
      <li name="mdd" class="{if $pinyin==$dest['pinyin']}current{/if}"><a href="/phone/spot/nearCity?pinyin={$dest['pinyin']}" data-id="{$dest['id']}" style='{if $pinyin==$dest['pinyin']}color:#2577e3;{/if}'>{$dest['kindname']}</a></li>
      {/loop}
        <!-----07.21 fengjishu jia  start ---------->
        {if $all==1||$all==6}
         <?php for($i=0;$i<4;$i++){?>
             <li class="<?php echo $i;?>"><a href="javascript;" data-id="" ></a></li>
         <?php }?>
        {elseif $all==2||$all==7}
        <?php for($i=0;$i<3;$i++){?>
            <li class="<?php echo $i;?>"><a href="javascript;" data-id="" ></a></li>
        <?php }?>
        {elseif $all==3||$all==8}
        <?php for($i=0;$i<2;$i++){?>
            <li class="<?php echo $i;?>"><a href="javascript;" data-id="" ></a></li>
        <?php }?>
        {elseif $all==4||$all==9}
        <?php for($i=0;$i<1;$i++){?>
            <li class="<?php echo $i;?>"><a href="javascript;" data-id="" ></a></li>
        <?php }?>
        {/if}
        <!-----07.21 fengjishu jia  end---------->
      {/st}
      <div class="cl"></div>
    </ul>
  </div>
  <div class="classify">
    <div class="nav">
      <ul class="tabs"  id="tabs002">
        <li class="{if $themeid}current{/if}"><a href="javascript:" id="change_theme">{$theme}<i></i></a></li>
        <li class="{if $greatid}current{/if}"><a href="javascript:" id="change_greate">{$grea}<i></i></a></li>
        <div class="clearfix"></div>
      </ul>
    </div>
    <div class="tab-content" id="tab-content002">
      <div class="tab-pane">
        <div class="search-down">
          <ul>
            {loop $spat1 $sp}
            <li name="theme" style="cursor: pointer" data-id="{$sp['id']}" data-title="{$sp['attrname']}"><a href="/phone/spot/nearCity?themeid={$sp['id']}">{$sp['attrname']}</a></li>
            {/loop}
          </ul>
        </div>
      </div>
      <div class="tab-pane">
        <div class="search-down">
          <ul>
            {loop $spat2 $sp}
            <li name="grate" style="cursor: pointer" data-id="{$sp['id']}" data-title="{$sp['attrname']}"><a href="/phone/spot/nearCity?greatid={$sp['id']}">{$sp['attrname']}</a></li>
            {/loop}
          </ul>
        </div>
      </div>
    </div>
  </div>
  
  <!-- 左图右字列表开始 -->
  <div class="listLR-item" style="z-index: 1000">
    <ul id="result_list">
    </ul>
    <div class="show-more" id="btn_getmore"><a href="javascript:" >加载更多</a></div>
    <!--左图右字列表结束 --> 
  </div>
  <div class="no-content" id="no-content" style="display: none; text-align:center;"> <img src="{$GLOBALS['cfg_public_url']}images/nocon.png" width="80%"/>
    <p style="text-align:center; padding:10px;">啊哦，暂时没有相关信息</p>
  </div>
  <input type="hidden" id="keyword" value="{$keyword}"/>
  <input type="hidden" id="pinyin" value="{$pinyin}" name="{$desid}"/>
  <input type="hidden" id="attrid" value="{if $themeid}{$themeid}{elseif $greatid}{$greatid}{elseif $attrId}{$attrId}{/if}"/>
  <input type="hidden" id="page" value="1"/>
</section>
<div id ="allmap"></div>
<script type="text/html" id="tpl_hotel_item">
    {{each list as value i}}
    <a target="_blank" href="{{value.url}}" title="">
    <li>
        <div class="img-item"><img src="{{value.litpic}}" alt="{{value.title}}"></div>
        <div class="txt-item">
            <h2 class="title-item"><span>{{value.title}}</span></h2>
            <div class="about-item">
                <dfn class="total-font" id="total_item_1">{if value.price}￥{{value.price}}{else}电询{/if}</dfn> <del>¥{{value.sellprice}}</del> <span>已售{{value.sellnum}}</span>
            </div>
            <div class="address"  data-lng="{{value.lng}}" data-lat="{{value.lat}}">{{value.kindlist}}&nbsp;&nbsp;<span></span></div>
        </div>
        <div class="cl"></div>
    </li>
    </a>
    {{/each}}
</script> 
<script type="text/javascript">
$(document).ready(function(){
    $(function () {
        //获取更新数据
        $('#btn_getmore').click(function(){
            get_data();
        });
        //第一次加载数据
        get_data();
        allmap();
    });

    //获取list地址
    function get_url() {
        //获取选中的目的地
        var pinyin = $("#pinyin").val();
        var url = pinyin;
        //获取priceid
        //var priceid = $("#priceid").val();
        var priceid =0;
        //获取选中的属性
        var attrid =$("#attrid").val();
        //排序规则
        //var sorttype = $("#sorttype").val();
        var  sorttype  = 0;//1是特价排序 2 价格  3是销量 4是人气 5是满意度
        //搜索名称
        var keyword = $('#keyword').val();
        //
        if(pinyin=='shijiazhuang'){
            var sjz = '';
        }else{
           var sjz    = 45;
        }
        //当前页 page
        var page = $("#page").val();
        keyword = keyword == '' ? 0 : keyword;
        url += '-' + priceid + '-' + sorttype + '-0-' + attrid + '-' + page + '?keyword=' + encodeURIComponent(keyword)+'&sjz='+sjz;
        return url;
    }

    //ajax获取数据
    var contentNum = 0;
    function get_data() {
        var paramUrl = get_url();
        var url = '/phone/spot/ajax_spot_more/'+paramUrl;
        layer.open({
            type: 2,
            content: '',
            time: 20
        });
        $.getJSON(url, {}, function (data) {
            if (data.list.length > 0) {
                var itemHtml = template('tpl_hotel_item', data);
                $("#result_list").append(itemHtml);
                contentNum++;
            }
            //设置分页
            if (data.page != -1) {
                $("#page").val(data.page);
            } else {
                $("#btn_getmore").hide();
            }
            //设置内内容显示
            if (contentNum == 0) {
                $('#list-content').hide();
                $("#no-content").show();
            }
            allmap();
            layer.closeAll();
        });
    }
    $("#textfield").click(function(){
        window.open("/phone/spot/search");
    });
    //属性点击
    $("li[name=theme]").click(function(){

        var attrid = $(this).attr('data-id');
        var url = '/phone/spot/nearCity?themeid='+attrid;
        window.location.href = url;
    });
    $("li[name=mdd]").click(function(){
        $(this).siblings("li[name=mdd]").removeClass("on");
        $(this).addClass("on");
    });
    //等级点击
    $("li[name=grate]").click(function(){

        var attrid = $(this).attr('data-id');
        var url = '/phone/spot/nearCity?greatid='+attrid;
        window.location.href = url;
    });
    
});
</script>
<script>
//导航选项卡
    function tabs(tabTit,tabCon){
		    mtop = $(".hot-seach").outerHeight();
			$("body").css({overflow:"hidden"});
			lmtop = mtop + 85;
			tmtop = mtop + 86;
			mtop = mtop + 45;
			$(".classify").css("top",mtop);				
			$(".listLR-item").css("top",lmtop);	
			$(".tab-content").css("top",tmtop);	
			$(".no-content").css("top",lmtop);
        $(tabTit).children().click(function(){			
			if($(this).hasClass("current"))			{
            $(this).toggleClass("current").siblings().removeClass("current");	
			$(this).children().children().toggleClass("on");
            $(".classify").css("z-index",1001);
			$(tabCon).toggle();
			var index = $(tabTit).children().index(this);
			$(tabCon).children().eq(index).addClass("active").siblings().removeClass("active");	
			}
			else
			{
			$(this).addClass("current").siblings().removeClass("current");	
            $(this).children().children().addClass("on");
            $(".classify").css("z-index",1001);
			$(tabCon).show();
			var index = $(tabTit).children().index(this);
			$(tabCon).children().eq(index).addClass("active").siblings().removeClass("active");	
			}            	
    	   });
		$(tabCon).children().children().click(function(){
            $(".classify").css("z-index",999);
			$(tabCon).hide();
			$(tabTit).children().children().children().removeClass("on");
			$(tabTit).children().removeClass("current");
	    });

	};
    tabs("#tabs002","#tab-content002");
</script>
</body>
</html>
<script type="text/javascript">
    // 百度地图API功能
    function allmap() {
        var map = new BMap.Map("allmap");
        var point = new BMap.Point(116.331398, 39.897445);
        map.centerAndZoom(point, 12);
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function (r) {
            if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                var mk = new BMap.Marker(r.point);
                map.addOverlay(mk);

                map.panTo(r.point);//当前位置的经纬度；
                var pointB = new BMap.Point(r.point.lng, r.point.lat);
                $('.address').each(function () {
                    var mdd_lng = $(this).attr('data-lng');
                    var mdd_lat = $(this).attr('data-lat');
                    var pointA = new BMap.Point(mdd_lng, mdd_lat);
                    var distance = (map.getDistance(pointA, pointB) * 1 / 1000).toFixed(0);
                    $(this).find('span').text(distance + " km");

                });
            }
            else {
                alert('failed' + this.getStatus());
            }
        }, {enableHighAccuracy: true})
        //关于状态码
        //BMAP_STATUS_SUCCESS	检索成功。对应数值“0”。
        //BMAP_STATUS_CITY_LIST	城市列表。对应数值“1”。
        //BMAP_STATUS_UNKNOWN_LOCATION	位置结果未知。对应数值“2”。
        //BMAP_STATUS_UNKNOWN_ROUTE	导航结果未知。对应数值“3”。
        //BMAP_STATUS_INVALID_KEY	非法密钥。对应数值“4”。
        //BMAP_STATUS_INVALID_REQUEST	非法请求。对应数值“5”。
        //BMAP_STATUS_PERMISSION_DENIED	没有权限。对应数值“6”。(自 1.1 新增)
        //BMAP_STATUS_SERVICE_UNAVAILABLE	服务不可用。对应数值“7”。(自 1.1 新增)
        //BMAP_STATUS_TIMEOUT	超时。对应数值“8”。(自 1.1 新增)
    }
</script>