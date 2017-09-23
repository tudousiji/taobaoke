$(function(){
	var url = "/indexcate/getIndexCateUrl/" + id;
	//alert(url);
	$.get(url,function(data) {
      var obj = eval('(' + data + ')');
      //alert(obj.url);
      if(obj.Code==0){
    	  var html ='<iframe id="iframepage" scrolling="no" onload="changeFrameHeight()" frameborder="0" src="'+obj.url+'"></iframe>'
    	  //$("#D-main").html(html);
      }
      
  });
	
	
	function changeFrameHeight(){
		alert("23");
	    var ifm= document.getElementById("iframepage"); 
	    ifm.height=document.documentElement.clientHeight;

	}

	window.onresize=function(){  
	     changeFrameHeight();  

	} 
	
});





