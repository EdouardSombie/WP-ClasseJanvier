<ul class="posts-list">
    <?php foreach ($posts as $p) { ?>
        <li>
            <a href="<?= get_permalink($p->ID) ?>">
                <span><?= $p->post_title ?></span>
                <time><?= wp_date('j F Y', strtotime($p->post_date)) ?></time>
            </a>
        </li>
    <?php } ?>
</ul>