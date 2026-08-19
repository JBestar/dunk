$(document).ready(function() {
    setNavBarElement();
    requestRooms();
    setTimeout(function() {
        requestLoop();
    }, 300000);
});

function ShowRooms(arrRoom){

    var strBuf = "";
    if(arrRoom != null && arrRoom.length > 0 ){
        let room = null;
        let totalCnt = arrRoom.length;
        let rowCnt = Math.ceil(totalCnt/2);
        let idx = 0;
        for (let i = 0; i < rowCnt ; i++) {
            idx = i;
            room = arrRoom[idx];

            strBuf += "<tr><td>";
            strBuf += (parseInt(idx)+1);
            strBuf += "</td><td>";
            strBuf += room.name;
            strBuf += "</td><td>";
            if(room.open == 0){
                strBuf += '<button class="button" style="background: rgb(87, 97, 97); width:30px; height:30px; border-radius:15px; "></button>';

            } else {
                strBuf += '<button class="button" style="background: rgb(0, 255, 0); width:30px; height:30px;  border-radius:15px; "></button>';
            }
            strBuf += '</td><td style="border-right:2px solid #aaa">';
            if(room.stop == 0){
                strBuf += '<button class="user-table button" onclick="setRoomState('+room.fid+', 1);" style="margin:0;">승인</button>';

            } else {
                strBuf += '<button class="user-table button" onclick="setRoomState('+room.fid+', 0);" style="background: rgb(255, 58, 90); color: #edff00; margin:0;">차단</button>';
            }
            strBuf += "</td><td>";
            
            idx = i+rowCnt;
            if(idx >= totalCnt){
                strBuf += "</td><td></td><td></td><td>";
            } else {
                room = arrRoom[idx];
                strBuf += (parseInt(idx)+1);
                strBuf += "</td><td>";
                strBuf += room.name;
                strBuf += "</td><td>";
                if(room.open == 0){
                    strBuf += '<button class="button" style="background: rgb(87, 97, 97); width:30px; height:30px; border-radius:15px; "></button>';
    
                } else {
                    strBuf += '<button class="button" style="background: rgb(0, 255, 0); width:30px; height:30px;  border-radius:15px; "></button>';
                }
                strBuf += "</td><td>";
                if(room.stop == 0){
                    strBuf += '<button class="user-table button" onclick="setRoomState('+room.fid+', 1);" style="margin:0;">승인</button>';
    
                } else {
                    strBuf += '<button class="user-table button" onclick="setRoomState('+room.fid+', 0);" style="background: rgb(255, 58, 90); color: #edff00; margin:0;">차단</button>';
                }
            }
            strBuf += "</td><tr>";

        }
    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='3'>자료가 없습니다.</td></tr>";
    }
    $("#pbbet-table-id").html(strBuf);

}

function requestRooms() {
    var jsonData = { "game": mGameId };
    jsonData = JSON.stringify(jsonData);
    // $(".loading").show();
    $.ajax({
        url: FURL + '/api/eroomlist',
        type: 'post',
        dataType: "json",
        data: { json_: jsonData },
        success: function(jResult) {
            // $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowRooms(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function setRoomState(fid, state){

    if(state == 0){
        if(!confirm("승인하시겠습니까?"))
            return;
    } else if(state == 1){
        if(!confirm("차단하시겠습니까?"))
            return;
    }

    var jsonData = {
        "id": fid,
        "stop": state,
    };
    $(".loading").show();

    jsonData = JSON.stringify(jsonData);
    $.ajax({
        url: FURL + '/api/eroomstate',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                requestRooms();
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function requestLoop() {

    requestRooms();

    // 1초뒤에 다시 실행
    setTimeout(function() {
        requestLoop();
    }, 300000);
}