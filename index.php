<?php
require_once('dbcred.php');
require_once("crypto.php");
require_once("user.php");

define('SUPPORTED_CURRENCIES', array("btc", "bch", "ltc", "xmr", "xrp", "zec", "eth"));

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($connection->connect_error) {
  die("Splatr can't connect to the database. <br> Error: " . $connection->connect_error);
}
session_start();
if(!(isset($_SESSION["splatr-web.login"]))){
  if(!isset($_COOKIE['splatr-web_login-forever'])){
    header("location:login.php");
  }
  else{
    if(explode(';', $_COOKIE["splatr-web_login-forever"])[1] == crypt(explode(';', $_COOKIE["splatr-web_login-forever"])[0], 'splatr123.hash')){
      $_SESSION["splatr-web.login"] = explode(';', $_COOKIE["splatr-web_login-forever"])[0];
    }
    else{
      header("location:login.php");
    }
  }
}else{
  $user = new User($connection, $_SESSION["splatr-web.login"]);
  echo '
    <html>
    <head>
      <title>Splatr</title>
      <link rel="stylesheet" href="css/stylesheet.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
      integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:700">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
      integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <meta http-equiv="refresh" content="60" >';
      include_once("analyticstracking.php");echo'
    </head><body>';

  require('header.php');
  if(!isset($_COOKIE['agree-cookie-tracking']))
  {
    echo'  <div class="alert alert-success alert-dismissable">
         <a href="#" class="close" data-dismiss="alert" aria-label="close"><strong>&times;</strong></a>
         By using Splatr, you agree to our <a href="">Terms of Service</a> and to the use of tracking cookies. Read our <a href="">privacy policy</a> to find out more.
         </div>';
    setcookie('agree-cookie-tracking', 'true', 2147483647);
  }
    echo '<div class="panel panel-primary">
            <div class="panel-heading">Market trends</div>
              <div class="panel-body">
                <table style="width: 100%;">
                    <tr>
                      <td class="market-unit">'.strtoupper($user->getFavorites()[0]).': €'.getPrice($user->getFavorites()[0], "kraken").'</td>
                      <td class="market-unit">'.strtoupper($user->getFavorites()[1]).': €'.getPrice($user->getFavorites()[1], "kraken").'</td>
                      <td class="market-unit">'.strtoupper($user->getFavorites()[2]).': €'.getPrice($user->getFavorites()[2], "kraken").'</td>
                      <td class="market-unit">'.strtoupper($user->getFavorites()[3]).': €'.getPrice($user->getFavorites()[3], "kraken").'</td>
                      <td class="market-unit">'.strtoupper($user->getFavorites()[4]).': €'.getPrice($user->getFavorites()[4], "kraken").'</td>
                      <td class="market-unit">'.strtoupper($user->getFavorites()[5]).': €'.getPrice($user->getFavorites()[5], "kraken").'</td>
                      <td class="market-unit">'.strtoupper($user->getFavorites()[6]).': €'.getPrice($user->getFavorites()[6], "kraken").'</td>
                    </tr>
                  </table>
                </div>
            </div>

            <div class="panel panel-primary">
              <div class="panel-heading">Portfolio details</div>
                <div class="panel-body">
                  <table style="width: 100%;">
                    <tr>
                    <td>Total investment: €'.$user->getTotalInvestment().'</td>
                    <td>Total profit: €'.round(getTotalProfit($user, $connection), 3).'</td>
                    <td>Diff: '.color(round(((getTotalProfit($user, $connection)+$user->getTotalInvestment())/$user->getTotalInvestment() - 1)*100, 2)).'</td>
                    <td align="right"><a href="add.php"><button type="button" class="btn btn-success">ADD</button></a>
                    <a href="logout.php"><button type="button" class="btn btn-danger">LOG OUT</button></a></td>
                    </tr>
                  </table>
                </div>
              </div>
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">Portfolio</a></li>
                <li role="presentation"><a href="#2" aria-controls="2" role="tab" data-toggle="tab">Settings</a></li>
              </ul>
              <div class="tab-content">';
                printPortfolio($user, $connection);
                printSettingsTab($user);
                $connection->close();
              echo '</div>';
}

/**
 *
 * Prints out a table line for the portfolio
 *
 * @param Crypto $coin position for which the table line has to be printed out
 * @return string table row with position given in parameter
 */
function printTableLine($coin)
{
  echo '<tr class="position"><td>'.$coin->getTickerCode().'</td>
        <td>'.$coin->getAmount().'</td>
        <td>€'.$coin->getInitPrice().'</td>
        <td>€'.$coin->getValue().'</td>
        <td>€'.$coin->getProfit().'</td>
        <td>'.color($coin->getDifferencePercentage()).'</td>
        <td>€'.$coin->getMarketPrice().'</td>
        <td><a id="removeSign" href="delete.php?id='.$coin->getId().'"><span class="glyphicon glyphicon-minus-sign" id="deletebtn"></span></a>
        <span class="glyphicon glyphicon-eur" id="sellbtn" data-toggle="modal" data-target="#sellModal'.$coin->getTickerCode().'"></span>
        </td></tr>';
}

/**
 *
 *Prints out a sell modal for the given position
 *
 *@param Crypto $coin position wor which the modal has to be printed out
 *@return string sell modal html code
 */
function printSellModal($coin)
{
  echo'<div id="sellModal'.$coin->getTickerCode().'" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Sell your '.$coin->getFullName().'</h4>
      </div>
      <div class="modal-body">
        <p>The highest bidder at the moment is: <strong>'.$coin->getExchange().'</strong><br><br>
        <a href="https://www.litebit.eu?referrer=25243" target="_blank"><button type="button" class="btn btn-primary">Create account</button></a>
        <a href="https://www.litebit.eu/en/sell/'.$coin->getFullName().'" target="_blank"><button type="button" class="btn btn-primary">Sell</button></a>
        </p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>';}

/**
 *
 * Prints out the portfolio of given $user
 *
 * @param User $user user object for whom the porfolio is to be printed out
 * @param mysqli $connection database object for retrieving the user's current positions
 * @return string html code for the portfolio of given $user
 */
function printPortfolio($user, $connection)
{
  $query = $connection->query("SELECT * FROM currencies WHERE email = '".$user->getEmail()."'");
  echo '<div role="tabpanel" class="tab-pane active" id="1">';
  if(mysqli_num_rows($query) > 0){
    echo '<table class="table table-striped table-hover">
    <thead>
    <th>Currency</th>
    <th>Amount</th>
    <th>Buy</th>
    <th>Current</th>
    <th>Profit</th>
    <th>Diff</th>
    <th>Market</th>
    <th><span class="glyphicon glyphicon-pencil"></span></th>
    </thead>
    </div>

    <tbody>';
    while($row = $query->fetch_assoc()) {
          $currency = $row['currency'];
          $amount = $row['amount'];
          $buyPrice = $row['buyprice'];
          $id = $row['ID'];
          $coin = new Crypto($currency, $amount, $buyPrice, getPrice($currency, "kraken"), "kraken", $id);
          printTableLine($coin);
          printSellModal($coin);
    }
    echo '</tbody>
          </table></div>';
  }else{
    echo '<br><center><h2>You have no currencies in your portfolio yet</h2></center>';
  }
}

/**
 *
 * Prints the settings tab
 *
 * @param User $user user object for whom the settings are to be printed out
 * @return string html code for the settings tab
 */
function printSettingsTab($user)
{
  echo '<div role="tabpanel" class="tab-pane" id="2">Current user: '.$user->getEmail().'
        <br>User id: '.$user->getId().
        '<br>Favorites: <form class="form-inline">';
        for($i = 0; $i < 7; $i++){
          echo '<select class="form-control" id="favoritesselector'.$i.'" name="favoritesselector'.$i.'" width="20%">
                  <option value="btc" ';if($user->getFavorites()[$i] == "btc"){echo "selected ";} echo'>Bitcoin (btc)</option>
                  <option value="bch" ';if($user->getFavorites()[$i] == "bch"){echo "selected ";} echo'>Bitcoin Cash (bch)</option>
                  <option value="ltc" ';if($user->getFavorites()[$i] == "ltc"){echo "selected ";} echo'>Litecoin (ltc)</option>
                  <option value="eth" ';if($user->getFavorites()[$i] == "eth"){echo "selected ";} echo'>Ethereum (eth)</option>
                  <option value="xmr" ';if($user->getFavorites()[$i] == "xmr"){echo "selected ";} echo'>Monero (xmr)</option>
                  <option value="zec" ';if($user->getFavorites()[$i] == "zec"){echo "selected ";} echo'>Z-Cash (zec)</option>
                  <option value="xrp" ';if($user->getFavorites()[$i] == "xrp"){echo "selected ";} echo'>Ripple (xrp)</option>
                  <option value="xlm" ';if($user->getFavorites()[$i] == "xlm"){echo "selected ";} echo'>Stellar (xlm)</option>
                  <option value="icn" ';if($user->getFavorites()[$i] == "icn"){echo "selected ";} echo'>Iconomi (icn)</option>
                  <option value="eos" ';if($user->getFavorites()[$i] == "eos"){echo "selected ";} echo'>EOS (eos)</option>
                </select><br>';
        }
        echo ' <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-default">Add</button>
                </div>
              </div></form></div>';
}
function printFavoritesDropDownMenu($index){
  echo '<form class="form-inline"><select class="form-control" id="favoritesselector" name="favoritesselector" width="20%">
          <option value="btc">Bitcoin (btc)</option>
          <option value="bch">Bitcoin Cash (bch)</option>
          <option value="ltc">Litecoin (ltc)</option>
          <option value="eth">Ethereum (eth)</option>
          <option value="xmr">Monero (xmr)</option>
          <option value="zec">Z-Cash (zec)</option>
          <option value="xrp">Ripple (xrp)</option>
          <option value="xlm">Stellar (xlm)</option>
          <option value="icn">Iconomi (icn)</option>
          <option value="eos">EOS (eos)</option>
        </select></form>';
}
/**
 *
 * Fetches the current price of an asset from the API
 *
 * @param string $currency ticker code of currency for which the price is to be fetched
 * @param string $market market on which given $currency is traded
 * @return integer current market price of given $currency on market $market, rounded down to 3 decimals, in EUR
 */
function getPrice($currency, $market){
  $market = 'kraken';
  $data = file_get_contents("http://jssgns.be/splatr/Splatr_web/internal/api.php?market=".$market."&currency=".$currency);
  if(json_decode($data)->{'error'} == "0"){
    if(json_decode($data)->{'to'} != "EUR"){
      return round(json_decode($data)->{'price'}*getPrice("btc", $market), 3);
    } else {
      return round(json_decode($data)->{'price'}, 3);}
  } else {
    return 0;
  }
}

/**
 *
 * Calculates the total unrealised profit a user has currently obtained
 *
 * @param User $user the user for whom the profit is to be calculated
 * @param mysqli $connection the database object for retrieving the user's current positions
 * @return integer total unrealised profit
 */
function getTotalProfit($user, $connection){
  $totalValue = 0;
  $query_result = $connection->query("SELECT * FROM currencies WHERE email = '".$user->getEmail()."'");
  while($row = $query_result->fetch_assoc()) {
        $totalValue += $row['amount']*getPrice($row['currency'], "kraken");
  }
  return $totalValue - $user->getTotalInvestment();
}

/**
 *
 * Gives a certain percentage a green or red color depending on whether it's positive or negative
 *
 * @param string $value value to be given a color, either an integer or "&infin;"
 * @return string $value between html font tags
 */
function color($value){
  if($value == "&infin;"){return "<font color=\"green\">+&infin;%</font>";}
  elseif($value == 0){return "+" . $value . "%";}
  elseif($value < 0){return "<font color=\"red\">" . $value . "%</font>";}
  elseif($value > 0){return "<font color=\"green\">+" . $value . "%</font>";}
  else throw new Exception("invalid value given @ color function (index.php)", 1);
}

?>
</body>
</html>
