/**
 * 
 * 作者：袁志蒙
 * 作用：会员注册验证
 * 版权：YzmCMS版权所有
 * 网址：http://www.yzmcms.com
 * 最后修改时间：2016-06-21
 * 
 */ 
 
$(function(){
   $("#username").blur(function(){
	  checkname();
   });

   $("#email").blur(function(){
	  checkemail();
   });
   
   $("#password").blur(function(){
	  checkpass();
   });	

   $("#password2").blur(function(){
	  checkpass2();
   });		   
})

function checkpass(){
	 if($("#password").val().length < 6){
		  $('#password').next().html('<img src="./templets/images/error.gif">密码不能低于6位');
		  $('#password').next().addClass('error');	
		  return false;				  
	 }else{
		  $('#password').next().html('<img src="./templets/images/ok.gif">');
		  $('#password').next().removeClass('error');
		  return true;				  
	 }            			 
}

function checkpass2(){
	if($("#password").val() != $("#password2").val()){
		  $('#password2').next().html('<img src="./templets/images/error.gif">两次密码不一致');
		  return false;				  
	}else{
		  $('#password2').next().html('<img src="./templets/images/ok.gif">');
		  return true;	
	}
}

function checkname(){
	 var res = false;
	 if($("#username").val() != ''){
	  $.ajax({   
		type: "post",   
		url: "./ajax/checkname.php",   
		dataType: "html",
		async: false,	
		data: "username="+$('#username').val(), 
		beforeSend: function(){
			 $('#username').next().html('<img src="./templets/images/loading.gif">');
			 $("#dosubmit").attr({ disabled: "disabled" });
		},				
		success: function(msg){  
		 if(msg == '1'){
			  $('#username').next().html('<img src="./templets/images/ok.gif">');
			  $('#username').next().removeClass('error'); 
			  $("#dosubmit").removeAttr("disabled");
			  res = true;				  
		 }else if(msg == '-1'){
			  $('#username').next().html('<img src="./templets/images/error.gif">用户名已存在');
			  $('#username').next().addClass('error'); 			  
		 }else if(msg == '-2'){
			  $('#username').next().html('<img src="./templets/images/error.gif">用户名格式不正确');
			  $('#username').next().addClass('error'); 			  
		 }else{
			  $('#username').next().html('<img src="./templets/images/error.gif">未知错误');
			  $('#username').next().addClass('error');			  
		 }				 
		} 
	  });
	  return res;
	 }else{
	  $('#username').next().html('<img src="./templets/images/error.gif">用户名不能为空');
	  $('#username').next().addClass('error');
	  return res;
	 }		   
}

function checkemail(){
	 var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
	 var res = false;
	 if(reg.test($("#email").val())){
	  $.ajax({   
		type: "post",   
		url: "./ajax/checkemail.php",   
		dataType: "html",
		async: false,	
		data: "email="+$('#email').val(), 
		beforeSend: function(){
			 $('#email').next().html('<img src="./templets/images/loading.gif">');
			 $("#dosubmit").attr({ disabled: "disabled" });
		},				
		success: function(msg){  
		 if(msg == '1'){
			  $('#email').next().html('<img src="./templets/images/ok.gif">');
			  $('#email').next().removeClass('error'); 
			  $("#dosubmit").removeAttr("disabled");
			  res = true;		  			  
		 }else if(msg == '-1'){
			  $('#email').next().html('<img src="./templets/images/error.gif">电子邮箱已存在');
			  $('#email').next().addClass('error'); 			  
		 }else if(msg == '-2'){
			  $('#email').next().html('<img src="./templets/images/error.gif">电子邮箱格式不正确');
			  $('#email').next().addClass('error'); 			  
		 }else{
			  $('#email').next().html('<img src="./templets/images/error.gif">未知错误');
			  $('#email').next().addClass('error');			  
		 }				 
		} 
	  });
	   return res;
	 }else{
		  $('#email').next().html('<img src="./templets/images/error.gif">电子邮箱格式不正确');
		  $('#email').next().addClass('error');
		  return res;
	 }		   
}

function checkall(){
	 if(!(checkname() && checkemail())){
		 return false;
	 }
     if(!(checkpass() && checkpass2())) return false;
	 if($("#code").val() == ''){
	   $('.codeimg').next().html('<img src="./templets/images/error.gif">验证码不能为空');
	   return false;
	 }else{
	   $('codeimg').next().html('<img src="./templets/images/ok.gif">'); 
	 }
	 if(!$('#agree').attr('checked')){
	   $('#agree').next().html('<img src="./templets/images/error.gif">你必须同意注册协议'); 
	   return false;
	 }else{
	   $('#agree').next().html('点击阅读注册协议'); 
	 }   
   return true;
} 

function show_protocol(){
 $(".protocol").show();
}

function close_protocol(){
 $(".protocol").hide()
}