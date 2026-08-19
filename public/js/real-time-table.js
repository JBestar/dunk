
function reqExchanges() {

  $.post('/api/recent_exchanges', {
  }, function (response) {
      if (response.status == 'success') {
        // console.log(response.charges);
        let html = "";
        let i= 0;
        if(response.charges.length >= 8){
          for(i=0; i<4; i++){
            html += getItemHtml(response.charges[i]);
          }
          $("#recentCharges1").html(html);
          html = "";
          for(i=4; i<8; i++){
            html += getItemHtml(response.charges[i]);
          }
          $("#recentCharges2").html(html);

        }
        html = "";
        // console.log(response.dischars);
        if(response.dischars.length >= 8){
          for(i=0; i<4; i++){
            html += getItemHtml(response.dischars[i]);
          }
          $("#recentDischars1").html(html);
          html = "";
          for(i=4; i<8; i++){
            html += getItemHtml(response.dischars[i]);
          }
          $("#recentDischars2").html(html);

        }

      } 
  }, 'json')
}

function getItemHtml(item){
  let html = `<div class="item d-flex justify-content-between" >`;
  html += `<span class="item-username ">${item.uid}</span>`;
  html += `<span class="item-amount"><span>${item.amount}</span><span>원</span></span>`;
  html += `<span class="item-date ">${item.time}</span></div>`;
  return html;
}