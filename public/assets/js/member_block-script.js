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
        strBuf += arrMember[nRow].block_ip;
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].block_updated;
        strBuf += "</td> <td>";
        if (arrMember[nRow].block_state == 1) {
            strBuf += "<button name='" + arrMember[nRow].block_fid + "' style='color:#000000' >차단</button>";
        } else {
            strBuf += "<button name='" + arrMember[nRow].block_fid + "' class='button-active' >승인</button>";
        }
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].block_fid + "' >삭제</button>";
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
    var strIp = $("#userpanel-ip-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "ip": strIp,
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/blocklist",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
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
    var strIp = $("#userpanel-ip-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "ip": strIp,
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/userapi/blockcnt',
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

        if (this.innerHTML.search("차단") >= 0) {
            var jsonData = { "block_fid": this.name, "block_state": 0 };
            requestUpdateBlock(jsonData);
        } else if (this.innerHTML.search("승인") >= 0) {
            var jsonData = { "block_fid": this.name, "block_state": 1 };
            requestUpdateBlock(jsonData);
        } else if (this.innerHTML.search("삭제") >= 0) {
            var jsonData = { "block_fid": this.name };
            requestDeleteBlock(jsonData);
        }
    });
}


function requestUpdateBlock(jsData) {

    var jsonData = JSON.stringify(jsData);
    // console.log(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/update_block",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


function requestDeleteBlock(jsData) {

    if (!confirm("삭제하시겠습니까?"))
        return;

    var jsonData = JSON.stringify(jsData);
    // console.log(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/delete_block",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
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
