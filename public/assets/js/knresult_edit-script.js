$(document).ready(function() {
    addBtnEvent();
});

function readConfigToObject() {

    var jsonData = new Object();

    jsonData.game = mGameId;
    jsonData.round_fid = $("#subnavbar-fid-p-id").text();
    jsonData.round_date = document.getElementById("pbresult_edit-rounddate-input-id").value;
    jsonData.round_num = document.getElementById("pbresult_edit-roundnum-input-id").value;

    var elemSelect = document.getElementById("pbresult_edit-power-oe-select-id");
    jsonData.round_result_1 = elemSelect.options[elemSelect.selectedIndex].value;

    elemSelect = document.getElementById("pbresult_edit-power-uo-select-id");
    jsonData.round_result_2 = elemSelect.options[elemSelect.selectedIndex].value;

    elemSelect = document.getElementById("pbresult_edit-super-oe-select-id");
    jsonData.round_result_3 = elemSelect.options[elemSelect.selectedIndex].value;

    elemSelect = document.getElementById("pbresult_edit-super-uo-select-id");
    jsonData.round_result_4 = elemSelect.options[elemSelect.selectedIndex].value;

    elemSelect = document.getElementById("pbresult_edit-hup-oe-select-id");
    jsonData.round_result_5 = elemSelect.options[elemSelect.selectedIndex].value;

    elemSelect = document.getElementById("pbresult_edit-hup-uo-select-id");
    jsonData.round_result_6 = elemSelect.options[elemSelect.selectedIndex].value;

    jsonData.round_power = document.getElementById("pbresult_edit-power-input-id").value;

    jsonData.round_normal = document.getElementById("pbresult_edit-normal-input-id").value;

    return jsonData;

}

function addBtnEvent() {

    $("#pbresult_edit-save-btn-id").click(function() {

        var jsonData = readConfigToObject();

        if (jsonData.round_date.length < 1) {
            alert("게임날짜는 필수정보입니다.");
            return;
        }

        if (jsonData.round_num.length < 1) {
            alert("게임회차는 필수정보입니다.");
            return;
        }

        if (!confirm("저장하시겠습니까?"))
            return;

        if (parseInt(jsonData.round_fid) > 0) {

            jsonData = JSON.stringify(jsonData);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: FURL + "/pbapi/modifyround",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        alert("저장되었습니다.");
                        goResultPage();
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        alert("저장이 실패되었습니다.");
                    }
                },
                error: function(request, status, error) {
                    console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }

            });
        } else if (parseInt(jsonData.round_fid) == 0) {

            jsonData = JSON.stringify(jsonData);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: FURL + "/pbapi/registerround",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        alert("저장되었습니다.");
                        goResultPage();
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        if (jResult.data == 2)
                            alert("이미 등록된 게임회차입니다.");
                        else if (jResult.data == 3)
                            alert("입력한 회차정보가 정확하지 않습니다.");
                        else if (jResult.data == 4)
                            alert("등록 가능한 회차가 아닙니다.");
                        else alert("저장이 실패되었습니다.");
                    }
                },
                error: function(request, status, error) {
                    // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }

            });
        }




    });


    $("#pbresult_edit-cancel-btn-id").click(function() {

        goResultPage();
    });

}
