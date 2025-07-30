<?php
    /**
     *  Template Name: abonnement
     */
?>
<?php 
session_start();
if(isset($_SESSION['user_id']) && isset($_SESSION['post_id'])): 
    $userID = $_SESSION['user_id'];
    $postID = $_SESSION['post_id'];
    // echo "userID : ".$userID;
    // echo "<br />";
    // echo  "postID : ".$postID;
    $participant = get_post_meta($postID, '_participant');

    //print_r($participant);

    if(count($participant) !== 0){
        //echo "tonga ato ve 1";
        if(in_array($userID, $participant)){
            die("Vous avez déjà participer à cette cours");
            //echo "tonga ato ve 1";
        }else{
            //ajout de l'id de l'user
            $post_id = add_post_meta($postID, '_participant', $userID);
            if($post_id){
                die("Votre participation est bien reussie");
            }else{
                die("Impossible de participer");
            }            
        }
    }else{
        $post_id = add_post_meta($postID, '_participant', $userID);
        if($post_id){
            die("Votre participation est bien reussie");
        }else{
            die("Impossible de participer");
        }
    }
endif;
?>