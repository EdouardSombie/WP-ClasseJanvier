<?php
/*
Plugin Name: ESGI plugin
Plugin URI: https://esgi.fr
Description: Un plugin d'exemple. Création d'un type custom de post (project)
Author: Doudou
Version: 1.0
*/

if (!class_exists('ESGI_Plugin')) {
    class ESGI_Plugin
    {
        public function __construct()
        {
            // On utilise le constructeur pour s'accrocher à tous les webhooks
            add_action('init', [$this, 'register_custom_post_type']); // Notez la manière dont on fait référence à la méthode de classe : [$this, callback]
            add_action('template_include', [$this, 'template_include'], 99);
            add_shortcode('related_posts', [$this, 'shortcode_related_posts']);
            add_shortcode('skills-list', [$this, 'shortcode_skills_list']);
            add_action('widgets_init', [$this, 'register_widgets']);
            add_action('admin_menu', [$this, 'register_admin_page']);
            add_action('admin_init', [$this, 'plugin_settings_init']);
            // On inclus le plugin tiers ACF
            $this->include_acf();
        }

        // Maintenant que l'on est dans un objet, plus besoin de préfixer nos fonctions :)
        public function register_custom_post_type()
        {
            $labels = array(
                'name'                  => __('Projets', 'ESGI'),
                'singular_name'         => __('Projet', 'ESGI'),
                'menu_name'             => __('Projets', 'ESGI'),
                'name_admin_bar'        => __('Projet', 'ESGI'),
                'add_new'               => __('Ajouter un projet', 'ESGI'),
                'add_new_item'          => __('Ajouter un projet', 'ESGI'),
                'new_item'              => __('Nouveau projet', 'ESGI'),
                'edit_item'             => __('Modifier le projet', 'ESGI'),
                'view_item'             => __('Voir le projet', 'ESGI'),
                'all_items'             => __('Tous les projets', 'ESGI'),
                'search_items'          => __('Rechercher parmi les projets', 'ESGI'),
                'not_found'             => __('Aucun projet trouvé', 'ESGI'),
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array('slug' => 'project'),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 1,
                'supports'           => ['title', 'editor', 'thumbnail', 'custom-fields'],
                'menu_icon'          => 'dashicons-media-code',
                'show_in_rest'       => true
            );

            register_post_type('project', $args);

            $labels = array(
                'name'              => __('Skills', 'ESGI'),
                'singular_name'     => __('Skill', 'ESGI'),
                'search_items'      => __('Rechercher parmi les skills', 'ESGI'),
                'all_items'         => __('Tous les skills', 'ESGI'),
                'edit_item'         => __('Modifier le skill', 'ESGI'),
                'update_item'       => __('Mettre à jour le skill', 'ESGI'),
                'add_new_item'      => __('Ajouter un skill', 'ESGI'),
                'menu_name'         => __('Skill', 'ESGI'),
                'parent_item'       => __('Skill parent', 'ESGI'),
            );

            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array('slug' => 'skill'),
                'show_in_rest'      => true
            );

            register_taxonomy('skill', ['project'], $args);
        }

        public function template_include(string $template): ?string
        {
            if (is_single() && get_query_var('post_type') == 'project' && !file_exists(get_stylesheet_directory() . '/single-project.php')) {
                $new_template = __DIR__ . '/templates/project.php';
                if (file_exists($new_template)) {
                    $template = $new_template;
                }
            } else {
                $queriedObject = get_queried_object();
                if (isset($queriedObject->taxonomy) && $queriedObject->taxonomy == 'skill' && !file_exists(get_stylesheet_directory() . '/taxonomy-skill.php')) {
                    $new_template = __DIR__ . '/templates/skill.php';
                    if (file_exists($new_template)) {
                        $template = $new_template;
                    }
                }
            }
            return $template;
        }

        public function shortcode_related_posts(array $attr): ?string
        {
            $posts = get_field('related_posts');
            $output = '';
            if (!empty($posts)) {
                $output .= '<h2>' . $attr['title'] . '</h2>';
                $output .= '<ul>';
                foreach ($posts as $p) {
                    $output .= '<li><a href="' . get_permalink($p) . '">' . get_the_title($p) . '</a></li>';
                }
                $output .= '</ul>';
                return $output;
            }
        }

        public function shortcode_skills_list(array $attr): null | string
        {
            $post = get_post();
            $terms = wp_get_post_terms($post->ID, 'skill');

            if (empty($terms)) {
                return null;
            }
            $output = '<h3>' . $attr['title'] . '</h3>';
            $output .=  '<ul>';
            foreach ($terms as $t) {
                $output .= '<li><a href="' . get_term_link($t) . '">' . $t->name . '</a></li>';
            }
            $output .=  '</ul>';
            return $output;
        }

        public function register_widgets()
        {
            register_widget('ESGI_Skills_List_Widget');
        }

        public function register_admin_page()
        {
            add_menu_page(
                __('Réglages plugin ESGI', 'ESGI'),
                'ESGI',
                'manage_options',
                'custompage',
                [$this, 'admin_page'],
                'dashicons-media-code',
                6
            );
        }

        public function admin_page()
        {
?>
            <div class="wrap">
                <h1><?php esc_html_e('Réglages plugin ESGI', 'ESGI'); ?></h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('esgi_plugin_options');
                    do_settings_sections('esgi_plugin');
                    submit_button();
                    ?>
                </form>
            </div>
        <?php
        }

        public function plugin_settings_init()
        {
            register_setting('esgi_plugin_options', 'esgi_plugin_options', [$this, 'plugin_options_validate']);

            add_settings_section(
                'esgi_plugin_section',
                __('Réglages du plugin', 'ESGI'),
                [$this, 'plugin_section_text'],
                'esgi_plugin'
            );

            add_settings_field(
                'esgi_plugin_show_related',
                __('Afficher les articles liés', 'ESGI'),
                [$this, 'plugin_setting_show_related'],
                'esgi_plugin',
                'esgi_plugin_section'
            );
        }

        public function plugin_section_text()
        {
            echo '<p>' . __('Texte de description de la section.', 'ESGI') . '</p>';
        }

        public function plugin_setting_show_related()
        {
            $options = get_option('esgi_plugin_options');
        ?>
            <input type="checkbox" id="show_related" name="esgi_plugin_options[show_related]" value="1" <?php checked(1, isset($options['show_related']) ? $options['show_related'] : 0); ?> />
            <label for="show_related"><?php _e('Afficher les articles liés aux projets', 'ESGI'); ?></label>
<?php
        }

        public function plugin_options_validate(array $input): array
        {
            $new_input = array();
            $new_input['show_related'] = isset($input['show_related']) ? absint($input['show_related']) : 0;
            return $new_input;
        }

        public function include_acf()
        {
            if (!function_exists('is_plugin_active')) {
                include_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            if (is_plugin_active('advanced-custom-fields-pro/acf.php')) {
                return;
            }

            if (defined('MY_ACF_PATH')) {
                return;
            }

            define('MY_ACF_PATH', __DIR__ . '/includes/acf/');
            define('MY_ACF_URL', plugin_dir_url(__FILE__) . 'includes/acf/');

            include_once(MY_ACF_PATH . 'acf.php');

            add_filter('acf/settings/url', function ($url) {
                return MY_ACF_URL;
            });

            if (!file_exists(WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php')) {
                add_filter('acf/settings/show_admin', '__return_false');
                add_filter('acf/settings/show_updates', '__return_false', 100);
            }

            if (function_exists('acf_add_local_field_group')) {
                acf_add_local_field_group(array(
                    'key' => 'project_group',
                    'title' => 'Groupe de champs custom projet',
                    'fields' => array(
                        array(
                            'key' => 'project_1',
                            'label' => 'Articles liés',
                            'name' => 'related_posts',
                            'type' => 'post_object',
                            'post_type' => 'post',
                            'allow_null' => 0,
                            'multiple' => 1,
                            'return_format' => 'id',
                        )
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'post_type',
                                'operator' => '==',
                                'value' => 'project',
                            ),
                        ),
                    ),
                ));
            }
        }
    }

    class ESGI_Skills_List_Widget extends WP_Widget
    {
        public function __construct()
        {
            parent::__construct(
                'esgi-skills-list',
                'Liste des skills'
            );
        }

        public function widget($args, $instance)
        {
            $terms = get_terms('skill');
            if (!empty($terms)) {
                $output = '<h3>Skills</h3>';
                $output .= '<ul>';
                foreach ($terms as $t) {
                    $output .= '<li><a href="' . get_term_link($t) . '">' . ucfirst($t->name) . '</a></li>';
                }
                $output .= '</ul>';
                echo $output;
            }
        }

        public function form($instance) {}

        public function update($new_instance, $old_instance)
        {
            return $new_instance;
        }
    }

    new ESGI_Plugin();
}
