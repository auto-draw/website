<?php
    define("requestedListing", false);
    require_once('admin.php');
    require_once('db_connect.php');

    function NewRelease(){
        global $conn;
        $query = "INSERT INTO public.releases (name, description, version) VALUES ($1, $2, $3)";
        if (pg_query_params($conn, $query, array("New release", "Description.", "1.2.3"))){
            return 200;
        } else{
            return 500;
        }
    }

    function DeleteRelease($id){
        global $conn;
        $result = pg_query_params($conn, "SELECT * FROM public.releases WHERE id = $1", array($id));
        $release = pg_fetch_assoc($result);
        unlink("../assets/autodrawer/".$release["version"].".png");
        $result = pg_query_params($conn, "DELETE FROM public.releases WHERE id = $1", array($id));
        if (pg_fetch_all($result)){
            return 200;
        } else{
            return 500;
        }
    }

    function UpdateRelease($id){
        global $conn;
                
        $result = pg_query_params($conn, "SELECT * FROM public.releases WHERE id = $1", array($id));
        $release = pg_fetch_assoc($result);

        $name = $_POST["name"];
        $description = $_POST["description"];
        $date = $_POST["uploaddate"];
        $downloadwindows = $_POST["downloadwindows"];
        $downloadmac = $_POST["downloadmac"];
        $downloadlinux = $_POST["downloadlinux"];
        $viewgithub = $_POST["viewgithub"];
        $version = $_POST["version"];

        if ($date != $release["date"]){
            rename("../assets/autodrawer/"+$release["date"]+".png", "../assets/autodrawer/"+$date+".png");
        }

        $keys = array('name', 'description', 'date', 'downloadwindows', 'downloadmac', 'downloadlinux', 'viewgithub', 'version');

        foreach ($keys as $key) {
            if (is_null($$key) && isset($release[$key]) && ($$key != $release[$key])) {
                $$key = $release[$key];
            }
        }        

        if (isset($_FILES['imageInput'])){
            $file = $_FILES['imageInput'];
            move_uploaded_file($file['tmp_name'], "../assets/autodrawer/$version.png");
        }

        $query = "UPDATE releases SET name = $1, description = $2, date = $3, 
        downloadwindows = $4, downloadmac = $5, downloadlinux = $6,
        viewgithub = $7, version = $8 WHERE id = $9";

        if (pg_query_params($conn, $query, array($name, $description, $date, $downloadwindows, 
                            $downloadmac, $downloadlinux, $viewgithub, $version, $id))){
            sendWebhook("Release", "Release '$version' has been updated successfully.", "https://auto-draw.com/marketplace/?filter=admin&selection=downloads", "7012214");
            return 200;
        } else{
            return 500;
        }
    }

    if (isAdmin()) {
        if (isset($_GET["release"])){
            echo NewRelease();
        } else if (isset($_GET["update"])){
            if (UpdateRelease($_GET["update"]) == 200){
                echo 200;
                header("Location: ../marketplace/?filter=admin&selection=downloads&saved=200");
            } 
        } else if (isset($_GET["delete"])){
            echo DeleteRelease($_GET["delete"]);
        } 
    }
?>