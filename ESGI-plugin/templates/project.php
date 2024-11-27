<?php get_header() ?>
<main class="post">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php // Dans un template de publication seule, une var $post est créée 
                ?>

                <h1><?= the_title() ?> FROM PLUGIN</h1>
                <?= the_content() ?>
            </div>
            <div class="col-md-2 offset-md-1">
                <?php
                $sidebar = get_theme_mod('sidebar', false);
                if ($sidebar) {
                    get_sidebar();
                }
                ?>
            </div>
        </div>
    </div>

</main>
<?php get_footer() ?>