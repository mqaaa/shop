$.fn.extend({
		datu:function(pagestyle){ //��װ��ͼ��������
			var this2=$(this); //�Ȼ�ȡ�������
			var fir=this2.find('.con img:first').clone(); //��¡��һ��ͼ
			var las=this2.find('.con img:last').clone(); //��¡���һ��ͼ
			this2.find('.con').append(fir); //���������¡�ĵ�һ��ͼ
			this2.find('.con').prepend(las); //��ǰ������¡�����һ��ͼ
			var imgw=Math.floor(this2.find('.con img').width()); //��ȡһ��ͼ�Ŀ��
			var out=this2.find('#out'); //��ȡԪ��Ŀ��
			//console.log(out);
			out.scrollLeft(imgw); //��ʼ�������
			var x=1,y=0; //ͼ��һ����Ϊ1(���page,�м�ײ���СԲ��)
			var time1=null; //��������
			var img=this2.find('.con img'),li=this2.find('.nav li'); //һ���Ի�ȡԪ�أ��Ż�����
			
			var left=this2.find('.left'),right=this2.find('.right'); //һ���Ի�ȡԪ�أ��Ż�����
			function huan(){  //��һ��ͼ����
				clearInterval(time1); //�������ʱ��
				time1=setInterval(function(){
					x++;

					if(x==img.length){
					
						x=2;//�����ڶ���
						out.scrollLeft(imgw);//����������
					}
					y++;
					if(y==li.length){//СԲ�����Լ���һ��
						y=0;
					}
					bian(); //ִ�й�������
				},4000) //���ƴ�ͼ������ʱ�����
			}
			function bian(){ //һ��ͼ����Ĺ��̺���
				li.eq(y).addClass(pagestyle).siblings().removeClass(pagestyle); //СԲ�����Լ���һ��
				out.animate({scrollLeft:imgw*x}); //jq�Ķ������ƹ�������������x������x-1������Ϊ��
				//	0 ����> 1,1 ����> 2,2 ����> 3 ��˳����ǰһ�εĻ���������
				//	������animate�����Ϳ����Լ��������Ч����
			}
			left.click(function(){ //����
				clearInterval(time1); //�������ʱ��
				x--;
				if(x<0){
					x=img.length-3;//length��ȥ�����x����0��ʼ�����1���ټ�ȥ��������������1���ټ�ȥҪ���������ڶ��Ų�����1����Ϊ3
					out.scrollLeft(imgw*(x+1),10);//��������1����Ϊ�����ֵ�x�ţ���Ҫע����뵽��ǰ�����һ��x=5���������6��ͼ�ľ��룩
				}
				y--;
				if(y<0){
					y=li.length-1;// yһֱ���������ʱ���ٵ���ټ���Ҫ�������ұ� 0 1 2 3  lengthΪ4���±�Ϊ3���� li.length-1;
				}
				bian() //һ��ͼ����Ĺ��̺���
				huan() //��һ��ͼ����
			})
			right.click(function(){ //�ҵ��
				clearInterval(time1); //�������ʱ��
				x++;
				if(x==img.length){
					x=2;
					out.scrollLeft(imgw);
				}
				y++;
				if(y==li.length){
					y=0;
				}
				bian() //һ��ͼ����Ĺ��̺���
				huan() //��һ��ͼ����
			})
			li.mouseenter(function(){ //pageСԲ��onmouseover�¼�
				clearInterval(time1); //�������ʱ��
				y=$(this).index(); //y��y��
				x=y+1; //yΪ1ʱ���������ǵ�һ��ͼ��ʵ����ǰ�滹��һ��ͼ ����>��ʱ x=y+1=1+1=2��
				bian() //һ��ͼ����Ĺ��̺���
				huan() //��һ��ͼ����
			})
			huan(); //�Զ�ִ�У���ҳ��Ϳ�ʼ��
		}
	})
window.onload=function(){
	$(function(){
		$('#main .main_body .main_body_center .roll').datu('select'); //��װ��� ʹ��ʱֻ����roll ��ǩ���� $('.roll2').datu();
	})
	
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(0) .roll').datu('select'); //��װ��� ʹ��ʱֻ����roll ��ǩ���� $('.roll2').datu();
	// })
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(1) .roll').datu('select'); //��װ��� ʹ��ʱֻ����roll ��ǩ���� $('.roll2').datu();
		
	// })
	// // ��ʽ�Լ�������Ҫ�ģ��������Ƽ��ɡ�
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(2) .roll').datu('select'); //��װ��� ʹ��ʱֻ����roll ��ǩ���� $('.roll2').datu();
		
	// })
	// $(function(){
	// 	$('#shoplist .context .display_news>ul>li:eq(3) .roll').datu('select'); //��װ��� ʹ��ʱֻ����roll ��ǩ���� $('.roll2').datu();
		
	// })
}
	