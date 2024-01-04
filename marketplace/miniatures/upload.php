<?php
if (is_null($user)) die("Unauthorised");
?>
<p class="text-center w-full text-4xl text-white p-6 font-semibold">Upload</p>
<?php
if ($_GET['success'] == "1"){
    ?>
    <div class="p-4 px-6 flex justify-center items-center bg-green-700">
        <p class="text-2xl text-center md:text-left font-semibold text-white">Upload submitted.</p>
    </div>
    <?php
} else if ($_GET['success'] == "0") {
    ?>
    <div class="p-4 px-6 flex justify-center items-center bg-red-700">
        <p class="text-2xl text-center md:text-left font-semibold text-white">An error occured with your upload.</p>
    </div>
    <?php
} else if ($_GET['success'] == "2") {
    ?>
    <div class="p-4 px-6 flex justify-center items-center bg-red-700">
        <p class="text-2xl text-center md:text-left font-semibold text-white">Invalid file type.</p>
    </div>
    <?php
}
?>
<div>
    <form action="../api/upload.php" method="post" enctype="multipart/form-data">
        <div class="flex gap-2 justify-center items-center">
            <p class="text-3xl p-1 pb-1 pl-2 text-white font-semibold text-center" required="true" for="user">Type:</p>
            <select id="type" name="type" onChange="changeUpload(this.value)" class="text-xl p-1 mt-2 bg-gray-700 text-white font-semibold text-center" type="text" id="user" name="user">
                <option value="theme">Theme</option>
                <option value="config">Config</option>
            </select>
        </div><br>
        <div class="flex gap-2 justify-center items-center">
            <p class="text-3xl p-1 pb-1 pl-2 text-white font-semibold text-center" required="true" for="user">Name: </p>
            <input class="text-2xl p-1 mt-2 bg-gray-700 text-white font-semibold text-center" type="text" id="name" name="name" required="true"><br><br>
        </div><br>
        <div class="flex gap-2 justify-center items-center">
            <p class="text-3xl p-1 pb-1 pl-2 text-white font-semibold text-center" required="true" for="user">Short Description: </p>
            <input class="text-2xl p-1 mt-2 bg-gray-700 text-white font-semibold text-center" type="text" id="desc" name="desc" required="true"><br><br>
        </div><br>
        <p class="text-3xl p-1 pb-1 pl-2 text-white font-semibold text-center" required="true" for="user">Details</p>
        <br>
        <div id="themeUploadArea" class="flex justify-center items-center">
            <input type="file" accept=".axaml,.daxaml,.laxaml" name="file" id="file" class="text-white">
        </div>
        <div id="configUploadArea" class="hidden">
            <input class="text-2xl p-1 mt-2 bg-gray-700 text-white font-semibold text-center" type="number" id="interval" name="interval" placeholder="Interval" value="10000">
            <input class="text-2xl p-1 mt-2 bg-gray-700 text-white font-semibold text-center" type="number" id="clickdelay" name="clickdelay" placeholder="Click Delay" value="1000"><br>
            <input class="text-2xl p-1 mt-2 bg-gray-700 text-white font-semibold text-center" type="number" id="blackthreshold" name="blackthreshold" placeholder="Black Threshold" value="127" min="0" max="255">
            <input class="text-2xl p-1 mt-2 bg-gray-700 text-white font-semibold text-center" type="number" id="alphathreshold" name="alphathreshold" placeholder="Alpha Threshold" value="200" min="0" max="255">
        </div><br><br>
        <div class="flex justify-center items-center p-2 gap-2">
            <button type="submit" value="submit" class="p-4 px-6 flex justify-center items-center bg-gray-700">
                <p class="text-2xl text-center md:text-left font-semibold text-white">Submit</p>
            </button>
        </div>
        <form>
</div>
<script>
    function changeUpload(value){
        if (value == "theme"){
            document.getElementById("themeUploadArea").classList.add("flex");
            document.getElementById("themeUploadArea").classList.remove("hidden");
            document.getElementById("configUploadArea").classList.add("hidden");
            document.getElementById("configUploadArea").classList.remove("grid");
        } else {
            document.getElementById("configUploadArea").classList.add("grid");
            document.getElementById("configUploadArea").classList.remove("hidden");
            document.getElementById("themeUploadArea").classList.add("hidden");
            document.getElementById("themeUploadArea").classList.remove("flex");
        }
    }
</script>
</div>