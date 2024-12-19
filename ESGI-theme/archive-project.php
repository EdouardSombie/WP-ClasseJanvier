<?php get_header(); ?>
<?php
$type = get_queried_object();
$args = ['post_type' => 'project', 'posts_per_page' => 1];
$the_query = new WP_Query($args);
?>
<main class="archive">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1><?= $type->label ?></h1>

                <?php
                $args = [
                    'posts_per_page' => get_option('posts_per_page'),
                    'post_type' => 'project'
                ];
                ?>
                <script>
                    const query_args = '<?= json_encode($args) ?>';
                </script>
                <?php
                echo '<div id="ajax-response">';
                include('template-parts/posts_list.php');
                echo '</div>';
                ?>
            </div>
        </div>
</main>

<?php get_footer() ?>