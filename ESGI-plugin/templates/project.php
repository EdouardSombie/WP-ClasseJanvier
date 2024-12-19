<?php get_header() ?>
<main>
    <h1><?= the_title() ?></h1>
    <div><?= the_content() ?></div>
    <div>
        <?php
        $posts = get_field('related_posts'); // pas besoin de préciser l'ID du post, car on se réfère au post courant
        // get_field c'est une autre manière d'utiliser la méthode WP native get_post_meta
        if (!empty($posts)) { ?>
            <h2>Articles liés</h2>
            <ul>
                <?php foreach ($posts as $p) { ?>
                    <li><a href="<?= get_permalink($p) ?>"> <?= get_the_title($p) ?> </a></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</main>
<?php get_footer() ?>