<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Auto-Draw Authentication</title>
    <link href="../output.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="author" content="Auto-Draw">
    <meta name="application-name" content="Auto-Draw">
    <meta name="description" content="Easily trace any picture with your cursor and impress your friends in drawing games.">
    <meta property="og:title" content="AutoDraw - Marketplace">
    <meta property="og:description" content="Download configuration files and themes for AutoDraw.">
  </head>
  <?php
    define("requestedListing", TRUE);
    require_once('../api/auth.php');

    if (isset($_COOKIE["token"])) {
        $user = TokenToUser($_COOKIE["token"], true);
        if ($user['banned'] == "t"){
            die("You are currently banned.");
        }
        if ($user['verified'] == 't' && !isset($_GET['reset'])){
            header("Location: ../marketplace/");
        }
    }
  ?>
<body>
    <div class="p-6 h-screen flex justify-center items-center bg-gray-950">
        <?php if (isset($_GET['reset'])) {?>
            <form class="bg-gray-900 p-6 py-8" action="../api/email.php?reset=<?php echo $_GET['reset']; ?>" method="post">
                <p class="text-4xl text-white pb-4 font-semibold text-center w-full"><?php if ($_GET['reset'] != 0) { echo "Change"; } else { echo "Reset"; }?> Password</p>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user"><?php if ($_GET['reset'] != 0) { echo "Password"; } else { echo "Email"; }?></p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="<?php if ($_GET['reset'] != 0) { echo "password"; } else { echo "text"; }?>" id="<?php if ($_GET['reset'] != 0) { echo "password"; } else { echo "email"; }?>" name="<?php if ($_GET['reset'] != 0) { echo "password"; } else { echo "email"; }?>"><br><br>
                <?php if ($_GET['reset'] != 0) { ?>
                    <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user">Confirm Password</p>
                    <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="password" id="password2" name="password2"><br><br>
                <?php } ?>
                <div class="flex justify-center items-center w-full">
                    <input class="text-2xl p-2 px-4 bg-gray-800 text-white font-semibold cursor-pointer" type="submit" value="<?php if ($_GET['reset'] != 0) { echo "Change"; } else { echo "Request"; }?>">
                </div>
                <?php if ($_GET["status"] === "1") { ?>
                    <p class="pt-4 text-red-300 text-center w-full">Reset request sent!</p>
                <?php } else ?>
                <div class="flex justify-center items-center w-full pt-4">
                    <a href="?" class="text-center font-semibold w-full text-white">Back</a>
                </div>
            </form>
        <?php } else if (isset($user['verified']) && $user['verified'] == 'f') {?>
            <form class="bg-gray-900 p-6 py-8" action="../api/email.php?ver=0" method="get">
                <p class="text-4xl text-white pb-4 font-semibold text-center w-full">Verify Email</p>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user">Code</p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="ver" name="ver"><br><br>
                <div class="flex justify-center items-center w-full">
                    <input class="text-2xl p-2 px-4 bg-gray-800 text-white font-semibold cursor-pointer" type="submit" value="Verify">
                </div>
                <?php if ($_GET["status"] === "1") { ?>
                    <p class="pt-4 text-red-300 text-center w-full">Incorrect or expired code!</p>
                <?php } ?>
            </form>
        <?php } else if (!isset($_GET['s'])) { ?>
            <form class="bg-gray-900 p-6 py-8" action="../api/auth.php" method="post">
                <p class="text-4xl text-white pb-4 font-semibold text-center w-full">AutoDraw</p>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user">Email / Username</p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="user" name="user"><br><br>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" for="password">Password</p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="password" required="true" id="password" name="password"><br><br>
                <div class="flex justify-center items-center w-full">
                    <input class="text-2xl p-2 px-4 bg-gray-800 text-white font-semibold cursor-pointer" type="submit" value="Sign in">
                </div>
                <?php if ($_GET["status"] === "1") { ?>
                    <p class="pt-4 text-red-300 text-center w-full">Username or password is incorrect!</p>
                <?php } else
                    if ($_GET["status"] === "2") { ?>
                        <p class="pt-4 text-green-300 text-center w-full">Email verified!</p>
                    <?php } else
                    if ($_GET["status"] === "4") { ?>
                        <p class="pt-4 text-green-300 text-center lg:w-72">An email will be sent if an account is associated with the email.</p>
                    <?php } ?>
                <div class="flex justify-center items-center w-full pt-4">
                    <a href="?s=1" class="text-center font-semibold w-full text-white">Sign up</a>
                </div>
                <div class="flex justify-center items-center w-full pt-4">
                    <a href="?reset=0" class="text-center font-semibold w-full text-white">Reset Password</a>
                </div>
            </form>
        <?php } else { ?>
            <form class="bg-gray-900 p-6 py-8" action="../api/auth.php?s=1" method="post">
                <p class="text-4xl text-white pb-4 font-semibold text-center w-full">AutoDraw Signup</p>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user">Username</p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="user" name="user"><br><br>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user">Email</p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="email" name="email"><br><br>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" for="password">Password</p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="password" required="true" id="password" name="password"><br><br>
                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" for="password">Confirm Password</p>
                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="password" required="true" id="password2" name="password2"><br><br>
                <div class="flex justify-center items-center w-full">
                    <input class="text-2xl p-2 px-4 bg-gray-800 text-white font-semibold cursor-pointer" type="submit" value="Sign up">
                </div>
                <?php if ($_GET["status"] === "1") { ?>
                    <p class="pt-4 text-red-300 text-center w-full">Email is already in use!</p>
                <?php } else if ($_GET["status"] === "2") { ?>
                    <p class="pt-4 text-red-300 text-center w-full">Username is taken!</p>
                <?php } else if ($_GET["status"] === "3") { ?>
                    <p class="pt-4 text-red-300 text-center w-full">Passwords do not match!</p>
                <?php } ?>
                <div class="flex justify-center items-center w-full pt-4">
                    <a href="?" class="text-center font-semibold w-full text-white">Sign in</a>
                </div>
                <div class="flex justify-center items-center w-full pt-4">
                    <a href="?reset=0" class="text-center font-semibold w-full text-white">Reset Password</a>
                </div>
            </form>
        <?php } ?>
    </div>
</body>
</html>
