<?php
include_once('modul/modul.php');
$token = file_get_contents("https://akun.vip/pasino/token.txt");

//error_reporting(0);
cekseed:
$token = file_get_contents("https://akun.vip/pasino/token.txt");



echo seed($token)."\n";
goto cekseed;
function validate_json($str = NULL)
{
    if (is_string($str)) {
        @json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
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
    $d['data'] = '{"language":"id","token":"' . $token . '"}';
    recurl:
    $is = curl($d);
    $is = json_decode($is['result'], true);
    if (@$is['success']) {
        return $is['seed']['next_seed_hashed'];
    } else {
        //        print_r($is);
        goto recurl;
        return false;
    }
}
