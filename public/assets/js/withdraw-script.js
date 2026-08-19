$(document).ready(function() {
    addEventListner();
    requestTotalPage();
    setTimeout(function() {
        pageLoop();
    }, 120000);
});

function pageLoop() {
    requestWithdrawList(true);
    // 1초뒤에 다시 실행
    setTimeout(function() {
        pageLoop();
    }, 120000);

}

function requestPageInfo() {
    requestWithdrawList();
}


function addEventListner() {
    $("#withdraw-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#withdraw-number-select-id").change(function() {
        requestTotalPage();
    });
    
    $("#withdraw-list-permit-but-id").click(function() {
        let items = getCheckedItems();
        if(items.length < 1){
            showAlert("일괄 처리대상들을 선택해 주세요.", 3);
            return;
        }

        if (confirm("선택된 대상들을 승인하시겠습니까?")) {
            var jsonData = { "exchange_fids": items, "process": 1 };
            requestProcWithdraws(jsonData);
        }
    });

    $("#withdraw-list-refuse-but-id").click(function() {
        let items = getCheckedItems();
        if(items.length < 1){
            showAlert("일괄 처리대상들을 선택해 주세요.", 3);
            return;
        }
        if (confirm("선택된 대상들을 거절하시겠습니까?")) {
            var jsonData = { "exchange_fids": items, "process": 2 };
            requestProcWithdraws(jsonData);
        }
    });

    $("#withdraw-list-wait-but-id").click(function() {
        let items = getCheckedItems();
        if(items.length < 1){
            showAlert("일괄 처리대상들을 선택해 주세요.", 3);
            return;
        }
        if (confirm("선택된 대상들을 임시대기시키겠습니까?")) {
            var jsonData = { "exchange_fids": items, "process": 3 };
            requestProcWithdraws(jsonData);
        }
    });
}

function getCheckedItems(){
    let list = [];
    let items = $(".user-table input[type='checkbox']");
    for (let item of items) {
        console.log(item);
        if($(item).is(":checked") == true)
            list.push(item.name);
    }
    return list;
}


function showWithdrawList(arrData) {
    var elemListTb = document.getElementById("bank-withdraw-table-id");
    var strBuf = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in arrData) {
        strBuf += "<tr><td>";
        if(arrData[nRow].exchange_action_state == 1 || arrData[nRow].exchange_action_state == 4)
            strBuf += "<input type='checkbox' style='zoom:120%;' name='" + arrData[nRow].exchange_fid + "'>";
        strBuf += "</td><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += "<a onclick='popupMemberUid(\"" + arrData[nRow].exchange_mb_uid + "\")' class='link-member'>"+ arrData[nRow].mb_nickname+ "</a>";
        strBuf += "</td><td>";
        strBuf += arrData[nRow].exchange_mb_uid;
        strBuf += "</td><td>";
        strBuf += Math.floor(arrData[nRow].mb_money).toLocaleString() + " 원";
        strBuf += "</td><td>";
        strBuf += arrData[nRow].exchange_time_require;
        strBuf += "</td><td>";
        strBuf += Math.floor(arrData[nRow].exchange_money).toLocaleString() + " 원";
        strBuf += "</td><td>";
        strBuf += arrData[nRow].exchange_bank_name + ":" + arrData[nRow].exchange_bank_account + ":" + arrData[nRow].exchange_bank_serial;
        strBuf += "</td><td>";
        strBuf += arrData[nRow].exchange_mb_phone;
        strBuf += "</td>";
        if (arrData[nRow].exchange_action_state == 1)
            strBuf += "<td>승인대기";
        else if (arrData[nRow].exchange_action_state == 2)
            strBuf += "<td style='color:blue;'>환전완료";
        else if (arrData[nRow].exchange_action_state == 3)
            strBuf += "<td style='color:red;'>환전취소";
        else if (arrData[nRow].exchange_action_state == 4)
            strBuf += "<td style='color:#aab000;'>임시대기";
        else if (arrData[nRow].exchange_action_state == 5)
            strBuf += "<td style='color:#aab000;'>직환전";
        else strBuf += "<td>";
        strBuf += "</td><td>";
        if (arrData[nRow].exchange_time_process != null)
            strBuf += arrData[nRow].exchange_time_process;
        strBuf += "</td><td>";
        if (arrData[nRow].exchange_action_uid != null)
            strBuf += arrData[nRow].exchange_action_uid;
        strBuf += "</td><td>";
        if (arrData[nRow].exchange_action_state == 1) {
            strBuf += "<button name='" + arrData[nRow].exchange_fid + "' >승인</button>";
            strBuf += "<button name='" + arrData[nRow].exchange_fid + "' >거절</button>";
            strBuf += "<button name='" + arrData[nRow].exchange_fid + "' >대기</button>";
        } else if (arrData[nRow].exchange_action_state == 2) {
            // strBuf += "<button name='" + arrData[nRow].exchange_fid + "' >취소</button>";
        } else if (arrData[nRow].exchange_action_state == 3) {
            // strBuf += "<button name='" + arrData[nRow].exchange_fid + "' >승인</button>";
        } else if (arrData[nRow].exchange_action_state == 4) {
            strBuf += "<button name='" + arrData[nRow].exchange_fid + "' >승인</button>";
            strBuf += "<button name='" + arrData[nRow].exchange_fid + "' >거절</button>";
        }
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='12'>자료가 없습니다.</td></tr>";
    }

    elemListTb.innerHTML = strBuf;

    addButtonEvent();
}

function addButtonEvent() {
    var elemListTb = document.getElementById("bank-withdraw-table-id");
    var elemTableBtns = elemListTb.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;

    for (i = 0; i < elemTableBtns.length; i++) {

        elemTableBtns[i].addEventListener("click", function() {
            /*
	      if(this.innerHTML.search("삭제") >=0){
	      	if (confirm("삭제하시겠습니까?")){
				var jsonData = { "exchange_fid":this.name, "process":0};
		      	requestProcWithdraw(jsonData);
	      	}
	      } else */
            if (this.innerHTML.search("취소") >= 0) {
                if (confirm("취소하시겠습니까?")) {
                    var jsonData = { "exchange_fid": this.name, "process": 0 };
                    requestProcWithdraw(jsonData);
                }
            } else if (this.innerHTML.search("승인") >= 0) {
                if (confirm("승인하시겠습니까?")) {
                    var jsonData = { "exchange_fid": this.name, "process": 1 };
                    requestProcWithdraw(jsonData);
                }
            } else if (this.innerHTML.search("거절") >= 0) {
                if (confirm("거절하시겠습니까?")) {
                    var jsonData = { "exchange_fid": this.name, "process": 2 };
                    requestProcWithdraw(jsonData);
                }
            } else if (this.innerHTML.search("대기") >= 0) {
                if (confirm("임시대기시키겠습니까?")) {
                    var jsonData = { "exchange_fid": this.name, "process": 3 };
                    requestProcWithdraw(jsonData);
                }
            }

        });
    }

}

function requestWithdrawList(auto = true) {

    var strUid = document.getElementById("withdraw-userid-input-id").value;
    var dtStart = document.getElementById("withdraw-datestart-input-id").value;
    var dtEnd = document.getElementById("withdraw-dateend-input-id").value;
    var nPage = getActivePage();

    var jsonData = { 
        "count": CountPerPage, 
        "page": nPage, 
        "mb_uid": strUid, 
        "start": dtStart, 
        "end": dtEnd,
        "auto":auto, 
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/withdrawlist",
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                showWithdrawList(jResult.data);

                $("#bank-withdraw-total-id").text(parseInt(jResult.total).toLocaleString() + " 원");
                $("#bank-withdraw-wait-id").text(parseInt(jResult.wait).toLocaleString() + " 원");
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}



function requestTotalPage() {

    var strUid = document.getElementById("withdraw-userid-input-id").value;
    var dtStart = document.getElementById("withdraw-datestart-input-id").value;
    var dtEnd = document.getElementById("withdraw-dateend-input-id").value;
    CountPerPage = document.getElementById("withdraw-number-select-id").value;

    var jsonData = { "count": CountPerPage, "mb_uid": strUid, "start": dtStart, "end": dtEnd };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/withdrawlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestWithdrawList();
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}



function requestProcWithdraw(jsData) {

    if (mDischarAudio != undefined && mDischarAudio != null) {
        mDischarAudio.pause();
    }

    var jsonData = JSON.stringify(jsData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/withdrawproc",
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                requestEmployeeInfo();
                setTimeout(function() { requestWithdrawList(); }, 500);
            } else if (jResult.status == "fail") {
                showAlert("환전처리가 실패되었습니다.", 0);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function requestProcWithdraws(jsData) {
    if (mDischarAudio != undefined && mDischarAudio != null) {
        mDischarAudio.pause();
    }
    $(".loading").show();
    var jsonData = JSON.stringify(jsData);
    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/Withdrawsproc",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                requestEmployeeInfo();
                if(jResult.msg){
                    showAlert(jResult.msg);
                }
                setTimeout(function() { requestWithdrawList(); }, 500);
            } else if (jResult.status == "fail") {
                showAlert("환전처리가 실패되었습니다.", 0);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}