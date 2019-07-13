
/*计算收益*/
$(".zhangdie").click(function(){
	$(this).addClass("active").siblings().removeClass("active");
});


$(".jisuanshouyi").on('click',function(){
	
	var obj=$(this).parent();
	var shuliang=obj.find(".shuliang").val(),
	    jiancan=obj.find(".jiancan").val(),
		pingcan=obj.find(".pingcan").val(),
		jianyong=obj.find(".jianyong").val(),
		pingyong=obj.find(".pingyong").val(),
		jiandian=obj.find(".jiandian").val(),
		pingdian=obj.find(".pingdian").val(),
		tianshu=obj.find(".tianshu").val();
	var tit=$(".banner_jieguobot").find(".tit");
	var par=0;
	var shouyi=0;
	
	
		var touru=parseFloat((jiancan*shuliang/10).toFixed(2));
		var cost=parseFloat((jiancan*shuliang*0.0025+pingcan*shuliang*0.0025).toFixed(2));
		if(obj.find(".zhangdie").eq(0).hasClass("active")){
			shouyi=parseFloat(((pingcan-jiancan)*shuliang-cost).toFixed(2));
		}else if(obj.find(".zhangdie").eq(1).hasClass("active")){
			shouyi=parseFloat(((jiancan-pingcan)*shuliang-cost).toFixed(2));
		}
		tit.eq(0).html("投　资：");
		tit.eq(1).html("手续费：");
		tit.eq(2).html("利　润：");
		$(".banner_lsbot").hide();
		$(".banner_jieguobot").show();
		
		$(".touru").html(touru);
		$(".sxf").html(cost);
		$(".lirun").html(shouyi);
	
	
	$(".chongxin").click(function(){
		$(".banner_lsbot").show();
		$(".banner_jieguobot").hide();
		return false;
	});
	return false;
});




$(".zhanghu").click(function(){
	$(this).addClass("active").siblings().removeClass("active");
});

function ch_demo(type){
		$('#demo').val(type);
	}








