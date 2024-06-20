<?php

$tmpfile = tempnam(sys_get_temp_dir(), "CURLCOOKIE");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://stackoverflow.com");
curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfile); // if logged in properly, login.php will tell the system which cookies to save and curl will save them in the temporary file $tmpfile
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$curl_result = @curl_exec($ch);
$curl_err = curl_error($ch);
curl_close($ch);

// $url = 'https://stackoverflow.com';

var_dump($curl_result);
