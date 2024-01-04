<!DOCTYPE html>
  <?php
    $requestedListing = $_GET["filter"];
    if ($requestedListing === null) { $requestedListing = "theme"; }
    $page = $_GET["page"];
    if ($page === null) { $page = 1; }
    define("requestedListing", $_GET["filter"]);
    require_once('../api/list.php');
    $items = ListMarketplace($page, $requestedListing)["items"];
    require_once('../api/auth.php');
    $user = TokenToUser($_COOKIE["token"]);
    if (($requestedListing === "admin" && $user["admin"] != 't')
    || ($requestedListing === "dashboard" && !$user)){
      header("Location: ../marketplace");
      die("401 Unauthorized.");
    }else{
      $subListings = $_GET["selection"];
      if ($subListings === null) { $subListings = "dashboard"; }
    }
  ?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Auto-Draw Marketplace</title>
    <link href="../output.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="author" content="Auto-Draw">
    <meta name="application-name" content="Auto-Draw">
    <meta name="description" content="Easily trace any picture with your cursor and impress your friends in drawing games.">
    <meta property="og:title" content="AutoDraw - Marketplace">
    <meta property="og:description" content="Download configuration files and themes for AutoDraw.">
  </head>
  <body class="bg-black">
    <?php include '../assets/header.php'; ?>
    <div class="h-full w-full bg-gray-900">
      <h1 class="p-5 pt-10 pb-10 text-center text-6xl font-semibold text-white">Marketplace</h1>
      <div class="w-full">
        <div class="flex justify-center items-center">
          <div class="flex p-2 gap-3 w-fit">
            <a href="?">
              <div class="p-4 px-6 flex justify-center items-center <?php if ($requestedListing === "theme"){?>bg-gray-700<?php } else {?>bg-gray-800<?php } ?>">
                <p class="text-2xl text-center md:text-left font-semibold text-white">Themes</p>
              </div>
            </a>
            <a href="?filter=config">
              <div class="p-4 px-6 flex justify-center items-center <?php if ($requestedListing === "config") {?>bg-gray-700<?php } else { ?>bg-gray-800<?php } ?>">
                <p class="text-2xl text-center md:text-left font-semibold text-white ">Configs</p>
              </div>
            </a>
            <?php if ($user){ ?>
              <a href="?filter=dashboard">
                <div class="p-4 px-6 flex justify-center items-center <?php if ($requestedListing === "dashboard") {?>bg-gray-700<?php } else { ?>bg-gray-800<?php } ?>">
                  <p class="text-2xl text-center md:text-left font-semibold text-white ">Dashboard</p>
                </div>
              </a>
            <?php } else { ?>
              <a href="auth">
                <div class="p-4 px-6 flex justify-center items-center bg-gray-800">
                  <p class="text-2xl text-center md:text-left font-semibold text-white ">Login</p>
                </div>
              </a>
            <?php } ?>
            <?php if ($user["admin"] == 't'){ ?>
              <a href="?filter=admin">
                <div class="p-4 px-6 flex justify-center items-center <?php if ($requestedListing === "admin") {?>bg-gray-700<?php } else { ?>bg-gray-800<?php } ?>">
                  <p class="text-2xl text-center md:text-left font-semibold text-white ">Admin</p>
                </div>
              </a>
            <?php } ?>
          </div>
        </div>
        <?php if ($requestedListing == "theme" || $requestedListing == "config") {
            include 'miniatures/themeconfig.php';
        } else if ($requestedListing == "dashboard") { ?>
          <div class="p-2 flex justify-center items-center">
            <div class="md:flex gap-4 w-[95%] md:w-[1250px]">
              <div class="w-[250px] divide-y  pb-2 md:pb-0">
                <?php 
                  $listitems = ["Dashboard", "Upload", "Themes", "Configs", "Archives", "Settings"];
                  foreach ($listitems as $item){ ?>
                    <a href="?filter=dashboard&selection=<?php echo strtolower($item) ?>">
                      <div class="p-5 px-6 text-white bg-gray-800">
                        <p class="font-semibold text-2xl"><?php echo $item ?></p>
                      </div>
                    </a>
                <?php }?>
              </div>
              <div class="relative w-full bg-gray-800">
                  <?php if ($subListings == "dashboard") {
                    include 'miniatures/dashboard.php';
                   } else if ($subListings == "upload") {
                    include 'miniatures/upload.php';
                    } else if ($subListings == "themes" || $subListings == "configs") {
                    include 'miniatures/listings.php';
                 } else { ?>
                  <p class="text-center w-full text-xl text-white p-2">
                      Coming soon!
                  </p>
              <?php } ?>
            </div>
          </div>
        <?php } else if ($requestedListing == "admin") {
          include 'miniatures/admin.php';
         } ?>
      </div>
    <br><br>
    </div>
    <?php include '../assets/footer.php'; ?>
  </body>
</html>
