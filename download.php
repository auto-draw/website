<!DOCTYPE html>
  <?php 
    require_once('./api/db_connect.php');

    $result = pg_query($conn, "SELECT * FROM releases WHERE name != 'New release' ORDER BY date DESC");
    $rows = pg_fetch_all($result);

  ?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Download Auto-Draw</title>
    <link href="output.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="author" content="Auto-Draw">
    <meta name="application-name" content="Auto-Draw">
    <meta name="description" content="Easily trace any picture with your cursor and impress your friends in drawing games.">
    <meta property="og:title" content="AutoDraw - Download">
    <meta property="og:description" content="Download the latest version of AutoDraw.">
  </head>
  <body>
    <?php include 'assets/header.php'; ?>
    <div class="h-full w-full bg-gray-900">
      <h1 class="p-5 pt-10 pb-3 text-center text-6xl font-semibold text-white">Download AutoDraw</h1>
      <div class="flex items-center justify-center">
        <div class="m-5">
          <h1 class="text-center text-3xl font-semibold text-white pb-2">Latest version</h1>
          <?php 
          foreach($rows as $key=>$value){
            if ($key == 1){
            ?><h1 class="p-4 text-center text-3xl font-semibold text-white">Older versions</h1><?php
            }
            ?>
          <div class="p-2 flex justify-center items-center">
            <div class="md:flex md:h-56 bg-gray-800 w-[95%] md:w-[750px]">
              <img src="/assets/autodrawer/<?php echo $value["version"] ?>.png" class="h-56 w-full md:w-auto p-4 pr-6 object-contain" />
              <div class="relative md:h-56">
                <p class="pt-5 text-3xl text-center md:text-left font-semibold text-white"><?php echo($value["name"]) ?></p>
                <p class="md:pl-0 py-4 md:py-2 px-4 text-lg text-center w-full md:mb-0 md:text-left mr-0 md:mr-4 font-semibold text-white"><?php echo($value["description"]) ?></p>
                <div class="w-full md:w-fit md:absolute md:bottom-0 mb-4 flex justify-center items-center flex-col gap-3 sm:gap-0 sm:flex-row">
                  <?php
                    $user_agent = getenv("HTTP_USER_AGENT");
                   if (strpos($user_agent, "Linux") !== FALSE && strpos($user_agent, "Android") === FALSE && $value["downloadlinux"]) {
                    ?><button onclick="window.location.href='<?php echo $value['downloadlinux'] ?>';" class="bg-gray-700 p-2 md:ml-0 mx-2 px-4 font-semibold text-white">Download for Linux</button><?php
                  } elseif (strpos($user_agent, "Mac") !== FALSE && $value["downloadmac"]) {
                    ?><button onclick="window.location.href='<?php echo $value['downloadmac'] ?>';" class="bg-gray-700 p-2 md:ml-0 mx-2 px-4 font-semibold text-white">Download for Mac</button><?php
                  } elseif (strpos($user_agent, "Win") !== FALSE && $value["downloadwindows"]) {
                    ?><button onclick="window.location.href='<?php echo $value['downloadwindows'] ?>';" class="bg-gray-700 md:ml-0 mx-2 p-2 px-4 font-semibold text-white">Download for Windows</button><?php
                  } else {
                    if (!$value["downloadwindows"] && !$value["downloadmac"] && !$value["downloadlinux"]){
                      ?><button class="disabled bg-gray-700 md:ml-0 mr-2 p-2 px-4 font-semibold text-white">Coming soon</button><?php
                    }
                    else{
                      ?><button class="disabled bg-gray-700 md:ml-0 mx-2 p-2 px-4 font-semibold text-white">Device not supported</button><?php
                    }
                  }
                  if ($value["viewgithub"]){
                    ?><button onclick="window.location.href='<?php echo $value['viewgithub'] ?>';" class="bg-gray-700 p-2 px-4 md:ml-0 mx-2 font-semibold text-white">View on GitHub</button><?php
                  }?>
                </div>
              </div>
            </div>
          </div>
          <?php
          }?>

        </div>
      </div>
    </div>
    <?php include 'assets/footer.php'; ?>
  </body>
</html>
