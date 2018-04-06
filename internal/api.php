<?php

define('KRAKEN_SUPPORTED_CURRENCIES', array('ETH'=>'ZEUR', 'XMR'=>'XXBT',
                                          'BCH'=>'XXBT', 'DASH'=>'XXBT',
                                          'ICN'=>'XXBT', 'XLM'=>'XXBT',
                                          'XRP'=>'ZEUR', 'EOS'=>'XXBT',
                                          'ETC'=>'XXBT', 'LTC'=>'ZEUR',
                                          'GNO'=>'XXBT', 'MLN'=>'XXBT',
                                          'REP'=>'XXBT', 'XDG'=>'XXBT',
                                          'ZEC'=>'ZEUR'));
define('BUFFER_LOCATION', '/home/user/jessegeens/www/splatr/Splatr_web/internal/python/buffer');

if(isValidGET('market') && isValidGET('currency')){
  $buffer = fopen(BUFFER_LOCATION, 'r') or returnError('failed to open buffer file');
  $data = fread($buffer, filesize(BUFFER_LOCATION));
  fclose($buffer);
  $data_json_formatted = json_decode($data);
  $returnString = json_encode($data_json_formatted->{'markets'}->{strtolower($_GET['market'])}->{strtoupper($_GET['currency'])});
  if($returnString == "null"){
    returnError('invalid/unsupported market or currency given');
  }else{
    echo $returnString;
  }
}
else{
  isValidGET('market') ? returnError("no currency variable given") : returnError("no market variable given");
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
  echo '{
      "error":"1",
      "description":"'.$error.'"
    }';
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
 ?>
