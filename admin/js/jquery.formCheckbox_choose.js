(function($){
	$.fn.extend({
		formCheckbox_choose:function(options){
			var paranames={
				value:1	
			};
			$.fn.extend(paranames,options);
			var checkbox=$(this).find("input:checkbox");
			checkbox.each(function(i){
				switch(paranames.value){
					case 1:
					checkbox.eq(i).attr({checked:"checked"});
					//alert("全选");
					break;
					case 0:
					checkbox.eq(i).removeAttr("checked");
					//alert("全部不选");
					break;
					case -1:
					if(checkbox.eq(i).attr("checked")==true){
						checkbox.eq(i).removeAttr("checked");	
					}else if(checkbox.eq(i).attr("checked")==false){
						checkbox.eq(i).attr({checked:"checked"});
					}
					//alert("反向选择");
					break;
					default:
					alert("非法参数");
					break;
				}
				
			});
		}
	});
})(jQuery);
