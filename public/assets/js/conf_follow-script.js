$(document).ready(function() {
    addBtnEvent();
});


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        let follow_check = $("#confsite-follow-check-id").prop('checked') ? 1 : 0;
        let follow_id = $("#confsite-follow-input-id").val().trim();
        let follow_percent = $("#confsite-follow-percent-id").val()
        if(follow_check == 1 && follow_id.length < 1 ){
            showAlert("따라가기 아이디를 입력해주세요", 3);
            return;
        }

        var jsonData = {follow_check:follow_check, follow_id:follow_id, follow_percent:follow_percent};

        if (!confirm("저장하시겠습니까?"))
            return;

        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/change_follow",
            data: { json_: jsonData },
            success: function(jResult) {
                //console.log(jResult);
                if (jResult.status == "success") {
                    showAlert("저장되었습니다.");
                } else if (jResult.status == "fail") {
                    showAlert(jResult.msg, 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });


    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });

}
