<?php

define('KRAKEN_SUPPORTED_CURRENCIES', array('ETH'=>'ZEUR', 'XMR'=>'XXBT',
                                          'BCH'=>'XXBT', 'DASH'=>'XXBT',
                                          'ICN'=>'XXBT', 'XLM'=>'XXBT',
                                          'XRP'=>'ZEUR', 'EOS'=>'XXBT',
                                          'ETC'=>'XXBT', 'LTC'=>'ZEUR',
                                          'GNO'=>'XXBT', 'MLN'=>'XXBT',
                                          'REP'=>'XXBT', 'XDG'=>'XXBT',
                                          'ZEC'=>'ZEUR'));

if(isValidGET('market') && isValidGET('currency')){
  switch ($_GET['market']) {
    case 'kraken':
      switch($_GET['currency']){
        case 'btc':
          returnData($_GET['market'], 'BTC', 'EUR', kraken('XXBTZEUR'));
          break;

        case array_key_exists(strtoupper($_GET['currency']), KRAKEN_SUPPORTED_CURRENCIES):
          returnData($_GET['market'], strtoupper($_GET['currency']),
          substr(KRAKEN_SUPPORTED_CURRENCIES[strtoupper($_GET['currency'])], 1), kraken('X'.strtoupper($_GET['currency']).KRAKEN_SUPPORTED_CURRENCIES[strtoupper($_GET['currency'])]));
          break;

        default:
          returnError('invalid/unsupported currency given');
          break;
      }
      break;

    default:
      returnError('invalid/unsupported market given');
      break;
  }
}else{
  isValidGET('market') ? returnError("no currency variable given") : returnError("no market variable given");
}
/**
 *
 * Returns the gathered data in JSON format
 *
 * @param string $exchange the exchange on which the price was looked up
 * @param string $from currency to be converted
 * @param string $to converted currency
 * @param integer $price value of $from currency
 * @return string data in JSON format
 */
function returnData($exchange, $from, $to, $price)
{
  echo '{"tradingpair": {
      "error":"0",
      "timestamp":"'.time().'",
      "market":"'.$exchange.'",
      "from":"'.$from.'",
      "to":"'.$to.'",
      "price":"'.$price.'"
  }}';
}
/**
 *
 * Return an error
 *
 * @param string $error error description
 * @return string error value and description in JSON format
 */
function returnError($error)
{
  echo '{"tradingpair": {
      "error":"1",
      "description":"'.$error.'"
    }}';
    exit;
}
/**
 *
 * Determines if a given $_GET variable is valid
 *
 * @param string $variable variable to check
 * @return bool false is $variable is invalid
 */
function isValidGET($variable)
{
  if(!isset($_GET[$variable]))
    return false;
  if($_GET[$variable] == null || $_GET[$variable] == '')
    return false;
  return true;
}
/**
 *
 * Use the Kraken API to retrieve up-to-date currency prices
 *
 * @param string $currency the currency of which the price is requested
 * @return integer Market price on Kraken for the given currency
 */
function kraken($currency)
{
  // api credentials
  $key = 'aGlmT37TXtSWv7kmFUej6odXYZNN9lM3OXK5l/78mZfwHhdRVD/FiGeN';
  $secret = 'q2QcYNz/vlRLD9m5wTxg/mRE3w62QSYqk0KntXBz/tMqCxOTMUpMgwJSfrgjUPF4vjVwctT7kIOqEr3fGsUZmw==';
  // set which platform to use (beta or standard)
  $beta = false;
  $url = $beta ? 'https://api.beta.kraken.com' : 'https://api.kraken.com';
  $sslverify = $beta ? false : true;
  $version = 0;
  $kraken = new KrakenAPI($key, $secret, $url, $version, $sslverify);
  // Query a public list of active assets and their properties:
  $res = $kraken->QueryPublic('Ticker', array('pair' => $currency));
  return $res['result'][$currency]['a']['0'];
}

class KrakenAPI{
  protected $key;     // API key
  protected $secret;  // API secret
  protected $url;     // API base URL
  protected $version; // API version
  protected $curl;    // curl handle
  /**
   * Constructor for KrakenAPI
   *
   * @param string $key API key
   * @param string $secret API secret
   * @param string $url base URL for Kraken API
   * @param string $version API version
   * @param bool $sslverify enable/disable SSL peer verification.  disable if using beta.api.kraken.com
   */
  function __construct($key, $secret, $url='https://api.kraken.com', $version='0', $sslverify=true)
  {
      /* check we have curl */
      if(!function_exists('curl_init')) {
       print "[ERROR] The Kraken API client requires that PHP is compiled with 'curl' support.\n";
       exit(1);
      }
      $this->key = $key;
      $this->secret = $secret;
      $this->url = $url;
      $this->version = $version;
      $this->curl = curl_init();
      curl_setopt_array($this->curl, array(
          CURLOPT_SSL_VERIFYPEER => $sslverify,
          CURLOPT_SSL_VERIFYHOST => 2,
          CURLOPT_USERAGENT => 'splatr apiPortal Agent',
          CURLOPT_POST => true,
          CURLOPT_RETURNTRANSFER => true)
      );
  }
  function __destruct()
  {
  	if(function_exists('curl_close')) {
       curl_close($this->curl);
}
  }
  /**
   * Query public methods
   *
   * @param string $method method name
   * @param array $request request parameters
   * @return array request result on success
   * @throws KrakenAPIException
   */
  function QueryPublic($method, array $request = array())
  {
      // build the POST data string
      $postdata = http_build_query($request, '', '&');
      // make request
      curl_setopt($this->curl, CURLOPT_URL, $this->url . '/' . $this->version . '/public/' . $method);
      curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
      curl_setopt($this->curl, CURLOPT_HTTPHEADER, array());
      $result = curl_exec($this->curl);
      if($result===false)
          throw new KrakenAPIException('CURL error: ' . curl_error($this->curl));
      // decode results
      $result = json_decode($result, true);
      if(!is_array($result))
          throw new KrakenAPIException('JSON decode error');
      return $result;
  }
  /**
   * Query private methods
   *
   * @param string $method method path
   * @param array $request request parameters
   * @return array request result on success
   * @throws KrakenAPIException
   */
  function QueryPrivate($method, array $request = array())
  {
      if(!isset($request['nonce'])) {
          // generate a 64 bit nonce using a timestamp at microsecond resolution
          // string functions are used to avoid problems on 32 bit systems
          $nonce = explode(' ', microtime());
          $request['nonce'] = $nonce[1] . str_pad(substr($nonce[0], 2, 6), 6, '0');
      }
      // build the POST data string
      $postdata = http_build_query($request, '', '&');
      // set API key and sign the message
      $path = '/' . $this->version . '/private/' . $method;
      $sign = hash_hmac('sha512', $path . hash('sha256', $request['nonce'] . $postdata, true), base64_decode($this->secret), true);
      $headers = array(
          'API-Key: ' . $this->key,
          'API-Sign: ' . base64_encode($sign)
      );
      // make request
      curl_setopt($this->curl, CURLOPT_URL, $this->url . $path);
      curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
      curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
      $result = curl_exec($this->curl);
      if($result===false)
          throw new KrakenAPIException('CURL error: ' . curl_error($this->curl));
      // decode results
      $result = json_decode($result, true);
      if(!is_array($result))
          throw new KrakenAPIException('JSON decode error');
      return $result;
  }
}
?>
