<?php
/*
Plugin Name: ESGI plugin
Plugin URI: https://esgi.fr
Description: Un plugin d'exemple. Création d'un type custom de post (project)
Author: Doudou
Version: 1.0
*/

// Fichier master du plugin //

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


// Définition du chemin du template à utiliser pour l'affichage des projects

add_filter('template_include', 'esgi_template_include', 99);
function esgi_template_include($template)
{
    if (is_single() && get_query_var('post_type') == 'project') {
        $template = __DIR__ . '/templates/project.php';
    }
    return $template;
}
