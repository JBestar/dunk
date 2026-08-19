$(document).ready(function() {
    setNavBarElement();
    requestConf();
    addBtnEvent();
    requestList();
    setInterval(function() {
        requestList();
    }, 60000);
});

function showConf(data) {
    if (data && data.length >= 5) {
        $("#confev-autopress-check-id").prop('checked', data[0] == 1 ? true : false);
        $("#confev-autopercent-input-id").val(parseInt(data[1])); 
        $("#confev-failpress-check-id").prop('checked', data[2] == 1 ? true : false);
        $("#confev-failamount-input-id").val(parseInt(data[3])); 
        $("#confev-forcepress-check-id").prop('checked', data[4] == 1 ? true : false);
    }
}

function requestConf() {
    let gameId = $(".confsite-game-panel").attr('id');

    var jsonData = {
        "cat":gameId
    };

    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getevpress",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConf(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var objData = new Object();

        objData.auto_press = $("#confev-autopress-check-id").prop('checked') ? 1 : 0;
        objData.auto_percent = $("#confev-autopercent-input-id").val();
        objData.fail_press = $("#confev-failpress-check-id").prop('checked') ? 1 : 0;
        objData.fail_amount = $("#confev-failamount-input-id").val();
        objData.force_press = $("#confev-forcepress-check-id").prop('checked') ? 1 : 0;

        var jsonData = JSON.stringify(objData);

        if (!confirm("저장하시겠습니까?"))
            return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/setevpress",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    showAlert("저장이 실패되었습니다.", 0);
                } else if (jResult.status == "nopermit") {
                    showAlert("권한이 없습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });
    });

    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });
    
}

function ShowList(arrInfo){
    var strBuf = "";

    var firstIdx = 0;

    for (nRow in arrInfo) {
        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].sess_mb_uid;
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].sess_join;
        strBuf += "</td><td>";
        strBuf += parseFloat(arrInfo[nRow].sess_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += parseFloat(arrInfo[nRow].mb_money).toLocaleString();
        strBuf += "</td>";
        if(parseInt(arrInfo[nRow].mb_state_view) == 1)
            strBuf += "<td  class = 'pb-home-table-betstate-wait'>누르기";
        else 
            strBuf += "<td  class = 'pb-home-table-betstate-loss'>넘기기";
        strBuf += "</td><td>";
        if(parseInt(arrInfo[nRow].mb_state_view) == 1)
            strBuf += "<button onclick='changePress(" + arrInfo[nRow].sess_mb_fid + ", 0)'>넘기기</button> ";
        else 
            strBuf += "<button onclick='changePress(" + arrInfo[nRow].sess_mb_fid + ", 1)'>누르기</button> ";
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
    }
    $("#pbbet-table-id").html(strBuf);

}

function requestList() {

    var strUser = $("#pbhistory-userid-input-id").val();
    var jsonData = {
        "user": strUser,
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/evpresslist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowList(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function changePress(fid, state){
    var jsonData = {
        "mb_fid": fid,
        "mb_state_view": state,
    };
    jsonData = JSON.stringify(jsonData);
    $.ajax({
        url: FURL + '/api/changeevpress',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                requestList();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}
