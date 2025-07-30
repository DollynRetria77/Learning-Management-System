<?php 
show_admin_bar(false);
function lms_supports(){
    //add_theme_support() : Enregistre la prise en charge du thème pour une fonctionnalité donnée.
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    //register_nav_menu() : Enregistre un emplacement de menu de navigation pour un thème.
    register_nav_menu('header', 'En tête du menu');
}
//lorsque cette action est appeler, appelle cette fonction
//add_action() : rajouter des comportement lors de certain operation de wordpress
add_action('after_setup_theme', 'lms_supports');


/*Chargement de script et styles*/
function lms_register_assets(){
    //wp_register_style() : permet d'enregistrer un style
    /*
        Enregistrez une feuille de style CSS :
        @param string $handle : premier paramètre, nom du style(unique)
        @param string|bool source : adresse du fichier
        @param string[] : dépendances
        @param string|bool|null  $ver : version
        @param string  $media
    */
    wp_register_style('police_opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap'); 
    wp_register_style('slick_theme', get_template_directory_uri().'/assets/css/slick-theme.css');
    wp_register_style('slick', get_template_directory_uri().'/assets/css/slick.css');
    wp_register_style('bootstrap_icon', get_template_directory_uri().'/assets/css/bootstrap-icons.css');
    wp_register_style('front_main_css', get_template_directory_uri().'/assets/css/main.min.css');

    /*
    * Annule l'inscription de jQuery de la liste des scripts déclarés, et refaire notre propre déclaration
    */ 
    wp_deregister_script('jquery'); 

    /*
        Enregistrez un script JS :
        @param string $handle : premier paramètre, nom du style(unique)
        @param string|bool source : adresse du fichier
        @param string[] : dépendances
        @param string|bool|null  $ver : version
        @param bool  $in_footer
    */
    wp_register_script('front_main_js', get_template_directory_uri().'/assets/js/dist/scripts.min.js', array(), false, true);
    wp_register_script('front_main_custom', get_template_directory_uri().'/assets/js/dist/custom-ext.js', array(), '1.0', true);

    // wp_enqueue_style(): inscrit un fichier CSS 
    wp_enqueue_style('police_opensans');
    wp_enqueue_style('slick_theme');
    wp_enqueue_style('slick');
    wp_enqueue_style('bootstrap_icon');
    wp_enqueue_style('front_main_css');

    // wp_enqueue_script(): inscrit un fichier JS à afficher dans la page
    wp_enqueue_script('front_main_js');
    wp_enqueue_script('front_main_custom');

    // Envoyer une variable de PHP à JS proprement
    wp_localize_script( 'front_main_custom', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
}
add_action('wp_enqueue_scripts', 'lms_register_assets');



//récupération du requete ajax et traitement
function liste_etudiants() {
  $post_id = $_GET['post_id'];

  //Récupère un champ méta associé au key '_participant' de publication pour $post_id.
  $listeEtudiant = get_post_meta($post_id, '_participant'); 

  foreach($listeEtudiant as $un_etudiant):
      //Récupére les informations de l'etudiant son ID
      $etudiant = get_user_by('ID', $un_etudiant);
      echo '<div>- '. $etudiant->last_name.' '.$etudiant->first_name.'</div>';
  endforeach;
  wp_die();
}
add_action( 'wp_ajax_liste_etudiants', 'liste_etudiants' );

function lms_pagination(){
    $pages = paginate_links(['type' => 'array']);
    if($pages === null){
        return;
    }
    echo '<nav aria-label="Pagination" class="my-4">';
    echo '<ul class="pagination">';
    
    foreach($pages as $page){
        $active = strpos($page, 'current') !== false;
        $class = 'page-item';
        if($active){
            $class .= ' active';
        }

        echo '<li class="'.$class.'">';
        echo str_replace('page-numbers', 'page-link', $page);
        echo '</li>';
    }
    echo '</ul>';
    echo '</nav>';
}


function gallerie_post_type(){
    //Custom Post Type Gallerie
    $labels = array(
        'name'               => 'Gallerie',
        'singular_name'      => 'Gallerie',
        'all_items'          => 'Tous les images',
        'add_new_item'       => 'Ajouter un images',
        'edit_item'          => 'Modifier l\'image',
        'new_item'           => 'Nouveau image',
        'view_item'          => 'voir l\'image',
        'search_items'       => 'Rechercher parmi les images',
        'not_found'          => 'Pas d\'image trouvé',
        'not_found_in_trash' => 'Pas d\'image dans la corbeille'
    );

    $supports = array('title', 'thumbnail');

    $args = array(
        'labels' => $labels,
        'public' => true, 
        'show_in_rest' => true,
        'has_archive' => true,
        'supports' => $supports,
        'menu_position' => 5, 
        'menu_icon' => 'dashicons-format-image',
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'gallerie' ),
        'capability_type'    => 'post',
        'hierarchical'       => true,
    );
    register_post_type('gallerie', $args);      
}


add_action('init', 'gallerie_post_type');

