<?

// ip : ip du sonoff mini
// deviceid : id du device
// cmd : command => switch, pulse, startup, info, signal
// data : si switch, pulse ou startup (non utiliser par le plugin)

$ip = getarg('ip', false, '');
$deviceid = getarg('id', false, '');
$cmd = getarg('cmd', false, 'info');
$data = getarg('data', false, '');

$time =time();
$json = '{ "deviceid": "'.$deviceid.'", "sequence": "'.$time.'", "data": {';
if ($data != '')
    $json = $json.$data;
$json = $json.'}  }';

$item=$cmd;
if ($cmd=='signal') $item = "signal_strength";


$json = str_replace("\\\"","\"", $json);
$json = str_replace("\\\"","\"", $json);

$url = "http://".$ip.":8081/zeroconf/".$item;
$jsresult =  httpQuery($url, "POST", $json);

// remplacement de / par _ dans le json pour la convertion XML
// + convertion tableau et xml
$jsresult = str_replace("/", "_",$jsresult);
$xmlresult = jsonToXML($jsresult);

sdk_header('text/xml');
$data = xpath($xmlresult, "data");
//$data = $data.' , json:"'.$json.'"';
echo jsonToXML($data);

?>