<?php get_header() ?>
<?php $term = get_queried_object(); ?>
<main>
    <h1><?= $term->name ?></h1>
    <div><?= $term->description ?></div>
    <div>
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
        $posts = get_posts($args);

        if (!empty($post)) { ?>
            <ul>
                <?php foreach ($posts as $p) { ?>
                    <li><a href="<?= get_permalink($p) ?>"><?= $p->post_title ?></a></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</main>
<?php get_footer() ?>