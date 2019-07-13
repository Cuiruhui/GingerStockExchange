//自动完成
(function($) {
	$.fn.jSuggest = function(options) {
		var opts = $.extend({}, $.fn.jSuggest.defaults, options);
		var jH = ".jSuggestHover";
		var jsH = "jSuggestHover";
		var iniVal = this.value;
		var textBox = this;
		var textVal = this.value;
		var jC = "#jSuggestContainer";
		$("body").append('<div id="jSuggestContainer"></div>');
		$(jC).hide();
		$(this).bind("keyup click", function(e){
			textBox = this;
			textVal = this.value;
			if (this.value.length >= opts.minchar && $.trim(this.value)!="Search Terms") {
				var offSet = $(this).offset();
				$(jC).css({
					position: "absolute",
					top: offSet.top + $(this).outerHeight() + "px",
					left: offSet.left,
					width: $(this).outerWidth()+100 + "px",
					opacity: opts.opacity,
					zIndex: opts.zindex
				}).show();
				if (e.keyCode == 27 ) {
					$(jC).hide();
				}
				else if (e.keyCode == 13 ) {
					if ($(jH).length == 1)
					$(textBox).val($(jH).text());
					$(jC).hide();
					iniVal = textBox.value;
				}
				else if (e.keyCode == 40) {
					if ($(jH).length == 1) {
						if (!$(jH).next().length == 0) {
							$(jH).next().addClass(jsH);
							$(".jSuggestHover:eq(0)").removeClass(jsH);
							if (opts.autoChange)
							$(textBox).val($(jH).text());
						}
					}
					else {
						$("#jSuggestContainer ul li:first-child").addClass(jsH);
						if (opts.autoChange)
						$(textBox).val($(jH).text());
					}

				}

				else if (e.keyCode == 38) {
					// if any suggestion is highlighted
					if ($(jH).length == 1 ) {
						if (!$(jH).prev().length == 0) {
							$(jH).prev().addClass(jsH);
							$(".jSuggestHover:eq(1)").removeClass(jsH);
							if (opts.autoChange)
							$(textBox).val($(jH).text());
						}
						// if is first child
						else {
							$(jH).removeClass(jsH);
							$(textBox).val(iniVal);
						}
					}
				}
				else if (textBox.value != iniVal){
					iniVal = textBox.value;
					if ($(".jSuggestLoading").length==0)
					$('<div class="jSuggestLoading"><img src="'+opts.loadingImg+'" align="bottom" /> '+ opts.loadingText+'</div>').prependTo("#jSuggestContainer");

					$(".jSuggestLoading").show();
					$(jC).find('ul').remove();

					if (opts.data == '')
					opts.data = $(this).serialize();
					else
					opts.data = opts.data + "=" + $(this).val();
					setTimeout(function () {
						$.ajax({
							type: opts.type,
							url: opts.url,
							data: opts.data,
							success: function(msg){
								$(jC).find('ul').remove();
								$(jC).append(msg);
								$("#jSuggestContainer ul li").bind("mouseover",	function(){
									$(jH).removeClass(jsH);
									$(this).addClass(jsH);
									textVal = $(this).attr('id');
									if (opts.autoChange)
									$(textBox).val($(jH).attr('id'));
								});
								$("#jSuggestContainer ul li").click(function(){
									stockType=0;
									$(this).addClass(jsH);
									$(textBox).val(textVal);
									$("#LoadMSg").show();
									getPage(0,1,0,textVal);
									$('#tabs .selected').removeClass('selected');
									$('#tabs div:first').addClass('selected');
									$(textBox).val($(jH).attr('id'));
									//$("#hy_stock").attr('value','0');
								});
								$(".jSuggestLoading").hide();
							}
						});
					}, opts.delay);
				}
			}
			else {
				$(jH).removeClass(jsH);
				$(jC).hide();
			}
			return false;
		});
		$(document).bind("click", function(){
			$(jC).hide();
			iniVal = textBox.value;
		});

	};

	$.fn.jSuggest.defaults = {
		minchar: 2,
		opacity: 1.0,
		zindex: 20000,
		delay: 500,
		loadingImg: 'images/loading.gif',
		loadingText: 'Loading...',
		autoChange: false,
		url: "",
		type: "GET",
		data: ""
	};




})(jQuery);