<?php get_header(); ?>

<main id="site-content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php include('template-parts/identity_card.php'); ?>
                <?php
                $query_args = [
                    'posts_per_page' => get_option('posts_per_page'), // on se repose sur les valeurs entrées en BO
                    'paged' => 1,
                    'post_type' => 'post', // valeur par défaut
                ];
                if (!is_front_page()) {
                    echo '<div id="ajax-response">';
                    include('template-parts/posts_list.php');
                    echo '</div>';
                }
                ?>
            </div>
        </div>
</main>

<?php get_footer() ?>