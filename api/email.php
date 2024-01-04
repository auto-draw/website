<?php

require_once('../api/auth.php');
require_once('db_connect.php');
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = $_ENV['EMAIL_HOST'];
$mail->SMTPAuth = true;
$mail->Username = $_ENV['EMAIL_USERNAME'];
$mail->Password = $_ENV['EMAIL_PASSWORD'];
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->isHTML(true);

//Recipients
function SendEmail($recipient, $header, $body){
    global $mail;
    $mail->setFrom($_ENV['EMAIL_SENDER_EMAIL'], $_ENV['EMAIL_SENDER_NAME']);
    $mail->addAddress($recipient);

    // Content
    $mail->Subject = $header;

    // To make sure we don't accidentally capture the PHP code inside the file, we will process the PHP code in a buffer.
    ob_start();
    include 'miniatures/template.php';
    $templateOutput = ob_get_clean();

    // We will then set this to the body, replacing any wildcards.
    $mail->Body = $templateOutput;

    $mail->send();
    return 200;
}
// Example code. To newline, do <br>. To add spacing between 2 sentences, do 2 <br>'s.

function SendVerEmail($emailuser){ // Can be either email or username
    global $conn;

    // Find the user based off of their name or email
    $type = strpos($emailuser, "@") !== false ? "email" : "username";
    $query = "SELECT id, username, email, banned FROM users WHERE $type = $1 AND banned = FALSE;";
    $result = pg_query_params($conn, $query, array($emailuser));
    $user = pg_fetch_assoc($result);
    if (!$user) { return "User does not exist!"; }
    $username = $user["username"];

    if ($user['banned'] == 't') { return "You are banned!"; }

    // Disallow verifications if the user requested one within the last 30 minutes... (FIX)
    /* $query = "SELECT expiry FROM temp WHERE user = $1 AND type = 'emailverification' ORDER BY expiry DESC";
     $result = pg_fetch_assoc(pg_query_params($conn, $query, array($user['id'])));
     if ($result['expiry'] > time() - (60 * 30)) { return "Try again in ". json_encode($result) ." minutes!"; }*/

    // Add the verification code to the database
    $query = "INSERT INTO temp VALUES ($1, $2, $3, $4);";
    $code = bin2hex(random_bytes(3));
    pg_query_params($conn, $query, array("emailverification", $user['id'], $code, intval(time() + (60 * 60))));

    SendEmail($user['email'], "Verify Email on ".$_ENV['EMAIL_SENDER_EMAIL'], "
    Dear $username,<br><br>
    
    Thank you for signing up to our website and engaging with our community! To ensure the security of your account, we require you to complete the verification process.<br><br>
    
    Please follow one of the steps below to successfully verify your account:
    
    <br> - Click on the following link to access the verification page: <a href='https://auto-draw.com/api/email?ver=$code'>https://auto-draw.com/api/email?ver=$code</a>
    <br> - Use the following verification code: $code
    <br><br>
    
    Note that these verification codes expire after 1 hour, where you can request a new code 30 minutes after the previous one. 
    Thank you for your cooperation in maintaining a secure environment for all our users.<br><br>
    
    Best regards,<br>
    The Auto-Draw developers.
    ");
}
function SendResetEmail($emailuser){ // Can be either email or username
    global $conn;

    // Find the user based off of their name or email
    $type = strpos($emailuser, "@") !== false ? "email" : "username";
    $query = "SELECT id, username, email, banned FROM users WHERE $type = $1 AND banned = FALSE;";
    $result = pg_query_params($conn, $query, array($emailuser));
    $user = pg_fetch_assoc($result);
    if (!$user) { return "User does not exist!"; }
    $username = $user["username"];

    if ($user['banned'] == 't') { return "You are banned!"; }

    // Disallow verifications if the user requested one within the last 30 minutes... (FIX)
    /* $query = "SELECT expiry FROM temp WHERE user = $1 AND type = 'emailverification' ORDER BY expiry DESC";
     $result = pg_fetch_assoc(pg_query_params($conn, $query, array($user['id'])));
     if ($result['expiry'] > time() - (60 * 30)) { return "Try again in ". json_encode($result) ." minutes!"; }*/

    // Add the verification code to the database
    $query = "INSERT INTO temp VALUES ($1, $2, $3, $4);";
    $code = bin2hex(random_bytes(32));
    pg_query_params($conn, $query, array("resetpassword", $user['id'], $code, intval(time() + (60 * 60))));

    SendEmail($user['email'], "Reset Password on ".$_ENV['EMAIL_SENDER_EMAIL'], "
    Dear $username,<br><br>
    
    You have requested a password reset. Please follow the steps below to reset your password:<br>
    
    <br> - Click on the following link: <a href='https://auto-draw.com/marketplace/auth?reset=$code'>https://auto-draw.com/marketplace/auth?reset=$code</a><br><br>
    
    Note that these verification codes expire after 1 hour, where you can request a new code 30 minutes after the previous one. <br><br>
    
    Best regards,<br>
    The Auto-Draw developers.
    ");
}

// SendResetEmail("alex@alexdalas.com");
// SendVerEmail("alex@alexdalas.com");
if (isset($_GET['ver'])){
    $code = $_GET['ver'];

    $query = "SELECT * FROM temp WHERE value = $1 AND type = 'emailverification';";
    $result = pg_query_params($conn, $query, array($code));
    if (is_null($result)) { header("Location: ../marketplace/auth.php?status=1"); die(); }
    $user = pg_fetch_assoc($result)['user'];

    $query = "UPDATE users SET verified = true WHERE id = $1";
    $result = pg_query_params($conn, $query, array($user));

    header("Location: ../marketplace/auth.php?status=2");
} else if (isset($_GET['reset']) && !defined("requestedListing")){
    if ($_GET['reset'] == "0")
    {
        SendResetEmail($_POST['email']);
        header("Location: ../marketplace/auth.php?status=4");
    }
    else{
        $code = $_GET['reset'];
        $password = $_POST['password'];
        if ($password != $_POST["password2"]) {
            header("Location: ../marketplace/auth.php?reset=$code&status=1");
            die();
        }

        $query = "SELECT * FROM temp WHERE value = $1 AND type = 'resetpassword';";
        $result = pg_query_params($conn, $query, array($code));
        if (is_null($result)) { header("Location: ../marketplace/auth.php?reset=$code?status=2"); die(); }
        $user = pg_fetch_assoc($result)['user'];

        ChangeUserPWD($user, $password);

        header("Location: ../marketplace/auth.php?status=2");
    }

}
?>