<?php
// TODO provide option to hide each fild on mobile
// TODO handle void data and specific types such as arrays and images
// IMPLEMENT ORDER
?>
<!--PLUGIN STARTS FROM HERE-->
<div class="fids-wrapper">
    <div class="fids-headers">
        <?php
        foreach ($headersWithCustomTitle as $h) {
        ?>
            <div class="header">
                <?php echo $h['title'] ?>
            </div>

        <?php
        }
        ?>
    </div>
</div>
<!--PLUGIN ENDS HERE-->