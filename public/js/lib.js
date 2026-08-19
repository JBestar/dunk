/**
 * Null 체크
 * @parameter
 * @return
 */
String.prototype.isNull = function() {
    if (this == null || this == "" || this == undefined || this == "undefined") {
        return true;
    } else {
        return false;
    }
}

/**
 * 영문자만 허용
 * @parameter
 * @return
 */
String.prototype.isAlpha = function() {
    if (this.search(/[^A-Za-z]/) == -1) {
        return true;
    } else {
        return false;
    }
}

/**
 * 숫자로만 허용
 * @parameter
 * @return
 */
String.prototype.isNumber = function() {
    if (this.search(/[^0-9]/) == -1) {
        return true;
    } else {
        return false;
    }
}

/**
 * 숫자로만 허용(실수형)
 * @parameter
 * @return
 */
String.prototype.isFloat = function() {
    if (this.search(/[^0-9.]/) == -1) {
        return true;
    } else {
        return false;
    }
}

$(document).ready(
    function() {
        $(document).on(
            "focus",
            ".__number",
            function() {
                if ($(this).attr("data-no-event-change") == "1")
                    return;

                $(this).val(GetNumber($(this).val()));
            });

        $(document).on(
            "keypress",
            ".__number",
            function(a_event) {
                if ((a_event.keyCode < 48 || a_event.keyCode > 57) && (a_event.keyCode != 46 && a_event.keyCode != 45)) {
                    a_event.preventDefault();
                    return false;
                }
            });

        $(document).on(
            "blur",
            ".__number",
            function() {
                if ($(this).attr("data-check-number") == "1") {
                    if (isNaN($(this).val()))
                        $(this).val('');
                }

                if ($(this).attr("data-no-event-change") == "1")
                    return;

                $(this).val(parseFloat($(this).val()));

                if (isNaN($(this).val()))
                    $(this).val(0);

                $(this).val(GetMoney($(this).val()));
            });

    }
);

function isNull(obj) {
    if (obj == null || typeof(obj) == "undefined" || typeof obj == "undefined" || typeof obj == "unknown")
        return true;

    return false;
}

function GetNumber(a_value) {
    return a_value.toString().replace(/(,)/g, "");
}

function GetMoney(a_nData) {
    var strData = a_nData.toString(),
        strMoney = "",
        bMinus = strData.charAt(0) == '-';

    if (bMinus)
        strData = strData.substr(1, strData.length - 1);

    if (strData.length <= 3)
        return strData;

    for (var nCount = strData.length; nCount >= 0; nCount -= 3) {
        if (nCount - 3 >= 0)
            strMoney = strData.substr(nCount - 3, 3) + "," + strMoney;
        else
            strMoney = strData.substr(0, nCount) + "," + strMoney;
    }

    if (strMoney.charAt(0) == ',')
        return (bMinus ? '-' : '') + strMoney.substr(1, strMoney.length - 2);

    return (bMinus ? '-' : '') + strMoney.substr(0, strMoney.length - 1);
}

function GetDBData(a_strURL, a_strData, a_strSendType, a_strDataType, a_contentType, a_processType, a_funcBefore, a_funcSuccess, a_funcComplate, a_funcError) {
    var strData = a_strData;
    var strResult = "";
    var bASync = true; //	비동기
    var contentType = "application/x-www-form-urlencoded";
    //var contentType	= "application/json;charset=utf-8";
    var processData = true;

    if (a_contentType != null)
        contentType = a_contentType;

    if (processData != null)
        processData = a_processType;

    if (isNull(a_funcSuccess))
        bASync = false;

    $.ajax({
            type: a_strSendType,
            async: bASync,
            url: a_strURL,
            data: strData,
            dataType: a_strDataType,
            contentType: contentType,
            processData: processData,
            cache: false,
            beforeSend: function() {
                    if (!isNull(a_funcBefore))
                        a_funcBefore();

                } //	beforeSend

            ,
            success: function(data) {
                    strResult = data;

                    if (!isNull(a_funcSuccess))
                        a_funcSuccess(strResult);
                } //	success

            ,
            error: function(data, status, err) {
                    // alert("Error : " + err);
                    if (!isNull(a_funcError))
                        a_funcError(data, status, err);
                } //	error

            ,
            complete: function() {
                    if (!isNull(a_funcComplate))
                        a_funcComplate();

                } //	complate

        } //	function
    ); //	ajax

    return strResult;

} //	DoDBRequest


function GetDBGetData(a_strURL, a_strData, a_strDataType, a_funcBefore, a_funcSuccess, a_funcComplate, a_funcError) {
    var strData = a_strData.replace(/(')/g, "''");
    return GetDBData(a_strURL, strData, "GET", a_strDataType, null, null, a_funcBefore, a_funcSuccess, a_funcComplate, a_funcError);
} //	DoDBRequest

function GetDBPostData(a_strURL, a_strFormName, a_strTarget, a_strDataType, a_contentType, a_processType, a_funcBefore, a_funcSuccess, a_funcComplate, a_funcError) {
    var objForm = null;

    if ($("form[name=" + a_strFormName + "]").attr("enctype") == "multipart/form-data") {
        try {
            objForm = new FormData($("form[name=" + a_strFormName + "]").get(0));
            return GetDBData(a_strURL, objForm, "POST", a_strDataType, a_contentType, a_processType, a_funcBefore, a_funcSuccess, a_funcComplate, a_funcError);
        } catch (e) {
            //alert( e );
        }
    } else {
        try {
            objForm = $("form[name=" + a_strFormName + "]").serialize();

            var http = new XMLHttpRequest();

            http.open("POST", a_strURL, false);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.send(objForm);

            //if( !isNull( a_funcSuccess ) )
            //a_funcSuccess( http.responseText );

            return http.responseText;
        } catch (e) {
            objForm = new FormData($("form[name=" + a_strFormName + "]").get(0));
            //return GetDBData(a_strURL, objForm, "POST", a_strDataType, a_contentType, a_processType, a_funcBefore, a_funcSuccess, a_funcComplate, a_funcError);
        }
    }

    return null;
} //	DoDBRequest


function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}

Array.prototype.remove = function(idx) {
    return this.splice(idx, 1);
};

Date.prototype.addDay = function(days) {
    var dt = new Date(this.valueOf());

    dt.setDate(dt.getDate() + days);

    return dt;
}

Date.prototype.getFullMonth = function() {
    var month = this.getMonth() + 1;

    if (month >= 10)
        return month;
    else
        return "0" + month;
}

Date.prototype.getFullDate = function() {
    var day = this.getDate();

    if (day >= 10)
        return day;
    else
        return "0" + day;
}

Date.prototype.getFullHours = function() {
    var value = this.getHours();

    if (value >= 10)
        return value;
    else
        return "0" + value;
}

Date.prototype.getFullMinutes = function() {
    var value = this.getMinutes();

    if (value >= 10)
        return value;
    else
        return "0" + value;
}

Date.prototype.getFullSecond = function() {
    var value = this.getSeconds();

    if (value >= 10)
        return value;
    else
        return "0" + value;
}

Date.prototype.toDateShortString = function() {
    var dt = new Date(this.valueOf());
    return dt.getFullYear() + "-" + dt.getFullMonth() + "-" + dt.getFullDate();
}

Date.prototype.toDateKorString = function() {
    var dt = new Date(this.valueOf());
    return dt.getFullYear() + "년 " + dt.getFullMonth() + "월 " + dt.getFullDate() + "일";
}

Date.prototype.getWeekString = function() {
    var dt = new Date(this.valueOf()),
        s = "";

    switch (dt.getDay()) {
        case 0:
            s = "일";
            break;
        case 1:
            s = "월";
            break;
        case 2:
            s = "화";
            break;
        case 3:
            s = "수";
            break;
        case 4:
            s = "목";
            break;
        case 5:
            s = "금";
            break;
        case 6:
            s = "토";
            break;
    }

    return s;
}

var m_etc_busy = false;

function changePoint(id) {
    if (!confirm("포인트를 머니로 전환 하시겠습니까?"))
        return;

    if (m_etc_busy)
        return;

    $("#_frmetc").children("#_n_s").val(b64EncodeUnicode(id));
    var result = GetDBPostData("/my/chage_point_update.php", "_frmetc", "", "json", false, false);
    $("#_frmetc").find("_n_s").val("");

    var jsonObj = JSON.parse(result);
    if (jsonObj.result != 0) {
        alert(jsonObj.msg.toString().replace("\\n", "\n"));
        m_etc_busy = false;
        return;
    }

    m_etc_busy = false;

    alert("포인트를 머니로 전환했습니다.");
    document.location.reload();
}