<!DOCTYPE html>
  <?php

    require_once('./api/db_connect.php');
  ?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Auto-Draw</title>
    <link href="output.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="author" content="Auto-Draw">
    <meta name="application-name" content="Auto-Draw">
    <meta name="description" content="Easily trace any picture with your cursor and impress your friends in drawing games.">
    <meta property="og:title" content="AutoDraw">
    <meta property="og:description" content="Easily trace any picture with your cursor and impress your friends in drawing games.">
  </head>
  <body>
  <?php 
    $result = pg_query($conn, "SELECT * FROM releases WHERE name != 'New release' ORDER BY date DESC");
    $rows = pg_fetch_all($result);
  ?>
    <div class="h-full w-full bg-gray-900">
      <div class="h-[100vh]">
          <div class="h-full w-full relative"> 
            <div class="flex items-center justify-center fixed inset-0">
              <div id="wobbleparent"class="h-[100vh] w-[100vw] flex justify-center items-center">
                <div id="wobblechild" class="absolute m-8 grid lg:flex">
                  <div class="flex justify-center items-center w-full lg:grid lg:justify-start lg:items-start">
                    <div class="flex justify-center items-center max-w-[400px] lg:max-w-none w-full lg:grid lg:justify-start lg:items-start">
                      <img id="img" class="lg:max-w-[650px] max-w-full h-auto object-contain" src="/assets/autodrawer/<?php echo $rows[0]["version"] ?>.png">
                    </div>
                  </div>
                  <div>
                    <h1 class="text-center lg:text-left p-5 pb-0 pt-5 text-5xl lg:text-6xl font-semibold text-white">AutoDraw</h1>
                    <h1 class="text-center lg:text-left p-5 pb-3 text-xl lg:text-3xl lg:w-72 font-semibold text-white">Easily trace any picture with your cursor and impress your friends in drawing games.</h1>
                    <div class="flex justify-center items-center lg:grid lg:justify-start lg:items-start">
                      <button onclick="javascript:location.href='download.php'" class="text-center lg:text-left disabled m-5 bg-gray-700 p-2 px-4 font-semibold text-white">Download now</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="header" class="w-full bg-opacity-85 absolute">
              <div class="sm:flex m-auto flex-row max-w-[1000px] place-content-between font-sans p-7 w-full align-middle">
                <ul class="flex justify-center items-center sm:pb-0 pb-4">
                  <button onclick="javascript:location.href='../'" class="text-gray-300 font-extrabold text-center text-4xl sm:text-2xl font-mono cursor-pointer">
                    AutoDraw
                  </button>
                </ul>
                <div class="right-0">
                  <ul class="flex m-auto flex-row justify-center items-center">
                  <button onclick="javascript:location.href='https://discord.gg/tXTxp3HMvX'" class="text-gray-300 font-semibold hover:text-grey-400 px-3 sm:px-5 py-1 cursor-pointer">
                      Discord
                    </button>
                    <?php /**<button onclick="javascript:location.href='wiki.php'" class="text-gray-300 font-semibold hover:text-grey-400 px-3 sm:px-5 py-1 cursor-pointer">
                      Wiki
                    </button> */?>
                    <button onclick="javascript:location.href='download.php'" class="text-gray-300 font-semibold hover:text-grey-400 px-3 sm:px-5 py-1 cursor-pointer">
                      Download
                    </button>
                    <button onclick="javascript:location.href='marketplace'" class="text-gray-300 font-semibold hover:text-grey-400 px-3 sm:px-5 py-1 cursor-pointer">
                      Marketplace
                    </button>
                    <button onclick="javascript:location.href='https://github.com/auto-draw/autodraw'" class="text-gray-300 font-semibold hover:text-grey-400 px-3 sm:px-5 py-1 cursor-pointer">
                      Source
                    </button>
                  </ul>
                </div>
              </div>
            </div>
            <footer class="bottom-0 absolute text-gray-300 text-center font-semibold p-5 w-full">
              Auto-Draw, developed by <a href="https://alexdalas.com/">AlexDalas</a> and <a href="https://siydge.com/">Siydge</a>. <a href="https://auto-draw.com/MIT">MIT License</a>.
            </footer>
        </div>
      </div>
    </div>
  </body>
</html>

<script>
  let constrain;
  let mouseOverContainer = document.getElementById("wobbleparent");
  let ex1Layer = document.getElementById("wobblechild");
  let img = document.getElementById("img").classList;

  (function() {
    window.addEventListener("resize", function() {
      if ((screen.width <= 640 && screen.height <= 750) || screen.height <= 660) {
        img.add("hidden");
      }else{
        img.remove("hidden");
      }
      if (screen.width <= 1024) return ex1Layer.style = "";
      constrain = 400*((screen.width+screen.height)/2000);
    });
    // Invoke the function immediately
    window.dispatchEvent(new Event('resize'));
  })();

  function transforms(x, y, el) {
    let box = el.getBoundingClientRect();
    let calcX = -(y - box.y - (box.height / 2)) / constrain;
    let calcY = (x - box.x - (box.width / 2)) / constrain;

    if (screen.width <= 1024) return "";
    return "perspective(100px) "
      + "   rotateX("+ calcX +"deg) "
      + "   rotateY("+ calcY +"deg) ";
  };

  function transformElement(el, xyEl) {
    el.style.transform  = transforms.apply(null, xyEl);
  }

  mouseOverContainer.onmousemove = function(e) {
    if (screen.width <= 1024) return;
    let xy = [e.clientX, e.clientY];
    let position = xy.concat([ex1Layer]);

    window.requestAnimationFrame(function(){
      transformElement(ex1Layer, position);
    });
  };
</script>