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
        'add_new'               => __('Ajouter un nouveau projet', 'ESGI'),
        'add_new_item'          => __('Ajouter un nouveau projet', 'ESGI'),
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
        'menu_icon'          => 'dashicons-media-code'
    );

    register_post_type(
        'project',
        $args
    );
}
