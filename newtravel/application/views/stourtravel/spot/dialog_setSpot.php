<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>思途CMS{$coreVersion}</title>
    {template 'stourtravel/public/public_min_js'}
    {php echo Common::getCss('base.css,destination_dialog_setdest.css'); }
</head>
<body >
   <div class="s-main">
       <div class="s-search">
            <div class="txt-wp">
                <input type="text" name="keyword" class="s-txt"/><a href="javascript:;" class="s-btn"></a>
            </div>
       </div>

       <div class="s-list" id="content_area">
           <div class="con-one all_menu">
              <a href="javascript:;" class="all-btn">全部</a>|<!--<a href="javascript:" id="all_select">全选</a>|<a href="javascript:" id="reverse_select">反选</a>-->
               <div class='con-one' step='1'><ul>
                 <ul>
                     <?php $totalCount=count($info)-1;?>
                   {loop $info $k $row}
                     <?php $roeNum=3;?>

                      <?php if($k%$roeNum==0){?>
                       <li>
                       <?php $lastIndex=$roeNum+$k-1;?>
                      <?php }?>

                  <span class="dest-item" id="item_{$row['id']}" pid="" style="width:160px ">
                      <input type="radio" name="spots"  class="lb-box" value="{$row['id']}"/>
                      <label class="lb-tit" >
                          {$row['title']}
                        </label>
                  </span>
                   <?php if($k==$lastIndex||$k==$totalCount){?>

                       </li>
                     <?php }?>
                  {/loop}
                 </ul>
               </div>
               <div class="clear-both"></div>
           </div>

       </div>
       <div class="clear-both"></div>
       <div class="save-con">
           <a href="javascript:;" class="confirm-btn">确定</a>
       </div>
   </div>
<script>
    var id="{$id}";
    var typeid="{$typeid}";
    var selector="{$selector}";
    var finalDestId="{$finalDest['id']}";
    $(function(){
         $(document).on('click','.lb-num',function(){
              var step=$(this).parents('.con-one:first').attr('step');
              var pid=$(this).attr('data-rel');
              var nextStep=parseInt(step)+1;
              getNextDests(pid,nextStep);
         })
         $(document).on('click','.con-one .all-btn',function(){
             getNextDests(0,1);
         });
        $(document).on('click','.s-search .s-btn',function(){
             var keyword=$(".s-search .s-txt").val();
             keyword= $.trim(keyword);
            window.location.href=SITEURL+"spot/getSpot?keyword="+keyword+"&selector="+selector;
            // getNextDests(0,1,keyword);

        })
        $(document).on('click','.s-chosen .chosen-con .chosen-one .del',function(){
             var parentEle=$(this).parents('.chosen-one:first');
             var id=parentEle.attr('data-rel');
             unchooseOne(id);

        });
        $(document).on('click','.con-one .lb-box',function(){
             var id=$(this).val();
             if($(this).is(':checked'))
             {
                 chooseOne(id);
             }
             else
             {
                 unchooseOne(id);
             }
        });
        $(document).on('click','.confirm-btn',function(){
             var dests=getSelectedDest();
             var finalKindname=$("#final_sel").find("option:selected").text();
             var finalId= $("#final_sel").val();
             var finalDest=finalId?{id:finalId,kindname:finalKindname}:null;

             ST.Util.responseDialog({id:0,typeid:4,data:dests,finalDest:finalDest,selector:selector},true);
        });
        //全选
        $(document).on('click','#all_select',function(){
            var obj=$('#content_area').find('.con-one:last');
            obj.find('.lb-box').each(function(){
                if(!$(this).is(':checked'))
                {
                    chooseOne($(this).val());
                }
            });
        });
        //反选
        $(document).on('click','#reverse_select',function(){
            var obj=$('#content_area').find('.con-one:last');
            obj.find('.lb-box').each(function(){
                var id=$(this).val();console.log(id);
                if($(this).is(':checked'))
                {
                    unchooseOne(id);
                }
                else
                {
                    chooseOne(id);
                }
            });
        });
        $('.s-txt').bind('keypress', function (event) {
            if (event.keyCode == "13") {
                var keyword = $(".s-search .s-txt").val();
                keyword = $.trim(keyword);
                window.location.href = SITEURL + "spot/getSpot?keyword=" + keyword + "&selector=" + selector;


            }
        })
//        getNextDests(0,1);
//        organizeSelect(finalDestId);

    })

    function getNextDests(pid,step,keyword)
    {
        var url=SITEURL+'spot/ajax_getSpotsetList';
        var rowNum=4;
        $.ajax({
            type: "post",
            url: url,
            dataType:'json',
            data:{pid:pid,keyword:keyword},
            success: function(data, textStatus){
                 var oldStep=parseInt(step);
                 $(".s-list .con-one").each(function(index,element){
                      var oneStep=$(element).attr('step');
                      oneStep=parseInt(oneStep);
                      if(oneStep>=oldStep)
                         $(element).remove();
                 });
                 if(typeof(data)=='object') {
                     var html = "<div class='con-one' step='" + step + "'><ul>";
                     var lastIndex=0;
                     var totalCount=data['nextlist'].length;
                     for(var i in data['nextlist'])
                     {
                         if(i%rowNum==0)
                         {
                             html+="<li>";
                             lastIndex=parseInt(rowNum)+parseInt(i)-1;
                         }
                         var row=data['nextlist'][i];
                         var num=row['childnum'];
                         var cls=num.length>1?'num-len2':'num-len1';
                         var labelCls=row['kindname'].length>5?'lb_5':'';
                         var numTag=num&&num>0?'<a class="lb-num '+cls+'" href="javascript:;" data-rel="'+row['id']+'">'+row['childnum']+'</a>':'';
                         var checkStr=isSelected(row['id'])?'checked="checked"':'';
                         html+=' <span class="dest-item" id="item_'+row['id']+'" pid="'+pid+'" ><input type="checkbox" name="dest" '+checkStr+' class="lb-box" value="'+row['id']+'"/><label class="lb-tit '+labelCls+'" >';
                         html+=row['kindname']+'</label>'+numTag+'<div class="clear-both"></div></span>';
                         if(i==lastIndex||i==totalCount-1)
                         {
                             html+="<div class='clear-both'></div></li>"
                         }

                     }
                     html+='</ul></div>';
                     $('.s-list').append(html);
                 }
                ST.Util.resizeDialog('.s-main');

            },
            error: function(){

            }
        });

    }
    function getSelectedDest()
    {
        var dests=[];
        $("input[checked=checked]").each(function(){
             var id=$(this).val();
             var kindname=$(this).siblings('label').text();
                  kindname =$.trim(kindname);

             dests.push({id:id,kindname:kindname});
        });

       return dests;
    }
    function isSelected(id)
    {
        var dests=getSelectedDest();
        for(var i in dests)
        {
            if(dests[i]['id']==id)
              return true;
        }
        return false;
    }
    function checkUp(pid)
    {
        var item=$("#item_"+pid);
        if(item.length>0)
        {
            var ppid=item.attr('pid');
            chooseOne(pid);

        }
        return;
    }
    function chooseOne(id)
    {

        var pid=$("#item_"+id).attr('pid');
        checkUp(pid);
        var kindname=$("#item_"+id).find('.lb-tit').text();
        if($("#chosen_item_"+id).length==0) {
            var tagHtml = '<span class="chosen-one" id="chosen_item_' + id + '" data-rel="' + id + '" pid="'+pid+'"><label class="lb-tit">' + kindname + '</label><a href="javascript:;" class="del">x</a></span>';
            $(tagHtml).insertBefore('.chosen-con .clear-both');
        }
        $("#item_"+id).find('input').attr('checked',true);
        organizeSelect();
        ST.Util.resizeDialog('.s-main');

    }
    function unchooseOne(id)
    {
        $("#chosen_item_"+id).remove();
        $("#item_"+id).find('input:checked').attr("checked",false);
        $(".chosen-con .chosen-one").each(function(index,element){
              var pid=$(element).attr('pid');
              var childId=$(element).attr('data-rel');
              if(pid==id)
                unchooseOne(childId);
        });
        organizeSelect();
        ST.Util.resizeDialog('.s-main');
    }
    //重新组织选择
    function organizeSelect(finalDestId)
    {
        //var destArr=[];
        var selectId=finalDestId?finalDestId:$("#final_sel").val();
        $("#final_sel").children().remove();
        $("#final_sel").append("<option value=''>请选择...</option>");
        $(".chosen-one").each(function(){

            var title=$(this).find("label").text();
            var id=$(this).attr("data-rel");
            var isSel=selectId==id?'selected="selected"':'';
            var option='<option value="'+id+'" '+isSel+'>'+title+'</option>';
            $("#final_sel").append(option);
           // destArr.push(item);
        });


    }





</script>

</body>
</html>
