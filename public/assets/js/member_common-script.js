

function calcAmount(elemName){
    $(elemName).val(
        $(elemName)
        .val()
        .replace(/[^0-9]/g, "")
    );
    $(elemName).val(
        $(elemName)
        .val()
        .replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
    );
}


function requestWaitToPermit(elemBut, jsData) {

    if (mUserAudio != undefined && mUserAudio != null) {
        mUserAudio.pause();
    }

    $(elemBut).attr('disabled', true);
    jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/wait_permit",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            $(elemBut).attr('disabled', false);

            if (jResult.status == "success") {
                requestEmployeeInfo();
                requestMember();

            } else if (jResult.status == "usererror") {
                showAlert('회원정보가 정확하지 않습니다.\n 다시 확인해주세요', 0);
            } else if (jResult.status == "fail") {
                showAlert('회원승인이 실패되었습니다.', 0);
            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                window.location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestUpdateMember(jsData, reload=true) {

    let jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/updatemember",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                if(reload)
                    requestMember();
                // updateMember(jResult.data, jResult.level);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function requestDeleteMember(jsData) {
    
    if (!confirm("하부회원까지 모두 삭제됩니다.\r\n그래도 계속하시겠습니까?"))
        return;
    let jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/deletemember",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);

            if (jResult.status == "success") {
                requestMember();
                //window.location.reload();
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                window.location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function requestDeleteRestore(elemBut, jsData) {

    $(elemBut).attr('disabled', true);
    jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/delete_restore",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            $(elemBut).attr('disabled', false);

            if (jResult.status == "success") {
                showAlert('복구되었습니다.');
                requestMember();
            } else if (jResult.status == "usererror") {
                showAlert('추천인이 이미 삭제되었습니다. \n 먼저 추천인을 확인해주세요.', 0);
            } else if (jResult.status == "fail") {
                showAlert('복구가 실패되었습니다.', 0);
            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}

function requestAutoStop() {

    if (!confirm("자동차단하시겠습니까?"))
        return;
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/auto_stop",
        success: function(jResult) {
            //console.log(jResult);

            if (jResult.status == "success") {
                if(jResult.msg)
                    showAlert(jResult.msg);
                requestMember();
            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}

function requestAddBlock(jsData) {

    let jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/add_block",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                location.replace( FURL +'/user/member_block');
                // updateMember(jResult.data, jResult.level);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function requestLogoutMember(jsData) {

    let jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/logoutmember",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);

            if (jResult.status == "success") {
                showAlert('로그아웃되었습니다.', 0);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                window.location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}


function refreshEgg(mbFid, elBtn) {
    let jsonData = { "mb_fid": mbFid };
    jsonData = JSON.stringify(jsonData);
    $(elBtn).addClass("refresh");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/egginfo",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(elBtn).removeClass("refresh");

            if (jResult.status == "success") {
                if(parseInt(jResult.egg) > 0)
                    $("#mm_" + mbFid).css('color', 'red');
                else $("#mm_" + mbFid).css('color', 'black');
                $("#mm_" + mbFid).text(Math.floor(jResult.money).toLocaleString());
                $("#mp_" + mbFid).text(Math.floor(jResult.point).toLocaleString());

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(elBtn).removeClass("refresh");
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


function collectEgg(elem, mbFid) {
    
    let jsonData = { "mb_fid": mbFid };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/eggcollect",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
                $("#mm_" + mbFid).text(Math.floor(jResult.money).toLocaleString());
                if(parseInt(jResult.egg) > 0)
                    $("#mm_" + mbFid).css('color', 'red');
                else $("#mm_" + mbFid).css('color', 'black');
                $(elem).css('display', 'none');
                showAlert("회수되었습니다.");
            } else if (jResult.status == "fail") {
                if(jResult.msg)
                    showAlert(jResult.msg, 0);
                else
                    showAlert("게임서버응답이 실패되었습니다.", 0);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}


function requestTrasnfer(jsonData, bReload = true){
    $(".loading").show();
    
    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/transfer",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                if(bReload)
                    location.reload();
                else requestMember();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {
                if (jResult.msg) {
                    showAlert(jResult.msg, 0);
                } else showAlert("조작이 실패되었습니다.", 0);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function reqMemSave(objMember, closeDlg = null){

    if (objMember.mb_uid.length < 1) {
        showAlert("아이디는 필수정보입니다.", 3);
        return;
    }

    if (objMember.admin_level >= LEVEL_ADMIN) {
        if (objMember.mb_pwd.length < 1) {
            showAlert("비밀번호는 필수정보입니다.", 3);
            return;
        }

        if (objMember.mb_nickname.length < 1) {
            showAlert("닉네임은 필수정보입니다.", 3);
            return;
        }

        if (objMember.mb_nickname.length < 2 || objMember.mb_nickname.length > 20) {
            showAlert("닉네임길이는 2~20자리입니다.", 3);
            return;
        }


        if (objMember.mb_phone.length < 1) {
            showAlert("핸드폰번호는 필수정보입니다.", 3);
            return;
        }

        if (objMember.mb_bank_pwd.length < 1) {
            showAlert("출금비번은 필수정보입니다.", 3);
            return;
        }

        if (objMember.mb_bank_name.length < 1 || objMember.mb_bank_own.length < 1 || objMember.mb_bank_num.length < 1) {
            showAlert("계좌정보를 입력해주세요.", 3);
            return;
        }
    }
    if (!confirm("저장하시겠습니까?"))
        return;

    if (parseInt(objMember.mb_fid) > 0) {

        jsonData = JSON.stringify(objMember);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/modifymember",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    if(closeDlg != null){
                        closeDlg();
                        requestMember();
                    }
                    else 
                        window.location.reload();
                        // showAlert("회원정보가 변경되었습니다.");
                } else if (jResult.status == "logout") {
                    showAlert("로그아웃되었습니다. 다시 로그인해주세요", 0);
                    // window.location.replace( FURL +'/');
                    if(closeDlg != null)
                        window.location.reload();
                    else window.close();
                } else if (jResult.status == "fail") {
                    if (jResult.error == 2)
                        showAlert("중복된 아이디입니다.", 0);
                    else if (jResult.error == 3)
                        showAlert("등록된 추천인이 아닙니다.", 0);
                    else if (jResult.error == 6)
                        showAlert("추천인 오류입니다.", 0);
                    else if (jResult.error == 11)
                        showAlert("따라가기 아이디가 존재하지 않습니다.", 0);
                    else if (jResult.error == 12)
                        showAlert("중복된 닉네임입니다.", 0);
                    else showAlert("수정이 실패되었습니다.", 0);
                } else if (jResult.status == "val_error") {
                    let errString = '';
                    for (property in jResult.error) {
                        errString += `${jResult.error[property]}\n`;
                    }
                    showAlert(errString, 0);
                } else if (jResult.status == "level_error") {
                    if(jResult.error > LEVEL_COMPANY){
                        showAlert("추천인을 변경할수 없습니다.", 0);
                    } else 
                        showAlert("입력하신 회원은 추천인으로 지정하실수 없습니다.", 0);
                } else if (jResult.status == "ratio_error") {
                    showAlert(jResult.error, 0);
                } else if (jResult.status == "employee_error") {
                    showAlert("추천인이 존재하지 않습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });
    } else if (parseInt(objMember.mb_fid) == 0) {

        jsonData = JSON.stringify(objMember);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/addmember",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    if(closeDlg != null){
                        closeDlg();
                        requestMember();
                    }
                    else {
                        showAlert("회원이 등록되었습니다.");
                    }
                } else if (jResult.status == "logout") {
                    showAlert("로그아웃되었습니다. 다시 로그인해주세요", 0);
                    if(closeDlg != null)
                        window.location.reload();
                    else window.close();
                } else if (jResult.status == "val_error") {
                    let errorString = '';
                    for (property in jResult.error) {
                        errorString += `${jResult.error[property]}\n`;
                    }
                    showAlert(errorString, 0);
                } else if (jResult.status == "fail") {
                    if (jResult.error == 2){
                        if(confirm("삭제된 회원중에 아이디가 이미 존재합니다. 그래도 계속하시겠습니까?")){
                            objMember.mb_state_alarm = 1;
                            create(objMember, closeDlg);
                        }
                    }
                    else if (jResult.error == 12)
                        showAlert("중복된 닉네임입니다.", 0);
                    else if (jResult.error == 3)
                        showAlert("등록된 추천인이 아닙니다.", 0);
                    else showAlert("등록이 실패되었습니다.", 0);
                } else if (jResult.status == "ratio_error") {
                    showAlert(jResult.error, 0);
                } else if (jResult.status == "employee_error") {
                    showAlert("추천인이 존재하지 않습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });
    }

}

function create(objMember, closeDlg){
    jsonData = JSON.stringify(objMember);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/addmember",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    if(closeDlg != null){
                        closeDlg();
                        requestMember();
                    }
                    else showAlert("회원이 등록되었습니다.");
                } else if (jResult.status == "logout") {
                    showAlert("로그아웃되었습니다. 다시 로그인해주세요", 0);
                    if(closeDlg != null)
                        window.location.reload();
                    else window.close();
                } else if (jResult.status == "val_error") {
                    let errorString = '';
                    for (property in jResult.error) {
                        errorString += `${jResult.error[property]}\n`;
                    }
                    showAlert(errorString, 0);
                } else if (jResult.status == "fail") {
                    if (jResult.error == 2){
                        showAlert("중복된 아이디입니다.", 0);
                    }
                    else if (jResult.error == 12)
                        showAlert("중복된 닉네임입니다.", 0);
                    else if (jResult.error == 3)
                        showAlert("등록된 추천인이 아닙니다.", 0);
                    else showAlert("등록이 실패되었습니다.", 0);
                } else if (jResult.status == "ratio_error") {
                    showAlert(jResult.error, 0);
                } else if (jResult.status == "employee_error") {
                    showAlert("추천인이 존재하지 않습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });
}


function countBlank(fid, chgCnt){
    if(chgCnt == 0){
        return;
    }
    if($("#blank_"+fid).length < 1)
        return;

    let curCnt = parseInt($("#blank_"+fid).val());

    let blankCnt = curCnt + chgCnt;
    if(blankCnt < 0){
        blankCnt = 0;
    }
    $("#blank_"+fid).val(blankCnt);

    let objData = { "mb_fid": fid, "mb_blank_count": blankCnt };
    reqSetBlank(objData, curCnt);
}


function reqSetBlank(objData, beforeCnt) {
    $(".blank-btn_"+objData.mb_fid).attr("disabled", true);

    let jsonData = JSON.stringify(objData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/updatemember",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".blank-btn_"+objData.mb_fid).attr("disabled", false);

            if (jResult.status == "success") {
                $("#blank_"+objData.mb_fid).val(objData.mb_blank_count);
            } else if (jResult.status == "fail") {
                $("#blank_"+objData.mb_fid).val(beforeCnt);
            } else if (jResult.status == "nopermit") {
                showAlert('변경권한이 없습니다.', 0);
                location.replace( FURL +'/pages/nopermit');
            } else if (jResult.status == "logout") {
                location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(".blank-btn_"+objData.mb_fid).attr("disabled", false);
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

