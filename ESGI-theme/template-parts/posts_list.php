<?php

if (!isset($paged)) {
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
}
if (isset($args) && null !== $args) {
    $args['paged'] = $paged;
} else {
    $args = [
        'posts_per_page' => get_option('posts_per_page'), // on se repose sur les valeurs entrées en BO
        'paged' => $paged,
        'post_type' => 'post',
    ];
}

$the_query = new WP_Query($args);

?>

<ul class="posts-list">
    <?php
    /* loop wordpress */
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post(); // Instanciation d'un object post, lancement de l'itération suivante
            $p = get_post(); // On met le post courant dans une variable $p
    ?>
            <li>
                <a href="<?= get_permalink($p->ID) ?>">
                    <span><?= $p->post_title ?></span>
                    <time><?= wp_date('j F Y', strtotime($p->post_date)) ?></time>
                </a>
            </li>
    <?php
        }
    }
    ?>
</ul>
<nav class="post-list-pagination">
    <?php
    $big = 999999999; // need an unlikely integer
    echo paginate_links(array(
        'base' => isset($base) ? str_replace($big, '%#%', $base) : str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, $paged),
        'total' => $the_query->max_num_pages,
        'prev_text' => __('Previous'),
        'next_text' => __('Next')
    ));
    ?>
</nav>




<?php

?>