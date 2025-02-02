﻿//兼容ie6的fixed代码 
//jQuery(function($j){
//	$j('#pop').positionFixed()
//})
(function($j){
    $j.positionFixed = function(el){
        $j(el).each(function(){
            new fixed(this)
        })
        return el;                  
    }
    $j.fn.positionFixed = function(){
        return $j.positionFixed(this)
    }
    var fixed = $j.positionFixed.impl = function(el){
        var o=this;
        o.sts={
            target : $j(el).css('position','fixed'),
            container : $j(window)
        }
        o.sts.currentCss = {
            top : o.sts.target.css('top'),              
            right : o.sts.target.css('right'),              
            bottom : o.sts.target.css('bottom'),                
            left : o.sts.target.css('left')             
        }
        if(!o.ie6)return;
        o.bindEvent();
    }
    $j.extend(fixed.prototype,{
        ie6 : 0 && 0,
        bindEvent : function(){
            var o=this;
            o.sts.target.css('position','absolute')
            o.overRelative().initBasePos();
            o.sts.target.css(o.sts.basePos)
            o.sts.container.scroll(o.scrollEvent()).resize(o.resizeEvent());
            o.setPos();
        },
        overRelative : function(){
            var o=this;
            var relative = o.sts.target.parents().filter(function(){
                if($j(this).css('position')=='relative')return this;
            })
            if(relative.size()>0)relative.after(o.sts.target)
            return o;
        },
        initBasePos : function(){
            var o=this;
            o.sts.basePos = {
                top: o.sts.target.offset().top - (o.sts.currentCss.top=='auto'?o.sts.container.scrollTop():0),
                left: o.sts.target.offset().left - (o.sts.currentCss.left=='auto'?o.sts.container.scrollLeft():0)
            }
            return o;
        },
        setPos : function(){
            var o=this;
            o.sts.target.css({
                top: o.sts.container.scrollTop() + o.sts.basePos.top,
                left: o.sts.container.scrollLeft() + o.sts.basePos.left
            })
        },
        scrollEvent : function(){
            var o=this;
            return function(){
                o.setPos();
            }
        },
        resizeEvent : function(){
            var o=this;
            return function(){
                setTimeout(function(){
                    o.sts.target.css(o.sts.currentCss)      
                    o.initBasePos();
                    o.setPos()
                },1)    
            }           
        }
    })
})(jQuery)

jQuery(function($j){
	$j('#footer').positionFixed()
})

//pop右下角弹窗函数
//作者：yanue
function Pop(title,url,intro){
	this.title=title;
	this.url=url;
	this.intro=intro;
	this.apearTime=1000;
	this.hideTime=500;
	this.delay=10000;
	//添加信息
	this.addInfo();
	//显示
	this.showDiv();
	//关闭
  this.closeDiv();
}
Pop.prototype={
  addInfo:function(){
    $("#popTitle a").attr('href',this.url).html(this.title);
    $("#popIntro").html(this.intro);
    $("#popMore a").attr('href',this.url);
  },
  showDiv:function(time){
		if (!(!$.support.style)) {
      $('#pop').slideDown(this.apearTime).delay(this.delay).fadeOut(400);;
    } else{//调用jquery.fixed.js,解决ie6不能用fixed
      $('#pop').show();
			jQuery(function($j){
			    $j('#pop').positionFixed()
			})
    }
  },
  closeDiv:function(){
  	$("#popClose").click(function(){
  		  $('#pop').hide();
  		}
    );
  }
}
