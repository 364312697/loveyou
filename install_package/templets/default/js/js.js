$(function(){
	$(".small li").mouseover(function(){$(this).addClass("now");});
	$(".small li").mouseleave(function(){$(this).removeClass("now");});
	var xx = 0;
	$(".small ul > li").click(tab);
	function tab(aa) { 
		aa = $(this).index();
		xx = aa;
		$(this).addClass("active").siblings().removeClass("active"); 
		var tabnum = $(this).attr("rel");
		$("#" + tabnum).fadeIn("slow").siblings().hide(); 
		$(".big_title").eq(xx).show().siblings().hide();
	}; 

	function setTab() {
		$(".big_title").eq(xx).show().siblings().hide();
		$(".small li").eq(xx).addClass("active").siblings().removeClass("active");
		$(".big div").eq(xx).fadeIn("slow").siblings().hide();
		xx+=1;
		if(xx>7) xx=0;
	}
	setInterval(setTab,2000);
});



$(function(){
	$('#statetab .tabbtn li').mouseover(function(){
		TabSelect("#statetab .tabbtn li", "#statetab .tabcon", "current", $(this))
	});
	$('#statetab .tabbtn li').eq(0).trigger("mouseover");
	$('#clicktab .tabbtn li').click(function(){
		TabSelect("#clicktab .tabbtn li", "#clicktab .tabcon", "current", $(this))
	});
	$('#clicktab .tabbtn li').eq(0).trigger("click");

	function TabSelect(tab,con,addClass,obj){
		var $_self = obj;
		var $_nav = $(tab);
		$_nav.removeClass(addClass),
		$_self.addClass(addClass);
		var $_index = $_nav.index($_self);
		var $_con = $(con);
		$_con.hide(),
		$_con.eq($_index).show();
	}

});


function toreply(obj){
    if($("#rep_" + obj).css("display") == "none"){
        $("#rep_" + obj).css("display", "block");
    }else{
        $("#rep_" + obj).css("display", "none");
    }
}

function check_comm(obj){
    var content = obj.content.value;
	if(content === ''){
	    alert('你不打算说点什么吗？');
		return false;
	}
	return true;
}

function check_rep(obj){
    var content = obj.content.value;
	if(content === ''){
	    alert('你不打算说点什么吗？');
		return false;
	}
	return true;
}