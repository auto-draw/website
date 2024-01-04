<!DOCTYPE html>
<html lang="en">
<head>
    <style type="text/css">
      .im {
          color: #ffffff !important;
      }
    </style>
    <meta charset="UTF-8">
</head>
<?php
// If your IDE errors, complaining that $header and $footer don't exist, just ignore it.
// - It is initialised in email.php, and since it is defined & imported there, it is relevant in that context.
?>
<body style="background-color: rgb(3, 7, 18); font-family: Arial; padding-left: 30px;">
    <div style="padding: 1.5rem; background-color: rgb(3, 7, 18); color: rgb(255, 255, 255); padding-left: 0px; padding-right: 0px;">
        <h1 style="color: rgb(255, 255, 255)!important; "><?php echo $header; ?></h1>
        <div style="color: rgb(255, 255, 255); padding: 0.5rem; padding-left: 0px; padding-right: 0px;">
            <div style="display: flex; justify-content: center; align-items: center; padding: 1.5rem; background-color: rgb(17, 24, 39);">
                <p style="color: rgb(255, 255, 255)!important; font-size: 1.25rem; line-height: 1.75rem; font-weight: 400; text-align: left "><?php echo $body; ?></p>
            </div>
        </div>
        <p style="font-size: 1rem; line-height: 1.5rem; padding: 0.25rem; color: rgb(209, 213, 219); font-weight: 600; text-align: left; ">This email was sent automatically by <a href="https://alexdalas.com">AlexDalas.com</a>, on behalf of <a href="https://auto-draw.com">Auto-Draw.com</a>.</p>
    </div>
</body>
</html>