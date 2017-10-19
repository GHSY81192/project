<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>用户登陆-{$webname}</title>
{include "pub/varname"}    {Common::css('user.css,base.css,extend.css')}    {Common::js('jquery.min.js,base.js,common.js,jquery.validate.js,jquery.md5.js')}


</head>
<body>
{request "pub/header"}
<div class="st-userlogin-box">
  <div class="st-login-wp">
    <div class="st-admin-box">
      <form id="loginfrm" method="post">
      <div class="login-nav">
        <ul class="tabs">
          <li class="current"><a href="javascript:" >快速登录</a></li>
          <li><a href="javascript:" >普通登录</a></li>
        </ul>
      </div>
        <div class="login-account-key">
          <ul>
            <li class="number"> <span class="tb"></span>
              <input type="text" class="np-box" name="loginname" id="loginname"  placeholder="请输入手机号或邮箱" />
            </li>
            <li class="password"> <span class="tb"></span>
              <input type="password" class="np-box" name="loginpwd" id="loginpwd" placeholder="请输入登录密码" />
            </li>
            <li class="forget"> <span class="login_err"></span> <a href="{$cmsurl}member/findpwd">忘记密码？</a> </li>
            <li class="dl-btn"><a href="javascript:;" class="btn_login">登 录</a></li>
            <li class="now-zc">您还没有账号？<a href="{$cmsurl}member/register">立刻注册</a></li>
          </ul>
          <input type="hidden" name="logincode" id="frmcode" value="{$frmcode}"/>
          <input type="hidden" name="fromurl" id="fromurl" value="{$fromurl}">
        </div>
      </form>
      <div class="other-login">
        <dl>
          <dt><span>使用其他方式登录</span><em></em></dt>
          <dd> {if (!empty($GLOBALS['cfg_qq_appid']) && !empty($GLOBALS['cfg_qq_appkey']))} <a class="qq qqlogin" href="{$GLOBALS['cfg_basehost']}/plugins/login_qq/index/index/?refer={urlencode($fromurl)}">QQ</a> {/if}                {if (!empty($GLOBALS['cfg_weixi_appkey']) && !empty($GLOBALS['cfg_weixi_appsecret']))} <a class="wx wxlogin" href="{$GLOBALS['cfg_basehost']}/plugins/login_weixin/index/index/?refer={urlencode($fromurl)}">wx</a> {/if}                {if (!empty($GLOBALS['cfg_sina_appkey']) && !empty($GLOBALS['cfg_sina_appsecret']))} <a class="wb wblogin" href="{$GLOBALS['cfg_basehost']}/plugins/login_weibo/index/index/?refer={urlencode($fromurl)}">wb</a> {/if} </dd>
        </dl>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="/min/?f=/res/js/jquery.min.js,/res/js/base.js,/res/js/common.js,/res/js/myjs/lubotu.js,/res/js/myjs/web.js"></script>
<script>
//同时多个选项卡组件
$(document).ready(function(){ 
        //导航选项卡	 	    
	
            $(".tabs").children().click(function(){												
	
            $(this).addClass("current").siblings().removeClass("current");	
		//内容选项卡	 	
			tabid = '#'+ $(this).parent().parent().attr("id");			
			var index = $(tabid).children(".tabs").children().index(this);						
			tabCon = $(this).parent().next();
			$(tabCon).addClass("1")
			$(tabCon).children().eq(index).addClass("active").siblings().removeClass("active");	
           	$(tabCon).children().eq(index).show().siblings().hide();					
           });	

</script>
{request "pub/footer"}{Common::js('layer/layer.js')}<script>    $(function(){        //登陆        $(".btn_login").click(function(){            $("#loginfrm").submit();        })        $("#loginfrm").validate({            rules: {                loginname: {                    required: true                },                loginpwd: {                    required: true,                    minlength: 6                }            },            messages: {                loginname: {                    required: '{__("error_user_not_empty")}'                },                loginpwd: {                    required: '{__("error_pwd_not_empty")}',                    minlength: '{__("error_pwd_min_length")}'                }            },            errorPlacement: function (error, element) {                var content = $('#loginfrm').find('.login_err:eq(0)').html();                if (content == '') {                    $('#loginfrm').find('.login_err').html(error);                }            },            showErrors: function (errorMap, errorList) {                if (errorList.length < 1) {                    $('#loginfrm').find('.login_err:eq(0)').html('');                } else {                    this.defaultShowErrors();                }            },            submitHandler:function(form){                var url = SITEURL+'member/login/ajax_login';                var loginname = $("#loginname").val();                var loginpwd = $.md5($("#loginpwd").val());                var frmcode = $("#frmcode").val();                $.ajax({                    type:"post",                    async: false,                    url:url,                    data:{loginname:loginname,loginpwd:loginpwd,frmcode:frmcode},                    dataType:'json',                    success: function(data){                        if(data.status == '1'){//登陆成功,跳转到来源网址                            var url = $("#fromurl").val();                            setTimeout(function(){window.open(url,'_self');},500);                            $('body').append(data.js);//同步登陆js                        }                        else{                            if(data.msg!=undefined){                                $(".login_err").html(data.msg);                            }else{                                $(".login_err").html('{__("error_user_pwd")}');                            }                        }                    },                    error:function(a,b,c){                    }                });                return false;            }        })    })</script>
</body>
</html>