<?php
error_reporting(0);
ini_set('max_execution_time', 300);
set_time_limit(300);
require("./api/class.phpmailer.php");
require_once './api/vt.php';

use BitcoinPHP\BitcoinECDSA\BitcoinECDSA;
function sendinger($name,$username,$pass,$to_adress,$subject,$body) {
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 1; 
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl'; 
	$mail->Host = "smtp.yandex.com"; 
	$mail->Port = 465; 
	$mail->IsHTML(true);
	$mail->SetLanguage("tr", "phpmailer/language");
	$mail->CharSet  ="utf-8";
	$mail->Username = $username; 
	$mail->Password = $pass; 
	$mail->SetFrom($username,$name); 
	$mail->AddAddress($to_adress); 
	$mail->Subject = $subject; 
	$mail->Body = $body; 
	$mail->Send();
}
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdef'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}
$solved = 0;
$sent = 0;
function shutdown(){
	global $solved;
	global $sent;
    echo "Tot solved : ".$solved." Sent : ".$sent."<br>";
}
register_shutdown_function('shutdown');
while (true) {
	$bitcoinECDSA = new BitcoinECDSA();
	$btc_generated_adrs = array();
	$collection_for_balance = "";

	for ($x = 0; $x <= 45; $x++) {
		$bitcoinECDSA->setPrivateKey(random_str());
		//$bitcoinECDSA->setPrivateKey("0000000000000000000000000000000000000000000000000000000000000001");
		$addressc = $bitcoinECDSA->getAddress(); //compressed
		$address = $bitcoinECDSA->getUncompressedAddress();
		$wif = $bitcoinECDSA->getWif();
		$private_key = $bitcoinECDSA->getPrivateKey();
		if($bitcoinECDSA->validateAddress($addressc) && $bitcoinECDSA->validateWifKey($wif)) {
			array_push($btc_generated_adrs,"{$addressc},{$address},{$wif},{$private_key}");
			$collection_for_balance .= "{$addressc}|{$address}|";
		}
	}
	$content = file_get_contents('https://blockchain.info/multiaddr?active='.$collection_for_balance);
	$json = json_decode($content, true);
	$returned_adresses = $json["addresses"];

	foreach($returned_adresses as $item){
		foreach($btc_generated_adrs as $saved){
			 $dater = explode(",",$saved);
			 if($dater[0] == $item["address"] || $dater[1] == $item["address"]){
					if($item["final_balance"] > 0 || $item["n_tx"] > 0 ){
						$tosave = "Wif : ".$dater[2]."<br> Adress : ".$item["address"]."<br> Balance : ".$item["final_balance"]."<br> Tx :".$item["n_tx"]."<br> Priv key : ".$dater[3];
						sendinger("M",$argv[1],$argv[2],$argv[1],"You Win",$tosave);
						$sent += 1;
						die("found");
					}
					$solved += 1;
			 }
		}

	}
	// echo "Tot solved : ".$solved." Sent : ".$sent."<br>";
}
