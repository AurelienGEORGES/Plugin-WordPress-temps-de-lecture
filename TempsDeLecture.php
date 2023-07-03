<?php
/*
Plugin Name: Mon plugin de temps de lecture d'un article
Plugin URI: https://mon-siteweb.com/
Description: Ceci est un plugin qui calcul le temps de lecture d'un post suivant son nombre de mots contenu dans celui ci
Author: Aurélien Georges
Version: 1.0
Author URI: http://mon-siteweb.com/
*/

//fonction qui calcule et affiche le temps de lecture d'un article
function calcul_reading_time( $post_id, $post, $update )  {

	if( ! $update ) { return; }
	if( wp_is_post_revision( $post_id ) ) { return; }
	if( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) { return; }
	if( $post->post_type != 'post' ) { return; }

	// Calculer le temps de lecture
	$word_count = str_word_count( strip_tags( $post->post_content ) );

	// On prend comme base 200 mots par minute
	$minutes = ceil( $word_count / 200 );
	
	// On sauvegarde la meta
	update_post_meta( $post_id, 'reading_time', $minutes );
}

add_action( 'save_post', 'calcul_reading_time', 10, 3 );

//fonction qui permet d'afficher le résultat de la fonction qui calcule le temps de lecture
function affichage($content) {
    global $id;
    if (!is_single()) {return $content;}
    ?>
    <p> 
    Temps de lecture : 
    <?php echo get_post_meta($id, 'reading_time', true); ?> minutes
    </p>
<?php
return $content;
}

add_filter('the_content', 'affichage', 10);





