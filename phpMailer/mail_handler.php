<?php
header('Access-Control-Allow-Origin: *');

require_once('email_config.php');
require_once('src/Exception.php');
require_once('src/PHPMailer.php');
require_once('src/SMTP.php');

foreach($_POST as $key=>$value){
    $_POST[$key] = htmlentities( addslashes($value));
}

$mail = new PHPMailer\PHPMailer\PHPMailer;
$mail->SMTPDebug = 0;           // Enable verbose debug output. Change to 0 to disable debugging output.

$mail->isSMTP();                // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;         // Enable SMTP authentication


$mail->Username = EMAIL_USER;   // SMTP username
$mail->Password = EMAIL_PASS;   // SMTP password
$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;              // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = 'edmundpark.server@gmail.com';  // sender's email address (shows in "From" field)
$mail->FromName = 'mailer';   // sender's name (shows in "From" field)
$mail->addAddress('park.edmund@gmail.com', 'Edmund');  // Add a recipient (name is optional)
//$mail->addAddress('ellen@example.com');                        // Add a second recipient
$mail->addReplyTo($_POST['email']);                          // Add a reply-to address
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'mailer message from '.$_POST['contactName'];
$mail->Body    = "
    time: ".date('Y-m-d H:is:s')."<br>
    from: {$_SERVER['REMOTE_ADDR']}<br>
    name: {$_POST['contactName']}<br>
    email: {$_POST['email']}<br>
    message: {$_POST['comments']}
    ";
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
