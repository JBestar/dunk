$(document).ready(function() {
    setNavBarElement();
    addBtnEvent();
});

function readConfigToObject() {

    var objData = new Object();

    objData.maintain = document.getElementById("confsite-maintain-select-id").value == 1 ? 1 : 0;

    objData.content = $("#confsite-maintain-text-id").val();


    return objData;

}

function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var objData = readConfigToObject();
        jsonData = JSON.stringify(objData);

        if (objData.maintain == 1) {
            if (!confirm("점검을 진행하시겠습니까?"))
                return;
        } else {
            if (!confirm("정상운영을 진행하시겠습니까?"))
                return;
        }


        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/saveconfmaintain",
            data: { json_: jsonData },
            success: function(jResult) {
                if (jResult.status == "success") {
                    window.location.reload();
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


    });


    $("#confsite-cancel-btn-id").click(function() {
        window.location.reload();
    });

}