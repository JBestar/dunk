$(document).ready(function() {

    addEventListner();
    requestTotalPage();
});


function requestPageInfo() {
    requestGame();
}

function addEventListner() {
    $("#confsite-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#confsite-prd-select-id").change(function() {
        requestTotalPage();
    });

    $("#confsite-number-select-id").change(function() {
        requestTotalPage();
    });

}

function showGame(list, appType) {
    var html = "";

    if (list) {
        var curPage = getActivePage();
        var firstIdx = (curPage - 1) * CountPerPage;
        var nRow = 0;
        list.forEach((game) => {
            html += "<tr><td>";
            html += (parseInt(nRow) + firstIdx + 1);
            html += "</td><td>";
            if(appType == 2)
                html += "<img class = 'zoom' width='25px' height='25px' src='" + game.img + "'></img>";
            else
                html += "<img class = 'zoom' width='25px' height='25px' src='" + game.xslot_img + "'></img>";
            html += "</td><td>";
            html += game.provider;
            html += "</td><td>";
            if(appType == 2)
                html += game.name_ko;
            else html += game.xslot_name_ko;
            html += "</td><td>";
            if(appType == 2)
                html += game.name;
            else html += game.xslot_name;
            html += "</td><td>";
            html += "<select class='' onchange='onChangeHidden(this)' data-fid='" + game.fid + "' ";
            if (game.hidden == 0) {
                html += " style='color:black'> <option selected='' value='0'>보이기</option><option value='1'>감추기</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>보이기</option><option selected='' value='1'>감추기</option></select>";
            }
            html += "</td><td>";
            html += "<select class='' onchange='onChangeMaintain(this)' data-fid='" + game.fid + "' ";
            if (game.maintain == 0) {
                html += " style='color:black'> <option selected='' value='0'>운영</option><option value='1'>점검</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>운영</option><option selected='' value='1'>점검</option></select>";
            }
            html += "</td><td>";

            html += "<select class='act' onchange='onChangeAct(this)' data-fid='" + game.fid + "' ";
            if(appType == 2){
                // if(game.fslot_cnt > 1){
                //     html += " style='color:red'> ";
                //     html += "<option "+(game.act==0?"selected":"")+" value='0'>OUR</option>";
                //     html += "<option "+(game.act!=0?"selected":"")+" value='1'>OUR_NEW</option>";
                // } else if(game.prd_code == 215){
                //     html += " style='color:black'> ";
                //     html += "<option selected value='0'>OUR_NEW</option>";
                // } else {
                    html += " style='color:black'> ";
                    html += "<option selected value='0'>OUR</option>";
                // }
            } else {
                html += (game.act == 1 ?" style='color:black'> ":" style='color:red'> ");
                // if(game.fslot_cnt > 1){
                //     html += "<option "+(game.act==1?"selected":"")+" value='1'>OUR</option>";
                //     html += "<option "+(game.act==2?"selected":"")+" value='2'>OUR_NEW</option>";
                // } else{
                    // if(parseInt(game.fslot_prd) != 215)
                        html += "<option "+(game.act==1?"selected":"")+" value='1'>네츄럴</option>";
                    // else 
                    //     html += "<option "+(game.act==2?"selected":"")+" value='2'>OUR_NEW</option>";
                // }
                html += "<option "+(game.act==0?"selected":"")+" value='0'>정품</option></select>";
            }
            

            html += "</td><tr>";
            nRow++;
        });
        if (html.length < 1) {
            html = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
        }
    }
    $("#confsite-table-data").html(html);

}

function onChangeHidden(objSelect) {

    let iType = $(objSelect).val();
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "hidden": iType == 1 ? 1 : 0,
        "fid": $(objSelect).data("fid"),
    };
    requestGameSet(jsonData);
}

function onChangeMaintain(objSelect) {

    let iType = $(objSelect).val();
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "maintain": iType == 1 ? 1 : 0,
        "fid": $(objSelect).data("fid"),
    };
    requestGameSet(jsonData);
}

function onChangeAct(objSelect) {

    let iType = $(objSelect).val();
    // console.log(iType);
    if (iType == 0)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "act": iType,
        "fid": $(objSelect).data("fid"),
    };
    requestGameSet(jsonData);
    
}

function requestGame() {

    var nPage = getActivePage();
    var sGame = $("#confsite-game-input-id").val();
    var sPrd = $("#confsite-prd-select-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "name": sGame,
        "prd":sPrd,
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/fslotlist",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
                showGame(jResult.data, jResult.app);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            $(".loading").hide();
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#confsite-number-select-id").val();
    var sGame = $("#confsite-game-input-id").val();
    var sPrd = $("#confsite-prd-select-id").val();

    var jsonData = {
        "count": CountPerPage,
        "name": sGame,
        "prd":sPrd,
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/fslotcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestGame();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}


function requestGameSet(data){
    let jsonData = JSON.stringify(data);
    // console.log(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/fslotset",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {}
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}