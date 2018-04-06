import urllib2
import json

BUFFER_PATH = '/home/user/jessegeens/www/splatr/Splatr_web/internal/python/buffer'
CURRENCIES = ['btc', 'bch', 'ltc', 'eth', 'xlm', 'xmr', 'zec', 'icn', 'eos', 'xrp']
output_array = []
try:
    for currency in CURRENCIES:
        req = urllib2.Request('http://splatr.jssgns.be/Splatr_web/internal/apiportal.php?market=kraken&currency=' + currency)
        response = urllib2.urlopen(req)
        data = response.read()
        parsed_data = json.loads(data)
        output_array.append(json.dumps(parsed_data['tradingpair']))

    output = '{"markets": { "kraken": {'
    for coin in output_array:
        output = output + str(json.dumps(json.loads(coin)["from"])) + ': ' + coin + ', '
    output = output[:-2] + '}}}'
    buffer = open(BUFFER_PATH, 'w')
    buffer.write(output)
except:
    exit
