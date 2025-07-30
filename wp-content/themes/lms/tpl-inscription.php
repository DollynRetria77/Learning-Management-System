<?php
    /**
     *  Template Name: inscription
     */
?>
<?php 
    $error = false;
    $success = false;
    if(!empty($_POST)){
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";
        // die();

        //tester si le mot de passe et la confirmation du mot de passe sont différent
        if($_POST['password'] != $_POST['password2']){
            $error = "Les 2 mots de passes ne correspondent pas";
        }else{
            //tester si c'est pas un adresse mail
            if(!is_email($_POST['mail'])){
                $error = "Veuillez entrer un email valide";
            }else{
                //insérer l'utilisateur dans la bdd
                $user = wp_insert_user(array(
                    'last_name'     => $_POST['nom'],
                    'first_name'    => $_POST['prenom'],
                    'user_login'    => $_POST['login'], 
                    'user_email'    => $_POST['mail'],
                    'user_pass'     => $_POST['password'],
                    'role'          => $_POST['type_user']
                ));
                //Vérifie si la variable $user est une erreur WordPress
                if(is_wp_error($user)){
                    $error = $user->get_error_message();
                }else{

                    $success = "inscription reussie";
                    //header('location: profil');
                    //vider le tableau $_POST
                    $_POST = array();
                }
            }
        }
    }
?>

<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-12 mt-4 mb-4">
            <h2 class="title-h2">Inscription :</h2>
            <?php if($error) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type_user" value="editor" id="formateur" required />
                    <label class="form-check-label" for="formateur">
                        Formateur
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type_user" value="subscriber" id="etudiant" />
                    <label class="form-check-label" for="etudiant">
                        Etudiant
                    </label>
                </div>
                <div class="form-group mt-3">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : ''; ?>" required />
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="<?php echo isset($_POST['prenom']) ? $_POST['prenom'] : ''; ?>" required />
                </div>
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" class="form-control" <?php echo isset($_POST['login']) ? $_POST['login'] : ''; ?> required />
                </div>
                <div class="form-group">
                    <label for="mail">Mail :</label>
                    <input type="text" name="mail" id="mail" class="form-control" <?php echo isset($_POST['mail']) ? $_POST['mail'] : ''; ?> required />
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" class="form-control" <?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?> required />
                </div>
                <div class="form-group">
                    <label for="password">Confirmation mot de passe:</label>
                    <input type="password" name="password2" id="password2" class="form-control" <?php echo isset($_POST['password2']) ? $_POST['password2'] : ''; ?> required />
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">S'inscrire</button>
                </div>
            </form>
        </div>
    </div>
</div>   
<?php get_footer(); ?>

