addBtnEvent();

function readConfigToObject() {

    var jsonData = new Object();

    jsonData.notice_fid = document.getElementById("subnavbar-fid-p-id").innerHTML;
    jsonData.notice_type = 2;
    jsonData.notice_state_active = document.getElementById("notice-state-check-id").checked ? 1 : 0;
    jsonData.notice_title = document.getElementById("notice-title-input-id").value;
    jsonData.notice_content = document.getElementById("notice-content-text-id").value;

    return jsonData;

}

function addBtnEvent() {

    $('#notice-ok-btn-id').on('click', function() {
        var jsonData = readConfigToObject();

        if (jsonData.notice_title.length < 1) {
            showAlert("제목을 입력하세요.", 3);
            return;
        }

        if (jsonData.notice_content.length < 1) {
            showAlert("내용을 입력하세요.", 3);
            return;
        }

        if (!confirm("저장하시겠습니까?"))
            return;

        if (parseInt(jsonData.notice_fid) > 0) {

            jsonData = JSON.stringify(jsonData);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: FURL + "/api/updatenotice",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace( FURL +'/board/event');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        showAlert("수정이 실패되었습니다.", 0);
                    }
                },
                error: function(request, status, error) {
                    //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }

            });
        } else if (parseInt(jsonData.notice_fid) == 0) {

            jsonData = JSON.stringify(jsonData);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: FURL + "/api/addnotice",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace( FURL +'/board/event');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        showAlert("저장이 실패되었습니다.", 0);
                    }
                },
                error: function(request, status, error) {
                    //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }

            });
        }

    });


    $('#notice-cancel-btn-id').on('click', function() {
        window.location.replace( FURL +'/board/event');
    });

}