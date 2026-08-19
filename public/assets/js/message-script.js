$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestNotice();
}


function showNotice(arrNotice) {

    var strBuf = "";
    var nRow;
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in arrNotice) {
        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        if (arrNotice[nRow].notice_type == 0)
            strBuf += "쪽지";
        else if (arrNotice[nRow].notice_type == 3)
            strBuf += "고객문의";
        strBuf += "</td> <td>";
        if (arrNotice[nRow].notice_type == 0) {
            if (arrNotice[nRow].notice_state_active == 1)
                strBuf += "<button name='" + arrNotice[nRow].notice_fid + "'  class='button-active'>발송</button>";
            else if (arrNotice[nRow].notice_state_active == 0)
                strBuf += "<button name='" + arrNotice[nRow].notice_fid + "' >대기</button>";
        } else if (arrNotice[nRow].notice_type == 3) {
            if (arrNotice[nRow].notice_read_count == 0)
                strBuf += "<span style='color:red;'>읽지 않음<span>";
            else if (arrNotice[nRow].notice_state_active == 0)
                strBuf += "<span  style='color:#ff9241;'>답변 미정<span>";
            else strBuf += "답변 완료";
        }
        strBuf += "</td> <td>";
        strBuf += arrNotice[nRow].notice_title;
        strBuf += "</td> <td>";
        strBuf += arrNotice[nRow].notice_time_create;
        strBuf += "</td> <td>";
        if (arrNotice[nRow].notice_mb_uid == '*')
            strBuf += "전체";
        else {
            strBuf += "<a onclick='popupMemberUid(\"" + arrNotice[nRow].notice_mb_uid + "\")' class='link-member'>"+ arrNotice[nRow].notice_mb_uid;
            if(arrNotice[nRow].mb_nickname != null)
                strBuf += "("+arrNotice[nRow].mb_nickname+")";
            strBuf += "</a>";
        }
            
        strBuf += "</td> <td>";
        if (arrNotice[nRow].notice_type == 0)
            strBuf += "<a href='"+FURL+"/board/message_edit/" + arrNotice[nRow].notice_fid + "/0' >수정</a>";
        else if (arrNotice[nRow].notice_type == 3)
            strBuf += "<a href='"+FURL+"/board/message_edit/" + arrNotice[nRow].notice_fid + "/0' >보기</a>";

        strBuf += "<button name='" + arrNotice[nRow].notice_fid + "'>삭제</button>   ";

        strBuf += "</td></tr>";
    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='7'>자료가 없습니다.</td></tr>";
    }
    document.getElementById("notice-table-id").innerHTML = strBuf;
    addBtnEvent();
}




function addEventListner() {
    $("#message-list-view-but-id").click(function() {
        requestTotalPage();
    });

    var selectView = document.getElementById("message-number-select-id");
    $("#message-number-select-id").change(function() {
        requestTotalPage();
    });

    $("#message-type-select-id").change(function() {
        requestTotalPage();
    });
}




function addBtnEvent() {
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

    var elemTable = document.getElementById("notice-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;
    var i;
    for (i = 0; i < elemTableBtns.length; i++) {

        elemTableBtns[i].addEventListener("click", function() {
            if (this.innerHTML.search("삭제") >= 0) {
                if(!confirm("삭제하시겠습니까?"))
                    return;
                var jsonData = { "notice_fid": this.name, "notice_state_delete": 1 };
                requestUpdateNotice(jsonData);
            } else if (this.innerHTML == "발송") {
                var jsonData = { "notice_fid": this.name, "notice_state_active": 0 };
                requestUpdateNotice(jsonData);
            } else if (this.innerHTML == "대기") {
                var jsonData = { "notice_fid": this.name, "notice_state_active": 1 };
                requestUpdateNotice(jsonData);
            }
        });
    }

}


function requestUpdateNotice(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/updateNotice",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);

            if (jResult.status == "success") {
                requestNotice();
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}




function requestNotice() {

    var nPage = getActivePage();
    var strNoticeType = $("#message-type-select-id").val();
    var strMbUid = $("#message-userid-input-id").val();
    var jsonData = { "count": CountPerPage, "page": nPage, "notice_type": strNoticeType, "notice_mb_uid": strMbUid };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getmessage",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                showNotice(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {

    CountPerPage = document.getElementById("message-number-select-id").value;
    var strNoticeType = document.getElementById("message-type-select-id").value;
    var strMbUid = document.getElementById("message-userid-input-id").value;

    var jsonData = { "count": CountPerPage, "notice_type": strNoticeType, "notice_mb_uid": strMbUid };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/getmessagecnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestNotice();
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}