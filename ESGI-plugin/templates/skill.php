<?php get_header() ?>
<?php $term = get_queried_object(); ?>
<main class="post">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php // Dans un template de publication seule, une var $post est créée 
                ?>
                <h1><?= $term->name ?></h1>
                <?= $term->description ?>
                <?php
                $args = [
                    'post_type' => 'project',
                    'tax_query' => [
                        [
                            'taxonomy' => 'skill',
                            'field' => 'term_id',
                            'terms' => $term->term_id
                        ]
                    ]
                ];
                //var_dump($args);
                $posts = get_posts($args);

                if (!empty($post)) { ?>
                    <ul>
                        <?php foreach ($posts as $p) { ?>
                            <li><a href="<?= get_permalink($p) ?>"><?= $p->post_title ?></a></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

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