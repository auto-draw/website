<?php
if (is_null($user)) die("Unauthorised");
?>
<div class="w-full">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
        <?php foreach($items as $item){ ?>
            <a href="../api/download?id=<?php echo $item['id']?>" data-id="">
                <div class="p-4 bg-gray-700 text-center h-full flex justify-center items-center">
                    <div>
                        <?php if (file_exists("../ugc/" . $item['author'] . "/" . $item['id'] . ".png")) {?>
                            <div class="pb-3">
                                <img src="<?php echo "../ugc/" . $item['author'] . "/" . $item['id'] . ".png" ?>" class="w-full aspect-[16/9] object-contain p-2">
                            </div>
                        <?php } ?>
                        <p class="text-2xl text-center w-full font-semibold text-white text-ellipsis"><?php echo $item['name']?></p>
                        <?php //<p class="text-md p-1 text-center w-full font-semibold text-white text-ellipsis"> echo mb_strimwidth($item['description'], 0, 50, "..."); </p> ?>
                        <p class="text-md p-1 text-center w-full font-semibold text-white text-ellipsis"><?php echo mb_strimwidth($item['description'], 0, 100, "..."); ?></p>
                        <div class="w-full p-2 gap-4 flex justify-center items-center">
                            <p class="text-white"> Posted by <?php echo $item['username'] ?> </p>
                            <?php if ($item['featured'] == 't') { ?>
                                <p class="text-xl text-center font-semibold text-white text-ellipsis bg-gray-600 p-1 px-4 w-min">Featured</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>