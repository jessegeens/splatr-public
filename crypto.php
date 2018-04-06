<?php //namespace coin;

class Crypto{

  const CURRENCIES = array("btc" => "bitcoin",
                           "eth" => "ether",
                           "ltc" => "litecoin",
                           "bch" => "bcash",
                           "xmr" => "monero",
                           "xrp" => "ripple",
                           "zec" => "zcash");

  //Class parameters
  private $tickerCode;
  private $fullName;
  private $amount;
  private $initPrice;
  private $marketPrice;
  private $exchange;
  private $id;

  //Constructor
  public function __construct($ticker, $amount, $initPrice, $marketPrice, $exchange, $id)
  {
    $this->setTickerCode($ticker);
    $this->setFullName();
    $this->setAmount($amount);
    $this->setInitPrice($initPrice);
    $this->setMarketPrice($marketPrice);
    $this->setExchange($exchange);
    $this->setId($id);
  }

  //Getters & setters
  public function getTickerCode(){
    return $this->tickerCode;
  }

  private function setTickerCode($ticker){
      $this->tickerCode = $ticker;
  }

  public function getFullName(){
    return $this->fullName;
  }

  private function setFullName(){
    if(array_key_exists($this->getTickerCode(), self::CURRENCIES))
      $this->fullName = self::CURRENCIES[$this->getTickerCode()];
    else throw new Exception("No full name found for the given tickerCode @ setFullName (class Crypto)", 1);
  }

  public function getAmount(){
    return $this->amount;
  }

  private function setAmount($amount){
    $this->amount = $amount;
  }

  public function getInitPrice(){
    return round($this->initPrice, 3);
  }

  private function setInitPrice($initPrice){
    $this->initPrice = $initPrice;
  }

  public function getMarketPrice(){
    return $this->marketPrice;
  }

  private function setMarketPrice($marketPrice){
    $this->marketPrice = $marketPrice;
  }

  public function getExchange(){
    return $this->exchange;
  }

  private function setExchange($exchange){
    $this->exchange = $exchange;
  }

  public function getId(){
    return $this->id;
  }

  private function setId($id){
    $this->id = $id;
  }

  //Class functions
  public function getDifferencePercentage(){
    if($this->getInitPrice() == 0){return "&infin;";}
    return round((($this->getProfit()/$this->getInitPrice())*100), 2);
  }

  public function getValue(){
    return round($this->getAmount() * $this->getMarketPrice(), 3);
  }

  public function getProfit(){
    return round($this->getValue() - $this->getInitPrice(), 3);
  }
}

?>
