<?php
    /**
     *  Template Name: ajouter-un-cours
     */
?>
<?php 
    //session_start(); 
    if(isset($_GET['id'])): 
        $id = $_GET['id'];
        //echo $id;
        $user = get_user_by('ID', $id);
        $current_slug = add_query_arg( array(), $wp->request );
    endif;

    $error = false;
    $errorUpload = array();
    $success = false;
    if(!empty($_POST)){
        // echo "<pre>";
        // var_dump($_POST);
        // var_dump($_FILES);
        // echo "</pre>";
        //die();

        if(!empty($_POST['titre']) && !empty($_FILES['supports']['name'])){
            $supported_types    = array('application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'video/mp4');
            $supportsName       = $_FILES['supports']['name'];
            $supportsTmp_name   = $_FILES['supports']['tmp_name'];
            $uploads             = array();

            for($i=0, $j=0; $i < count($supportsName), $j < count($supportsTmp_name); $i++, $j++):

                //Récupérez le type de fichier à partir du nom de fichier.
                $arr_file_type = wp_check_filetype(basename($supportsName[$i]));
                // print_r($arr_file_type);
                $uploaded_type = $arr_file_type['type'];
                if(in_array($uploaded_type, $supported_types)):
                    //Créez un fichier dans le dossier de téléchargement avec un contenu donné.
                    $upload = wp_upload_bits($supportsName[$i], null, file_get_contents($supportsTmp_name[$j]));
                    // echo '<pre>';
                    // print_r($upload);
                    // echo '</pre>';
                    if(isset( $upload['error'] ) && $upload['error'] != 0){
                        $errorUpload = "Une erreur s'est produite lors du téléchargement du ficher ".$supportsName[$i];
                    }else{
                        array_push($uploads, $upload);
                    }
                endif;
            endfor;
            // echo '<pre>';
            // print_r($uploads);
            // echo '</pre>';
            // die();
            $args = array(
                'post_title'    => $_POST['titre'],
                'post_type'     => 'cours',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => $id,
                'meta_input'    => array(
                    '_duree' => $_POST['duree'],
                    '_lieux' => $_POST['lieux'],
                    '_supports' => $uploads
                )

            );
            $post_id = wp_insert_post($args);
            $cat_ids = array_map(function($catalogue){
                return (int) $catalogue;
            }, $_POST['catalogue']);
            $term_taxonomy_ids = wp_set_object_terms( $post_id, $cat_ids, 'catalogue' );

            if(!is_wp_error($term_taxonomy_ids)){
                $success = "insertion post reussie";
                $_POST = array();
            }else{
                $error = $term_taxonomy_ids->get_error_message();
            }
        }
    }
?>

<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-12 mt-4 mb-4">
            <h2 class="title-h2">Ajouter un cours :</h2>
            <ul class="dashboard-menu">
                <li><a href="<?php echo bloginfo('url') ?>/profil" class="btn <?php echo ($current_slug == 'profil') ? 'active': '' ?>">Mon profl</a></li>
                <li><a href="<?php echo bloginfo('url') ?>/edit-profil?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-profil') ? 'active': '' ?>">Modifier mon profil</a></li>
                <li><a href="<?php echo bloginfo('url') ?>/edit-password?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'edit-password') ? 'active': '' ?>">Changer mot de passe</a></li>
                <li><a href="<?php echo bloginfo('url') ?>/mes-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'mes-cours') ? 'active': '' ?>">Mes cours</a></li>
                <li><a href="<?php echo bloginfo('url') ?>/ajout-cours?id=<?php echo $user->ID ?>" class="btn <?php echo ($current_slug == 'ajout-cours') ? 'active': '' ?>">Ajouter un cours</a></li>

            </ul>
            <?php if($error) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if(count($errorUpload) !== 0) : ?>
                <?php foreach($errorUpload as $error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                    <br /><br />
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" enctype='multipart/form-data'>
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" class="form-control" value="<?php echo isset($_POST['titre']) ? $_POST['titre'] : ''; ?>"  required/>
                </div>
                <div class="form-group">
                    <label for="duree">Durée :</label>
                    <input type="text" name="duree" id="duree" class="form-control" value="<?php echo isset($_POST['duree']) ? $_POST['duree'] : ''; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="lieux">Lieux</label>
                    <input type="text" name="lieux" id="lieux" class="form-control" value="<?php echo isset($_POST['lieux']) ? $_POST['lieux'] : ''; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="catalogue">Catalogue</label>
                    <select multiple class="form-control" id="catalogue" name="catalogue[]" required>
                        <?php 
                            $post_type = 'cours';
                            $post_type_taxonomies = get_object_taxonomies($post_type);
                        ?>
                        <?php foreach($post_type_taxonomies as $post_type_taxonomy): ?>
                        <?php 
                            $taxo_terms = get_terms(array(
                                'taxonomy' => $post_type_taxonomy,
                                'hide_empty' => false
                            ));
                        ?>
                            <?php for($i=0; $i < count($taxo_terms); $i++): ?>
                                <option value="<?php echo $taxo_terms[$i]->term_id; ?>"><?php echo $taxo_terms[$i]->name; ?></option>
                            <?php endfor; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group custom-file mb-2">
                    <input type="file" class="custom-file-input" name="supports[]" id="support" multiple required />
                    <label class="custom-file-label" for="support">Supports*</label>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                </div>
            </form>
            <div>
            * Format supporté : pdf, docx, mp4
            </div>
        </div>
    </div>
</div>   
<?php get_footer(); ?>

