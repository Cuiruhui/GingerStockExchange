(function($){
	jQuery.AjaxLoadDefaults = {
		event:'click', /*指定事件*/
		link:false, /* 指定连接,优先href属性.*/
		target:'#container', /*数据加载到这里*/
		animateOut:false,
		animateIn:false,
		animateOutSpeed:'normal',
		animateInSpeed:'normal',
		method: 'GET', /* 请求方式 GET或POST*/
		tagToload:false, /* inserts just the tag from the data loaded, it can be specified as t a second argument in the 'target' attr(#box,#result)*/
		loading_txt:'数据加载中...',
		loading_img:"../images/loading.gif",
		loading_target: false,
		loading_fn:function(options){
			jQuery.AjaxLoadLoading(options);
		},
		loadHash:false,	/* for use this to resolve bookmarking issues, see example for more details*/
		title:false, /* 改变页面标题. */
		forms:false, /* 发烧表单数据以及请求 (forms, input , radio ... 等选项) */
		params:'ajax=true',/*扩展网页参数,自定义功能*/
		timeout:false, /*超时设定*/
		contentType:"application/x-www-form-urlencoded",
		dataType:'html',
		cache:false, /* 开关浏览器缓冲*/
		username:false, /*username HTTP access authentication*/
		password:false, /*password HTTP access authentication*/
		onStart:function(op){}, /* 请求前调用功能.*/
		onError:function(op){
			jQuery.AjaxLoadManip(op,"<font style='color: #CC0000'>错误: </font> 没有找到相关页面.");
			jQuery('#AjaxLoadLoading').remove();
		}, /* a callback function if error happened while requesting*/
		onSuccess:function(op){},/* 请求完毕调用功能*/
		onComplete:function(op){}//*a callback function when the request finished weather it was a successful one or not.*/
	};
	jQuery.AjaxLoadFirstLoad = true;
	jQuery.AjaxLoadhistorySet = new Object();
	jQuery.AjaxLoadPageTitle = document.title;
	jQuery.AjaxLoadDebug = false;

    //$.AjaxLoad=jQuery.fn.AjaxLoad();
    jQuery.fn.AjaxAuto =function(options)
    {
    	
    }
	jQuery.fn.AjaxLoad = function(options) {
		if(!jQuery(this).size()){
			jQuery.AjaxLoadlog('Error: No matched element/s for your AjaxLoad selector " '+jQuery(this).selector+' ".');
			return false;
		}
		return this.each(function() {
			var current = jQuery.extend({},jQuery.AjaxLoadDefaults, options);
			if(jQuery.metadata){
				current = jQuery.extend(current,jQuery(this).metadata());
			}
			if(current.event){
				jQuery(this).bind(current.event,function(){
					jQuery(this).AjaxLoadAnalyse(current);
					if(!current.hash)
					jQuery.AjaxLoadLoad(current);
					else{
						jQuery.AjaxLoadHash(current);
					}
					//停止浏览器
					if(jQuery(this).is('a') || jQuery(this).is('form')) return false;
				});
			}else{
				jQuery(this).AjaxLoadAnalyse(current);
				jQuery.AjaxLoadLoad(current);
			}
			//加入书签
			if(current.loadHash  && jQuery.AjaxLoadFirstLoad){
				jQuery(this).AjaxLoadAnalyse(current);
				if(document.location.hash.replace(/^#/, '') == current.hash	&& current.hash){
				jQuery.AjaxLoadHash(current);
				jQuery.AjaxLoadFirstLoad = false;
			}
		}

	}); // end each fn
}; // end AjaxLoad fn





jQuery.fn.AjaxLoadAnalyse = function(current){
	current.object = this;
	//href 属性优先 之后到 value
	if(jQuery(this).attr('href') && jQuery(this).is('a')){
		if(jQuery(this).attr('href')){
			var link = jQuery(this).attr('href').replace(/^#/, "");
			current.link = link || current.link;
		}else {
			current.link;
		}
		if(typeof current.tagToload != 'object')
		if(jQuery(this).attr('target'))
		current.target = jQuery(this).attr('target');
		else
		current.target;
		else
		current.target = current.loading_target || '#AjaxLoadTemp';
	}else if(jQuery(this).attr('value') && (jQuery(this).is('div') || jQuery(this).is('span'))){
		if(jQuery(this).attr('value') && (jQuery(this).is('div') || jQuery(this).is('span'))){
			var link = jQuery(this).attr('value');
			current.link = link || current.link;
		}else {
			current.link;
		}
		if(typeof current.tagToload != 'object')
		if(jQuery(this).attr('target'))
		current.target = jQuery(this).attr('target');
		else
		current.target;
		else
		current.target = current.loading_target || '#AjaxLoadTemp';
	}else{
		current.link = link || current.link;
	}
	if(!current.loading_target)
	current.loading_target = current.target;


	if(current.forms){
		var text = jQuery(current.forms).serialize();
		current.paramres = text;
	}

	if(typeof current.params == 'function')
	var params = current.params(current);
	else
	var params = current.params;

	if(typeof params == 'string'){
		if(text)
		current.paramres +='&'+params;
		else
		current.paramres = params;
	}

	var len = current.target.length-1;
	if(typeof current.tagToload !='object')
	if(current.target.charAt(len) == '+' || current.target.charAt(len)=='-'){
		current.manip = current.target.charAt(len);
		current.target = current.target.substr(0,len);
	}

	if(current.loadHash){
		if(!jQuery.historyInit){
			jQuery.AjaxLoadlog('Error: loadHash is enabled but history plugin couldn\'t be found.');
			return false;
		}

		if(current.loadHash === true){
			jQuery.AjaxLoadlog('Info: It seemes you are upgrading from v1.0. Please see the new documentation about loadHash. "attr:href" will be used instead of "true".');
			current.loadHash = "attr:href";
		}
		if(current.loadHash.toLowerCase() == 'attr:href' ||
		current.loadHash.toLowerCase() == 'attr:rel' ||
		current.loadHash.toLowerCase() == 'attr:title'){

			current.loadHash = current.loadHash.toLowerCase();
			current.hash = jQuery(this).attr(current.loadHash.replace('attr:',''));
			if(jQuery.browser.opera){
				current.hash = current.hash.replace('?','%3F');
				current.hash = current.hash.replace('&','%26');
				current.hash = current.hash.replace('=','%3D');
			}
		}else
		current.hash = current.loadHash;

		if(!current.hash)
		jQuery.AjaxLoadlog('Warning: You have specified loadHash, but its empty or attribute couldn\'t be found.');
	}

	if(!jQuery(current.target).size() && typeof current.tagToload !='object')
	jQuery.AjaxLoadlog('Warning: Target " '+current.target+' " couldn\'t be found.');


};




jQuery.AjaxLoadLoading = function(options){
	var html = "<div id='AjaxLoadLoading'><img src='"+options.loading_img+"' alt='加载中...' title='加载中...' >  "+options.loading_txt+"</div>";
	if(options.loading_target)
	jQuery.AjaxLoadManip(options.loading_target,html);
	else
	jQuery.AjaxLoadManip(options,html);
}





jQuery.AjaxLoadHash = function(current){
	var ob = new Object();
	jQuery.each(current, function(key, value) {
		ob[key] = value;
	});
	jQuery.AjaxLoadhistorySet[ob.hash] = ob;
	location.hash = ob.hash;
	//if(jQuery.AjaxLoadFirstLoad.history){
	//alert(ob.hash);
	jQuery.historyInit(jQuery.AjaxLoadHistory);
	jQuery.AjaxLoadFirstLoad.history = false;
	//}
};





jQuery.AjaxLoadLoad = function(current) {
	// turn off globals
	//alert('AjaxLoadLoad'+print_r(current,true));
	jQuery.ajaxSetup({global:false});
	//start calling  jQuery.ajax function. thank you jquery for making this easy
	jQuery.ajax({
		type: current.method,
		url: current.link,
		dataType: current.dataType,
		data: current.paramres,
		contentType:current.contentType,
		processData:true,
		timeout:current.timeout,
		cache:current.cache,
		username:current.username,
		password:current.password,
		complete: function(){
			current.onComplete(current)
		},
		beforeSend: function(){
			current.onStart(current);

			if(current.animateOut){
				if(current.loading_target != current.target);//diff target? fire before start anim
				current.loading_fn(current);
				jQuery(current.target).animate(current.animateOut,current.animateOutSpeed,function(){
					//alert('hr');
					if(!current.loading_target)//already fired
					current.loading_fn(current);
				});
			}else
			current.loading_fn(current);
		},
		success: function(data){
			jQuery(current.target).stop();
			jQuery('#AjaxLoadLoading').remove();

			if(current.title)
			document.title = current.title;
			else if(document.title != jQuery.AjaxLoadPageTitle)
			document.title = jQuery.AjaxLoadPageTitle;

			if(current.tagToload){
				data = '<div>'+data+'</div>'; //wrap data so we can find tags within it.
				if(typeof current.tagToload == 'string'){
					jQuery.AjaxLoadManip(current,jQuery(data).find(current.tagToload));
				}else if(typeof current.tagToload == 'object') {
					jQuery.each(current.tagToload, function(tag, target) {
						if(jQuery(data).find(tag).size())
						jQuery.AjaxLoadManip(target,jQuery(data).find(tag));
						else
						jQuery.AjaxLoadlog('Warning: Tag "'+tag+'" couldn\'t be found.');

					});
				}

			}else{
				//
				jQuery.AjaxLoadManip(current,data);
			}
			current.onSuccess(current,data);
			if(current.animateIn)
			jQuery(current.target).animate(current.animateIn,current.animateInSpeed);

		},
		error:function(msg){
			jQuery(current.target).stop();
			current.onError(current,msg);
			if(current.animateIn)
			jQuery(current.target).animate(current.animateIn,current.animateInSpeed);
		}
	});
};





jQuery.AjaxLoadlog = function(message) {
	if(jQuery.AjaxLoadDebug)
	if(window.console) {
		console.debug(message);
	} else {
		alert(message);
	}
};





jQuery.AjaxLoadHistory = function(hash){
	if(hash){
		if(jQuery.browser.safari){
			var options = jQuery.AjaxLoadhistorySet[location.hash.replace(/^#/,'')]; //fix bug in history.js
		}else
		var options = jQuery.AjaxLoadhistorySet[hash];

		if(options)
		jQuery.AjaxLoadLoad(options);
		else
		jQuery.AjaxLoadlog('History Fired. But I couldn\'t find hash. Most propabley, the hash is empty. If so, its normal.');
	}
};





jQuery.AjaxLoadManip = function(current,data){

	if(typeof current != 'object'){
		var target = current;
		var current = new Object;
		var len = target.length-1;
		if(target.charAt(len) == '+' || target.charAt(len)=='-'){
			current.manip = target.charAt(len);
			current.target = target.substr(0,len);
		}
		else{
			current.manip = '';
			current.target = target;
		}
		if(!jQuery(current.target).size())
		jQuery.AjaxLoadlog('Warning: Target "'+current.target+'" couldn\'t be found.');
	}


	if(current.manip == '+')
	jQuery(current.target).append(data);
	else if(current.manip == '-')
	jQuery(current.target).prepend(data);
	else
	jQuery(current.target).html(data);
};


})(jQuery);