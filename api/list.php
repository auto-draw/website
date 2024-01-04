<?php
require_once('db_connect.php');

function ListMarketplace($scope, $type){ // scope is the page, type is "config" or "theme"
    global $conn;
    //$itemsPerPage = 12;
    //$start = ($scope - 1) * $itemsPerPage + 1;
    //$end = $scope * $itemsPerPage;

    // Select columns from both tables
    //$selectQuery = "SELECT marketplace.*, users.username
    //                 FROM marketplace
    //                 JOIN users ON marketplace.author = users.id
    //                  WHERE marketplace.id BETWEEN $1 AND $2
    //                 AND marketplace.approved = TRUE
    //                 AND marketplace.filter = $3
    //                 ORDER BY marketplace.id";

    $query = "SELECT marketplace.*, users.username 
                     FROM marketplace 
                     JOIN users ON marketplace.author = users.id 
                     WHERE marketplace.approved = TRUE 
                     AND marketplace.filter = $1 
                     ORDER BY marketplace.id";

    $result = pg_query_params($conn, $query, array($type));
    if (!$result) return false;
    $rows = pg_fetch_all($result);

    // Calculate total pages
    //$countQuery = "SELECT COUNT(*) as total FROM marketplace WHERE approved = TRUE AND filter = $1";
    //pg_prepare($conn, "count_marketplace", $countQuery);
    //$countResult = pg_execute($conn, "count_marketplace", array($type));
    //$countRow = pg_fetch_assoc($countResult);
    //$totalItems = $countRow['total'];
    //$totalPages = ceil($totalItems / $itemsPerPage);

    // Return the result
    //return array('items' => $rows, 'page' => $scope, 'totalPages' => $totalPages);
    return array('items' => $rows, 'page' => 1, 'totalPages' => 1);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && !defined("requestedListing")) {
    header("Content-Type: application/json");
    echo json_encode(ListMarketplace($_GET["page"], $_GET["filter"]));
}

?>