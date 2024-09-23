<?php get_header(); ?>

<main id="site-content">
    <?php include('template-parts/identity_card.php'); ?>
    <?php
    if (!is_front_page()) {
        /* Récupération des 6 derniers articles */
        $args = [
            'numberposts' => 6,
        ];
        $posts = get_posts($args);
        include('template-parts/posts_list.php');
    }
    ?>
</main>

<?php get_footer() ?>