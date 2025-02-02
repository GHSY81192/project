<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{$info['title']}预订-{$webname}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {Common::css('amazeui.css,style.css,extend.css')}
    {Common::js('jquery.min.js,amazeui.js,template.js,layer/layer.m.js')}

</head>

<body>

{request "pub/header/typeid/$typeid/isbookpage/1"}
<section>

    <div class="mid_content">
        {if empty($userinfo['mid'])}
        <div class="order-hint-msg-box">
            <p class="hint-txt">温馨提示：<a href="/phone/member/login">登录</a>可享受预定送积分、积分抵现！</p>
        </div><!-- 未登录提示 -->
        {/if}
        <div class="confirm_order_msg">
            <dl>
                <dt><img src="{$info['litpic']}"/></dt>
                <dd>
                    <span>{$info['title']}</span>
                    <strong><i class="currency_sy">{Currency_Tool::symbol()}</i><b>{$info['price']}</b>起</strong>
                </dd>
            </dl>
        </div>
        <!--产品简介-->

        <div class="book_type">
            <h3 class="book_tit">产品类型</h3>

            <div class="book_con_box">
                <ul>
                    {st:hotel action="suit" productid="$info['id']"}
                    {loop $data $r}
                    <li data-suitid="{$r['id']}" data-jftp="{$r['jifentprice']}">{$r['title']}</li>
                    {/loop}
                    {/st}
                </ul>
            </div>
        </div>
        <!--产品类型-->

        <form action="{$cmsurl}hotel/create" id="orderfrm" method="post">
            <div class="book_type">
                <h3 class="book_tit">预定日期</h3>

                <div class="people_num">
                    <p>
                        <strong>到店日期：</strong>
                        <span>
                            <input type="text" style="  width: 200px;border: 0;text-align: left" class="startdate" name="startdate" id="startdate" value="{date('Y-m-d')}"/>
                        </span>
                        <span>&gt;</span>
                    </p>

                    <p>
                        <strong>离店日期：</strong>
                        <span>
                            <input type="text" style="  width: 200px;border: 0;text-align: left" class="leavedate"
                                   name="leavedate" id="leavedate" value="{date('Y-m-d',strtotime('+1 day'))}"/>
                        </span>
                        <span>&gt;</span>
                    </p>

                    <p>
                        <strong>房间数量：</strong>
                        <span>
                            <a class="sub" href="javascript:;">-</a>
                            <input type="text" name="dingnum" id="dingnum" readonly value="1"/>
                            <a class="add" href="javascript:;">+</a>
                        </span>
                        <span>（点击选择）</span>
                    </p>

                    <p>
                        <strong>房费：</strong>
                        <span>
                            <em><i class="currency_sy">{Currency_Tool::symbol()}</i><b class="totalprice"></b></em>
                        </span>
                    </p>

                </div>
            </div>
            <!--预定人数-->

            <div class="book_type">
                <h3 class="book_tit">联系人<a href="javascript:;" id="chooseman">选择常用联系人&gt;</a></h3>

                <div class="linkman">
                    <dl>
                        <dt>预订联系人</dt>
                        <dd><strong>姓名：</strong><input type="text" id="linkman" name="linkman" placeholder="预订联系人姓名" class="" value="{$member['truename']}"/><span>(必填)</span></dd>
                        <dd><strong>联系电话：</strong><input type="text" id="linktel" name="linktel" class="" placeholder="手机号或固定电话" value="{$member['mobile']}"/><span>(必填)</span></dd>
                        <dd><strong>身份证号：</strong><input type="text" id="linkidcard" name="linkidcard" class="" placeholder="输入18位身份证号码" value="{$member['cardid']}"/></dd>
                        <dd><strong>订单备注：</strong><textarea name="remark"></textarea></dd>
                    </dl>
                </div>
            </div>
            <!--预定人数-->
            <!--隐藏域-->
            <input type="hidden" name="suitid" id="suitid" value=""/>
            <input type="hidden" name="productid" id="productid" value="{$info['id']}"/>
            <input type="hidden" name="typeid" value="{$typeid}"/>
            <input type="hidden" name="usejifen" id="usejifen" value="0"/>
        </form>
    <!--常用联系人-->
    <div id="linkman_list" style="display: none">
        <div class="linkman_page">
            <h3 class="tit">选择联系人</h3>
            <ul class="linkman_list">
                {st:member action="linkman" memberid="$userinfo['mid']"}
                {loop $data $r}
                <li class="cursor">
                    <strong>{$r['linkman']}</strong>
                    <span>联系电话{$r['mobile']}</span>
                    <span>身份证号{$r['idcard']}</span>
                    <i class="lk_choose"  data-linkman="{$r['linkman']}" data-mobile="{$r['mobile']}" data-idcard="{$r['idcard']}"></i>
                </li>
                {/loop}
                {/st}

            </ul>
        </div><!--选择联系人-->
    </div>
        {request "pub/integral"}
    </div><!--预定人数-->

    <div id="js_calendar" style="display: none">

    </div>
</section>
{request "pub/footer"}
<div class="bom_link_box">
    <div class="bom_fixed">
        <a class="price" href="javascript:;">总额：<span><i class="currency_sy">{Currency_Tool::symbol()}</i><b class="totalprice"></b></span></a>
        <a class="on btn_book" href="javascript:;">立即预定</a>
    </div>
</div>
<script>
    $(function () {
        $(".sub").click(function () {
            var obj = $(this).parent().children('input');
            if (parseInt($(obj).val()) > 1) {
                $(obj).val(parseInt($(obj).val()) - 1);
                get_range_price();
            }
        });
        $(".add").click(function () {
            var obj = $(this).parent().children('input');
            $(obj).val(parseInt($(obj).val()) + 1);
            get_range_price();
        });

        //套餐选择
        $(".book_con_box").find('li').click(function () {
            var suitid = $(this).attr('data-suitid');
            $("#suitid").val(suitid);
            $(this).addClass('on').siblings().removeClass('on');
            // get_mindate_book();
            get_range_price();
        })
        $(".book_con_box").find('li').first().trigger('click');
        //开始日期
        $(".startdate").click(function () {
            var suitid = $("#suitid").val();
            var typeid = "{$typeid}";
            var productid = "{$info['id']}";

            if (suitid > 0) {
                $.ajax({
                    type: 'POST',
                    data: {typeid: typeid, suitid: suitid, productid: productid, containdiv: 'startdate'},
                    url: SITEURL + 'pub/ajax_calendar',
                    dataType: 'html',
                    success: function (data) {

                        $("#js_calendar").html(data);
                        show_calendar();
                    }
                });
            } else {
                layer.open({
                    content: '请选择产品类型',
                    btn: ['{__("OK")}']
                });
            }

        })
        //离店日期
        $(".leavedate").click(function () {
            var suitid = $("#suitid").val();
            var typeid = "{$typeid}";
            var productid = "{$info['id']}";
            var startdate = $("#startdate").text();
            //住店日期加1
            startdate = add_date(startdate, 1);

            if (suitid > 0) {
                $.ajax({
                    type: 'POST',
                    data: {
                        typeid: typeid,
                        suitid: suitid,
                        productid: productid,
                        containdiv: 'leavedate',
                        startdate: startdate
                    },
                    url: SITEURL + 'pub/ajax_calendar',
                    dataType: 'html',
                    success: function (data) {

                        $("#js_calendar").html(data);
                        show_calendar();
                    }
                })
            } else {
                layer.open({
                    content: '请选择产品类型',
                    btn: ['{__("OK")}']
                });
            }

        })
        //关闭日历
        $("body").delegate('.close_calendar', 'click', function () {
            hide_calendar();
        })

        //获取最小时间范围
        //get_mindate_book();
        get_range_price();

        //常用联系人选择事件
        $("body").delegate('.linkman_list li','click',function(){
            $(this).find('i').toggleClass('on');
        });
        //常用联系人选择
        $("#chooseman").click(function () {
            $.getJSON('{$cmsurl}member/login/ajax_is_login', {}, function (data) {
                if (data.status == 1) {
                    var content = $("#linkman_list").html();
                    layer.open({
                        type: 1,
                        content: content,
                        style: 'width:80%; border:none;',
                        btn:['{__("OK")}'],
                        yes:function(){
                            $(".linkman_list").find('.on').each(function(i,obj){
                                var lkman = $(obj).attr('data-linkman');
                                var lkmobile = $(obj).attr('data-mobile');
                                var idcard = $(obj).attr('data-idcard');
                                $("#linkman").val(lkman);
                                $("#linktel").val(lkmobile);
                                $("#linkidcard").val(idcard);
                            })
                            layer.closeAll();
                        }
                    });
                }else{
                    layer.open({
                        content: '{__("error_linktel_not_login")}',
                        btn: ['确认', '取消'],
                        shadeClose: false,
                        yes: function () {
                            window.location.href = "{$cmsurl}member/login";
                        }, no: function () {
                        }
                    });
                }
            });
        });

        //提交订单
        $(".btn_book").click(function () {
            var lkman = $("#linkman").val();
            var lkmobile = $("#linktel").val();
            var linkidcard = $("#linkidcard").val();
            //联系人信息验证
            if (lkman == '') {
                layer.open({
                    content: '{__("error_linkman_not_empty")}',
                    btn: ['{__("OK")}']
                });
                return false;
            }
            //联系人手机验证
            re = /^1\d{10}$/
            if (!re.test(lkmobile)) {
                layer.open({
                    content: '{__("error_user_phone")}',
                    btn: ['{__("OK")}']
                });
                return false;
            }
			//联系人身份证验证
                    re = /^(\d{18,18}|\d{15,15}|\d{17,17}x)$/
                  if(linkidcard != ''){
                      if (!re.test(linkidcard)) {
                          layer.open({
                              content: '{__("身份证不合法")}',
                              btn: ['{__("OK")}']
                          });
                          return false;
                      }
                  }
            //订单金额验证
            var totalprice = Number($('.totalprice').first().text());
            if (totalprice == 0) {
                layer.open({
                    content: '{__("error_no_product")}',
                    btn: ['{__("OK")}']
                });
                return false;
            }

            //库存验证
            var check = check_storage();

            if (check) {
                $("#orderfrm").submit();
            } else {
                layer.open({
                    content: '{__("error_no_storage")}',
                    btn: ['{__("OK")}']
                });
                return false;
            }


        })

    })

    //检测库存

    function check_storage() {
        var startdate = $("#startdate").val();
        var enddate = $("#leavedate").val();
        var dingnum = $("#dingnum").val();
        var suitid = $("#suitid").val();
        var flag = 1;

        $.ajax({
            type: 'POST',
            url: SITEURL + 'hotel/ajax_check_storage',
            data: {startdate: startdate, enddate: enddate, dingnum: dingnum, suitid: suitid},
            async: false,
            dataType: 'json',
            success: function (data) {

                flag = data.status;
            }
        })
        return flag;

    }

    //显示日历
    function show_calendar() {
        $(".mid_content").hide();
        $("#js_calendar").show();
        $(".sys_header").hide();

    }
    //隐藏日历
    function hide_calendar() {
        $("#js_calendar").hide();
        $(".mid_content").show();
        $(".sys_header").show();
    }

    //选择天数
    function choose_day(day, containdiv) {
        $("#" + containdiv).val(day);
        if (containdiv == 'startdate') {
            var nextday = add_date(day, 1);

            $('#leavedate').val(nextday);
        }
        hide_calendar();
        get_range_price();
    }

    function choose_null_day(day,containdiv)
    {
        if (containdiv == 'startdate')
             return;
        $("#" + containdiv).val(day);
        hide_calendar();
        get_range_price();
    }





    //日期添加
    function add_date(date, days) {
        var d = new Date(date);
        d.setDate(d.getDate() + days);
        var m = d.getMonth() + 1;
        return d.getFullYear() + '-' + m + '-' + d.getDate();
    }
    //获取日期范围内报价
    function get_range_price() {
        var startdate = $("#startdate").val();
        var leavedate = $("#leavedate").val();
        var suitid = $("#suitid").val();
        var dingnum = $("#dingnum").val();
        var url = SITEURL + 'hotel/ajax_range_price';
        var money=0;
        $.getJSON(url, {startdate: startdate, leavedate: leavedate, suitid: suitid, dingnum: dingnum}, function (data) {
            //积分抵现
            if($('#usejifen').val()>0 && data.price>0){
                money=-(parseFloat($('#jifen-price').html()));
            }
            $('.totalprice').html(data.price+money);
        })
    }

    //获取可预订最小日期
    function get_mindate_book() {
        var suitid = $("#suitid").val();
        var url = SITEURL + 'hotel/ajax_mindate_book'
        $.getJSON(url, {suitid: suitid}, function (data) {

            $('#startdate').val(data.startdate);
            $('#leavedate').val(data.enddate);
            //get_range_price();
        })
    }

</script>

</body>
</html>
