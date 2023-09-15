<?php
// CORS HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header('Content-Type: application/json; charset=utf-8');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1

$ipClient = $_SERVER['REMOTE_ADDR'];
$whois = shell_exec("whois $ipClient");
$browserCliente = $_SERVER['HTTP_USER_AGENT'];

$browser = '';
$so = '';

$result = explode("\n", $whois);


$out = array();
foreach ($result as $line) {
    if (substr($line, 0, 1) == '%' || substr($line, 0, 1) == '#') {
        continue;
    }

    $ps = explode(':', $line);
    $out[trim($ps[0])] = trim($ps[1]);
}

$browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'MSIE'];

$sos = ['Windows', 'Linux', 'Mac OS X'];

foreach ($sos as $s) {
    if (strpos($browserCliente, $s) !== false) {
        $so = $s;
    }
}

foreach ($browsers as $b) {
    if (strpos($browserCliente, $b) !== false) {
        $browser = $b;
    }
}

if (strpos($browserCliente, 'Chrome') !== false) {
    $browser = 'Google Chrome';
}

$browser2 = get_browser(null, true);

$empresa = $out['owner'] ?? $out['Admin Organization'];
$empresa = empty($empresa) ? 'Não identificado.' : $empresa;

$data = [
    'isp' => $empresa === 'ENTER INFO INFORMATICA E SERVI�OS LTDA' ? str_replace('�', 'Ç', $empresa) : $empresa,
    'ip' => $ipClient,
    'so' => $so,
    'browser' => (!empty($browser2)) ? $browser2['browser'] : $browser,
    'isGoodConnection' => $empresa === 'ENTER INFO INFORMATICA E SERVI�OS LTDA'
];

echo json_encode($data);

/*print '<pre>';
print_r($out);
print '</pre>';*/
