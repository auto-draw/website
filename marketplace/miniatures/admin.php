<?php
if (is_null($user) || $user['admin'] == 'f') die("Unauthorised");
?>
<div class="p-2 flex justify-center items-center">
    <div class="md:flex gap-4 w-[95%] md:w-[1250px]">
        <div class="w-[250px] divide-y pb-2 md:pb-0">
            <?php
            $listitems = ["Dashboard", "Downloads", "Queue", "Users"];
            foreach ($listitems as $item){ ?>
                <a href="?filter=admin&selection=<?php echo strtolower($item) ?>">
                    <div class="p-5 px-6 text-white bg-gray-800">
                        <p class="font-semibold text-2xl"><?php echo $item ?></p>
                    </div>
                </a>
            <?php } ?>
        </div>
        <div class="relative w-full bg-gray-800">
            <?php if ($subListings == "dashboard") { ?>
                <p class="text-center w-full text-4xl text-white p-6 font-semibold">Welcome, <?php echo $user["username"] ?>!</p>
            <?php } else if ($subListings == "downloads") { ?>
                <p class="text-center w-full text-4xl text-white p-6 font-semibold">Downloads</p>
            <?php if ($_GET['saved'] == "200"){
            ?>
                <div class="pb-4">
                    <div class="p-4 px-6 flex justify-center items-center bg-green-700">
                        <p class="text-2xl text-center md:text-left font-semibold text-white">Successfully edited.</p>
                    </div>
                </div>
            <?php
            }?>
                <div class="flex justify-center items-center">
                    <button onclick="NewRelease();" class="bg-gray-700 text-2xl p-2 px-4 md:ml-0 font-semibold text-white">Create new</button>
                </div>
                <script>
                    function NewRelease(){
                        fetch("../api/releases.php?release=1").then(response => {
                            location.reload();
                        })
                    }

                    function DeleteRelease(id, name){
                        if (confirm("Are you sure you want to delete '"+name+"'?") == true) {
                            fetch("../api/releases.php?delete="+id).then(response => {
                                location.reload();
                            })
                        }
                    }
                </script>

            <?php
            $result = pg_query($conn, "SELECT * FROM releases ORDER BY date DESC");
            $rows = pg_fetch_all($result);

            foreach($rows as $key=>$value){
            ?>
                <div class="p-2 flex justify-center items-center">
                    <div class="md:flex bg-gray-800 w-[95%] md:w-[750px]">
                        <img onClick="" alt="AutoDraw Image" src="/assets/autodrawer/<?php echo $value["version"] ?>.png" class="h-56 w-full md:w-auto p-4 pr-6 object-contain" />
                        <div class="">
                            <form action="../api/releases.php?update=<?php echo $value['id'] ?>" method="post" enctype="multipart/form-data">
                                <input id="name" name="name" required="true" placeholder="Version x.y.z" class="text-3xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4" value="<?php echo($value["name"]) ?>"></input>
                                <textarea id="description" name="description" required="true" placeholder="Brief description" class="p-4 bg-gray-700 text-lg text-center w-full md:text-left font-semibold text-white w-full"><?php echo($value["description"]) ?></textarea>

                                <p class="text-2xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4">Upload date:</p>
                                <input value="<?php echo $value["date"] ?>" required="true" type="date" id="uploaddate" name="uploaddate">
                                <br>
                                <p class="text-2xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4">Change Image:</p>
                                <input id="imageInput-<?php echo $value['id'] ?>" class="text-white" type="file" name="imageInput" accept="image/*">

                                <p class="text-2xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4">Windows download</p>
                                <input id="downloadwindows" name="downloadwindows" type="text" placeholder="Not required" id="windows" name="windows" class="bg-gray-700 pl-2 text-lg text-center w-full md:text-left font-semibold text-white w-full" value="<?php echo $value["downloadwindows"] ?>">
                                <p class="text-2xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4">Mac download</p>
                                <input id="downloadmac" name="downloadmac" type="text" placeholder="Not required" id="mac" name="mac" class="bg-gray-700 text-lg pl-2 text-center w-full md:text-left font-semibold text-white w-full" value="<?php echo $value["downloadmac"] ?>">
                                <p class="text-2xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4">Linux download</p>
                                <input id="downloadlinux" name="downloadlinux" type="text" placeholder="Not required" id="linux" name="linux" class="bg-gray-700 text-lg pl-2 text-center w-full md:text-left font-semibold text-white w-full" value="<?php echo $value["downloadlinux"] ?>">
                                <p class="text-2xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4">GitHub Release URL</p>
                                <input id="viewgithub" name="viewgithub" type="text" placeholder="Not required" id="github" name="github" class="bg-gray-700 text-lg pl-2 text-center w-full md:text-left font-semibold text-white w-full" value="<?php echo $value["viewgithub"] ?>">
                                <p class="text-2xl text-center bg-gray-800 md:text-left font-semibold text-white py-2 pt-4">Version number (ex: v2.0.2)</p>
                                <input id="version" name="version" type="text" required="true" id="version" name="version" class="bg-gray-700 text-lg text-center pl-2 w-full md:text-left font-semibold text-white w-full" value="<?php echo $value["version"] ?>">

                                <div class="w-full md:w-fit md:bottom-0 pt-2 mb-4 flex justify-center h-min items-center flex-col gap-3 sm:gap-0 sm:flex-row">
                                    <button type="button" onclick="DeleteRelease(<?php echo $value['id'] ?>, '<?php echo$value['name'] ?>')" class="bg-red-700 p-2 px-4 md:ml-0 mx-2 font-semibold text-white">Delete</button>
                                    <button class="bg-green-700 p-2 px-4 md:ml-0 mx-2 font-semibold text-white">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }?>
            <?php
            } else if ($subListings == "queue") {
            $query = pg_query($conn, 'SELECT marketplace.*, users.username 
                                      FROM marketplace 
                                      JOIN users ON marketplace.author = users.id 
                                      WHERE marketplace.approved = FALSE');

            $items = pg_fetch_all($query);
            ?>
                <p class="text-center w-full text-4xl text-white p-6 font-semibold">Queue</p>
                <div id="queueitems" class="flex flex-col items-center">
                    <?php if (is_null($items[0])){?>
                        <p class="text-center w-full text-xl text-white p-2">
                            Queue is all clear!
                        </p>
                    <?php }
                    else{?>
                    <?php foreach ($items as $item): ?>
                        <div id="queue-<?php echo $item['id'] ?>" class="flex flex-col p-6 gap-2 text-white w-full">
                            <div class="flex flex-col items-center">
                                <p class="text-5xl font-bold"><?php echo $item['name'] ?></p>
                                <p class="text-2xl"><?php echo $item['description'] . " (Created by " . $item['username'] . ")" ?></p>
                            </div>
                            <div id="preview-<?php echo $item['id'] ?>" class="flex justify-center items-center"></div>
                            <input id="imageInput-<?php echo $item['id'] ?>" type="file" id="imageInput" style="display: none" accept="image/*">
                            <div class="flex gap-2 cursor-pointer w-full">
                                <a href="../api/download?id=<?php echo $item['id'] ?>" class="w-full">
                                    <div class="p-4 px-6 flex justify-center items-center bg-gray-700 w-full">
                                        <p class="text-2xl font-semibold text-white">Download</p>
                                    </div>
                                </a>
                                <div onClick="UploadImage(<?php echo $item['id'] ?>)" class="p-4 px-6 flex justify-center items-center bg-gray-700 w-full">
                                    <p class="text-2xl font-semibold text-white">Upload Image</p>
                                </div>
                            </div>
                            <div class="flex gap-2 cursor-pointer w-full">
                                <div onClick="Approve(<?php echo $item['id'] ?>)" class="p-4 px-6 flex justify-center items-center bg-green-700 w-full">
                                    <p class="text-2xl font-semibold text-white">Approve</p>
                                </div>
                                <div onClick="Deny(<?php echo $item['id'] ?>)" class="p-4 px-6 flex justify-center items-center bg-red-700 w-full">
                                    <p class="text-2xl font-semibold text-white">Deny</p>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.getElementById('imageInput-<?php echo $item['id'] ?>').addEventListener('change', function () {
                                const fileInput = this;
                                if (fileInput.files && fileInput.files[0]) {
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        const preview = document.getElementById('preview-<?php echo $item['id'] ?>');
                                        preview.innerHTML = '<img src="' + e.target.result + '" alt="Selected Image">';
                                        uploadImage(fileInput.files[0], <?php echo $item['id'] ?>);
                                    };
                                    reader.readAsDataURL(fileInput.files[0]);
                                }
                            });</script>
                    <?php endforeach; }?>
                </div>
                <script>
                    function deletefromlist(id) {
                        document.getElementById("queue-" + id).remove();
                    }

                    function Deny(id) {
                        deletefromlist(id);
                        fetch("../api/admin?deny=" + id);
                    }

                    function Approve(id) {
                        deletefromlist(id);
                        fetch("../api/admin?accept=" + id);
                    }

                    function UploadImage(id) {
                        document.getElementById('imageInput-'+id).click();
                    }

                    function uploadImage(file, id) {
                        const formData = new FormData();
                        formData.append('image', file);

                        fetch('../api/admin.php?img='+id, {
                            method: 'POST',
                            body: formData
                        })
                            .catch(error => console.error('Error:', error));
                    }

                </script>
            <?php } else if ($subListings == "users") {
            $query = 'SELECT * FROM users ORDER BY id desc';
            $temp = pg_query($conn, $query);
            $items = pg_fetch_all($temp);
            ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

                <div id="addnewuser" class="hidden fixed w-full h-full" style="left: 0px; right: 0px; top: 0px; z-index: 100; margin-top: 0px!important;">
                    <button class="bg-gray-950" onclick="document.getElementById('addnewuser').classList.add('hidden')" type="button" style="width:100%; height: 100%; opacity:85%; border-color: transparent; cursor: default;"></button>
                    <div class="flex fixed bg-red-500" style="z-index: 1001; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                        <form class="bg-gray-900 p-6 py-8" action="../api/admin.php?createuser=0" method="post">
                            <p class="text-4xl text-white pb-4 font-semibold text-center w-full">Create User</p>
                            <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="email">Email</p>
                            <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="email" name="email"><br><br>
                            <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user">Username</p>
                            <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="user" name="user"><br><br>
                            <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" for="password">Password</p>
                            <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="password" required="true" id="password" name="password"><br>
                            <div class="flex justify-center items-center p-4 gap-2">
                                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" for="hash">Is the password hashed?</p>
                                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="checkbox" required="true" id="hash" name="hash"><br>
                            </div>
                            <div class="flex justify-center items-center w-full">
                                <input class="text-2xl p-2 px-4 bg-gray-800 text-white font-semibold cursor-pointer" type="submit" value="Create">
                            </div>
                        </form>
                    </div>
                </div>

                <div id="modifyuser" class="hidden fixed w-full h-full" style="left: 0px; right: 0px; top: 0px; z-index: 100; margin-top: 0px!important;">
                    <button class="bg-gray-950" onclick="document.getElementById('modifyuser').classList.add('hidden')" type="button" style="width:100%; height: 100%; opacity:85%; border-color: transparent; cursor: default;"></button>
                    <div class="flex fixed bg-red-500" style="z-index: 1001; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                        <form id="modifyuser-form" class="bg-gray-900 p-6 py-8" action="../api/admin.php?modifyuser=0" method="post">
                            <p class="text-4xl text-white pb-4 font-semibold text-center w-full">Modify User</p>
                            <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="email">Email</p>
                            <input id="modifyuser-email" class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="email" name="email"><br><br>
                            <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" required="true" for="user">Username</p>
                            <input id="modifyuser-user" class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="text" id="user" name="user"><br><br>
                            <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" for="password">Password</p>
                            <input id="modifyuser-password" class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="password" id="password" name="password"><br>
                            <div class="flex justify-center items-center p-4 gap-2">
                                <p class="text-xl p-1 pb-1 pl-2 text-white font-semibold" for="hash">Is the password hashed?</p>
                                <input class="text-2xl p-1 mt-2 bg-gray-800 text-white font-semibold" type="checkbox" id="hash" name="hash"><br>
                            </div>
                            <div class="flex justify-center items-center w-full">
                                <input class="text-2xl p-2 px-4 bg-gray-800 text-white font-semibold cursor-pointer" type="submit" value="Modify">
                            </div>
                        </form>
                    </div>
                </div>

                <p class="text-center text-4xl text-white pr-5 p-2 py-6 font-semibold"><?php echo ucfirst($subListings) ?></p>
                <div class="flex justify-center items-center">
                    <button onclick="document.getElementById('addnewuser').classList.remove('hidden')" class="bg-gray-700 text-2xl p-2 px-4 md:ml-0 font-semibold text-white">Add new</button>
                </div>
                <script>
                    function Ban(item, id){
                        fetch("../api/admin.php?change=0&"+item+"=0&id="+id).then(value => {
                            location.reload();
                        })
                    }

                    function ModUser(user, email, id){
                        document.getElementById('modifyuser-user').value = user;
                        document.getElementById('modifyuser-email').value = email;
                        document.getElementById('modifyuser-form').action = "../api/admin.php?modifyuser="+id;
                        document.getElementById('modifyuser').classList.remove('hidden')
                    }
                </script>
                <div class="w-full">
                    <?php if (is_null($items[0])){?>
                        <p class="text-center w-full text-xl text-white p-2">
                            No <?php echo $subListings ?> were found. Maybe you can try and create one?
                        </p>
                    <?php }
                    else {?>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                            <?php foreach($items as $item){ ?>
                                <div class="p-4 bg-gray-700 text-center h-full">
                                    <p class="text-2xl text-center w-full font-semibold text-white text-ellipsis"><?php echo $item['username']?></p>
                                    <p class="text-md p-1 text-center w-full font-semibold text-white text-ellipsis"><?php echo $item['email']; ?></p>
                                    <p class="text-md p-1 text-center w-full font-semibold text-gray-300 text-ellipsis">Created on <?php echo date('M d, Y', $item['created']) ; ?></p>
                                    <div class="w-full p-2 gap-4 flex justify-center items-center">
                                        <?php if ($item['admin'] == 't') { ?>
                                            <p class="text-xl text-center font-semibold text-white text-ellipsis bg-green-700 p-1 px-4 w-min">Admin</p>
                                        <?php } else {
                                            if ($item['banned'] == 't') { ?>
                                                <p class="text-xl text-center font-semibold text-white text-ellipsis bg-red-700 p-1 px-4 w-min">Banned</p>
                                            <?php } ?>
                                            <i onclick="Ban('ban', <?php echo $item['id'] ?>)" class="text-xl text-center font-semibold text-white text-ellipsis p-2 cursor-pointer w-min fa-solid fa-ban"></i>
                                            <i onclick="ModUser('<?php echo $item['username'] ?>', '<?php echo $item['email'] ?>', <?php echo $item['id'] ?>)" class="text-xl text-center font-semibold text-white text-ellipsis p-2 cursor-pointer w-min fa-solid fa-pencil"></i>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    <?php } ?>
                </div>
            <?php }  ?>
        </div>
    </div>
</div>