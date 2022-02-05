<?php
include_once('modul/modul.php');
$token = file_get_contents("https://akun.vip/pasino/token.txt");


//error_reporting(0);


function validate_json($str = NULL)
{
    if (is_string($str)) {
        @json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
}



function bet($bet, $win_chance, $type = 1,$coin)
{
    global $btse,$token;
    $bet = number_format($bet, 8, '.', '');
    $bet = explode(".", $bet);
    $lim = 8;
    if (@$bet[1]) {
        $bet = $bet[0] . "." . substr($bet[1], 0, $lim);
    } else {
        $bet = $bet[0];
    }

    $btse = $bet;

    $payout = explode(".", (95 / $win_chance));

    if ($bet[0] <= 1) {
        $lim = 8;
    } else {
        $lim = 8;
    }
    
    if (@$payout[1]) {
        $payout = $payout[0] . "." . substr($payout[1], 0, 5);
    } else {
        $payout = $payout[0];
    }

   

    
    $profit = (($payout * $bet) - $bet);
    $profit = number_format($profit, 10);
    $profit = explode(".",$profit);

    if (@$profit[1]) {
        $profit = $profit[0] . "." . substr($profit[1], 0, $lim);
    } else {
        $profit = $profit[0];
    }


//        $seed = "00000000999999990000000099999999888888889999999900000000";

     $seed = rand(11111111,99999999)."" . rand(11111111, 99999999) . "" . rand(11111111, 99999999) . "" . rand(11111111, 99999999) . "" . rand(11111111, 99999999) . "" . rand(11111111, 99999999) . "" . rand(11111111, 99999999) . "" . rand(11111111, 99999999);
  //  $seed = "000";
    $d['url'] = "https://api.pasino.com/dice/play";
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
    $d['data'] = '{
    "token": "'.$token.'",
    "bet_amt": "' . $bet . '",
    "coin": "'.$coin.'",
    "type": ' . $type . ',
    "payout": "' . $payout . '",
    "winning_chance": "' . $win_chance . '",
    "profit": "' . $profit . '",
    "client_seed": "' . $seed . '"
}';

 //   echo $d['data'];


     // $d['sock'] = '127.0.0.1:8080';
    batgen:
    $is = curl($d);
    if (validate_json($is['result'])) {
        return $is['result'];
    }else{
        goto batgen;
    }
}


//non 0
$lst = 0;
$btse=0;
$allbet=0;
$non=0;

awal:
$tipe = rand(1, 2);
if($non>100000){
    die();
}

$set = explode("|", file_get_contents(__DIR__ . "/modul/set.txt"));
$stope = explode("|", file_get_contents(__DIR__ . "/modul/stop.txt"));

if ($stope == true) {
    die();
}
$winchan = $set[1];
$ls = 0;
$bjum = $set[0];
$jum = $set[0];
$coin = $set[2];
$maxlose = $set[3];


ulang:
if ($jum > $maxlose) {
    tele("[+] Pecah Telor");
    $jum = $bjum;
}

echo "|";


$search  = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49", "50", "51", "52", "53", "54", "55", "56", "57", "58", "59", "60", "61", "62", "63", "64", "65", "66", "67", "68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81", "82", "83", "84", "85", "86", "87", "88", "89", "90");
$replace = array("1.011", "1.022", "1.033", "1.045", "1.057", "1.069", "1.081", "1.094", "1.107", "1.120", "1.134", "1.148", "1.162", "1.177", "1.192", "1.207", "1.223", "1.240", "1.256", "1.273", "1.291", "1.309", "1.328", "1.347", "1.367", "1.387", "1.408", "1.430", "1.452", "1.475", "1.499", "1.523", "1.549", "1.575", "1.602", "1.630", "1.659", "1.689", "1.720", "1.753", "1.786", "1.821", "1.858", "1.895", "1.935", "1.976", "1.999", "2.063", "2.110", "2.159", "2.210", "2.264", "2.320", "2.380", "2.442", "2.508", "2.577", "2.651", "2.728", "2.811", "2.898", "2.991", "3.090", "3.197", "3.310", "3.432", "3.563", "3.705", "3.859", "4.025", "4.207", "4.406", "4.625", "4.867", "5.135", "5.435", "5.772", "6.153", "6.588", "7.090", "7.674", "8.363", "9.188", "10.193", "11.446", "13.049", "15.176", "18.130", "22.512", "29.688");
$multiper = $replace[$winchan - 1];

$dice  = json_decode(bet($jum, $winchan, $tipe,$coin), true); //1.094
$non++;


if ($dice['message'] == "Your balance is not sufficient to make this transaction.") {
    tele("[+] Saldo Habis");
    die();
}


if (@$dice['coin']) {
    if ($dice['win'] == 1) {
        echo "|Bet[$tipe]  Win $btse : " . $dice['profit'] . " Saldo :" . $dice['balance'] . " LS : $ls |[$lst][$allbet][$non]\n";
        goto awal;
    } else if ($dice['win'] == 0) {
        echo "|Bet[$tipe] Lose $btse : " . $dice['profit'] . " Saldo :" . $dice['balance'] . " LS : $ls |[$lst][$allbet][$non]\n";
        sleep(1);
        $jum = $jum * $multiper;
        $ls++;
        if ($ls > $lst) {
            $lst = $ls;
        }
        if ($btse > $allbet) {
            $allbet = $btse;
        }
        goto ulang;
    }
} else if(@$dice['bad_token']){
    echo "|| Update token\n";
    $token = file_get_contents("https://akun.vip/pasino/token.txt");
    goto ulang;

}else{
    print_r($dice);
}
