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
            ['insert', ['picture']],
            // ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {	//여기 부분이 이미지를 첨부하는 부분
            onImageUpload : function(files) {
                uploadSummernoteImageFile(files[0],this);
            },
            onPaste: function (e) {
                var clipboardData = e.originalEvent.clipboardData;
                if (clipboardData && clipboardData.items && clipboardData.items.length) {
                    var item = clipboardData.items[0];
                    if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                        e.preventDefault();
                    }
                }
            }
        },
    });
    addBtnEvent();
});

function readConfigToObject() {

    var jsonData = new Object();

    jsonData.notice_fid = $("#subnavbar-fid-p-id").html();
    jsonData.notice_type = 1;
    jsonData.notice_state_active = $("#notice-state-check-id").prop('checked') ? 1 : 0;
    jsonData.notice_title = $("#notice-title-input-id").val();
    if($("#notice-title_cn-input-id").length > 0)
        jsonData.notice_title_cn = $("#notice-title_cn-input-id").val();
    jsonData.notice_content = $("#notice-content").summernote('code');
    if($("#notice-content_cn").length > 0)
        jsonData.notice_content_cn = $("#notice-content_cn").summernote('code');

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
                        window.location.replace( FURL +'/board/notice');
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
                        window.location.replace( FURL +'/board/notice');
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


    $("#notice-cancel-btn-id").click(function() {
        window.location.replace( FURL +'/board/notice');
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

function uploadSummernoteImageFile(file, editor) {
    // console.log(file);
    data = new FormData();
    data.append("file", file);
    $.ajax({
        data : data,
        dataType: "json",
        type : "POST",
        url : FURL + "/home/upload",
        contentType : false,
        processData : false,
        success : function(data) {
            // console.log(data.url);
            $(editor).summernote('insertImage', data.url);
        }
    });
}