<?php
if (is_null($user)) die("Unauthorised");
?>
<p class="text-center w-full text-4xl text-white p-6 font-semibold">Welcome, <?php echo $user["username"] ?>!</p>
<p class="text-center w-full text-xl text-white p-2">
    To upload a new theme or config, click "Upload" on the left.
</p>
<p class="text-center w-full text-xl text-white p-2">
    To view your currently published themes or configs, click on "Themes" or "Configs".
</p>
<p class="text-center w-full text-xl text-white p-2">
    In this menu, you can also archive your posts.
</p>
<p class="text-center w-full text-xl text-white p-2">
    To view your archived posts, click on "Archives".
</p>
<p class="text-center w-full text-xl text-white p-2">
    To manage your account, click on "Settings".
</p>
<p class="text-center w-full text-3xl text-white p-2">
    Have fun!
</p>