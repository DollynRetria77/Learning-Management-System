<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); // permet d'inserer toutes les infos à mettre en tête ?>
</head>
<body <?php echo body_class(); ?>>
    <header class="main-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header-top">
                        <div class="header-menu">
                            <!-- <a class="navbar-brand" href="<?php echo site_url(); ?>"><?php bloginfo('name'); ?></a> -->
                            <?php wp_nav_menu(array(
                                'theme_location' => 'header', 
                                'container' => false,
                                'menu_class' => 'header-navigation'
                            )); ?>
                        </div>
                        <div class="header-auth">
                            <?php $user = wp_get_current_user(); ?>
                            <?php //print_r($user); ?>
                            <div class="header-auth-wrapper">
                                <?php if($user->ID == 0): ?> 
                                <div class="header-auth-item header-registration">
                                    <a class="auth-btn registration" href="<?php echo bloginfo('url') ?>/inscription">S'inscrire</a>
                                </div>
                                <?php endif; ?>
                                <div class="header-auth-item header-login">
                                    <?php if($user->ID == 0): ?> 
                                        <a class="auth-btn login"  href="<?php echo bloginfo('url') ?>/connexion">Se connecter</a>
                                    <?php else: ?>   
                                        <ul class="logged-in">
                                            <li>
                                                <a class="logged-in-btn"  href="#"><i class="bi bi-person"></i><?php echo $user->user_login ?></a>
                                                <ul class="logged-in-submenu">
                                                    <li>
                                                        <div class="user-infos">
                                                            <div class="user-infos-sigle">
                                                                <div class="sigle">
                                                                    <?php echo substr($user->first_name, 0, 1);  ?>
                                                                    <?php echo substr($user->last_name, 0, 1);  ?>
                                                                </div>
                                                            </div>
                                                            <div class="user-infos-txt">
                                                                <div class="user-infos-name">
                                                                    <span class="last-name"><?php echo $user->first_name ?></span>
                                                                    <span class="first-name"><?php echo $user->last_name ?></span> 
                                                                </div>
                                                                <div class="user-infos-mail"><?php echo $user->user_email; ?></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li><a  href="<?php echo site_url().'/profil' ?>">Mon profil</a></li>
                                                    <li><a  href="<?php echo site_url().'/mes-cours' ?>">Mes cours</a></li>
                                                    <?php if ( in_array( 'editor', (array) $user->roles ) ): ?>
                                                    <li><a href="<?php echo site_url() ?>/ajout-cours?id=<?php echo $user->ID ?>">Ajouter un cours</a></li>
                                                    <?php endif; ?>
                                                    <li><a  href="<?php echo wp_logout_url( home_url() ); ?>">Se deconnecter</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    <?php endif; ?> 
                                </div>
                            </div>           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>




    
