<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
#console {
	overflow-x:hidden;
	overflow-y:scroll;
	height: 0px;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script language="javascript">
	setInterval(function() {
		var oldscrollHeight = $("#console").attr("scrollHeight") - 20;
		$.ajax({
			url: "chat.php",
			cache: false,
			success: function(html){		
				$("#console").html(html);			
				var newscrollHeight = $("#console").attr("scrollHeight") - 20;
				if(newscrollHeight > oldscrollHeight){
					$("#console").animate({ scrollTop: newscrollHeight }, 'normal');
				}				
		  	},
		});
		
	},2500);
	$(window).resize(function(){
		$("#console").height(window.innerHeight - 20);
	});
</script>
</head>

<body>
<div id="console"></div>
<script language="javascript">
	$("#console").height(window.innerHeight - 20);
</script>
</body>
</html>