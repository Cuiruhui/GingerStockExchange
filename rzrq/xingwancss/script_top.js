//mainNav
$(function(){

	$('.centerList li').hover(function(){
		$(this).addClass('over').siblings().removeClass('over');
	})

	if($(".exabout,.exprocedures,.exdeposit,.exprice,.exqa,.exschool,.exsafety,.exactivity,.exdown,.excontact").hasClass("current")){
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').dblclick(function(){
			$(this).stop(true,true).slideUp();
		})
	}else{
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').mouseleave(function(){
			$(this).stop(true,true).slideUp();
		})
		}
		
	/*exabout*/
	$('.exnav .exabout').hover(function(){
		$('.exnavShow-exabout').stop(true,true).slideDown();
		$('.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exabout').hasClass('current')){			
		$('.exnavShow-exabout').stop(true,true).slideDown('fast');
		//$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exabout').mouseleave(function(){
		//if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	/*exprocedures*/
	$('.exnav .exprocedures').hover(function(){
		$('.exnavShow-exprocedures').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exprocedures').hasClass('current')){			
		$('.exnavShow-exprocedures').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exprocedures').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		//if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	
	/*exdeposit*/
	$('.exnav .exdeposit').hover(function(){
		$('.exnavShow-exdeposit').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exdeposit').hasClass('current')){			
		$('.exnavShow-exdeposit').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exdeposit').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		//if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})

	/*exprice*/
	$('.exnav .exprice').hover(function(){
		$('.exnavShow-exprice').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exprice').hasClass('current')){			
		$('.exnavShow-exprice').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exprice').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		//if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	
	/*exqa*/
	$('.exnav .exqa').hover(function(){
		$('.exnavShow-exqa').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exprice').hasClass('current')){			
		$('.exnavShow-exprice').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exqa').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		//if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	/*exqa*/
	$('.exnav .exschool').hover(function(){
		$('.exnavShow-exschool').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exschool').hasClass('current')){			
		$('.exnavShow-exschool').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exschool').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		//if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	/*exqa*/
	$('.exnav .exsafety').hover(function(){
		$('.exnavShow-exsafety').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exactivity,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exsafety').hasClass('current')){			
		$('.exnavShow-exsafety').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exsafety').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		//if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	
	/*exqa*/
	$('.exnav .exactivity').hover(function(){
		$('.exnavShow-exactivity').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exdown,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exactivity').hasClass('current')){			
		$('.exnavShow-exactivity').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exactivity').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		//if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	
	
	/*exqa*/
	$('.exnav .exdown').hover(function(){
		$('.exnavShow-exdown').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-excontact').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .exdown').hasClass('current')){			
		$('.exnavShow-exdown').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		//$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-exdown').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		//if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	
	
	/*excontact*/
	$('.exnav .excontact').hover(function(){
		$('.exnavShow-excontact').stop(true,true).slideDown();
		$('.exnavShow-exabout,.exnavShow-exprocedures,.exnavShow-exdeposit,.exnavShow-exprice,.exnavShow-exqa,.exnavShow-exschool,.exnavShow-exsafety,.exnavShow-exactivity,.exnavShow-exdown').stop(true,true).slideUp('fast');		
	})
		
	if($('.exnav .excontact').hasClass('current')){			
		$('.exnavShow-excontact').stop(true,true).slideDown('fast');
		$('.exnavShow-exabout').mouseleave(function(){$('.exnavShow-exabout').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprocedures').mouseleave(function(){$('.exnavShow-exprocedures').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdeposit').mouseleave(function(){$('.exnavShow-exdeposit').stop(true,true).slideUp('fast');})
		$('.exnavShow-exprice').mouseleave(function(){$('.exnavShow-exprice').stop(true,true).slideUp('fast');})
		$('.exnavShow-exqa').mouseleave(function(){$('.exnavShow-exqa').stop(true,true).slideUp('fast');})
		$('.exnavShow-exschool').mouseleave(function(){$('.exnavShow-exschool').stop(true,true).slideUp('fast');})
		$('.exnavShow-exsafety').mouseleave(function(){$('.exnavShow-exsafety').stop(true,true).slideUp('fast');})
		$('.exnavShow-exactivity').mouseleave(function(){$('.exnavShow-exactivity').stop(true,true).slideUp('fast');})
		$('.exnavShow-exdown').mouseleave(function(){$('.exnavShow-exdown').stop(true,true).slideUp('fast');})
		//$('.exnavShow-excontact').mouseleave(function(){$('.exnavShow-excontact').stop(true,true).slideUp('fast');})
		}
		
	$('.exnavShow-excontact').mouseleave(function(){
		if($('.exnav .exabout').hasClass('current')){$('.exnavShow-exabout').stop(true,true).slideDown();}
		if($('.exnav .exprocedures').hasClass('current')){$('.exnavShow-exprocedures').stop(true,true).slideDown();}
		if($('.exnav .exdeposit').hasClass('current')){$('.exnavShow-exdeposit').stop(true,true).slideDown();}
		if($('.exnav .exprice').hasClass('current')){$('.exnavShow-exprice').stop(true,true).slideDown();}
		if($('.exnav .exqa').hasClass('current')){$('.exnavShow-exqa').stop(true,true).slideDown();}
		if($('.exnav .exschool').hasClass('current')){$('.exnavShow-exschool').stop(true,true).slideDown();}
		if($('.exnav .exsafety').hasClass('current')){$('.exnavShow-exsafety').stop(true,true).slideDown();}
		if($('.exnav .exactivity').hasClass('current')){$('.exnavShow-exactivity').stop(true,true).slideDown();}
		if($('.exnav .exdown').hasClass('current')){$('.exnavShow-exdown').stop(true,true).slideDown();}
		//if($('.exnav .excontact').hasClass('current')){$('.exnavShow-excontact').stop(true,true).slideDown();}
	})
	
	
})

/*计算器*/

$(function(){
    $("#btn_math").click(function(){
	var final_val = 0;
	var fig = true;
	var err = "";
	var buy_type = $("#buy_type").val(); //买卖方向 1为买入  2为卖出 买入->买涨 , 卖出->买跌
	var num = $("#num").val(); //交易数量
	var j_val = $("#j_val").val(); //建仓价格
	var p_val = $("#p_val").val(); //平仓价格
	var j_count = $("#j_count").val(); //建仓佣金
	var p_count = $("#p_count").val(); //平仓佣金
	
	err += num==""?'交易数量不能为空！\n\r':'';
	err += j_val==""?'建仓价格不能为空！\n\r':'';
	err += p_val==""?'平仓价格不能为空！\n\r':'';
	err += j_count==""?'建仓佣金不能为空！\n\r':'';
	err += p_count==""?'平仓佣金不能为空！\n\r':'';
	
	if(err){
		alert(err);
		return false;
		}
	
	var len = $("input[name^='inc']").length;
	var i = 0;
	
	while(i<len){
		var k = $("input[name^='inc']").eq(i);
		var v = k.val();	
		
		if(!checkRate(k,v)){		    
			fig = false;
			break;
		}
		i++;
	}
	//alert(fig);
	if(!fig) return;
	
	j_count = j_count * 0.001;
	p_count = p_count * 0.001;
	
	//佣金
	

	if (buy_type == "1") { //买涨		
		final_val = (p_val-j_val)* num - j_val * num * j_count - p_val * num * p_count;
		
	} else if (buy_type == "2") { //买跌		
		final_val = (j_val - p_val)* num - j_val * num * j_count - p_val * num * p_count;
	}
	$("#finailly_value").val(final_val.toFixed(2));
  });
  //360收益器
  $("#btn_math_300").click(function(){
	var final_val = 0;
	var hs = 300;
	var fig = true;
	var err = "";
	var buy_type = $("#buy_type_300").val(); //买卖方向 1为买入  2为卖出 买入->买涨 , 卖出->买跌
	var num = $("#num_300").val(); //交易数量
	var j_val = $("#j_val_300").val(); //建仓价格
	var p_val = $("#p_val_300").val(); //平仓价格
	var j_count = $("#j_count_300").val(); //建仓佣金
	var p_count = $("#p_count_300").val(); //平仓佣金
	
	err += num==""?'交易数量不能为空！\n\r':'';
	err += num<0.05?'交易数量最小不能小于0.05！\n\r':'';
	err += num>100?'交易数量最大不能超过100！\n\r':'';
	err += j_val==""?'建仓点数不能为空！\n\r':'';
	err += p_val==""?'平仓点数不能为空！\n\r':'';
	err += j_count==""?'建仓佣金不能为空！\n\r':'';
	err += p_count==""?'平仓佣金不能为空！\n\r':'';
	
	if(err){
		alert(err);
		return false;
		}
	
	var len = $("input[name^='inp2']").length;
	var i = 0;
	while(i<len){
		var k = $("input[name^='inp2']").eq(i);
		var v = k.val();	
		if(!checkRate2(k,v)){		    
			fig = false;
			break;
		}
		i++;
	}
	//alert(fig);
	if(!fig) return;
	
		
	//佣金
   
	//alert(p_count);

	if (buy_type == "1") { //买涨		
		final_val = (p_val-j_val)*hs*num - p_val*j_count*hs*num - j_val*p_count*hs*num;
		
		if(Math.abs(p_val-j_val)>=15){
			final_val = final_val - p_val*hs*num*0.0015;
			//alert(final_val);
			}
		
	} else if (buy_type == "2") { //买跌		
		final_val = (j_val - p_val)*hs*num - j_count*hs*num - p_count*hs*num;
		if(Math.abs(p_val-j_val)>=15){
			final_val = final_val - p_val*hs*num*0.0015;
			//alert(final_val);
			}
	}
	//alert(final_val);
	$("#result_300").val(final_val.toFixed(2));
  });
  
  var checkRate = function(k,v){
     var re = /^\d+(\.\d+)?$/; //20140916非负浮点数  //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/   
     if (!re.test(v)){   
        k.focus();
		k.val("");
		$("#finailly_value").val('');
        return false;
     } else {
	 
	 	return true;
	 }
	}
	
var checkRate2 = function(k,v){
     var re = /^\d+(\.\d+)?$/; //20140916非负浮点数  //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/   
     if (!re.test(v)){   
        k.focus();
		k.val("");
		$("#result_300").val('');
        return false;
     } else {
	 
	 	return true;
	 }
	}
  
});
