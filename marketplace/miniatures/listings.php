<?php
if (is_null($user)) die("Unauthorised");
$query = 'SELECT * FROM marketplace WHERE author = $1 AND filter = $2 ORDER BY id desc';
pg_prepare($conn, "dashboardListing", $query);
$temp = pg_execute($conn, "dashboardListing", array($user['id'], substr_replace($subListings ,"", -1)));
$items = pg_fetch_all($temp);
?>
<p class="text-center w-full text-4xl text-white p-6 font-semibold"><?php echo ucfirst($subListings) ?></p>
<div class="w-full">
    <?php if (is_null($items[0])){?>
        <p class="text-center w-full text-xl text-white p-2">
            No <?php echo $subListings ?> were found. Maybe you can try and create one?
        </p>
    <?php }
    else{?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            <?php foreach($items as $item){ ?>
                <a <?php if ($item['approved'] == 't') { ?> href="../api/download?id=<?php echo $item['id']?>"<?php } ?> data-id="">
                    <div class="p-4 bg-gray-700 text-center">
                        <?php if (file_exists("../ugc/" . $item['author'] . "/" . $item['id'] . ".png")) {?>
                            <div class="pb-3">
                                <img src="<?php echo "../ugc/" . $item['author'] . "/" . $item['id'] . ".png" ?>" class="w-full aspect-[16/9] object-contain p-2">
                            </div>
                        <?php } ?>
                        <p class="text-2xl text-center w-full font-semibold text-white text-ellipsis"><?php echo $item['name']?></p>
                        <p class="text-md p-1 text-center w-full font-semibold text-white text-ellipsis"><?php echo mb_strimwidth($item['description'], 0, 50, "..."); ?></p>
                        <div class="w-full p-2 gap-4 flex justify-center items-center">
                            <?php if ($item['approved'] == 't') { ?>
                                <p class="text-xl text-center font-semibold text-white text-ellipsis bg-green-700 p-1 px-4 w-min">Approved</p>
                                <?php
                                if ($item['featured'] == 't') { ?>
                                    <p class="text-xl text-center font-semibold text-white text-ellipsis bg-green-700 p-1 px-4 w-min">Featured</p>
                                <?php }
                            }  else { ?>
                                <p class="text-xl text-center font-semibold text-white text-ellipsis bg-red-700 p-1 px-4 w-min">Unapproved</p>
                            <?php } ?>
                        </div>
                        <?php //if ($item['approved'] == 't') { ?>
                        <?php if (false) { ?>
                            <div class="w-full p-2 gap-4 flex justify-center items-center">
                                <p class="text-md text-center font-semibold text-white text-ellipsis bg-gray-600 p-1 px-4 w-min">Archive</p>
                            </div>
                        <?php } ?>
                    </div>
                </a>
            <?php }?>
        </div>
    <?php } ?>
</div>