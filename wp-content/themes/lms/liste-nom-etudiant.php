<?php
    /**
     *  Template Name: liste etudiant
     */
?>
<?php 
if(isset($_GET['id'])){
    $id = $_GET['id'];
    //echo $id;

    $listeEtudiant = get_post_meta($id, '_participant'); 

    foreach($listeEtudiant as $un_etudiant):
        $etudiant = get_user_by('ID', $un_etudiant);
        echo '<div>'. $etudiant->last_name.' '.$etudiant->first_name.'</div>';
    endforeach;
    die();
}
?>