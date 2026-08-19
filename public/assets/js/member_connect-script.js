$(document).ready(function() {
    addEventListner();
    requestTotalPage();
    setTimeout(function() {
        hitoryLoop();
    }, 120000);
});

function requestPageInfo() {
    requestMember();
}

function hitoryLoop() {
    requestMember(true);
    // 120초뒤에 다시 실행
    setTimeout(function() {
        hitoryLoop();
    }, 120000);

}

function showMember(arrMember, bFollow=false) {

    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (var nRow in arrMember) {
        strBuf += "<tr>";
        arrMember[nRow].sess_type = parseInt(arrMember[nRow].sess_type);
        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        strBuf += "<a onclick=' popupMemberEdit(" + arrMember[nRow].sess_mb_fid + ", "+arrMember[nRow].sess_mb_fid+ ")' class='link-member'>"+ arrMember[nRow].sess_mb_uid+ "</a>";
        strBuf += "</td> <td>";
        if(bFollow){
            strBuf += "<a onclick=' popupMemberFollow(" + arrMember[nRow].sess_mb_fid + ", "+arrMember[nRow].sess_mb_fid+ ")' class='link-member'>"+ arrMember[nRow].mb_nickname;
            if(arrMember[nRow].sess_type == 1 || arrMember[nRow].sess_type == 2)
                strBuf += " ( "+ arrMember[nRow].sess_follows +" )";    
            strBuf += "</a>";
        }
        else strBuf += arrMember[nRow].mb_nickname;
        strBuf += "</td> <td>";
        strBuf += Math.floor(arrMember[nRow].mb_money).toLocaleString();
        strBuf += "</td> <td>";
        strBuf += Math.floor(arrMember[nRow].mb_point).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].sess_type == 1 || arrMember[nRow].sess_type == 2){
            if(arrMember[nRow].sess_type == 1)
                strBuf += "에볼앱";
            else 
                strBuf += "프라그앱";

            if(arrMember[nRow].sess_spec.length > 0)
                strBuf += " ( "+arrMember[nRow].sess_spec+" )"; 
        }
        else 
            strBuf += arrMember[nRow].sess_ip;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].sess_join;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].sess_update;
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].sess_mb_fid + "' data-nickname='" + arrMember[nRow].mb_nickname + "'>강제아웃</button>   ";
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='9'>자료가 없습니다.</td></tr>";
    }

    $("#user-member-table-id").html(strBuf);

    addBtnEvent();
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



function addButtonElementListener(buttonElement) {
    buttonElement.addEventListener("click", function() {

        let tHtml = this.innerHTML; 
        if (tHtml.search("강제아웃") >= 0) {
            let nickname  = $(this).data('nickname');
            if (!confirm(nickname+" 회원을 강제아웃 시키겠습니까?"))
                return;
            var jsonData = { "mb_fid": this.name };
            requestLogoutMember(jsonData);
        }
    });
}

function addEventListner() {
    $("#userpanel-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#userpanel-number-select-id").change(function() {
        requestTotalPage();
    });
    
    $("#userpanel-type-select-id").change(function() {
        requestTotalPage();
    });
}



function requestMember(auto=false) {

    var nPage = getActivePage();
    var strUid = $("#userpanel-userid-input-id").val();
    var type = 0;
    if($("#userpanel-type-select-id").length > 0)
        type = $("#userpanel-type-select-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "mb_uid": strUid,
        "type":type,
        "auto":auto
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/conlist",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.follow);
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
    var strUid = $("#userpanel-userid-input-id").val();
    var type = 0;
    if($("#userpanel-type-select-id").length > 0)
        type = $("#userpanel-type-select-id").val();

    var jsonData = {
        "count": CountPerPage,
        "mb_uid": strUid,
        "type":type,
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/userapi/concnt',
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

function requestLogoutMember(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/logoutmember",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
                showAlert('로그아웃되었습니다.', 0);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                window.location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}
