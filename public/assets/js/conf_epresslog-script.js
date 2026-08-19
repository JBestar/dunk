$(document).ready(function() {
    setNavBarElement();
    requestTotalPage();
    addBtnEvent();
    setInterval(function() {
        requestLogHistory();
    }, 60000);
});

function requestPageInfo() {
    requestLogHistory();
}

function addBtnEvent() {

    $("#pbhistory-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#pbhistory-number-select-id").change(function() {
        requestTotalPage();
    });
    
    $("#pbhistory-type-select-id").change(function() {
        requestTotalPage();
    });
}

function ShowLogHistory(arrInfo){
    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in arrInfo) {
        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_mb_uid;
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_data;
        strBuf += "</td>";
        if(parseInt(arrInfo[nRow].log_type) == 2)
            strBuf += "<td  class = 'pb-home-table-betstate-earn'>자동";
        else
            strBuf += "<td>수동";
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_time;
        strBuf += "</td><td>";
        strBuf += arrInfo[nRow].log_memo;
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
    }
    $("#pbbet-table-id").html(strBuf);

}

function requestLogHistory() {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
    var type = $("#pbhistory-type-select-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var nPage = getActivePage();
    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "start": dtStart,
        "end": dtEnd,
        "user": strUser,
        "type": type,
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/eballoglist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowLogHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage() {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var type = $("#pbhistory-type-select-id").val();
   
    var jsonData = {
        "count": CountPerPage,
        "start": dtStart,
        "end": dtEnd,
        "user": strUser,
        "type": type,
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/eballogcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestLogHistory();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}