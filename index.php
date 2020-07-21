<?php
   $varmail = $_GET['mail'];
   $varname = $_GET['name'];
?>
<?php
//require("dot.php");
require("class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1; // hata ayiklama: 1 = hata ve mesaj, 2 = sadece mesaj
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl'; // Güvenli baglanti icin ssl normal baglanti icin tls
$mail->Host = "smtp.yandex.com.tr"; // Mail sunucusuna ismi
$mail->Port = 465; // Gucenli baglanti icin 465 Normal baglanti icin 587
$mail->IsHTML(true);
$mail->SetLanguage("tr", "phpmailer/language");
$mail->CharSet  ="utf-8";
$mail->Username = "realist.tm@yandex.com"; // Mail adresimizin kullanicı adi
$mail->Password = "qqhfibcisbzglakw"; // Mail adresimizin sifresi
$mail->SetFrom("realist.tm@yandex.com",$varname); // Mail attigimizda gorulecek ismimiz
$mail->AddAddress("realist.tm@yandex.com"); // Maili gonderecegimiz kisi yani alici
$mail->Subject = "You win"; // Konu basligi
$mail->Body = "your sub"; // Mailin icerigi

if(!$mail->Send()){
    echo "Mailer Error: ".$mail->ErrorInfo;
} else {
    echo "Mesaj gonderildi";
}
?>