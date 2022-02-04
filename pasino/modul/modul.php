<?php

function save_history($data)
{
    $file = "../modul/history.txt";
    if (file_exists($file)) {
        file_put_contents($file, "\n\n\n" . $data, FILE_APPEND);
    } else {
        echo "File Tidak Ditemukan";
        die();
    }
}


function gen_email()
{
    return  str_replace(" ", "", gen_nama()) . mt_rand(100, 999);
}

function gen_nohp($kode = false)
{
    if ($kode) {
        return $kode . "81" . rand(111111111, 999999999);
    } else {
        echo "Generate No Hp Gagal, kode tidak ditemukan";
        die();
    }
}

function gen_nama()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $ex = curl_exec($ch);
    preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
    return $name[2][mt_rand(0, 14)];
}


function curl($is = array(), $time = 20)
{
    set_time_limit(0);
    //Tamplate->  $data = array('url'=>'','data'=>'','sock'=>'','header'=>'','save'=>'','cookie'=>'');

    $load = count($is) <= 0 ? die() : $is;
    array_key_exists('url', $load);
    $load['url'] = array_key_exists('url', $load) ? $load['url'] : '';
    $load['data'] = array_key_exists('data', $load) ? $load['data'] : '';
    $load['sock'] = array_key_exists('sock', $load) ? $load['sock'] : '';
    $load['save'] = array_key_exists('save', $load) ? $load['save'] : '';
    $load['cookie'] = array_key_exists('cookie', $load) ? $load['cookie'] : '';
    $load['header'] = array_key_exists('header', $load) ? $load['header'] : '';



    $url = $load['url'] !== '' ? $load['url'] : false;
    $data =  $load['data'] !== '' ? $load['data'] : false;
    $socks = $load['sock'] !== '' ? $load['sock'] : false;
    $cookie =  $load['cookie'] == '1' ? true : false;
    $save =  $load['save'] == '1' ? true : false;
    $ex_header = $load['header'] !== '' ? $load['header'] : false;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, $time); //timeout in seconds
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);




    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, '../modul/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '../modul/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    }

    if ($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    }

    if ($socks) {
        curl_setopt($ch, CURLOPT_PROXY, $socks);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $headers = array();


    if ($ex_header) {
        $hd = preg_split('/\r\n|\r|\n/', $ex_header);

        for ($x = 0; $x < count($hd); $x++) {
            $headers[] = $hd[$x];
        }
    } else {
      //  $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Origin: ' . $url;
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
        $headers[] = 'Accept: application/json, text/plain, */*';
    }


    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $ex = curl_exec($ch);
    $url =  curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $httpcode = curl_getinfo($ch);


    if (!$httpcode) {
        $result = 'curl faild!';
        $header = '';
    } else {
        $header = substr($ex, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body   = substr($ex, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $result   = $ex;
    }

    if ($save) {
        save_history($result);
    }

    //    $info =  curl_getinfo($ch) . '<br/>'. curl_errno($ch) . '<br/>'. curl_error($ch) . '<br/>';

    curl_close($ch);

    return array('result' => $result, 'url' => $url, 'header' => $header, 'body' => $body);
}


function rupiah($angka)
{

    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}