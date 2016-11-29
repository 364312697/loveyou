$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var filesize = $('.filesize');
	var showimg = $('.showimg');
	var progress = $(".progress");
	var files = $(".files");
	var upbox = $(".upbox span");
	$("#fileupload").wrap("<form id='myupload' action='libs/upload.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
				upbox.html("上传中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
			success: function(data) {
			    filesize.html(data.size+"k <span class='delimg' rel='"+data.name+"'>删除</span>");
				var img = data.path+data.name;
				$("#uphidden").val(img);
				showimg.attr('src',img); 
				upbox.html("添加附件");
				files.html('<p class="green">上传成功!</p>');
			},
			error:function(xhr){
				upbox.html('上传失败');
				bar.width('0')
				progress.html('');
				files.html(xhr.responseText);
			}
		});
	});
	
	$(".delimg").live('click',function(){
		var pic = $(this).attr("rel");
		$.post("libs/upload.php?del=delimg",{imagename:pic},function(msg){
			if(msg==1){
				files.html("删除成功.");
				showimg.attr('src','../common/images/thumbnail.jpg');
				$("#uphidden").val('');
				progress.hide();
			}else{
				alert(msg);
			}
		});
	});
});