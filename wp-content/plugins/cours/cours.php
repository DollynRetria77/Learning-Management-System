<?php
/*
Plugin Name: Cours
Plugin URI: http://ideo.loc
Description: Custom plugin
Version: 0.1 
Author: Dollyn RETRIA
Author URI: http://ideo.loc 
*/

if(!class_exists('Cours')){
    class Cours
    {
        public static function init(){
            add_action('init', [self::class, 'custom_register_post_type']);
            add_action('add_meta_boxes', [self::class, 'custom_metaboxes']);
        }

        public static function custom_register_post_type(){
            //Custom Post Type cours
            $labels = array(
                'name'               => 'Cours',
                'singular_name'      => 'Cours',
                'all_items'          => 'Tous les cours',
                'add_new_item'       => 'Ajouter un cours',
                'edit_item'          => 'Modifier cours',
                'new_item'           => 'Nouveau cours',
                'view_item'          => 'voir le cours',
                'search_items'       => 'Rechercher parmi les cours',
                'not_found'          => 'Pas de cours trouvé',
                'not_found_in_trash' => 'Pas de cours dans la corbeille'
            );

            $supports = array('title', 'editor');

            $args = array(
                'labels' => $labels,
                'public' => true, 
                'show_in_rest' => true,
                'has_archive' => true,
                'supports' => $supports,
                'menu_position' => 5, 
                'menu_icon' => 'dashicons-book',
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'cour' ),
                'capability_type'    => 'post',
                'hierarchical'       => true,
            );
            register_post_type('cours', $args);

            //déclaration de la taxonomie
            $labels = array(
                'name'          => 'Catalogue', 
                'new_item_name' => 'Nouveau catalogue', 
                'parent_item'   => 'Catalogue parent'
            );
            
            $args = array( 
                'labels' => $labels,
                'public' => true, 
                'show_in_rest' => true,
                'hierarchical' => true, 
            );

            register_taxonomy('catalogue', 'cours', $args);        
        }

        public static function custom_metaboxes(){
            /*
            * @param infos_cours // id du metabox
            * @param 'Information supplémentaires' // Titre du metabox
            * @param infos_cours // fonction callback
            * @param cours // post type associé au metabox
            * @param advanced // side, normal, advanced
            */
            add_meta_box('infos_cours', 'Information supplémentaires', [self::class, 'infos_cours'], 'cours', 'advanced');
        }

       public static function infos_cours($post){
            //$listAbonnee = array();
            $duree = get_post_meta($post->ID, '_duree', true);
            $lieux = get_post_meta($post->ID, '_lieux', true);
            $supports = get_post_meta($post->ID, '_supports', false);

            $abonnee = get_post_meta($post->ID, '_participant', false);
            // echo '<pre>';
            // print_r($supports);
            // echo '</pre>';
            //echo '<input id="abonnee" type="text" name="abonnee" value="'.$abonnee.'" readonly />';
            echo '<label for="duree">Durée : </label>';
            echo '<input id="duree" type="text" name="duree"  value="'.$duree.'" />';
            echo '<br /><br />';
            echo '<label for="auteur">Lieux : </label>';
            echo '<input id="lieux" type="text" name="lieux"  value="'.$lieux.'" />';
            echo '<br /><br />';
            echo '<label for="supports">Supports : </label>';
            echo '<input id="supports" type="file" name="supports[]"  value="" multiple />';
        
            // if($supports['url'] != ''):
            //     echo "<div> Support :". $supports['url'] ."</div>";
            // endif;
            if($supports != ''):
                foreach($supports[0] as $support){
                    echo "<div> Support :". $support['url'] ."</div>";
                }
            endif;

            if($abonnee != ''){
                print_r($abonnee);
                //array_push($listAbonnee, $abonnee);
            }
        }
    }

    Cours::init();
}

