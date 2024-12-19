<?php
/*
Plugin Name: ESGI plugin
Plugin URI: https://esgi.fr
Description: Un plugin d'exemple. Création d'un type custom de post (project)
Author: Doudou
Version: 1.0
*/

// Fichier master du plugin //

// Il faudra à l'avenir tout encapsuler dans un objet (copilot est très bon pour cela)

// Enregistrement d'un type de post custom (project)
add_action('init', 'esgi_custom_post_type');
function esgi_custom_post_type()
{
    // Définition des labels

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
        'supports'           => [],
        'menu_icon'          => 'dashicons-media-code',
        'supports'           => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'show_in_rest'       => true
    );

    register_post_type(
        'project',
        $args
    );

    // Enregistrer une taxonomie custom (skill)
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
        'show_in_rest'       => true
    );

    register_taxonomy('skill', ['project'], $args);
}

// add_filter('the_title', 'esgi_the_title');
// function esgi_the_title($title)
// {
//     $title = $title . '😅';
//     return $title;
// }


// Définition du chemin du template à utiliser pour l'affichage des projects

add_filter('template_include', 'esgi_template_include', 99);
function esgi_template_include($template)
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


// Shortcodes du plugin

add_shortcode('edo', 'handlerEdo');
function handlerEdo($attr)
{
    return '<h2>' . $attr['text'] . '</h2>';
}

add_shortcode('related_posts', 'esgi_shortcode_related_posts');

function esgi_shortcode_related_posts($attr)
{
    $posts = get_field('related_posts'); // pas besoin de préciser l'ID du post, car on se réfère au post courant
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

add_shortcode('skills-list', 'esgi_shortcode_skills_list');
function esgi_shortcode_skills_list($attr)
{
    $post = get_post(); // récupère la publication courante
    $terms = wp_get_post_terms($post->ID, 'skill');

    if (empty($terms)) {
        return;
    }
    $output = '<h3>' . $attr['title'] . '</h3>';
    $output .=  '<ul>';
    foreach ($terms as $t) {
        $output .= '<li><a href="' . get_term_link($t) . '">' . $t->name . '</a></li>';
    }
    $output .=  '</ul>';
    return $output;
}


// Widget du plugin

class ESGI_skills_list_widget extends WP_Widget
{
    public function __construct()
    {
        // actual widget processes
        parent::__construct(
            'esgi-skills-list',  // Base ID
            'Liste des skills'   // Name
        );
    }

    public function widget($args, $instance)
    {
        // outputs the content of the widget
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

    public function form($instance)
    {
        // outputs the options form in the admin
    }

    public function update($new_instance, $old_instance)
    {
        // processes widget options to be saved
        return $new_instance;
    }
}

add_action('widgets_init', 'esgi_register_widgets');
function esgi_register_widgets()
{
    register_widget('ESGI_skills_list_widget');
}


// Inclusion du plugin ACF

function includeACF()
{

    if (! function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    // Check if ACF PRO is active
    if (is_plugin_active('advanced-custom-fields-pro/acf.php')) {
        // Abort all bundling, ACF PRO plugin takes priority
        return;
    }

    // Check if another plugin or theme has bundled ACF
    if (defined('MY_ACF_PATH')) {
        return;
    }

    define('MY_ACF_PATH', __DIR__ . '/includes/acf/');
    define('MY_ACF_URL', plugin_dir_url(__FILE__) . 'includes/acf/');

    // Include the ACF plugin.
    include_once(MY_ACF_PATH . 'acf.php');

    // Customize the URL setting to fix incorrect asset URLs.
    add_filter('acf/settings/url', 'my_acf_settings_url');
    function my_acf_settings_url($url)
    {
        return MY_ACF_URL;
    }


    //Check if ACF free is installed
    if (! file_exists(WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php')) {
        // Free plugin not installed
        // Hide the ACF admin menu item.
        add_filter('acf/settings/show_admin', '__return_false');
        // Hide the ACF Updates menu
        add_filter('acf/settings/show_updates', '__return_false', 100);
    }

    // Ajout du champ custom related_posts sur les publications de type project
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'project_group',
            'title' => 'Groupe de champs custom projet',
            'fields' => array(
                array(
                    'key' => 'project_1',
                    'label' => 'Articles liés',
                    'name' => 'related_posts',
                    'type' => 'post_object',
                    /* (mixed) Specify an array of post types to filter the available choices. Defaults to '' */
                    'post_type' => 'post',

                    /* (mixed) Specify an array of taxonomies to filter the available choices. Defaults to '' */
                    'taxonomy' => '',

                    /* (bool) Allow a null (blank) value to be selected. Defaults to 0 */
                    'allow_null' => 0,

                    /* (bool) Allow mulitple choices to be selected. Defaults to 0 */
                    'multiple' => 1,

                    /* (string) Specify the type of value returned by get_field(). Defaults to 'object'.
    Choices of 'object' (Post object) or 'id' (Post ID) */
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

    endif;
}

includeACF();

/**
 * Créer une page de réglage pour notre plugin.
 */
add_action('admin_menu', 'esgi_register_admin_page');
function esgi_register_admin_page()
{
    add_menu_page(
        __('Réglages plugin ESGI', 'ESGI'),
        'ESGI',
        'manage_options',
        'custompage',
        'esgi_admin_page', // callback en charge de fabriquer
        'dashicons-media-code',
        6
    );
}


/**
 * Afficher la page de menu
 */
function esgi_admin_page()
{
?>
    <div class="wrap">
        <h1><?php esc_html_e('Réglages plugin ESGI', 'ESGI'); ?></h1>
        <form method="post" action="options.php">
            <?php
            // fonctions helper de formulaire wp
            settings_fields('esgi_plugin_options');
            do_settings_sections('esgi_plugin');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

/**
 * Définition des réglages du plugin
 */
function esgi_plugin_settings_init()
{
    // enregistrement du setting
    register_setting('esgi_plugin_options', 'esgi_plugin_options', 'esgi_plugin_options_validate');

    // ajout d'une section pour afficher le réglage
    add_settings_section(
        'esgi_plugin_section',
        __('Réglages du plugin', 'ESGI'),
        'esgi_plugin_section_text', // callback chargé d'afficher du texte
        'esgi_plugin'
    );

    // ajout du champ dans la section
    add_settings_field(
        'esgi_plugin_show_related',
        __('Afficher les articles liés', 'ESGI'),
        'esgi_plugin_setting_show_related',
        'esgi_plugin',
        'esgi_plugin_section'
    );
}
add_action('admin_init', 'esgi_plugin_settings_init');

function esgi_plugin_section_text()
{
    echo '<p>' . __('Texte de description de la section.', 'ESGI') . '</p>';
}

function esgi_plugin_setting_show_related()
{
    $options = get_option('esgi_plugin_options');
    // Affichage du formulaire
?>
    <input type="checkbox" id="show_related" name="esgi_plugin_options[show_related]" value="1" <?php checked(1, isset($options['show_related']) ? $options['show_related'] : 0); ?> />
    <label for="show_related"><?php _e('Afficher les articles liés aux projets', 'ESGI'); ?></label>
<?php
}

// sanitizer des valeurs envoyées par l'utilisateur
function esgi_plugin_options_validate($input)
{
    $new_input = array();
    $new_input['show_related'] = isset($input['show_related']) ? absint($input['show_related']) : 0;
    return $new_input;
}
