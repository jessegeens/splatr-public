function updateTable(){
  var table = document.getElementById("currencies-table");
  //Start at 1 for table head
  for (var i = 1, row; row = table.rows[i]; i++) {
    var cur = row.cells[0];
    var amount = row.cells[1];
    var buy = row.cells[2];
    var current_old = row.cells[3];
    var profit_old = row.cells[4];
    var diff_old = row.cells[5];
    var market_old = row.cells[6];
    var market_new = getMarketSellPrice(cur.innerHTML);
    var current_new = amount.innerHTML * market_new;
    var profit_new = current_new - buy.innerHTML.substring(1);
    var diff_new = color(round_down(profit_new / buy.innerHTML.substring(1) * 100, 2));
    current_old.innerHTML = '€' + round_down(current_new, 2);
    profit_old.innerHTML = '€' + round_down(profit_new, 2);
    diff_old.innerHTML = diff_new;
    market_old.innerHTML = market_new;
  }
}

function getMarketSellPrice(currency){
  var req = new XMLHttpRequest();

  req.open('GET', 'internal/sell.php?tc=' + currency, false);
  req.onload = function (e) {
    if(req.readyState === 4){
      if(req.status === 200) {
        var json = req.responseText;
        var markets = JSON.parse(json);
        return markets.maximum.price;
      } else throw 'API returned an error [' + req.status + ']';
    }
  };
  req.onerror = function (e) { throw 'API returned an error: ' + req.statusText; };
  req.send(null);
}

function color(value){
  if(value == '&infin;'){return '<font color=\'green\'>+&infin;%</font>';}
  else if(value == 0){return '+' + value + '%';}
  else if(value < 0){return '<font color=\'red\'>' + value + '%</font>';}
  else if(value > 0){return '<font color=\'green\'>+' + value + '%</font';}
  else{
    throw 'Invalid value given @ color()\n[VAL] ' + value;
  }
}

function round_down(value, decimals){
  return Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals);
}

function printDebug(message){
  document.getElementById("debugMessages").innerHTML = document.getElementById("debugMessages").innerHTML + message + "; ";
}
