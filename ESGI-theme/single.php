<?php get_header() ?>
<main>
    <?php // Dans un template de publication seule, une var $post est créée 
    ?>
    <h1><?= the_title() ?></h1>
    <div class="post-meta">
        <div class="post-author">
            <?= get_the_author_meta('display_name', $post->post_author) ?>
        </div>
        <time>
            <?= wp_date('j F Y', strtotime($post->post_date)) ?>
        </time>
    </div>
    <?= get_the_post_thumbnail($post->ID, 'post-thumbnail') ?>
    <?= the_content() ?>
</main>
<?php get_footer() ?>