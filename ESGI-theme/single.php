<?php get_header() ?>
<main class="post">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php // Dans un template de publication seule, une var $post est créée 
                ?>
                <h1><?= the_title() ?></h1>
                <div class="post-meta">
                    <div class="post-author">
                        <?= get_avatar($post->post_author) ?>
                        <?= get_the_author_meta('display_name', $post->post_author) ?>
                    </div>
                    <time>
                        <?= wp_date('j F Y', strtotime($post->post_date)) ?>
                    </time>
                </div>
                <?= get_the_post_thumbnail($post->ID, 'large') ?>
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