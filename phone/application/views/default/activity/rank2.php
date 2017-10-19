<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="telephone=no" name="format-detection" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>排行榜</title>
  {Common::css('activity_2/bootstrap-min.css,activity_2/public.css,activity_2/index.css,activity_2/activity-Ranking.css,activity_2/activity-Join.css')}
  <script src="/phone/public/js/activity_2/jquery-1.11.3.min.js"></script><!-- jQuery库 -->
  <script src="/phone/public/js/activity_2/activity-Join.js"></script>
  <script type="text/javascript">
    var SITEURL = "{URL::site()}";
  </script>
  <script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "//hm.baidu.com/hm.js?f968b43cd6404299f54d25d87c3bbe3a";
      var s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(hm, s);
    })();
  </script>

</head>
<body>
<header><img src="/phone/public/images/activity_2/header.jpg" width="100%"></header>
<section>
  <div class="container">
    <div class="ranking-top">
      <ul>
        <li>
          <div class="ran-box"><span>参与人数</span><span>{$info['part_num']}</span></div>
        </li>
        <li>
          <div class="ran-box"><span>红叶数</span><span>{$info['sum']}</span></div>
        </li>
        <li>
          <div class="ran-box"><span>访问次数</span><span>{$info['show_num']}</span></div>
        </li>
        <div class="clearfix"></div>
      </ul>
    </div>
  </div>
</section>
<section>
  <div class="container">
    <div class="input-group">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="95%"><span class="input-group">
            <input class="input-text" placeholder="输入编号进行搜索" id ="sousuo" name="sousuo" type="text"  onBlur="if (value ==''){value='输入编号或微信昵称进行搜索'}">
            </span></td>
          <td width="5%"><span class="input-btn">
            <button class="btn" type="button" id ='serach'>搜索</button>
            </span></td>
        </tr>
      </table>
      <div class="clearfix"></div>
    </div>
  </div>
</section>
<section>
  <div class="container">
    <div class="ranking-list">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-Border">
        <tr>
          <th width="11%">排名</th>
          <th width="11%">编号</th>
          <th width="30%">微信昵称</th>
          <th width="15%">红叶数</th>
          <th width="33%">集齐时间</th>
        </tr>
        <?php $p = ($page-1)*$pagesize;?>
        {loop $arr $k $v}
        <tr>
          <td><?php echo $s =$p+$k*1+1;?></td>
          <td>{$v['id']}</td>
          <td><div class="table-txt"><a href="/phone/activity/show_{$v['id']}.html">{if empty($v['nickname'])}会员{else}{$v['nickname']}{/if}</a></div></td>
          <td>{$v['getpay']}</td>
          <td><div class="table-txt" {if $v['exchange_time']!='0000-00-00 00:00:00'}style="margin-top:-7px;"{/if}>{if $v['exchange_time']=='0000-00-00 00:00:00'}未达到条件{else}{$v['exchange_time']}{/if}</div></td>
        </tr>
        {/loop}
        <tr>
          <td colspan="5"><!-- 分页开始 -->
            
            <div id="Pagination">
              <a class="prev" href="{if $page!=1}/phone/activity/rank?page={$page-1}{else}javascript:{/if}">上一页</a>
              <a class="current">{$page}/{$totalpagesize}</a>
              <a class="next" href="{if $page==$totalpagesize}javascript:{else}/phone/activity/rank?page={$page+1}{/if}">下一页</a>
              <input type="number" name="jump" id="jump" style="width: 30px;height: 24px">
              <a href="javascript:" onclick="jump(this)">跳转</a>
            </div>
            
            <!-- 分页结束 -->
          </td>
        </tr>
      </table>
    </div>
  </div>
</section>
<div class="nobox"></div>
<div class="modal" id="DialogDiv4">
  <div class="modal-backdrop"></div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">

        <div class="MsgBtns">

        </div>
      </div>
    </div>
  </div>
</div>
<div class="footer">
  <ul>
    <li><a href="#"  onclick="window.location='/phone/activity/index'"><span class="ui-icon ico00"></span><span class="ui-txt">活动首页</span></a></li>
    <li><a href="#" onclick="window.location='/phone/activity/show'"><span class="ui-icon ico01"></span><span class="ui-txt">我的</span></a></li>
    <li><a href="#" class="last-child" onclick="window.location='/phone/activity/rank'"><span class="ui-icon ico02"></span><span class="ui-txt">排行榜</span></a></li>
  </ul>
</div>


<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({
    debug: false,
    appId: '{$signPackage["appId"]}',
    timestamp: "{$signPackage['timestamp']}",
    nonceStr: '{$signPackage["nonceStr"]}',
    signature:'{$signPackage["signature"]}',
    jsApiList:['onMenuShareTimeline','onMenuShareAppMessage']
    // 所有要调用的 API 都要加到这个列表中
  });
  wx.ready(function(){
    wx.onMenuShareTimeline({
      title: "集18片“红叶”秋山免费游！",// 111
      desc: "集18片“红叶”秋山免费游！", // 分享描述
      link: "http://www.aitto.net/phone/activity/index", // ''
      imgUrl: 'http://www.aitto.net/phone/public/images/activity/share.jpg', // 分享图标
      success: function () {

      },
      cancel: function () {
        // 用户取消分享后执行的回调函数

      }
    });
    wx.onMenuShareAppMessage({
      title: "集红叶秋山免费游", // 分享标题
      desc: "河北旅游网集18片“红叶”免费游秋山活动，正在进行中!", // 分享描述
      link: "http://www.aitto.net/phone/activity/index", // 分享链接
      imgUrl: 'http://www.aitto.net/phone/public/images/activity/share.jpg', // 分享图标
      type: 'link', // 分享类型,music、video或link，不填默认为link
      dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
      success: function () {
        // 用户确认分享后执行的回调函数
      },
      cancel: function () {
        // 用户取消分享后执行的回调函数
      }
    });


  })
</script>
<script>
  function jump(th){
    var totalpage  =parseInt("{$totalpagesize}");
    var first      =0 ;
    var jumppage   = parseInt($(th).siblings('input[name=jump]').val());
    if(jumppage>first&&jumppage<=totalpage){
      window.location.href = "/phone/activity/rank?page="+jumppage
    }

  }
  $("#serach").click(function(){
    var keyword = $("#sousuo").val();
    if(keyword==''){
      return false;
    }
    // var  reg       = /(1[3-9]\d{9}$)/;
    $.ajax({
      type: 'POST',
      url : '/phone/activity/check_rank',
      data:{keyword:keyword},
      dataType:'json',
      success: function (data) {
        if(data.status=='-1'){
          var thisObjID  ="#DialogDiv4";
          $(thisObjID).addClass('in').children().children().children('.modal-body').text(data.msg);
          var thisObjcon = $(thisObjID).children(".modal-dialog");
          thisObjcon.css({
            'top': "35%",
            'left': "10%"
          });
          setTimeout(function(){ $(thisObjID).removeClass('in')},1000);
        }else{
          var  url = "/phone/activity/show_"+data.status+".html";

          window.location.href  = url ;
        }
      }
    })


  })
  $("#sousuo").keydown(function(){
    if(event.keyCode==13){
      $("#serach").click();
    }
  })
</script>
</body>
</html>