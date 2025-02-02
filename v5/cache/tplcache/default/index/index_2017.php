<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $seoinfo['seotitle'];?>-<?php echo $webname;?></title>
  <?php if($seoinfo['keyword']) { ?>
  <meta name="keywords" content="<?php echo $seoinfo['keyword'];?>"/>
  <?php } ?>
  <?php if($seoinfo['description']) { ?>
  <meta name="description" content="<?php echo $seoinfo['description'];?>"/>
  <?php } ?>
  <?php echo Common::css('common/bootstrap-min.css,common/public.css,mycss/index/index.css');?>
  <?php echo Common::js('common/jquery-1.11.3.min.js,common/bootsAitto-min.js,myjs/index/bootstrap-index.js');?>
  <?php echo $GLOBALS['cfg_tongjicode'];?>
</head>
<body>
<!-- 登陆代码 开始 -->
<?php echo Request::factory("pub/header")->execute()->body(); ?>
<!-- 登陆代码 结束 -->
<div class="notebox"></div>
<!-- 幻灯广告 开始 -->
<div class="ctd-head-box carousel-items" data-slide=""><!-- slide值有 空:“” 自动:"auto" 左右切换:"x"-->
  <?php require_once ("/home/wwwroot/www.aitto.net/taglib/ad.php");$ad_tag = new Taglib_Ad();if (method_exists($ad_tag, 'getad')) {$data = $ad_tag->getad(array('action'=>'getad','name'=>'s_index_2','pc'=>'1',));}?>
  <ul class="slideImg-hd">
    <?php $src  =$data['aditems']; ?>
    <?php //var_dump($src);?>
    <?php for($i=0;$i<count($src);$i++):?>
    <li <?php if($i>0) { ?>style="display:none"<?php } ?>
 onclick="window.location.href='<?php echo $src[$i]['adlink'];?>'"><a href="<?php echo $src[$i]['adlink'];?>"><img src="<?php echo $src[$i]['adsrc'];?>" alt="" width="100%" ></a></li>
    <?php endfor?>
  </ul>
  
  <a href="javascript:;" class="ctrl-slide Page-prev">上一张</a> <a href="javascript:;" class="ctrl-slide Page-next">下一张</a> 
  <!-- 幻灯导航 -->
  <div class="slideNav-bd">
    <ul class="nav-bar">
      <?php $src  = $data['aditems'];?>
      <?php for($i=0;$i<count($src);$i++):?>
      <li <?php if($i==0) { ?>class="current"<?php } ?>
><em><?php echo $i+1;?></em></li>
       
      <?php endfor?>
    </ul>
  </div>
  <!-- 搜索条 开始 -->
  <div class="search-container">
    <div class="search-group">
      <div class="searchtab" id="_j_index_search_tab">
        <ul class="clearfix">
          <li data-index="3" class="tab-selected"><i onclick="change_css(this)" class="change_i"></i>攻略</li>
          <li data-index="4" ><i onclick="change_css(this)" class="change_i"></i>游记</li>
          <li data-index="2" ><i onclick="change_css(this)" class="change_i"></i>门票</li>
          <div class="clearfix"></div>
        </ul>
      </div>
      <script>
        function change_css(th){
         $(th).parent().addClass('tab-selected').siblings('li').removeClass('tab-selected');
        }
      </script>
      <!-- 全部 begin -->
      <div class="searchbar">
        <div class="search-wrapper">
          <div class="search-input">
            <input name="q" placeholder="输入目的/景区搜索" id="-j-index-search-input-all"  type="text">
          </div>
        </div>
        <div class="search-button"> <a role="button" href="javascript:"><i class="icon-search"></i></a> </div>
      </div>
      <!-- 全部 end -->
      <div class="clearfix"></div>
    </div>
  </div>
  <!-- 搜索条 结束 --> 
</div>
<!-- 幻灯广告 结束 --> 
<!-- 广告导航 开始-->
<div class="advbox">
  <ul>
    <li onclick="window.location.href='/destination/'" style="cursor: pointer"><img src="/res/images/index/ad_01.jpg"  /></li>
    <li onclick="window.location.href='/lines/self'" style="cursor: pointer"><img src="/res/images/index/ad_02.jpg"  /></li>
    <li onclick="window.location.href='/notes'" style="cursor: pointer"><img src="/res/images/index/ad_03.jpg"  /></li>
    <li onclick="window.location.href='/raiders'" style="cursor: pointer"><img src="/res/images/index/ad_04.jpg"  /></li>
    <li onclick="window.location.href='/spots'" style="cursor: pointer"><img src="/res/images/index/ad_05.jpg"  /></li>
    <div class="clearfix"></div>
  </ul>
</div>
<!-- 广告导航 结束 -->
<div class="container ">
  <section>
    <div class="ctd-main-city clearfix">
      <div class="J-con-tit">
        <h2>带你游<span>河</span>北<span class="small-tit">了解河北从这里开始起步</span></h2>
        <div class="more"><a href="/destination">查看更多内容</a></div>
      </div>
      <div class="ctd-main-bd clearfix">
        <div class="sid-city">
          <div class="cityNav">
            <div class="cityNav-tit">
              <h4>城市</h4>
            </div>
            <div class="city">
              <ul>
                <?php require_once ("/home/wwwroot/www.aitto.net/taglib/dest.php");$dest_tag = new Taglib_Dest();if (method_exists($dest_tag, 'query')) {$index_mdd = $dest_tag->query(array('action'=>'query','flag'=>'index_nav','row'=>'11','return'=>'index_mdd',));}?>
                <?php //var_dump($index_mdd);exit?>
                <?php $n=1; if(is_array($index_mdd)) { foreach($index_mdd as $mdd) { ?>
                <li ><a href="<?php echo $cmsurl;?><?php echo $mdd['pinyin'];?>/?param=survey"><?php echo $mdd['kindname'];?></a></li>
                <?php $n++;}unset($n); } ?>
                
              </ul>
            </div>
          </div>
          <br/>
          <div class="bd-sid-city">
<!--            <h3>带您游魅力河北</h3>-->
<!--            <p>吃—住—行—玩</p-->
            <img src="/res/images/index/wangmushang.jpg" alt="王母山景区"  onclick="location.href='/spots/show_42.html'" style="cursor: pointer"/>
          </div>
        </div>
        <ul class="mod-proList">
          <?php require_once ("/home/wwwroot/www.aitto.net/taglib/dest.php");$dest_tag = new Taglib_Dest();if (method_exists($dest_tag, 'query')) {$index_mdd = $dest_tag->query(array('action'=>'query','flag'=>'index_nav','row'=>'6','return'=>'index_mdd',));}?>
          <?php $n=1; if(is_array($index_mdd)) { foreach($index_mdd as $mdds) { ?>
          <li style="cursor: pointer;" onclick="window.location.href='<?php echo $cmsurl;?><?php echo $mdds['pinyin'];?>/?param=survey'">
            <div class="mak-box"> <img src="<?php echo Common::img($mdds['litpic']);?>" alt="" />
              <div class="msk-tit">
                <h3><?php echo $mdds['kindname'];?></h3>
                <p>
                 <?php require_once ("/home/wwwroot/www.aitto.net/taglib/dest.php");$dest_tag = new Taglib_Dest();if (method_exists($dest_tag, 'query')) {$tags = $dest_tag->query(array('action'=>'query','flag'=>'gettag','row'=>'3','destid'=>$mdds[id],'return'=>'tags',));}?>
                  <?php $n=1; if(is_array($tags)) { foreach($tags as $tag) { ?>
                  <span><?php echo $tag['tag_name'];?></span>
                  <?php $n++;}unset($n); } ?>
                 
                </p>
                <a href="<?php echo $cmsurl;?><?php echo $mdds['pinyin'];?>/?param=survey" class="more">查看更多</a></div>
            </div>
          </li>
          <?php $n++;}unset($n); } ?>
          
        </ul>
      </div>
    </div>
  </section>
  <!-------------畅游 攻略 开始----------------->
  <section>
    <div class="ctd-main-Trategy clearfix">
      <div class="J-con-tit  clearfix">
        <h2>畅游<span>攻</span>略</h2>
        <ul class="J-con-nav" data-toggle="tabs" data-target="tab-content1">
          <?php $n=1; if(is_array($index_mdd)) { foreach($index_mdd as $k => $mudd) { ?>
          <?php if($k<4) { ?>
          <li <?php if($k<1) { ?>class="current"<?php } ?>
 data-panel = "<?php echo $k+1;?>" onclick="tab_raiders(this)"><a href="javascript:"><?php echo $mudd['kindname'];?></a></li>
          <?php } ?>
          <?php $n++;}unset($n); } ?>
        </ul>
        <div class="more"><a href="/raiders/all">查看更多内容</a></div>
      </div>
      <?php $n=1; if(is_array($index_mdd)) { foreach($index_mdd as $ke => $mudds) { ?>
      <?php if($ke<4) { ?>
      <div class="ctd-Trategy-List clearfix" <?php if($ke>0) { ?>style="display:none"<?php } ?>
 id="panel-<?php echo $ke+1;?>">
        <ul>
          <?php require_once ("/home/wwwroot/www.aitto.net/taglib/article.php");$article_tag = new Taglib_Article();if (method_exists($article_tag, 'query')) {$article = $article_tag->query(array('action'=>'query','flag'=>'mdd','row'=>'3','destid'=>$mudds[id],'return'=>'article',));}?>
          <?php $n=1; if(is_array($article)) { foreach($article as $ar) { ?>
          <li ><?php //var_dump($ar['piclist']);exit; ?>
            <div class="ctd-inner">
              <div class="J-img" onclick="window.location.href='<?php echo $ar['url'];?>'" style="cursor: pointer"><img src="<?php echo Common::img($ar['piclist']['1']['0']);?>" height="100%">
                <div class="J-title">
                  <h2><?php echo $ar['title'];?></h2>
                  <p><?php echo $ar['jiriyou'];?></p>
                </div>
              </div>
              <div class="J-shots">
                <?php //var_dump($ar['piclist']);?>
                <?php $n=1; if(is_array($ar['piclist'])) { foreach($ar['piclist'] as $pic_k => $pic) { ?>
                <?php if($pic_k>1&&$pic_k<5) { ?>
                <span class="bshape" onclick="window.location.href='<?php echo $ar['url'];?>'" style="cursor: pointer">
                  <img src="<?php echo Common::img($pic['0']);?>" height="64">
                </span>
                <?php } ?>
                <?php $n++;}unset($n); } ?>
                <span class="bshape last-child">
                  <a href="<?php echo $ar['url'];?>">查看<br/>
                详细内容</a>
                </span>
              </div>
              <div class="clearfix"></div>
            </div>
          </li>
          <?php $n++;}unset($n); } ?>
          
        </ul>
      </div>
      <?php } ?>
      <?php $n++;}unset($n); } ?>
    </div>
  </section>
  <script>
    //  攻略 的切换 js
   function tab_raiders(th){
      var panel = $(th).attr('data-panel');
      $(".ctd-Trategy-List").css({
       'display' : 'none'
     })
       $("#panel-"+panel).css({
         'display' : 'block'
       })
   }
  </script>
  <!--------精品游记开始 ----------------------->
  <section>
    <div class="ctd-main-travels clearfix">
      <div class="J-con-tit">
        <h2>精品<span>游</span>记<span class="small-tit">记录下美好瞬间,写下美好回忆</span></h2>
        <div class="more"><a href="/notes/list">查看更多内容</a></div>
      </div>
      <div class="ctd-travels">
        <?php require_once ("/home/wwwroot/www.aitto.net/taglib/notes.php");$notes_tag = new Taglib_Notes();if (method_exists($notes_tag, 'query')) {$notes = $notes_tag->query(array('action'=>'query','flag'=>'hot','row'=>'3','return'=>'notes',));}?>
        <ul>
          <?php $n=1; if(is_array($notes)) { foreach($notes as $note) { ?>
          <li>
            <a href="<?php echo $note['url'];?>">
            <div class="J-inner">
              <div class="J-img"><img src="<?php echo Common::img($note['litpic']);?>" width="100%" height="100%">
<!--                <div class="J-day"><span class="num"></span><span class="day"></span></div>-->
                <div class="user-name"><?php echo $note['member']['nickname'];?></div>
                <div class="user-img"><img src="<?php if(empty($note['member']['litpic'])) { ?><?php echo Common::nopic();?><?php } else { ?><?php echo $note['member']['litpic'];?><?php } ?>
"></div>
              </div>
              <div class="J-tit">
                <h3><?php echo $note['title'];?> </h3>
                <p></p>
              </div>
              <div class="J-report"><span class="data">发表于<?php echo date('Y-m-d',$note['addtime']);?></span><span class="icon-view"><i></i>评论<?php echo $note['commentnum'];?></span><span class="icon-comment"><i></i>浏览<?php echo $note['shownum'];?></span></div>
            </div>
            </a>
          </li>
          <?php $n++;}unset($n); } ?>
        </ul>
        
      </div>
    </div>
  </section>
  <!--------精品游记完 -------------->
  <!--------自驾游  ---------------->
  <section>
    <div class="ctd-main-SelfDrive clearfix">
      <div class="J-con-tit">
        <h2>自驾<span>游</span><span class="small-tit">跟着领队领略你从没看到过的风景</span></h2>
        <div class="more"><a href="/lines/self/all">查看更多内容</a></div>
      </div>
      <div class="mod-proList">
        <?php require_once ("/home/wwwroot/www.aitto.net/taglib/line.php");$line_tag = new Taglib_Line();if (method_exists($line_tag, 'query')) {$line = $line_tag->query(array('action'=>'query','flag'=>'new','row'=>'5','return'=>'line',));}?>
        <ul class="clearfix">
          <li class="sbig">
            <div class="mod-pic"> <a href="<?php echo $line['0']['url'];?>" title="" target="_blank"><img src="<?php echo Common::img($line['0']['piclist']['1']['0']);?>" alt="" /></a>
            
               <div class="msk-but">
                  <h3>特别推荐</h3>
                </div>
            
              <div class="msk-tit">
                <div class="msk-doc">
                  <?php  $time = strtotime($line['0']['linedate']);?>
                  <span class="data"><?php echo date("m/d",$time);?></span><span class="md"> [<?php echo $line['0']['selfDriMdd'];?>]</span><span class="price"><?php echo $line['0']['storeprice'];?>元</span>
                </div>
                <h3><?php echo $line['0']['title'];?></h3>
                <p class="sub-title"><?php echo $line['0']['features'];?></p>
              </div>
            </div>
          </li>
          <?php $n=1; if(is_array($line)) { foreach($line as $l => $li) { ?>
          <?php if($l>0) { ?>
          <li>
            <div class="mod-pic"> <a href="/lines/self_show_<?php echo $li['aid'];?>.html" title="" target="_blank"><img src="<?php echo Common::img($li['piclist']['1']['0']);?>" alt="" /></a>
              <div class="msk-tit">
                <div class="msk-doc">
                  <?php $times = strtotime($li['linedate'])?>
                  <span class="data"><?php echo date("m/d",$times);?></span><span class="md"> [<?php echo $li['selfDriMdd'];?>]</span><span class="price"><?php echo $li['storeprice'];?>元</span>
                </div>
                <h3><?php echo $li['title'];?></h3>
              </div>
            </div>
          </li>
          <?php } ?>
          <?php $n++;}unset($n); } ?>
        </ul>
        
      </div>
    </div>
  </section>
  <!--------自驾游完------------------>
  <!--------推荐景点------------------->
  <section>
    <div class="ctd-main-ticket clearfix">
      <div class="J-con-tit  clearfix">
        <h2>推荐<span>景</span>点</h2>
        <ul class="J-con-nav" data-toggle="tabs" data-target="tab-content1">
          <?php require_once ("/home/wwwroot/www.aitto.net/taglib/dest.php");$dest_tag = new Taglib_Dest();if (method_exists($dest_tag, 'query')) {$spot_mdd = $dest_tag->query(array('action'=>'query','flag'=>'index_nav','row'=>'4','return'=>'spot_mdd',));}?>
          <?php $n=1; if(is_array($spot_mdd)) { foreach($spot_mdd as $g => $s_mdd) { ?>
          <li <?php if($g<1) { ?>class="current"<?php } ?>
 data-panel = "<?php echo $g+1;?>" onclick="tab_spots(this)"><a href="javascript:void(0)"><?php echo $s_mdd['kindname'];?></a></li>
          <?php $n++;}unset($n); } ?>
          
        </ul>
        <div class="more"><a href="/spots/all">查看更多内容</a></div>
      </div>
      <div class="ctd-ticket-bd clearfix">
        <div class="sid-city">
          <div class="cityNav">
            <div class="cityNav-tit">
              <h4>主题</h4>
            </div>
            <div class="city">
              <ul>
                <?php require_once ("/home/wwwroot/www.aitto.net/taglib/attr.php");$attr_tag = new Taglib_Attr();if (method_exists($attr_tag, 'query')) {$attrlist = $attr_tag->query(array('action'=>'query','flag'=>'childitem','typeid'=>'5','groupid'=>'13','row'=>'8','return'=>'attrlist',));}?>
                <?php $n=1; if(is_array($attrlist)) { foreach($attrlist as $k => $attr) { ?>
                <li><a href="/spots/all-0-0-<?php echo $attr['id'];?>-1"><?php echo $attr['attrname'];?></a></li>
                <?php $n++;}unset($n); } ?>
                
              </ul>
            </div>
          </div>
        </div>
        <?php $n=1; if(is_array($spot_mdd)) { foreach($spot_mdd as $gs => $s_mdds) { ?>
        <?php if($gs<4) { ?>
        <?php require_once ("/home/wwwroot/www.aitto.net/taglib/spot.php");$spot_tag = new Taglib_Spot();if (method_exists($spot_tag, 'query')) {$spotbymdd = $spot_tag->query(array('action'=>'query','flag'=>'mdd','row'=>'3','destid'=>$s_mdds[id],'return'=>'spotbymdd',));}?>
        <?php if(!empty($spotbymdd)) { ?>
        <div class="ctd-tickets" id="spot_<?php echo $gs+1;?>" <?php if($gs>0) { ?>style="display:none"<?php } ?>
>
          <ul>
            <?php $n=1; if(is_array($spotbymdd)) { foreach($spotbymdd as $spot) { ?>
            <li onclick="location.href='<?php echo $spot['url'];?>'" style="cursor: pointer">
              <div class="J-inner">
                <div class="J-img"><img src="<?php echo $spot['litpic'];?>">
                  <div class="J-magazine">
                    <p><?php echo $spot['mdd'];?></p>
                  </div>
                </div>
                <div class="J-price">
                  <p class="price"><?php if(!empty($spot['price'])) { ?>&yen;<i><?php echo $spot['price'];?></i><?php } else { ?>电询<?php } ?>
</p>
                  <div class="ticket-tit">
                    <h2><a href="javascript:;"><?php echo $spot['title'];?></a></h2>
                    <h3><?php if($spot['grade']) { ?><?php echo $spot['grade'];?>级景区<?php } ?>
</h3>
                  </div>
                </div>
              </div>
            </li>
            <?php $n++;}unset($n); } ?>
          </ul>
        </div>
        <?php } ?>
        
        <?php } ?>
        <?php $n++;}unset($n); } ?>
      </div>
    </div>
  </section>
  <!--------推荐景点 完------------------->
  <script>
    //  景点 的切换 js
    function tab_spots(th){
      var panel = $(th).attr('data-panel');
      $(".ctd-tickets").css({
        'display' : 'none'
      })
      $("#spot_"+panel).css({
        'display' : 'block'
      })
    }
  </script>
</div>
<?php echo Request::factory("pub/footer")->execute()->body(); ?>
</body>
</html>
<script>
  $("#-j-index-search-input-all").keydown(function(e){
    if(e.keyCode==13){
      $('.search-button').click();
    }
  });
  $('.search-button').click(function(){
    var keyword = $('#-j-index-search-input-all').val();
    if(keyword == ''){
      alert("请输入内容");
      $("#-j-index-search-input-all").focus();
      return false;
    }
    var  selected_li  = $('.tab-selected').attr('data-index');
    var  durl ;
    switch (selected_li){
      case '1' : '目的地';break;
      case '2' : durl ='spots';break;
      case '3' : durl ='raiders';break;
      case '4': durl ='notes';break;
    }
    // alert(durl);
   // return false;
      var url = "<?php echo $GLOBALS['cfg_basehost'];?>/"+durl+'/all?keyword='+encodeURIComponent(keyword);
   location.href = url;
  });
</script>
