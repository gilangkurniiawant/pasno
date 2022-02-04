<?php
include_once('modul/modul.php');
$token = file_get_contents("https://akun.vip/pasino/token.txt");

//error_reporting(0);
ntoken:
$token = file_get_contents("https://akun.vip/pasino/token.txt");



$new_token = token($token);
if ($new_token) {
    stoken:
    $var = fopen(__DIR__ . "/modul/token.txt", "w");
    if (flock($var, LOCK_EX)) {
        fwrite($var, $new_token);
        flock($var, LOCK_UN);
        fclose($var);
        $d['url'] = "https://akun.vip/pasino/index.php/?token=$new_token";
        $is = curl($d);
    } else {
        goto stoken;
    }
    echo "[+] Update Token Berhasil \n";
    $nam = 0;

    for ($nam = 0; $nam < 30; $nam++) {
        $sap = "";
        $ibet = infobet($new_token);
        $saldo = saldo($new_token);
        $saldoidr = 966 * $saldo;

        $sap =  "[+] Saldo               : $saldo TRX\n";
        $sap .=  "                                  (" . rupiah($saldoidr) . ")\n";
        $sap .= "[+] Total Bet         : " . $ibet[1] . "\n";
        $sap .= "[+] Total Warger  : $" . $ibet[0] . "\n";
        tele($sap);
        for($cap=0;$cap<60; $cap++)
        {
            seed($new_token);
            sleep(1);

        }
    }
    goto ntoken;
} else {
    echo "[+] Update Token Gagal \n";
    goto ntoken;
}
function token($token)
{
    $d['url'] = "https://api.pasino.com/account/refresh-token";
    $d['header'] = 'Host: api.pasino.com
Cache-Control: max-age=0
Sec-Ch-Ua: " Not A;Brand";v="99", "Chromium";v="96", "Microsoft Edge";v="96"
Sec-Ch-Ua-Mobile: ?0
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62
Sec-Ch-Ua-Platform: "Windows"
Content-Type: application/x-www-form-urlencoded
Accept: */*
Origin: https://pasino.com
Sec-Fetch-Site: same-site
Sec-Fetch-Mode: cors
Sec-Fetch-Dest: empty
Referer: https://pasino.com/
Accept-Language: en-US,en;q=0.9';
    $d['data'] = '{"token":"' . $token . '","language":"id"}';
    recurl:
    $is = curl($d);
    $is = json_decode($is['result'], true);
    print_r($is);
    if ($is['new_token']) {
        return $is['new_token'];
    } else {
        print_r($is);
        goto recurl;
        return false;
    }
}

function validate_json($str = NULL)
{
    if (is_string($str)) {
        @json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
}


function tele($data)
{
    $data  = urlencode($data);

    $d['url'] = "https://api.telegram.org/bot1356149887:AAFOD2v7emP9b1AcfhdEQXuRz3hjddvW624/sendMessage?chat_id=@caridolarcair&text=$data";
    $is = curl($d);
}


function saldo($token)
{
    $d['url'] = "https://api.pasino.com/coin/get-balances";
    $d['header'] = 'Host: api.pasino.com
Cache-Control: max-age=0
Sec-Ch-Ua: " Not A;Brand";v="99", "Chromium";v="96", "Microsoft Edge";v="96"
Sec-Ch-Ua-Mobile: ?0
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62
Sec-Ch-Ua-Platform: "Windows"
Content-Type: application/x-www-form-urlencoded
Accept: */*
Origin: https://pasino.com
Sec-Fetch-Site: same-site
Sec-Fetch-Mode: cors
Sec-Fetch-Dest: empty
Referer: https://pasino.com/
Accept-Language: en-US,en;q=0.9';
    $d['data'] = '{"token":"' . $token . '"}';
    recurl:
    $is = curl($d);
    $is = json_decode($is['result'], true);

    if ($is['coins']) {
        foreach ($is['coins'] as $has) {
            if ($has['coin'] == "TRX") {
                return $has['balance'];
            }
        }
    } else {
        //        print_r($is);
        goto recurl;
        return false;
    }
}



function infobet($token)
{
    $d['url'] = "https://api.pasino.com/statistics/profile";
    $d['header'] = 'Host: api.pasino.com
Cache-Control: max-age=0
Sec-Ch-Ua: " Not A;Brand";v="99", "Chromium";v="96", "Microsoft Edge";v="96"
Sec-Ch-Ua-Mobile: ?0
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62
Sec-Ch-Ua-Platform: "Windows"
Content-Type: application/x-www-form-urlencoded
Accept: */*
Origin: https://pasino.com
Sec-Fetch-Site: same-site
Sec-Fetch-Mode: cors
Sec-Fetch-Dest: empty
Referer: https://pasino.com/
Accept-Language: en-US,en;q=0.9';
    $d['data'] = '{"language":"id","user_name":"gilang89167y93","token":"' . $token . '"}';
    recurl:
    $is = curl($d);
    $is = json_decode($is['result'], true);
    if (@$is['profile']) {
        return array($is['profile']['total_wagered'], $is['profile']['bets']);
    } else {
        //        print_r($is);
        goto recurl;
        return false;
    }
}



function seed($token)
{
    $d['url'] = "https://api.pasino.com/dice/rotate-seed";
    $d['header'] = 'Host: api.pasino.com
Cache-Control: max-age=0
Sec-Ch-Ua: " Not A;Brand";v="99", "Chromium";v="96", "Microsoft Edge";v="96"
Sec-Ch-Ua-Mobile: ?0
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62
Sec-Ch-Ua-Platform: "Windows"
Content-Type: application/x-www-form-urlencoded
Accept: */*
Origin: https://pasino.com
Sec-Fetch-Site: same-site
Sec-Fetch-Mode: cors
Sec-Fetch-Dest: empty
Referer: https://pasino.com/
Accept-Language: en-US,en;q=0.9';
    $d['data'] = '{"language":"id","token":"'.$token.'"}';
    recurl:
    $is = curl($d);
    $is = json_decode($is['result'], true);
    if (@$is['success']) {
        return true;
    } else {
        //        print_r($is);
        goto recurl;
        return false;
    }
}
