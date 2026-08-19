$(document).ready(function() {
    addEventListner();
    requestGame();
});


function addEventListner() {

    $("#confpb-maintain-select-id").change(function() {
        var jsonData = {
            "maintain": $(this).val(),
            "fid": 0,
        };
        requestGameSet(jsonData);
    });
    
    $("#confpb-display-select-id").change(function() {
        var jsonData = {
            "hidden": $(this).val(),
            "fid": 0,
        };
        requestGameSet(jsonData);
    });

}

function showGame(list) {
    if (list && list.length > 0) {
        $("#confpb-maintain-select-id").val(parseInt(list[0].maintain));
        $("#confpb-display-select-id").val(parseInt(list[0].hidden)); 
    }
}

function requestGame() {
    let gameId = $(".confsite-game-panel").attr('id');

    var jsonData = {
        "count": 1,
        "page": 1,
        "name": "",
        "cat":gameId
    };

    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/kgonlist",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showGame(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}



function requestGameSet(data){
    let jsonData = JSON.stringify(data);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/kgonset",
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