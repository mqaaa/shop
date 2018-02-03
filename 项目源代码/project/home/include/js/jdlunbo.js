$.fn.extend({
		datu:function(pagestyle){ //封装大图滚动函数
			var this2=$(this); //先获取这个对象
			var fir=this2.find('.con img:first').clone(); //克隆第一张图
			var las=this2.find('.con img:last').clone(); //克隆最后一张图
			this2.find('.con').append(fir); //最后面插入克隆的第一张图
			this2.find('.con').prepend(las); //最前面插入克隆的最后一张图
			var imgw=Math.floor(this2.find('.con img').width()); //获取一张图的宽度
			var out=this2.find('#out'); //获取元素目标
			//console.log(out);
			out.scrollLeft(imgw); //初始化到起点
			var x=1,y=0; //图多一步故为1(相比page,中间底部的小圆点)
			var time1=null; //声明变量
			var img=this2.find('.con img'),li=this2.find('.nav li'); //一次性获取元素，优化性能
			
			var left=this2.find('.left'),right=this2.find('.right'); //一次性获取元素，优化性能
			function huan(){  //换一张图函数
				clearInterval(time1); //先清除计时器
				time1=setInterval(function(){
					x++;

					if(x==img.length){
					
						x=2;//跳到第二张
						out.scrollLeft(imgw);//滚动条归零
					}
					y++;
					if(y==li.length){//小圆点走自己的一套
						y=0;
					}
					bian(); //执行滚动函数
				},4000) //控制大图滚动的时间节奏
			}
			function bian(){ //一张图走完的过程函数
				li.eq(y).addClass(pagestyle).siblings().removeClass(pagestyle); //小圆点走自己的一套
				out.animate({scrollLeft:imgw*x}); //jq的动画控制滚动条，这里是x而不是x-1，是因为：
				//	0 ——> 1,1 ——> 2,2 ——> 3 的顺序，在前一次的基础上再走
				//	这里用animate动画就可以自己添加其他效果了
			}
			left.click(function(){ //左点击
				clearInterval(time1); //先清除计时器
				x--;
				if(x<0){
					x=img.length-3;//length减去本身比x（从0开始）多的1，再减去新增的最后的那张1，再减去要跳到倒数第二张产生的1，共为3
					out.scrollLeft(imgw*(x+1),10);//滚动条加1是因为（出现第x张，还要注意插入到最前面的那一张x=5，则滚的是6张图的距离）
				}
				y--;
				if(y<0){
					y=li.length-1;// y一直减到最左边时，再点击再减就要跳到最右边 0 1 2 3  length为4，下标为3，故 li.length-1;
				}
				bian() //一张图走完的过程函数
				huan() //换一张图函数
			})
			right.click(function(){ //右点击
				clearInterval(time1); //先清除计时器
				x++;
				if(x==img.length){
					x=2;
					out.scrollLeft(imgw);
				}
				y++;
				if(y==li.length){
					y=0;
				}
				bian() //一张图走完的过程函数
				huan() //换一张图函数
			})
			li.mouseenter(function(){ //page小圆点onmouseover事件
				clearInterval(time1); //先清除计时器
				y=$(this).index(); //y管y的
				x=y+1; //y为1时，看到的是第一张图，实际上前面还有一张图 ——>此时 x=y+1=1+1=2，
				bian() //一张图走完的过程函数
				huan() //换一张图函数
			})
			huan(); //自动执行，进页面就开始滚
		}
	})
window.onload=function(){
	$(function(){
		$('#main .main_body .main_body_center .roll').datu('select'); //封装完毕 使用时只更换roll 标签即可 $('.roll2').datu();
	})
	
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(0) .roll').datu('select'); //封装完毕 使用时只更换roll 标签即可 $('.roll2').datu();
	// })
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(1) .roll').datu('select'); //封装完毕 使用时只更换roll 标签即可 $('.roll2').datu();
		
	// })
	// // 样式自己调成想要的，其他复制即可。
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(2) .roll').datu('select'); //封装完毕 使用时只更换roll 标签即可 $('.roll2').datu();
		
	// })
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(3) .roll').datu('select'); //封装完毕 使用时只更换roll 标签即可 $('.roll2').datu();
		
	// })
}
	