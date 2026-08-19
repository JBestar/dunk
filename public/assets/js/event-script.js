

addBtnEvent();

function addBtnEvent(){
  /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

  var elemTable = document.getElementById("notice-table-id");
  var elemTableBtns = elemTable.getElementsByTagName("button");
  if(elemTableBtns == null)
  	return;
  var i;
  for (i = 0; i < elemTableBtns.length; i++) {
    
    elemTableBtns[i].addEventListener("click", function() {      
      if(this.innerHTML.search("삭제") >=0){
		var jsonData = { "notice_fid":this.name, "notice_state_delete":1};
      	requestUpdateNotice(jsonData);
      } else if(this.innerHTML=="공개"){      	
      	var jsonData = { "notice_fid":this.name, "notice_state_active":0};
      	requestUpdateNotice(jsonData);
      } else if(this.innerHTML=="비공개"){
      	var jsonData = { "notice_fid":this.name, "notice_state_active":1};
      	requestUpdateNotice(jsonData);
      } 
    });
  }

}


function requestUpdateNotice(jsData){
	//console.log(jsData);
   var jsonData = JSON.stringify(jsData);

   $.ajax({
       type: "POST",
       dataType: "json",
       url: FURL +"/api/updateNotice",
       data: {json_: jsonData},
       success: function(jResult) {
       	//console.log(jResult);

           if(jResult.status == "success")
           {
           		window.location.replace( FURL +'/board/event');
           }
           else if(jResult.status == "fail")
           {
               
           }
       	},
       	error:function(request,status,error){
          //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    	}

   });

}
