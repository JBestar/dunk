$(document).ready(function() {
    setNavBarElement();
    addButtonEvent();
    let nEmpFid = 0;
    let nTbRow = -1;
    requestCalculate(nEmpFid, nTbRow);
});


function showCalcualte(arrCalcData, empLv) {

    let strBuf = "", strLevel = "";
    let colorLv = 0;
    let elemDataTbBody = document.getElementById("calculate-table-tbody-id");
    let tAmt = 0, tAmt2 = 0;
    let sumChg = 0, sumExg = 0, sumGiv = 0, sumWit = 0, sumMon = 0, sumPot = 0, sumBet = 0, sumWin = 0, sumRat = 0, sumLast = 0;
    for (nRow in arrCalcData) {
        strBuf += "<tr";

        colorLv = arrCalcData[nRow].mb_level % 10;
        strBuf += " class='tr-level" + colorLv + "-color'";

        strBuf += "><td>";
        if (arrCalcData[nRow].mb_level > LEVEL_MIN && arrCalcData[nRow].mb_user_cnt > 1)
            strBuf += "<i class='glyphicon glyphicon-triangle-right'></i>"
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_emp_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_level + "</p>";
        strBuf += "</td><td>";
        strBuf += arrCalcData[nRow].mb_uid; //
        strLevel = getMemberLevelString(arrCalcData[nRow].mb_level, false); 
        if (strLevel != null)
            strBuf += " | " + strLevel;
        strBuf += "</td><td>";
        strBuf += arrCalcData[nRow].mb_nickname; //
        strBuf += "</td><td>";
            tAmt = Math.floor(arrCalcData[nRow].mb_money_all);
            sumMon += tAmt;
        strBuf += Math.floor(arrCalcData[nRow].mb_money_single).toLocaleString() + " / " + tAmt.toLocaleString();
        strBuf += "</td><td>";
            tAmt = Math.floor(arrCalcData[nRow].mb_point_all);
            sumPot += tAmt;
        strBuf += Math.floor(arrCalcData[nRow].mb_point_single).toLocaleString() + " / " + tAmt.toLocaleString();
        strBuf += "</td><td>";
             tAmt = Math.floor(arrCalcData[nRow].mb_charge);
             sumChg += tAmt;
        strBuf += tAmt.toLocaleString(); //
        strBuf += " | ";
            tAmt2 = Math.floor(arrCalcData[nRow].mb_exchange);
            sumExg += tAmt2;
        strBuf += tAmt2.toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(arrCalcData[nRow].mb_charge_benefit).toLocaleString();
        strBuf += "</td><td>";
             tAmt = Math.floor(arrCalcData[nRow].mb_give);
             sumGiv += tAmt;
        strBuf += tAmt.toLocaleString(); //
        strBuf += " | ";
            tAmt2 = Math.floor(arrCalcData[nRow].mb_withdraw);
            sumWit += tAmt2;
        strBuf += tAmt2.toLocaleString();

        strBuf += "</td><td>";
            tAmt = Math.floor(arrCalcData[nRow].mb_bet_money);
            sumBet += tAmt;
        strBuf += tAmt.toLocaleString();
        strBuf += "</td><td>";
            tAmt = Math.floor(arrCalcData[nRow].mb_bet_win_money);
            sumWin += tAmt;
        strBuf += tAmt.toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(arrCalcData[nRow].mb_bet_benefit_money).toLocaleString();
        strBuf += "</td><td>";
            tAmt = Math.floor(arrCalcData[nRow].mb_rate_all);
            sumRat += tAmt;
        strBuf += Math.floor(arrCalcData[nRow].mb_rate_single).toLocaleString() + " / " + tAmt.toLocaleString();
        strBuf += "</td><td>";
            tAmt = Math.floor(arrCalcData[nRow].mb_last_money);
            sumLast += tAmt;
        strBuf += tAmt.toLocaleString();
        strBuf += "</td></tr>";
    }

    if(empLv >= LEVEL_ADMIN){
        strBuf += "<tr><td colspan='3'><b>합계</b></td>";
        strBuf += "<td>"+ sumMon.toLocaleString() +"</td>";
        strBuf += "<td>"+ sumPot.toLocaleString() +"</td>";
        strBuf += "<td>"+ sumChg.toLocaleString() + " | " + sumExg.toLocaleString() +"</td>";
        strBuf += "<td>"+ (sumChg-sumExg).toLocaleString() +"</td>";
        strBuf += "<td>"+ sumGiv.toLocaleString() + " | " + sumWit.toLocaleString() +"</td>";
        strBuf += "<td>"+ sumBet.toLocaleString() +"</td>";
        strBuf += "<td>"+ sumWin.toLocaleString() +"</td>";
        strBuf += "<td>"+ (sumBet-sumWin).toLocaleString() +"</td>";
        strBuf += "<td>"+ sumRat.toLocaleString() +"</td>";
        strBuf += "<td>"+ sumLast.toLocaleString() +"</td></tr>";
    }  
    elemDataTbBody.innerHTML = strBuf;

    addTableEvent();
}


function addButtonEvent() {

    $("#calculate-list-view-but-id").click(function() {
        let nEmpFid = 0;
        let nTbRow = -1;
        requestCalculate(nEmpFid, nTbRow);

    });

}


//Function to Request Betting History to WebServer
function requestCalculate(nFid, nRow) {

    let dtStart = $("#calculate-datestart-input-id").val();
    let dtEnd = $("#calculate-dateend-input-id").val();
    
    let jsonData = { "mb_fid": nFid, "start": dtStart, "end": dtEnd, "type": mGameId };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/calculate',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                if (nRow < 0) showCalcualte(jResult.data, jResult.level);
                else addRow(nRow, jResult.data, jResult.level, jResult.tree);
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



function addTableEvent() {
    let elemDataTb = document.getElementById("calculate-table-tbody-id");
    let elemRows = elemDataTb.getElementsByTagName("tr");

    for (let i = 0; i < elemRows.length; i++) {
        elemRows[i].addEventListener("click", rowEventHander);
    }
}


function rowEventHander() {
    let nRow = this.rowIndex;
    let elemCells = this.getElementsByTagName("td");
    if (elemCells.length < 1) return;

    let elemP = elemCells[0].getElementsByTagName("p");

    if (elemP.length < 3) return;

    let nEmpFid = elemP[0].innerHTML;
    let nEmpLevel = parseInt(elemP[2].innerHTML);
    if (nEmpLevel < 2) return;

    if (elemCells[0].innerHTML.indexOf("triangle-right") >= 0) {
        elemCells[0].innerHTML = elemCells[0].innerHTML.replace(/triangle-right/gi, 'triangle-bottom');

        if (nEmpFid > 0)
            requestCalculate(nEmpFid, nRow);

    } else if (elemCells[0].innerHTML.indexOf("triangle-bottom") >= 0) {
        elemCells[0].innerHTML = elemCells[0].innerHTML.replace(/triangle-bottom/gi, 'triangle-right');
        removeRow(nRow, nEmpLevel);
    }
}




function addRow(nTbRow, arrCalcData, level, tree) {
    let elemDataTb = document.getElementById("calculate-table-tbody-id");

    let strBuf = "", strLevel = "";
    let colorLv = 0;
    for (nRow in arrCalcData) {

        let elemNewRow = elemDataTb.insertRow(nTbRow);
        nTbRow++;

        colorLv = arrCalcData[nRow].mb_level % 10;
        elemNewRow.className = "tr-level" + colorLv + "-color";

        strBuf = "";
        let elemCell0 = elemNewRow.insertCell(0);
        if (arrCalcData[nRow].mb_level > LEVEL_MIN && (level >= LEVEL_ADMIN || tree) && arrCalcData[nRow].mb_user_cnt > 1)
            strBuf = "<i class='glyphicon glyphicon-triangle-right'></i>"
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_emp_fid + "</p>";
        strBuf += "<p hidden>" + arrCalcData[nRow].mb_level + "</p>";

        elemCell0.innerHTML = strBuf;

        let elemCell1 = elemNewRow.insertCell(1);
        elemCell1.innerHTML = arrCalcData[nRow].mb_uid; //
        strLevel = getMemberLevelString(arrCalcData[nRow].mb_level, false); 
        if (strLevel != null)
            elemCell1.innerHTML +=  " | " + strLevel;

        let elemCell2 = elemNewRow.insertCell(2);
        elemCell2.innerHTML = arrCalcData[nRow].mb_nickname; //
        
        let elemCell4 = elemNewRow.insertCell(3);
        elemCell4.innerHTML = Math.floor(arrCalcData[nRow].mb_money_single).toLocaleString() + " / " + Math.floor(arrCalcData[nRow].mb_money_all).toLocaleString();

        let elemCell5 = elemNewRow.insertCell(4);
        elemCell5.innerHTML = Math.floor(arrCalcData[nRow].mb_point_single).toLocaleString() + " / " + Math.floor(arrCalcData[nRow].mb_point_all).toLocaleString();
        
        let elemCell6 = elemNewRow.insertCell(5);
        elemCell6.innerHTML = Math.floor(arrCalcData[nRow].mb_charge).toLocaleString() + " | " + Math.floor(arrCalcData[nRow].mb_exchange).toLocaleString(); 

        let elemCell7 = elemNewRow.insertCell(6);
        elemCell7.innerHTML = Math.floor(arrCalcData[nRow].mb_charge_benefit).toLocaleString();

        let elemCell8 = elemNewRow.insertCell(7);
        elemCell8.innerHTML = Math.floor(arrCalcData[nRow].mb_give).toLocaleString() + " | " + Math.floor(arrCalcData[nRow].mb_withdraw).toLocaleString();
        
        let elemCell9 = elemNewRow.insertCell(8);
        elemCell9.innerHTML = Math.floor(arrCalcData[nRow].mb_bet_money).toLocaleString();

        let elemCell10 = elemNewRow.insertCell(9);
        elemCell10.innerHTML = Math.floor(arrCalcData[nRow].mb_bet_win_money).toLocaleString();

        let elemCell11 = elemNewRow.insertCell(10);
        elemCell11.innerHTML = Math.floor(arrCalcData[nRow].mb_bet_benefit_money).toLocaleString();

        let elemCell12 = elemNewRow.insertCell(11);
        elemCell12.innerHTML = Math.floor(arrCalcData[nRow].mb_rate_single).toLocaleString() + " / " + Math.floor(arrCalcData[nRow].mb_rate_all).toLocaleString();

        let elemCell13 = elemNewRow.insertCell(12);
        elemCell13.innerHTML = Math.floor(arrCalcData[nRow].mb_last_money).toLocaleString();

        elemNewRow.addEventListener("click", rowEventHander);

    }

}



function removeRow(nRow, nParentLevel) {
    let elemDataTb = document.getElementById("calculate-table-tbody-id");
    let elemRows = elemDataTb.getElementsByTagName("tr");

    let rowCnt = elemRows.length;

    for (let i = 0; i < rowCnt ; i++) {

        if (elemRows[nRow] == undefined) break;

        let elemCells = elemRows[nRow].getElementsByTagName("td");

        let elemPs = elemCells[0].getElementsByTagName("p");
        if (elemPs.length < 3) break;

        let nRowEmpLevel = elemPs[2].innerHTML;

        if (nRowEmpLevel < nParentLevel) {
            elemRows[nRow].removeEventListener("click", rowEventHander);
            elemDataTb.deleteRow(nRow);
        } else break;

    }
}