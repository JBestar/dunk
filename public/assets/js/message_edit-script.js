$(document).ready(function() {

    
    $('textarea[name="editordata"]').summernote({
        // height: 300, // 에디터 높이
        minHeight: 100, // 최소 높이
        // maxHeight: null, // 최대 높이
        focus: false, // 에디터 로딩후 포커스를 맞출지 여부
        lang: "ko-KR", // 한글 설정
        placeholder: '', //placeholder 설정
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            // ['table', ['table']],
            // ['insert', ['link', 'picture', 'video']],
            // ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    initMessageEdit();
    // $('#notice-content').summernote('code', '<p>This is the content of the editor.</p>');
});

function initMessageEdit() {
    CountPerPage = 10;
    addBtnEvent();
    requestTotalPage();
    requestMembers();
}

function requestPageInfo() {
    requestMacro();
}

var mArrMacro = null;
function showMacro(arrMacro){

    mArrMacro = arrMacro;
    let strBuf = "";

    // var curPage = getActivePage();
    // var firstIdx = (curPage - 1) * CountPerPage;
    for(let nRow in arrMacro){
        strBuf += "<tr><td>";
        strBuf += "<button onclick='editMacro(" + nRow + ")' >◀</button>";
        strBuf += "</td><td>";
        strBuf += arrMacro[nRow].conf_memo;
        strBuf += "</td><tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='3'>자료가 없습니다.</td></tr>";
    }
    $("#confsite-table-data").html(strBuf);

}

function editMacro(nRow){
    if(mArrMacro.length < nRow)
        return;

    let notice = mArrMacro[nRow];
    if(!notice)
        return;
    
    let notice_type = $("#subnavbar-type-p-id").html();
        
    if(notice_type == 0){
        // let code = $("#notice-content").summernote('code');
        // if(code == "<p><br></p>" )
        let code = "";
        code += notice.conf_content;
        console.log($("#notice-title-input-id").css("display"));
        if($("#notice-title-input-id").css("display") != "none"){
            $("#notice-content").summernote('code', code);
            $("#notice-title-input-id").val(notice.conf_memo);
        } else{
            $("#notice-content_cn").summernote('code', code);
            $("#notice-title_cn-input-id").val(notice.conf_memo);
        }
        
    } else {
        // let code = $("#custom-answer").summernote('code');
        // if(code == "<p><br></p>" )
        let code = "";
        code += notice.conf_content;
        $("#custom-answer").summernote('code', code);
    }

}

function readConfigToObject() {
    var jsonData = new Object();

    jsonData.notice_fid = $("#subnavbar-fid-p-id").html();
    jsonData.notice_type = $("#subnavbar-type-p-id").html();
    jsonData.notice_state_active = $("#notice-state-check-id").prop('checked') ? 1 : 0;
    jsonData.notice_title = $("#notice-title-input-id").val();
    if($("#notice-title_cn-input-id").length > 0)
        jsonData.notice_title_cn = $("#notice-title_cn-input-id").val();
    jsonData.notice_content = "";
    if($("#notice-content").length > 0){
        jsonData.notice_content = $("#notice-content").summernote('code');
    } else {
        jsonData.notice_content = $("#custom-content").text();
    }
    if($("#notice-content_cn").length > 0)
        jsonData.notice_content_cn = $("#notice-content_cn").summernote('code');
    jsonData.notice_answer = "";
    if ($("#custom-answer").length > 0) {
        jsonData.notice_answer = $("#custom-answer").summernote('code');
        jsonData.notice_state_active = 1;
    }
    jsonData.notice_mb_uid = $("#notice-mbuid-input-id").val();

    return jsonData;

}

function addBtnEvent() {

    $("#notice-save-btn-id").click(function() {

        var jsonData = readConfigToObject();

        if (jsonData.notice_title.length < 1) {
            showAlert("제목을 입력하세요.", 3);
            return;
        }

        if (jsonData.notice_content.length < 1) {
            showAlert("내용을 입력하세요.", 3);
            return;
        }

        if (!confirm("발송하시겠습니까?"))
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
                        window.location.replace( FURL +'/board/message');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {

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
                url: FURL + "/api/addMessage",
                data: { json_: jsonData },
                success: function(jResult) {
                    //console.log(jResult);
                    if (jResult.status == "success") {
                        window.location.replace( FURL +'/board/message');
                    } else if (jResult.status == "logout") {
                        window.location.replace( FURL +'/');
                    } else if (jResult.status == "fail") {
                        showAlert("발송자 아이디가 존재하지 않습니다.", 0);
                    }
                },
                error: function(request, status, error) {
                    //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }

            });
        }


    });


    $("#notice-cancel-btn-id").click(function() {
        window.location.replace( FURL +'/board/message');
    });

    $("#notice-lang-select-id").change(function() {
        let lang = $(this).val();
        if(lang == "cn"){
            $("#notice-title-input-id").hide();
            $("#notice-title_cn-input-id").show();

            $("#notice-form").hide();
            $("#notice-form_cn").show();
            
        } else{

            $("#notice-title-input-id").show();
            $("#notice-title_cn-input-id").hide();

            $("#notice-form").show();
            $("#notice-form_cn").hide();
        }

    });

}


function requestMembers() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/memberIds",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showAutoComplete(jResult.data);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}



function showAutoComplete(ids) {
    $(function() {

        var arrData = new Array();
        arrData[0] = "*";
        for (nRow in ids) {
            arrData[parseInt(nRow) + 1] = ids[nRow];
        }

        $("#notice-mbuid-input-id").autocomplete({
            source: arrData,
            select: function(event, ui) {},
            focus: function(event, ui) {
                return false;
            }

        });
    });

}


function requestTotalPage() {

    var jsonData = {
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/getmacrocnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestMacro();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

function requestMacro() {

    var nPage = getActivePage();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/getmacro",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
                showMacro(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            $(".loading").hide();
        }

    });

}