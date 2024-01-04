<?php
require_once('db_connect.php');
require_once('admin.php');

function FetchDownloadInfo($id){
    global $conn; 
    $nxt = "AND approved = TRUE";
    if (isAdmin()){
        $nxt = "";
    }
    $query = "SELECT id, name, author, approved, extension FROM public.marketplace WHERE id = $1 ".$nxt;
    pg_prepare($conn, "fetch_download_info", $query);
    $result = pg_execute($conn, "fetch_download_info", array($id));
    return pg_fetch_assoc($result);
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $downloadInfo = FetchDownloadInfo($_GET["id"]);

    if ($downloadInfo) {
        $fileURL = "../ugc/" . $downloadInfo['author'] . "/" . $downloadInfo['id'] . '.' . $downloadInfo['extension'];
        $newFileName = preg_replace('/[^a-zA-Z0-9]/', '', $downloadInfo['name']) . '.' . $downloadInfo['extension'];

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $newFileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileURL));
        ob_clean();
        flush();
        readfile($fileURL);
        exit;
    } else {
        echo "File not found or not approved.";
    }
}
?>
