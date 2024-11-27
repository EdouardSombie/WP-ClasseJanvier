<?php get_header(); ?>

<main id="site-content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php include('template-parts/identity_card.php'); ?>
                <?php
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