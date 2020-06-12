<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php bloginfo('name'); echo " | " . get_the_title(); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">

</head>
<body>

<div class="container-fluid">

    <div class="logo-menu">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <h1>
                <?php
                    echo '<a class="navbar-brand" href="' . get_home_url() .'">';
                    bloginfo('name'); 
                    echo '</a>';
                ?>
            </h1>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php 
            /**
             * 
             * GENERAL MENU BOOTSTRAP
             * 
             * Add a parent CSS class for nav menu items.
             *
            **/
            function wpdocs_add_menu_parent_class( $items ) {
                $parents = array();
             
                // Collect menu items with parents.
                foreach ( $items as $item ) {
                    if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
                        $parents[] = $item->menu_item_parent;
                    }
                }
             
                // Add class.
                foreach ( $items as $item ) {
                    if ( in_array( $item->ID, $parents ) ) {
                        $item->classes[] = 'dropdown';
                    }
                }
                return $items;
            }
            add_filter( 'wp_nav_menu_objects', 'wpdocs_add_menu_parent_class' );


            function add_class_to_item($classes, $item){

                /* ADD .active class to ACTIVE menu item */
                if( in_array('current-menu-item', $classes) ){
                        $classes[] = 'active';
                }

                /* ADD .nav-item class to menu item */
                if( in_array('menu-item', $classes) ){
                    $classes[] = 'nav-item';
                }

                /* ADD TOGGLE CLASS TO SUBMENU */
                if( in_array('sub-menu', $classes) ){
                    $classes[] = 'nav-link dropdown-toggle';
                }
                return $classes;
            }
            add_filter( 'nav_menu_css_class' , 'add_class_to_item' , 10 , 2);


            //CLASS TO LINK
            function class_to_link( $atts, $item, $args ) {
                $class = 'nav-link'; 
                $atts['class'] = $class;
                return $atts;
            }
            add_filter( 'nav_menu_link_attributes', 'class_to_link', 10, 3 );


            //BOOTSTRAP CLASS TO MENU
            wp_nav_menu (array(
                'container_class' => 'collapse navbar-collapse',
                'container_id'    => 'navbarSupportedContent',
                'menu_class'      => 'navbar-nav mr-auto',
            ));
        ?>

        </nav>
    </div> <!-- end-logo-menu -->


    <hr>


    <?php
    /**
     * 
     * BREADCRUMB with BOOTSTRAP
     * 
     */
    function drg_breadcrumb (){

        if  (!is_front_page()){
            echo '<nav aria-label="breadcrumb">';
            echo '<ol class="breadcrumb">';
            echo '<li class="breadcrumb-item"><a href="http://localhost/wp">Home</a></li>';
            echo '<li class="breadcrumb-item active" aria-current="page">'. get_the_title() .'</li>';
            echo '</ol>';
            echo '</nav>';
        }

    }
    
    drg_breadcrumb ();
    
    ?>


    <hr>

            
    <?php
    /**
     * 
     * SHOW CONTENT OF THE PAGE
     * 
     */
        if (have_posts()):
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        else:
            echo '<p>Sorry, no posts matched your criteria.</p>';
        endif;
    ?>

    <hr>

    <?php
    /**
     * 
     * SHOW CONTENT OF THE CHILD PAGE INTO THE PARENT
     * TASKS: TO SHOW ONLY IF PRIVATE
     * 
     */
        $args = array(
            'post_type'      => 'page',
            'posts_per_page' => -1,
            'post_parent'    => $post->ID,
            'order'          => 'ASC',
            'orderby'        => 'menu_order'
        );

        $parent = new WP_Query( $args );

        if ( $parent->have_posts() ) {

            //get_post_status( $post->ID )

            while ( $parent->have_posts() ) : $parent->the_post();

                echo '<div id="parent-' . get_the_ID() . '"class="parent-page">';
                echo '<h1>' . get_the_title() . '</h1>';
                echo '<p>' . get_the_content() . '</p>';
                echo '</div>';

            endwhile;

        }

    ?>

    

    
<hr>   

<!-- 
TO DO LIST
- BOOTSTRAP FULLY WORKING
- BREADCRUMB
- NEWS PAGE SHOWING ALL POST
- PHOTO GALLERY
- PARALLAX
 -->