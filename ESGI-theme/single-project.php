<?php get_header() ?>
<main class="post">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php // Dans un template de publication seule, une var $post est créée 
                ?>

                <h1><?= the_title() ?></h1>
                <?= the_content() ?>
                <div class="related_articles">

                    <?php
                    // Récupérer les articles liés et les afficher dans un composant posts_list
                    $options = get_option('esgi_plugin_options');
                    if (isset($options["show_related"]) && $options["show_related"] === 1) {
                        // Créer une query qui sera utilisée par le composant
                        $related_posts = get_field('related_posts');
                        if (!empty($related_posts)) {
                            echo '<h2>Articles en lien</h2>';
                            $args = [
                                'posts_per_page' => get_option('posts_per_page'),
                                'post__in' => $related_posts
                            ];
                    ?>
                            <script>
                                const query_args = '<?= json_encode($args) ?>';
                            </script>
                    <?php
                            echo '<div id="ajax-response">';
                            include('template-parts/posts_list.php');
                            echo '</div>';
                        }
                    }

                    ?>
                </div>
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