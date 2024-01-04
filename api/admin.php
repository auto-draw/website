<?php
    define("requestedListing", false);
    require_once('auth.php');
    require_once('db_connect.php');
    global $conn;
    function isAdmin(){
        $user = TokenToUser($_COOKIE["token"]);
        return $user["admin"] == 't';
    }

    function sendWebhook($title, $text, $url2, $color){
        $url = "https://discord.com/api/webhooks/1176758028104642680/EeKqtjOrJ2S1uLq5uV6nA5O5F98EY1kxsmm7kLPxe8P6yE95xSxx1wej-kxh8oWga3on";
        $data = array(
            'username' => 'AutoDraw Webhooks',
           // 'avatar_url' => 'https://example.com/avatar.png',
            'embeds' => array(
                array(
                    'title' => $title,
                    'description' => $text,
                    'url' => $url2,
                    'color' => $color, //decimal https://www.mathsisfun.com/hexadecimal-decimal-colors.html
                ),
            ),
        );
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    }

    function DenyMarketplace($id){
        global $conn;
        $sql = "DELETE FROM public.marketplace WHERE id = $1";
        return pg_query_params($conn, $sql, array($id));    
    }

    function AcceptMarketplace($id){
        global $conn;
        $sql = "UPDATE public.marketplace SET approved = TRUE WHERE id = $1";
        return pg_query_params($conn, $sql, array($id));    
    }

    function SetImage($id){
        global $conn;
        $query = "SELECT author FROM marketplace WHERE id = $1 ";
        $result1 = pg_query_params($conn, $query, array($id));
        $result = pg_fetch_assoc($result1);
        $author = $result["author"];
        return move_uploaded_file($_FILES['image']['tmp_name'], "../ugc/".$author."/".$id.".png");
    }

    if (isAdmin()) {
        if (isset($_GET["deny"])){
            echo DenyMarketplace($_GET["deny"]);
        } else if (isset($_GET["accept"])){
            echo AcceptMarketplace($_GET["accept"]);
        }else if (isset($_GET["img"])){
            echo SetImage($_GET["img"]);
        }else if (isset($_GET["createuser"])){
            CreateUser($_POST["user"], $_POST["email"], $_POST["password"], $_POST["hash"]);
            header("Location: https://auto-draw.com/marketplace/?filter=admin&selection=users");
        }else if (isset($_GET["change"])){
            if (isset($_GET["ban"])){
                ToggleBan($_GET["id"]);
                header("Location: https://auto-draw.com/marketplace/?filter=admin&selection=users");
            }
        }else if (isset($_GET["modifyuser"])){
            $id = $_GET["modifyuser"];
            $result = pg_query($conn, "SELECT * FROM users WHERE id = $id");
            $userDB = pg_fetch_assoc($result);
            $email = $_POST["email"];
            $user = $_POST["user"];
            $password = $_POST["password"];
            if (!isset($_POST["hash"]) && (!empty($password))){
                $password = password_hash($password, PASSWORD_BCRYPT);
            }
            $params = array();
            if (!empty($password)){
                array_push($params, "password = '$password'");
            }
            if ($user != $userDB["username"]){
                array_push($params, "username = '$user'");
            }
            if ($email != $userDB["email"]){
                array_push($params, "email = '$email'");
            }
            if (!$params){
                header("Location: https://auto-draw.com/marketplace/?filter=admin&selection=users");
            }
            $query = "UPDATE users SET ".implode(", ", $params)." WHERE id = $id";
            pg_query($conn, $query);
            header("Location: https://auto-draw.com/marketplace/?filter=admin&selection=users");
        }
    }
  ?>