$("#xj_pre").hover(function () {
$("#xj_pre").css("opacity",'1');},
function () {
$("#xj_pre").css("opacity",'0.3');})
$("#xj_next").hover(function () {
$("#xj_next").css("opacity",'1');},
function () {
$("#xj_next").css("opacity",'0.3');})


window.onresize = function(){
	console.log(screen.width);
	if(screen.width=='1366'){console.log('1366px');
	   $("#shiyin").css("left","1070px");
	}


	if(screen.width=='1440'){console.log('1440px');
	   $("#shiyin").css("left","1107px");
			var xj_w=1424-document.documentElement.clientWidth;
		if (document.documentElement.clientWidth<1424) {
			$("#shiyin").css("clear","both");
			$("#shiyin").attr("width","210px");
			console.log(xj_w);
		}
}
	console.log(document.documentElement.clientWidth+","+document.documentElement.clientHeight);
	
}//窗口大小变化的时候可以执行的事件

function xj_slide() {
  
}