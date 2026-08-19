
$(document).ready(function() {
    addBtnEvent();
});

function addBtnEvent() {

    $(".useredit-panel select").change(function() {

        let fid = $(this).data('fid');
        let percent = $(this).val();
        let master = $(this).data('master');

        let jsonData = { "mb_fid": fid, "mb_follow_ev": "1:"+master+":"+percent };
        requestUpdateMember(jsonData, false);
    });

    $("#useredit-cancel-btn-id").click(function() {
        window.close();
    });
}