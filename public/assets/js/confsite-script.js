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
            ['insert', ['picture']],
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
    // $('textarea[name="editordata"]').summernote({
    //     // height: 300, // 에디터 높이
    //     minHeight: 100, // 최소 높이
    //     // maxHeight: null, // 최대 높이
    //     focus: false, // 에디터 로딩후 포커스를 맞출지 여부
    //     lang: "ko-KR", // 한글 설정
    //     placeholder: '', //placeholder 설정
    //     toolbar: [
    //         ['style', ['style']],
    //         ['font', ['bold', 'underline', 'clear']],
    //         ['fontsize', ['fontsize']],
    //         ['color', ['color']],
    //         ['para', ['ul', 'ol', 'paragraph']],
    //         ['height', ['height']],
    //     ]
    // });
    setNavBarElement();
    addBtnEvent();
    onChangeElement();



});

function readConfigToObject() {

    var jsonData = new Object();

    jsonData.sitename = $("#confsite-sitename-input-id").val();
    jsonData.domainname = $("#confsite-domainname-input-id").val();

    if($("#confsite-bankapi-url-id").length > 0){
        let url = $("#confsite-bankapi-url-id").val().trim();
        if(url.length > 10 && url.endsWith('/')){
            url = url.substr(0, url.length-1);
        }
        jsonData.bankapi = url + "#" + $("#confsite-bankapi-key-id").val().trim() ;
    } else if($("#confsite-bankname-input-id").length > 0){
        jsonData.bank = $("#confsite-bankname-input-id").val().trim() + "#" + $("#confsite-bankown-input-id").val().trim() +
        "#" + $("#confsite-banknum-input-id").val().trim();
    }
    
    jsonData.mainnotice = $("#confsite-mainnotice-text-id").val();
    if($("#confsite-mainnotice_cn-text-id").length > 0)
        jsonData.mainnotice_cn = $("#confsite-mainnotice_cn-text-id").val();
    jsonData.mainnotice_ok = $("#confsite-mainnotice-check-id").prop('checked') ? 1 : 0;
    jsonData.depositenotice = $("#confsite-deposite-text-id").summernote('code');
    if($("#confsite-deposite_cn-id").length > 0)
        jsonData.depositenotice_cn = $("#confsite-deposite_cn-text-id").summernote('code');
    jsonData.depositenotice_ok = $("#confsite-deposite-check-id").prop('checked') ? 1 : 0;
    jsonData.depositenotice_color = $("#confsite-deposite-color-id").val();
    jsonData.urgentnotice = $("#confsite-urgentnotice-text-id").summernote('code');
    if($("#confsite-urgentnotice_cn-id").length > 0)
        jsonData.urgentnotice_cn = $("#confsite-urgentnotice_cn-text-id").summernote('code');
    jsonData.urgentnotice_ok = $("#confsite-urgentnotice-check-id").prop('checked') ? 1 : 0;
    jsonData.urgentnotice_color = $("#confsite-urgentnotice-color-id").val();
    if($("#confsite-chargemanual-text-id").length > 0){
        jsonData.chargemanual = $("#confsite-chargemanual-text-id").summernote('code');
        jsonData.discharmanual = $("#confsite-discharmanual-text-id").summernote('code');
    }
    jsonData.bankmacro = $("#confsite-bankmacro-text-id").summernote('code');
    if($("#confsite-bankmacro_cn-id").length > 0)
        jsonData.bankmacro_cn = $("#confsite-bankmacro_cn-text-id").summernote('code');
    jsonData.multilog_ok = $("#confsite-multilog-check-id").prop('checked') ? 1 : 0;
    jsonData.trans_deny = $("#confsite-transdeny-check-id").prop('checked') ? 0 : 1;
    jsonData.return_deny = $("#confsite-returndeny-check-id").prop('checked') ? 0 : 1;
    jsonData.trans_lv1 = $("#confsite-translv1-check-id").prop('checked') ? 1 : 0;
    jsonData.return_lv1 = $("#confsite-returnlv1-check-id").prop('checked') ? 1 : 0;

    if($("#confsite-chargeurl-input-id").length > 0){
        jsonData.chargeurl = $("#confsite-chargeurl-input-id").val();
    }

    if($("#confsite-teleid-input-id").length > 0){
        jsonData.teleid = $("#confsite-teleid-input-id").val();
    }

    if($("#confsite-exchange-check-id").length > 0){
        jsonData.exchange_delay = $("#confsite-exchange-check-id").prop('checked') ? 1 : 0;
        jsonData.exchange_delay += "#"+$("#confsite-exchange-input-id").val();
    }
    if($("#confsite-bank-check-id").length > 0){
        jsonData.bank_rest = $("#confsite-bank-check-id").prop('checked') ? 1 : 0;
        jsonData.bank_rest += "#"+$("#confsite-bankstart-input-id").val();
        jsonData.bank_rest += "#"+$("#confsite-bankend-input-id").val();
    }

    if($("#confsite-autoapps-check-id").length > 0){
        jsonData.autoapps_check = $("#confsite-autoapps-check-id").prop('checked') ? 1 : 0;
        let elApps = $("input[name=auto-apps]");
        let data = '';
        for(var i=0; i<elApps.length; i++){
            let ename = $(elApps[i]).data("ename");
            let name = $(elApps[i]).data("name");
            let path = $(elApps[i]).data("path");
            let act = $(elApps[i]).prop('checked') ? 1 : 0;
            data+=`${ename}#${name}#${path}#${act};`
        }
        jsonData.autoapps_data = data;
    }

    if($("#confsite-userout-input-id").length > 0){
        let delayUser = parseInt($("#confsite-userout-input-id").val());
        if(delayUser < 0)
            delayUser = 0;

        let delayAdmin = parseInt($("#confsite-adminout-input-id").val());
        if(delayAdmin < 0)
            delayAdmin = 0;

        jsonData.logout_delay = delayUser+"#"+delayAdmin;

    }
    return jsonData;

}

function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var jsonData = readConfigToObject();
        if (!confirm("저장하시겠습니까?"))
            return;

        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/saveconfsite",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    showAlert("저장이 실패되었습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });


    $("#confsite-cancel-btn-id").click(function() {
        window.location.reload();
    });

    $("#confsite-charge-reset-id").click(function() {
        if (!confirm("회원 입금통장들을 전부 리셋하시겠습니까?"))
            return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/resetchargeinfo",
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    showAlert("리셋되었습니다.");
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    showAlert("조작이 실패되었습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });

    $("#confsite-transdeny-check-id").change(function() {
        onChangeElement();
    });

    $("#confsite-returndeny-check-id").change(function() {
        onChangeElement();
    });

    $('#confsite-deposite-color-id').on('input', function() {
        $("#confsite-deposite-id").css("background-color", $(this).val());
        if($("#confsite-deposite_cn-id").length > 0)
            $("#confsite-deposite_cn-id").css("background-color", $(this).val());
    });

    $("#confsite-urgentnotice-color-id").on('input', function() {
        $("#confsite-urgentnotice-id").css("background-color", $(this).val());
        if($("#confsite-urgentnotice_cn-id").length > 0)
            $("#confsite-urgentnotice_cn-id").css("background-color", $(this).val());
    });
    
    $("#confsite-lang-select-id").change(function() {
        let lang = $(this).val();
        if(lang == "cn"){
            $("#confsite-mainnotice-text-id").hide();
            $("#confsite-mainnotice_cn-text-id").show();

            $("#confsite-deposite-id").hide();
            $("#confsite-deposite_cn-id").show();
            
            $("#confsite-urgentnotice-id").hide();
            $("#confsite-urgentnotice_cn-id").show();

            $("#confsite-bankmacro-id").hide();
            $("#confsite-bankmacro_cn-id").show();
        } else{

            $("#confsite-mainnotice-text-id").show();
            $("#confsite-mainnotice_cn-text-id").hide();

            $("#confsite-deposite-id").show();
            $("#confsite-deposite_cn-id").hide();
            
            $("#confsite-urgentnotice-id").show();
            $("#confsite-urgentnotice_cn-id").hide();

            $("#confsite-bankmacro-id").show();
            $("#confsite-bankmacro_cn-id").hide();
        }

    });
}

function onChangeElement(){
    let checked = $("#confsite-transdeny-check-id").prop('checked');
    $("#confsite-translv1-check-id").prop('disabled', !checked);

    checked = $("#confsite-returndeny-check-id").prop('checked');
    $("#confsite-returnlv1-check-id").prop('disabled', !checked);

    if($("#confsite-autoapps-check-id").length > 0){
        checked = $("#confsite-autoapps-check-id").prop('checked');
        $("input[name=auto-apps]").prop('disabled', !checked);
    }
    
}

function cleanDb(iType) {

    var objData = { "type": iType };
    if (iType == 2){
        objData.date = $("#conf-dbclean-input-id").val();
        if(!confirm(objData.date + " 이전내역을 정리하시겠습니까?"))
            return;
    } else if (iType == 0){
        if(!confirm("디비초기화를 하시겠습니까?"))
           return;
    }
    requestCleanDb(objData);
}


function requestCleanDb(objData) {
    if (objData == null)
        return;
    $("#cleanDb-but").attr("disabled", true);
    var jsonData = JSON.stringify(objData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { json_: jsonData },
        url: FURL + "/api/cleanDb",
        success: function(jResult) {
            $(".loading").hide();
            $("#cleanDb-but").attr("disabled", false);
            // console.log(jResult);
            if (jResult.status == "success") {
                showAlert("디비정리가 완료되었습니다.!");
                location.reload();
            } else if (jResult.status == "fail") {
                if (jResult.data == 2)
                    showAlert("권한이 없습니다.", 0);
                else showAlert("거절되었습니다.", 0);
            } else if (jResult.status == "logout") {
                location.reload();
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            $("#cleanDb-but").attr("disabled", false);
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}

function uploadSummernoteImageFile(file, editor) {
    // console.log(editor);
    data = new FormData();
    data.append("file", file);
    $.ajax({
        data : data,
        dataType: "json",
        type : "POST",
        url : FURL +"/home/upload",
        contentType : false,
        processData : false,
        success : function(data) {
            // console.log(data.url);
            $(editor).summernote('insertImage', data.url);
        }
    });
}