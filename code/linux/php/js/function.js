$(function(){
		$(".control").live("mouseover",function(){
			//alert($(this).attr('alt'))
			var pos = $(this).offset();
			var left = pos.left + 20 + "px";
			var top = pos.top + 20 + "px";
			$("div#alt").css({
				"zIndex" : 500 ,      
				"left": left,                  
				"top": top 
			})
			$("div#alt").text($(this).attr('alt'))
			$("div#alt").show()
		})
		
		$(".control").live("mouseout",function(){
			$("div#alt").hide()
		})
	})
	
	function newfolder(msg){
	var folder=prompt(msg,"");
	if ((folder!="") && (folder!=null))
	{
		var query_string = ("#listform").attr("action")
		document.listform.action="?" + query_string + "&newfolder=" + escape(folder);
		document.listform.submit();
	}
	}
	
	function deleted(){
		if(confirm('ยืนยันการลบ')) {
			$("form#listform").append(
				$("<input>").attr({
					"type" : "hidden",
					"name" : "deleted"
				})
			)
		}
	} 
	
	function rename(msg){
		var count = 0;	
		$("#listform tr").find(" :checked").each(function(){
			oldname = $(this).parent().parent().find("td:eq(1)").text()
				count++;
		})
		if(count == 1){
			var file=prompt(msg, oldname);
			if((file!="") && (file!=null))
			{
				document.listform.action = query_string + "&rename=" + escape(file);
				document.listform.submit();
			}
		}else alert("เลือกหนึ่งรายการเท่านั้น!!")	
	}
	
	