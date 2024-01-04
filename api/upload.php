<?php
require_once('db_connect.php');
define("requestedListing", TRUE);
require_once('auth.php');
require_once('admin.php');
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $type = $_POST["type"];
    $name = $_POST["name"];
    $desc = $_POST["desc"];
    $user = TokenToUser($_COOKIE["token"]);
    $author = $user["id"];
    $authorName = $user["name"];

    if ($type != "theme" && $type != "config") { echo "huh"; die();}

    // Depending on the type, retrieve additional values
    if ($type == "config") {
        // For config type
        $interval = $_POST["interval"];
        $clickDelay = $_POST["clickdelay"];
        $blackThreshold = $_POST["blackthreshold"];
        $alphaThreshold = $_POST["alphathreshold"];
        if (!(isset($_POST["interval"]) && isset($_POST["clickdelay"]) && isset($_POST["blackthreshold"]) && isset($_POST["alphathreshold"]))){
            header("Location: ../marketplace/?filter=dashboard&selection=upload&success=0");
            die();
        }
        if (!((0 <= $blackThreshold) && ($blackThreshold <= 255)) || 
            !((0 <= $alphaThreshold) && ($alphaThreshold <= 255))){
            header("Location: ../marketplace/?filter=dashboard&selection=upload&success=0");
            die();
        }

        $query = "INSERT INTO public.marketplace (filter, name, description, author, featured, approved, extension) VALUES ($1, $2, $3, $4, FALSE, FALSE, $5)";
        $result = pg_query_params($conn, $query, array($type, $name, $desc, $author, "drawcfg"));
        
        $query = "SELECT * FROM public.marketplace WHERE author = $1 ORDER BY id DESC LIMIT 1";
        $result = pg_query_params($conn, $query, array($author));

        if ($result){
            $row = pg_fetch_assoc($result);
            $postID = $row['id'];
            $fileName = "../ugc/".$author."/".$postID.".drawcfg";
            
            $lines = array(
                $interval,
                $clickDelay,
                $blackThreshold,
                $alphaThreshold
            );
            
            $fileContent = implode("\n", $lines);
            
            file_put_contents($fileName, $fileContent);
        }        
        sendWebhook("Uploaded Config", "Config '$name' has been uploaded to the AutoDraw website.", "https://auto-draw.com/marketplace/?filter=admin&selection=queue", 3145596);
    }

    // Handle file upload if a file is provided

    else if ($type == "theme" && isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $file = $_FILES["file"];
        if (!(str_ends_with(($file["name"]), '.axaml') || 
        str_ends_with(($file["name"]), '.daxaml') ||
        str_ends_with(($file["name"]), '.laxaml'))){
            header("Location: ../marketplace/?filter=dashboard&selection=upload&success=2");
            die();
        }

        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);

        // Handle the uploaded file, such as moving it to a specific directory
        // $file["tmp_name"] contains the temporary filename on the server
        // Move the file to a desired location, e.g., move_uploaded_file($file["tmp_name"], "/path/to/uploaded/files/".$file["name"]);
        

        $query = "INSERT INTO public.marketplace (filter, name, description, author, featured, approved, extension) VALUES ($1, $2, $3, $4, FALSE, FALSE, $5)";
        $result = pg_query_params($conn, $query, array($type, $name, $desc, $author, $ext));
        
        $query = "SELECT * FROM public.marketplace WHERE author = $1 ORDER BY id DESC LIMIT 1";
        $result = pg_query_params($conn, $query, array($author));

        if ($result){
            $row = pg_fetch_assoc($result);
            $postID = $row['id'];
            if (!file_exists("../ugc/".$author."/")) {
                mkdir("../ugc/".$author."/", 0755, true);
            }
            move_uploaded_file($file["tmp_name"], "../ugc/".$author."/".$postID.".".$ext);
        }
        sendWebhook("Uploaded Theme", "Theme '$name' has been uploaded to the AutoDraw website.", "https://auto-draw.com/marketplace/?filter=admin&selection=queue", 3145596);

    } else {
        header("Location: ../marketplace/?filter=dashboard&selection=upload&success=0");
        die();
    }

    // Now you can use the retrieved values for further processing or storage
    // For example, you can store them in a database or perform other operations

    // Respond to the client
    header("Location: ../marketplace/?filter=dashboard&selection=upload&success=1");
    die();
} else {
    // If the form is not submitted via POST, handle accordingly
    header("Location: ../marketplace/?filter=dashboard&selection=upload&success=0");
}
?>
