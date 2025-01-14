<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" Content="text/html; charset=utf-8;">
    <title>移动端触摸滑动</title>
    <meta name="author" content="rainna" />
    <meta name="keywords" content="rainna's js lib" />
    <meta name="description" content="移动端触摸滑动" />
<!--    <meta name="viewport" content="target-densitydpi=320,width=640,user-scalable=no">-->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <style>
        *{margin:0;padding:0;}
        li{list-style:none;}

        .m-slider{width:600px;margin:50px 20px;overflow:hidden;}
        .m-slider .cnt{position:relative;left:0;width:3000px;}
        .m-slider .cnt li{float:left;width:600px;}
        .m-slider .cnt img{display:block;width:100%;height:450px;}
        .m-slider .cnt p{margin:20px 0;}
        .m-slider .icons{text-align:center;color:#000;}
        .m-slider .icons span{margin:0 5px;}
        .m-slider .icons .curr{color:red;}
        .f-anim{-webkit-transition:left .2s linear;}
    </style>
</head>

<body>
<div class="m-slider">
    <ul class="cnt" id="slider">
        <li>
            <img src="/phone/public/images/20170301/baoding.jpg">
            <p>20140813镜面的世界，终究只是倒影。看得到你的身影，却触摸不到你的未来</p>
        </li>
        <li>
            <img src="/phone/public/images/20170301/shijiazhuang.jpg">
            <p>20140812锡林浩特前往东乌旗S101必经之处，一条极美的铁路。铁路下面是个小型的盐沼，淡淡的有了一丝天空之境的感觉。可惜在此玩了一个小时也没有看见一列火车经过，只好继续赶往东乌旗。</p>
        </li>
        <li>
            <img src="/phone/public/images/20170301/qinhuangdao.jpg">
            <p>20140811水的颜色为什么那么蓝，我也纳闷，反正自然饱和度和对比度拉完就是这个颜色的</p>
        </li>
        <li>
            <img src="/phone/public/images/20170301/xingtai.jpg">
            <p>海洋星球3重庆天气热得我想卧轨自杀</p>
        </li>
        <li>
            <img src="/phone/public/images/20170301/cangzhou.jpg">
            <p>以上这些作品分别来自两位设计师作为观者，您能否通过设计风格进行区分</p>
        </li>
    </ul>
    <div class="icons" id="icons">
        <span class="curr">1</span>
        <span>2</span>
        <span>3</span>
        <span>4</span>
        <span>5</span>
    </div>
</div>

<script>
    var slider = {
        //判断设备是否支持touch事件
        touch:('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch,
        slider:document.getElementById('slider'),

        //事件
        events:{
            index:0,     //显示元素的索引
            slider:this.slider,     //this为slider对象
            icons:document.getElementById('icons'),
            icon:this.icons.getElementsByTagName('span'),
            handleEvent:function(event){
                var self = this;     //this指events对象
                if(event.type == 'touchstart'){
                    self.start(event);
                }else if(event.type == 'touchmove'){
                    self.move(event);
                }else if(event.type == 'touchend'){
                    self.end(event);
                }
            },
            //滑动开始
            start:function(event){
                var touch = event.targetTouches[0];     //touches数组对象获得屏幕上所有的touch，取第一个touch
                startPos = {x:touch.pageX,y:touch.pageY,time:+new Date};    //取第一个touch的坐标值
                isScrolling = 0;   //这个参数判断是垂直滚动还是水平滚动
                this.slider.addEventListener('touchmove',this,false);
                this.slider.addEventListener('touchend',this,false);
            },
            //移动
            move:function(event){
                //当屏幕有多个touch或者页面被缩放过，就不执行move操作
                if(event.targetTouches.length > 1 || event.scale && event.scale !== 1) return;
                var touch = event.targetTouches[0];
                endPos = {x:touch.pageX - startPos.x,y:touch.pageY - startPos.y};
                isScrolling = Math.abs(endPos.x) < Math.abs(endPos.y) ? 1:0;    //isScrolling为1时，表示纵向滑动，0为横向滑动
                if(isScrolling === 0){
                    event.preventDefault();      //阻止触摸事件的默认行为，即阻止滚屏
                    this.slider.className = 'cnt';
                    this.slider.style.left = -this.index*600 + endPos.x + 'px';
                }
            },
            //滑动释放
            end:function(event){
                var duration = +new Date - startPos.time;    //滑动的持续时间
                if(isScrolling === 0){    //当为水平滚动时
                    this.icon[this.index].className = '';
                    if(Number(duration) > 10){
                        //判断是左移还是右移，当偏移量大于10时执行
                        if(endPos.x > 10){
                            if(this.index !== 0) this.index -= 1;
                        }else if(endPos.x < -10){
                            if(this.index !== this.icon.length-1) this.index += 1;
                        }
                    }
                    this.icon[this.index].className = 'curr';
                    this.slider.className = 'cnt f-anim';
                    this.slider.style.left = -this.index*600 + 'px';
                }
                //解绑事件
                this.slider.removeEventListener('touchmove',this,false);
                this.slider.removeEventListener('touchend',this,false);
            }
        },

        //初始化
        init:function(){
            var self = this;     //this指slider对象
            if(!!self.touch) self.slider.addEventListener('touchstart',self.events,false);    //addEventListener第二个参数可以传一个对象，会调用该对象的handleEvent属性
        }
    };

    slider.init();
</script>
</body>
</html>