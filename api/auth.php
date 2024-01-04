<?php
require_once('db_connect.php');
require_once('email.php');

function AuthenticateUser($user, $password){ //returns token or bool
    // In this context, $user could be a name or an email. We need an ID.
    global $conn;
    $id = pg_escape_string($user);
    $type = strpos($user, "@") !== false ? "email" : "username";
    $query = "SELECT id FROM users WHERE $type = $1 AND banned = FALSE";
    $result = pg_query_params($conn, $query, array($id));

    if (!$result) {
        return false;
    }
    $row = pg_fetch_assoc($result);
    $id = $row['id'];

    if (ValidateUser($id, $password) == true){
        return GenerateToken($id);
    }
    return false;
}

function CreateUser($name, $email, $password, $pwdIsHashed = false){ // returns ID
    global $conn;
    $time = time();
    if (!$pwdIsHashed) {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }

    $query = "SELECT id FROM users WHERE username = $1";
    $result = pg_query_params($conn, $query, array($name));
    $rows = pg_num_rows($result);
    if ($rows > 0) { return 1; }

    $query = "SELECT id FROM users WHERE email = $1";
    $result = pg_query_params($conn, $query, array($email));
    $rows = pg_num_rows($result);
    if ($rows > 0) { return 2; }

    $query = "INSERT INTO users (username, email, password, created, banned, admin) VALUES ($1, $2, $3, $time, FALSE, FALSE) RETURNING id";
    $result = pg_query_params($conn, $query, array(pg_escape_string($name), pg_escape_string($email), pg_escape_string($password)));
    if (!$result) {
        return false;
    }
    $row = pg_fetch_assoc($result);
    return $row['id'];
}

function ChangeUserPWD($id, $password, $pwdIsHashed = false){ // returns ID
    global $conn;
    if (!$pwdIsHashed) {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }
    $query = "UPDATE users SET password = $1 WHERE id = $2";
    $result = pg_query_params($conn, $query, array($password, $id));
    if (!$result) {
        return false;
    }
    return true;
}
function ToggleBan($id){ // returns ID
    global $conn;
    $query = "UPDATE users SET banned = NOT banned WHERE id = $1";
    $result = pg_query_params($conn, $query, array($id));
    return 200;
}

function ValidateUser($id, $password){ // returns bool
    global $conn;
    $id = pg_escape_string($id);
    $query = "SELECT password FROM users WHERE id = $1 AND banned = FALSE";
    $result = pg_query_params($conn, $query, array($id));

    if (!$result) {
        return false;
    }

    $row = pg_fetch_assoc($result);

    if (password_verify($password, $row['password'])){
        return true;
    }

    return false;
}

function GenerateToken($id){ // returns token
    global $conn;
    $expiry = time() + (6 * 30 * 24 * 60 * 60); // 6 months ahead from current time
    $token = bin2hex(random_bytes(128));
    $query = "INSERT INTO tokens VALUES ($1, $2, $3, FALSE);";
    $result = pg_query_params($conn, $query, array($token, $expiry, pg_escape_string($id)));
    if (!$result) {
        return false;
    }
    return $token;
}

function TokenToUser($token, $bypass = false){ // returns user's data
    if ($token == null) { return false; }
    global $conn;
    $query = "SELECT * FROM tokens WHERE token = $1;";

    $result = pg_query_params($conn, $query, array($token));
    if (!$result) { return false; }
    $row = pg_fetch_assoc($result);
    $user = $row['user'];

    $query = "SELECT id, username, email, banned, admin, verified FROM users WHERE id = $1;";
    $result = pg_query_params($conn, $query, array($user));
    if (!$result) { return false; }
    $user = pg_fetch_assoc($result);

    if (($user['banned'] == 't' || $user['verified'] == 'f') && !$bypass){
        return false;
    }
    return $user;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !defined("requestedListing")) {
    // for trolls
    if ($_POST["user"] == "admin"){
        header("Location: https://www.youtube.com/watch?v=K9V60HGK4AI&t=181s");
        die();
    }

    // We create the account, then below will authenticate the user.
    if (isset($_GET["s"])) {
        if ($_POST["password"] != $_POST["password2"]) {
            header("Location: ../marketplace/auth.php?s=1&status=3");
            die();
        }
        $status = CreateUser($_POST["user"], $_POST["email"], $_POST["password"]);
        if ($status == 1 || $status == 2){
            header("Location: ../marketplace/auth.php?s=1&status=".$status);
            die();
        }
        SendVerEmail($_POST['email']);
    }

    $tk = AuthenticateUser($_POST["user"], $_POST["password"]);

    if ($tk == false) {
        // Authentication failed
        header("Location: ../marketplace/auth.php?status=1");
        return false;
    } else {
        // Authentication succeeded
        setcookie("token", $tk, [
            'expires' => time() + 6 * 30 * 24 * 60 * 60,
            'path' => '/',
            'domain' => 'auto-draw.com',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        if (!isset($_GET["s"])) {
            header("Location: ../marketplace/auth.php?s=1");
        } else {
            header("Location: ../marketplace/");
        }
        return true;
    }
}
?>
