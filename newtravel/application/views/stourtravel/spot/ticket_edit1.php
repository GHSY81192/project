<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>门票添加/修改</title>
    {template 'stourtravel/public/public_js'}
    {php echo Common::getCss('style.css,base.css,base2.css'); }
    {php echo Common::getScript("uploadify/jquery.uploadify.min.js,DatePicker/WdatePicker.js,product_add.js,imageup.js,jquery.validate.js"); }
    {php echo Common::getCss('uploadify.css','js/uploadify/'); }
</head>

<body>

	<table class="content-tab">
    <tr>
    <td width="119px" class="content-lt-td"  valign="top">
     {template 'stourtravel/public/leftnav'}
    <!--右侧内容区-->
    </td>
     <td valign="top" class="content-rt-td">

          <form method="post" name="product_frm" id="product_frm">
          <div class="manage-nr">
              <div class="w-set-tit bom-arrow" id="nav">
                  <span class="on"><s></s>{$position}</span>
                  <a href="javascript:;" class="refresh-btn" onclick="window.location.reload()">刷新</a>
              </div>

               <!--基础信息开始-->
              <div class="product-add-div">
              <div class="add-class">
                  <dl>
                      <dt>当前景点：</dt>
                      <dd>
                          {$spotname}
                      </dd>
                  </dl>
                  <dl>
                      <dt>门票名称：</dt>
                      <dd>
                          <input type="text" name="title" id="title" class="set-text-xh text_700 mt-2" value="{$info['title']}" />
                      </dd>
                  </dl>
                  <dl>
                      <dt>合作伙伴编号：</dt>
                      <dd>
                          <input type="text" name="partner" id="partner" class="set-text-xh text_250 mt-2" value="{$info['PartnerNumber']}" />
                      </dd>
                  </dl>
                  <dl>
                      <dt>门票类型：</dt>
                      <dd>

                          <select name="tickettypeid">

                              {loop $tickettypelist $k}
                              <option value="{$k['id']}" {if $info['tickettypeid']==$k['id']}selected="selected"{/if} >{$k['kindname']}</option>
                              {/loop}

                          </select>

                      </dd>
                  </dl>
                  <dl>
                      <dt>票面价格：</dt>
                      <dd>
                          <input type="text" name="sellprice" id="sellprice" class="set-text-xh text_100 mt-2" value="{$info['sellprice']}" />
                          <span class="fl ml-5">元</span>
                      </dd>
                  </dl>
                  <dl>
                      <dt>本站价格：</dt>
                      <dd>
                          <input type="text" name="ourprice" id="ourprice" class="set-text-xh text_100 mt-2" value="{$info['ourprice']}" />
                          <span class="fl ml-5">元</span>
                      </dd>
                  </dl>
                  <dl>
                      <dt>门票库存：</dt>
                      <dd>
                          <input type="text" name="ticketnum" id="ourprice" class="set-text-xh text_100 mt-2" value="{$info['number']}" />
                          <span class="fl ml-5">张</span>
                      </dd>
                  </dl>
                  <dl>
                      <dt>门票描述：</dt>
                      <dd style="height:100px">

                          <textarea class="set-area wid_695"  name="description" id="description" cols="" rows="">{$info['description']}</textarea>
                      </dd>
                  </dl>




                  <div class="add-class">
                  <dl>
                      <dt>预订送积分：</dt>
                      <dd>
                          <input type="text" name="jifenbook" id="jifenbook" class="set-text-xh text_100 mt-2" value="{$info['jifenbook']}" />
                          <span class="fl ml-5">分</span>
                      </dd>
                  </dl>
                  <dl>
                      <dt>积分抵现金：</dt>
                      <dd>
                          <input type="text" name="jifentprice" id="jifentprice" value="{$info['jifentprice']}" class="set-text-xh text_100 mt-2" />
                          <span class="fl ml-5">元</span>
                      </dd>
                  </dl>
                  <dl>
                      <dt>评论送积分：</dt>
                      <dd>
                          <input type="text" name="jifencomment" id="jifencomment" class="set-text-xh text_100 mt-2" value="{$info['jifencomment']}" />
                          <span class="fl ml-5">分</span>
                      </dd>
                  </dl>
                  <dl>
                      <dt>支付方式：</dt>
                      <dd>

                              <div class="on-off">
                                  <input type="radio" name="paytype" value="1" {if $info['paytype']=='1'}checked="checked"{/if} />全款支付 &nbsp;
                                  <input type="radio" name="paytype" value="2" {if $info['paytype']=='2'}checked="checked"{/if} />定金支付 &nbsp;
                                  <span id="dingjin" style="{if $info['paytype'] == '2'}display:inline-block{else}display: none{/if}"><input type="text"  name="dingjin" id="dingjintxt" value="{$info['dingjin']}" size="8" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-\.]+/,'');}).call(this)" onblur="this.v();">&nbsp;元</span>
                                  <input type="radio" name="paytype" value="3"  {if $info['paytype']=='3'}checked="checked"{/if} />二次确认支付 &nbsp;
                                  <input type="radio" name="paytype" value="4"  {if $info['paytype']=='4'}checked="checked"{/if} />线下支付 &nbsp;
                                  <script>
                                      $("input[name='paytype']").click(function(){
                                          if($(this).val() == 2)
                                          {
                                              $("#dingjin").show();
                                          }
                                          else
                                          {

                                              $("#dingjin").hide()
                                          }
                                      })

                                  </script>

                              </div>

                      </dd>
                  </dl>

              </div>

                  <div class="add-class">
                      <dl>
                          <dt>报价：</dt>
                          <dd> <div class="price_con">
                                  <div class="price_menu">
                                      <a href="javascript:;" class="{if $info['lastoffer']['pricerule']=='all'}on{/if}" data-val="all">所有日期</a>
                                      <a href="javascript:;" class="{if $info['lastoffer']['pricerule']=='week'}on{/if}" data-val="week">按星期</a>
                                      <a href="javascript:;" class="{if $info['lastoffer']['pricerule']=='month'}on{/if}" data-val="month">按号数</a>
                                  </div>
                                  <div class="price_sub">
                                      <div>
                                          <table class="price_tb">
                                              <tr>
                                                  <td class="tit">日期范围：</td><td><input type="text" name="starttime" class="set-text-xh text_100 choosetime" value="{$info['lastoffer']['starttime']}"/> <span class="fl">&nbsp;~&nbsp;</span> <input type="text" class="set-text-xh text_100 choosetime" name="endtime" value="{$info['lastoffer']['endtime']}"/></td>
                                              </tr>
                                              <tr>
                                                  <td class="tit" valign="top">价格：</td><td>
                                                      <table class="group-tb" style="border:0px">
                                                          <tr class="group_2">
                                                              <td>
                                                                  <span class="fl">成本</span>
                                                                  <input type="text" class="set-text-xh text_60 mt-2 ml-10" name="adultbasicprice" onkeyup="calPrice(this)" value="{$info['lastoffer']['adultbasicprice']}" autocomplete="off">
                                                                  <span class="fl ml-10">+利润</span>
                                                                  <input type="text" class="set-text-xh text_60 mt-2 ml-10" name="adultprofit" onkeyup="calPrice(this)" value="{$info['lastoffer']['adultprofit']}" autocomplete="off">
                                                                  <span class="fl ml-10">售价：<b style=" color:#f60" class="tprice">{$info['lastoffer']['price']}</b></span>
                                                              </td>
                                                          </tr>
                                                          <tr>
                                                              <td>
                                                                  <span class="fl">市场价</span>
                                                                  <input type="text" class="set-text-xh text_60 mt-2 ml-10" name="adultmarketprice"  value="{$info['lastoffer']['adultmarketprice']}" autocomplete="off">
                                                                  <span class="fl ml-10">分销价</span>
                                                                  <input type="text" class="set-text-xh text_60 mt-2 ml-10" name="adultdistributionprice" value="{$info['lastoffer']['adultdistributionprice']}" autocomplete="off">

                                                              </td>
                                                          </tr>
                                                      </table>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td class="tit">价格描述：</td><td><input type="text" class="set-text-xh text_700 mt-2" style="width: 300px" name="description"></td>
                                              </tr>
                                              <tr>
                                                  <td class="tit">库存：</td><td><input type="text" class="set-text-xh text_100 mt-2" name="number" value="-1"> <span style="color:gray;padding-left:10px">-1表示不限</span></td>
                                              </tr>
                                          </table>



                                      </div>
                                      <div class="sub_item" {if $info['lastoffer']['pricerule']!='all'}style="display:none"{/if}>
                                  </div>
                                  <div class="sub_item" {if $info['lastoffer']['pricerule']!='week'}style="display:none"{/if}>
                                  <table class="price_tb" >
                                      <tr>
                                          <td>星期选择：</td>
                                          <td><div class="price_week">
                                                  <table class="day_cs" id="week_cs" style="display: table;">
                                                      <tr height="30px"><td><a href="javascript:;" val="1">周一</a></td><td val="2"><a href="javascript:;" val="2">周二</a></td><td val="3"><a href="javascript:;" val="3">周三</a></td><td val="4"><a href="javascript:;" val="4">周四</a></td>
                                                          <td val="5"><a href="javascript:;" val="5">周五</a></td><td val="6"><a href="javascript:;" val="6">周六</a></td><td val="7"><a href="javascript:;" val="7">周日</a></td></tr>
                                                  </table>
                                              </div>
                                          </td>
                                      </tr>
                                  </table>


                              </div>
                              <div class="sub_item" {if $info['lastoffer']['pricerule']!='month'}style="display:none"{/if}>
                              <table class="price_tb">
                                  <tr>
                                      <td valign="top">日期选择：</td><td>
                                          <table class="day_cs" id="month_cs">
                                              <tr><td><a href="javascript:;">1</a></td><td><a href="javascript:;">2</a></td><td><a href="javascript:;">3</a></td><td><a href="javascript:;">4</a></td><td><a href="javascript:;">5</a></td>
                                                  <td><a href="javascript:;">6</a></td><td><a href="javascript:;">7</a></td><td><a href="javascript:;">8</a></td><td><a href="javascript:;">9</a></td><td><a href="javascript:;">10</a></td></tr>
                                              <tr><td><a href="javascript:;">11</a></td><td><a href="javascript:;">12</a></td><td><a href="javascript:;">13</a></td><td><a href="javascript:;">14</a></td>
                                                  <td><a href="javascript:;">15</a></td><td><a href="javascript:;">16</a></td><td><a href="javascript:;">17</a></td><td><a href="javascript:;">18</a></td>
                                                  <td><a href="javascript:;">19</a></td><td><a href="javascript:;">20</a></td></tr>
                                              <tr><td><a href="javascript:;">21</a></td><td><a href="javascript:;">22</a></td><td><a href="javascript:;">23</a></td><td><a href="javascript:;">24</a></td>
                                                  <td><a href="javascript:;">25</a></td><td><a href="javascript:;">26</a></td><td><a href="javascript:;">27</a></td><td><a href="javascript:;">28</a></td>
                                                  <td><a href="javascript:;">29</a></td><td><a href="javascript:;">30</a></td></tr>
                                              <tr><td><a href="javascript:;">31</a></td><td colspan="9"></td></tr>
                                          </table>
                                      </td>
                                  </tr>
                              </table>

                  </div>



              </div>
              <!--/基础信息结束-->
                  <div class="opn-btn" style="padding-left: 10px; " id="hidevalue">
                      <input type="hidden" name="ticketid" id="ticketid" value="{$info['id']}"/>
                      <input type="hidden" name="action" id="action" value="{$action}"/>
                      <input type="hidden" name="spotid" id="spotid" value="{$spotid}">
                      <input type="hidden" name="pricerule" id="pricerule" value="{$info['lastoffer']['pricerule']}"/>
                      <a class="normal-btn" id="btn_save" href="javascript:;">保存</a>
                      <a class="normal-btn"  href="javascript:;" id="btn_view_more" style="{if $action=='add'}display:none{/if}"  onclick="showMore()">查看报价</a>
                  </div>

          </div>
        </form>


    </td>
    </tr>
    </table>
  
	<script>
        var typeid="5";
	$(document).ready(function(){
        var action = "{$action}";
        //保存
        $("#btn_save").click(function(){
                var roomname = $("#title").val();
                if(roomname==''){
                    ST.Util.showMsg('请输入门票名称',5,1000);
                    return false;
                }

                   Ext.Ajax.request({
                       url   :  SITEURL+"spot/ajax_ticket_save",
                       method  :  "POST",
                       isUpload :  true,
                       form  : "product_frm",
                       success  :  function(response, opts)
                       {
                           //console.log(response);
                           var data = $.parseJSON(response.responseText);
                           if(data.status)
                           {
                               if(data.ticketid!=null){
                                   $("#ticketid").val(data.ticketid);
                               }
                               ST.Util.showMsg('保存成功!','4',2000);
                               $("#btn_view_more").show();
                           }
                       }});
        })
     });

    $(".price_menu a").click(function(){
        var index=$(".price_menu a").index(this);
        var pricerule=$(this).attr("data-val");
        $("#pricerule").val(pricerule);
        $(this).siblings().removeClass('on');
        $(this).addClass('on');
        $(".price_sub .sub_item:eq("+index+")").show().siblings(".sub_item").hide();
    });

    //日历选择
    $(".choosetime").click(function(){
        WdatePicker({skin:'default',dateFmt:'yyyy-MM-dd'})
    })


    $("#week_cs td a").click(function(e) {
        var v=$(this).attr('val');
        if($(this).hasClass('active'))
        {
            $("#weekval_"+v).remove();
        }
        else
        {
            $("#hidevalue").append("<input type='hidden' id='weekval_"+v+"' name='weekval[]' value='"+v+"'/>");
        }
        $(this).toggleClass('active');



    });

    $("#month_cs td a").click(function(e) {

        var v=$(this).text();
        v=$.trim(v);
        v=window.parseInt(v);

        if($(this).hasClass('active'))
        {
            $("#monthval_"+v).remove();
        }
        else
        {
            $("#hidevalue").append("<input type='hidden' id='monthval_"+v+"' name='monthval[]' value='"+v+"'/>");
        }
        $(this).toggleClass('active');
    });


    $(".pricerule").click(function(e) {
        if($(this).val()=='week')
        {
            $(".day_cs").hide();
            $("#week_cs").show();
        }
        else if($(this).val()=='month')
        {
            $(".day_cs").hide();
            $("#month_cs").show();
        }
        else
        {
            $("#week_cs").hide();
            $(".day_cs").hide();
        }
    });




    //查看日历报价
    function showMore()
    {
        var ticketid = $("#ticketid").val();
        var productid = $("#spotid").val();

        var width = $(window).width()-100;
        var height = $(window).height()-100
        // var url = "calendar.php?suitid="+suitid+"&carid="+carid;
        var url = SITEURL+'spotcalendar/index/suitid/'+ticketid+'/typeid/'+typeid+'/productid/'+productid;
        ST.Util.showBox('查看报价',url,width,height);
        // parent.window.showJbox('查看报价',url,width,height);

    }
    //计算价格
    function calPrice(obj)
    {
        var dd=$(obj).parents('td:first');


        var tprice=0;
        dd.find('input:text').each(function(index, element) {
            var price=parseInt($(element).val());
            if(!isNaN(price))
                tprice+=price;

        });
        dd.find(".tprice").html("¥"+tprice);
    }





    </script>


</body>
</html>
