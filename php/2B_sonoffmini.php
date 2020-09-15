<?
// -----------------------------------------------------------------------------
// 2B_sonoffmini : Gestion de l'interface des SonOff Mini en mode DIY
// -----------------------------------------------------------------------------


// ip : ip du sonoff mini
// deviceid : id du device
// cmd : command => switch, pulse, startup, info, signal
// data : si switch, pulse ou startup (non utiliser par le plugin)
// ver : 1 : firmware < à 3.5.0 , 2 : firmware >= à 3.5.0

$ip = getarg('ip', false, '');
$deviceid = getarg('id', false, '');
$cmd = getarg('cmd', false, 'info');
$data = getarg('data', false, '');
$ver = getarg('ver', false, 1);
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

//$jsresult = preg_replace('/(-?\d+(\.\d+)?)(,|})/', '"$1"$3', $jsresult);
//$jsresult = preg_replace('/(true|false)(,|})/', '"$1"$2', $jsresult);

$xmlresult = jsonToXML($jsresult);
sdk_header('text/xml');


if ($ver == 1)
{
    // pour les firmware < à 3.5.0
    $data = xpath($xmlresult, "data");
    echo jsonToXML($data);
}
else
{
    // pour les firmware >= 3.5.0
    echo $xmlresult;
}


?>