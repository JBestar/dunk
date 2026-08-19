$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestMember();
}


function showMember(arrMember) {

    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (var nRow in arrMember) {
        strBuf += "<tr>";

        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].log_mb_uid;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_nickname;
        strBuf += "</td> <td>";

        if(parseInt(arrMember[nRow].log_type) != 0){
            if(parseInt(arrMember[nRow].log_type) == 1)
                strBuf += "에볼앱";
            else if(parseInt(arrMember[nRow].log_type) == 2)
                strBuf += "프라그앱";
            if(arrMember[nRow].log_spec.length > 0)
                strBuf += " ( "+arrMember[nRow].log_spec+" )"; 
        } 
        else 
            strBuf += arrMember[nRow].log_ip;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].log_time;
        strBuf += "</td> <td>";
        if(parseInt(arrMember[nRow].log_type) == 0){
            if (arrMember[nRow].block_state == 1) {
                strBuf += "<button style='color:#000000' name='" + arrMember[nRow].log_ip + "' >차단</button>";
            } else
                strBuf += "<button class='button-active' name='" + arrMember[nRow].log_ip + "' >승인</button>";
        }
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
    }

    $("#user-member-table-id").html(strBuf);
    addBtnEvent();
}



function addEventListner() {
    $("#userpanel-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#userpanel-number-select-id").change(function() {
        requestTotalPage();
    });
}



function requestMember() {

    var nPage = getActivePage();
    var search = $("#userpanel-userid-input-id").val();
    var dtStart = $("#userpanel-datestart-input-id").val();
    var dtEnd = $("#userpanel-dateend-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "type": 0,
        "search": search,
        "start": dtStart,
        "end": dtEnd
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/loglist",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#userpanel-number-select-id").val();
    var search = $("#userpanel-userid-input-id").val();
    var dtStart = $("#userpanel-datestart-input-id").val();
    var dtEnd = $("#userpanel-dateend-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "search": search,
        "type": 0,
        "start": dtStart,
        "end": dtEnd
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/userapi/logcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestMember();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function requestAddBlock(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/add_block",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                requestPageInfo();
                // location.replace( FURL +'/user/member_block');
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


function addButtonElementListener(buttonElement) {
    buttonElement.addEventListener("click", function() {

        if (this.innerHTML.search("차단") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 0 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("승인") >= 0) {
            var jsonData = { "block_ip": this.name, "block_state": 1 };
            requestAddBlock(jsonData);
        }
    });
}

function addBtnEvent() {
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

    var elemTable = document.getElementById("user-member-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        addButtonElementListener(elemTableBtns[i]);
    }
}