 $(document).ready(function () {
 	  var p=function () {
		 	    $('#head-1 p:eq(0)').css({'transform':'translateX(20px)', 'opacity':'1','transition':'3s'});
				$('#head-1 p:eq(1)').css({'transform':'translateX(20px)', 'opacity':'1','transition':'3s 4s'});
				$('#head-1 p:eq(2)').css({'transform':'translateX(20px)', 'opacity':'1','transition':'3s 8s'});
				$('#head-1 p:eq(3)').css({'transform':'translateX(20px)', 'opacity':'1','transition':'3s 12s'});}
				p();//文字效果
      var photowall=function () {
      	  var norepeat=true;
      	  var text=['爱情指数：','工作指数：','加薪指数：','财运指数：','健康指数：','社交指数：','懒惰指数：','综合指数：','幸运数字：','幸运颜色：'];
      	  var color=['红色','橙色','蓝色','绿色','紫色','黑色','灰色','白色','粉色','黄色','棕色','银色'];
      	  var content=function () {
      	  	                var con=text[Math.floor(Math.random()*10)];
                            if (con==='幸运颜色：') {
                            	 $('#photowall-2 li p').html( '幸运颜色：'+color[Math.floor(Math.random()*10)]);
                                    }
                           else{
	                           	    if (con==='幸运数字：') {
	                           		$('#photowall-2 li p').html('幸运数字：'+
	                           		 	Math.floor(Math.random()*10-1));}
	                         	   else{
	                         	   	  $('#photowall-2 li p').html(text[Math.floor(Math.random()*8)]+Math.floor(Math.random()*100)+"%");}
                            	}
                            }
      		 $('#photowall-1 img').
      		 mouseenter(function () {
						$(this).css({'opacity':'0.6','transition':'0.5s'});
      		 }).click(function () {
      		 	if (norepeat) {
      		 		norepeat=false;
      		 	$(this).css({'box-shadow':'10px 15px 10px #000','transform':'rotateX(180deg)','opacity':'0','transition':'1s'});
	            $('#photowall-2 li p').html(content()).css({'opacity':'1','transition':'4s'});};}).mouseleave(function () {
	            	norepeat=true;
			 	$(this).css({'scale':'1','transform':'rotateX(0deg)','box-shadow':'0px 0px 0px #ffffff','transition':'1s','opacity':'1'});
			 	 $('#photowall-2 li p').css({'opacity':'0','transition':'0.1s'});
			 })
      };photowall();
      var dotted=function () {
      		
      };dotted();
      var active =function () {
      	  $('#bookshelf-2 li').click(function (e) {
      	  	 e.preventDefault();
      	  	 $('#bookshelf-2 li').removeClass('active');
      	  	$(this).addClass('active');});


      };
      active();	
      var looking=function () {
      	var keywords=$('#kw1').val();
      	console.log(keywords);
      	$('#bt1').click(function () {
      		$.ajax({
      			url:'1.php?'+keywords,
      			type:'get',
      			success:function (res,state,xhr) {
      				console.log(res);
      				window.open('index.html');
      			},
      			error:function (xhr,state,err) {
      			console.log(state+err);
      			}
      		})
      	})
      	  
      }
      looking();  


 })
