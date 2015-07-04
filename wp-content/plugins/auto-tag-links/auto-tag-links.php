<?php 
/*
Plugin Name: Auto Tag Links
Plugin URI: http://www.freeplugin.org/
Description: This plugin adds html links to words in post's text, words that match all tags' names in use in the blog, and only a few tags, randomly, from 1 to 4 depending the content's length, and only the most used tags in the entire blog 
Version: 1.0.4
Author: SEO Roma by PMI Servizi
Plugin URI: http://www.freeplugin.org/
*/

/* 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


$atl_option = atl_get_options_stored();


add_action('init', 'atl_init');

function atl_init() {
	load_plugin_textdomain('atl', false, dirname( plugin_basename( __FILE__ ) ).'/lang/');
	//COLOR PICKER LIBRARY
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
}

add_action('admin_menu', 'atl_menu');

function atl_menu() { 
	add_options_page('Auto Tag Links', 'Auto Tag Links', 8, __FILE__, 'atl_options');
}

add_action('wp_head', 'atl_head' );

function atl_head() {
	echo '<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'/css/styles.css" type="text/css" />';
}


add_filter( 'the_content', 'auto_tag_links' , 100);

function auto_tag_links ($content) { 

	global $post, $wpdb, $atl_option;
	
	// dans tous ces cas on ne fait rien
	// on ne fait marcher le plugin que sur les articles
	if ( !is_single() || !isset($wpdb) || !isset($post) || $post->post_type!='post' || !isset($post->ID) || !preg_match("`[0-9]+`", $post->ID) ) return $content;

	// tables qui vont etre sqlées
	$tr = $wpdb->prefix . 'term_relationships';
	$tt = $wpdb->prefix . 'term_taxonomy';
	$t = $wpdb->prefix . 'terms';
	$p = $wpdb->prefix . 'posts';	
	
	// la liste de tous les tags qui ont été appliqués au moins $limit fois à des articles
	static $tous_les_tags = array();
	
	if ( empty($tous_les_tags) ) { 

		$sql = "
			SELECT t.term_id, t.name, t.slug, count(p.ID) as 'count' 
			FROM $tr tr, $tt tt, $t t, $p p
			WHERE p.post_type = 'post'
			AND p.post_status = 'publish'
			AND tr.object_id = p.ID
			AND tr.term_taxonomy_id = tt.term_taxonomy_id
			AND tt.taxonomy = 'post_tag'
			AND tt.term_id = t.term_id 
			GROUP BY t.slug 
			HAVING ( (count( p.ID ) >= ".$atl_option['tag_frequency_threshold'].") AND (count( p.ID ) <= ".$atl_option['tag_frequency_limit'].")  ) 
			ORDER BY `t`.`slug` ASC
			";
		$rows = $wpdb->get_results($sql, ARRAY_A);
		
		foreach ( $rows as $i => $tag ) { 
			// les name des tags sont purgés, pour pouvoir être comparé avec les mots du texte, purgés aussi de la même façon
			// est ce que c'est une bonne idée finalement ? 
			$tous_les_tags[ad_purge_mot($tag['name'])] = array(
				'slug' => $tag['slug'], 
				'count' => $tag['count'], 
				'term_id' =>  $tag['term_id']
			);		
		}
	}
	
	//	STEP 2: decoupage du texte de l'article en cours

	// This function can be used for any other plugin that will analyze the syntax of a text
	$decoupe = array();
	ad_decoupe_texte($decoupe, $content);	

	//	STEP 3: chercher si y'a bien des mots dans le texte de l'article pour tous nos tags 
	
	$tags_du_texte = ad_cherche_tags_du_texte($decoupe, $tous_les_tags);
	
	$n = count($tags_du_texte);
	
	if ( $n == 0 ) { 
		return $content;
	}
	
	// reformer le tableau avec des indices numeriques
	// j'en ai seulement besoin pour faire des pioches aléatoires à améliorer...
	$tags_du_texte2 = array();
	foreach ( $tags_du_texte as $name => $tag ) {
		$tags_du_texte2[] = array('name' => $name, 'slug' => $tag['slug'], 'count' => $tag['count'], 'term_id' =>  $tag['term_id']);
	}

	// STEP 4: keep a minimum and maximum number of tags, random
	// tags kept array
	$tags_gardes = array();
	
	// nombre minimal de tags gardés, dépend de la longueur du texte
	// devrait faire entre 2 et 3 en général
	$garde = round((log(mb_strlen($content), 16)), 0);
	
	// BIND log BETWEEN MIN AND MAX VALUES
	$garde = max($garde, $atl_option['min_tag_count']);
	$garde = min($garde, $atl_option['max_tag_count'], $n);
	
	// bon là le choix est vite vu :O) 
	if ( $n <= $garde ) {	
		$tags_gardes = $tags_du_texte2;
	} else {  
		// y'en a plus mais on en garde que $garde, au hasard
		$count_tags = 0;
		while ( $count_tags < $garde ) {	
		
			$key_random = mt_rand(0, $n-1); // un indice au hasard
			
			if ( !array_key_exists($key_random, $tags_gardes) ) { 				
				$tags_gardes[$key_random] = $tags_du_texte2[$key_random];
				$count_tags++;
			}
		}
	}
	
	//	STEP 5: RECOMPOSE TEXT
	// recomposer le tableau avec les noms des tags comme clé pour revenir en normal
	$tags_du_texte3 = array();
	foreach ( $tags_gardes as $i => $tag ) {
		$tags_du_texte3[$tag['name']] = array('slug' => $tag['slug'], 'count' => $tag['count'], 'term_id' =>  $tag['term_id']);
	}
	$recompose = ad_recompose_text($decoupe, $tags_du_texte3);
					 
	return $recompose;	
}


// MAIN FILTER
// returns a table by cutting the text in different trançons:
// html < >, wordpress tags (balise) [], normal text, links
// takes as a parameter the array $decoupe which contain the result of cutting 
// the tag delimiters, first we look for the default html tags with < and >
function ad_decoupe_texte(&$decoupe, $texte = '', $limiteur1 = '<', $limiteur2 = '>', $type = 'html' ) {

	if ( !is_string($texte) || $texte == '' ) return array();
	
	$texte = trim($texte);
	
	$longueur_texte = mb_strlen($texte, 'UTF-8');
	
	if ( $longueur_texte == 0 ) return array();
	
	$curseur = 0;
	$tronçon_actuel = '';
	$balise_en_cours = '';
	$inside_tag = false;
	$stop_balise = false;
	
	$nl = utf8_encode("\n");
	$ta = utf8_encode("\t");
	$rc = utf8_encode("\r");

	$exclusions = array(
		'<param', '<script', '<object', '<embed', '<style', '<span', '<label', '<form', '<code', '<pre', '<h2', '<h3', '<h4', '<a');
		
	$exclusions_toutes = array(
		'<param', '<script', '<object', '<embed', '<style', '<span', '<label', '<form', '<code', '<pre', '<h2', '<h3', '<h4', '<a',
		'</param', '</script', '</object', '</embed', '</style', '</span', '</label', '</form', '</code', '</pre', '</h2', '</h3', '</h4', '</a'
		);
	
	while ( $curseur < $longueur_texte ) { // on parcout le texte caractère par caractère 
	
		// obligé de faire comme ça à cause utf-8
		$car_courant = mb_substr($texte, $curseur, 1, 'UTF-8');
		
		// je cherche à mémoriser dans quel balise on est 
		if ( $inside_tag == true ) {
			if ( $car_courant == ' ' ) { 
				$stop_balise = true;
			}
			if ( !$stop_balise && $car_courant != '>' ) $balise_en_cours .= $car_courant;
		}
		
		if ( $car_courant == $limiteur1 ) {	// on est sur un debut de balise 
		
			if ( $inside_tag == true ) { // on était dans une balise, ceci est très bizarre .... !!! 
				// ne devrait jamais arriver
				$tronçon_actuel .= $car_courant;	
			} else { // on n'était pas dans un tag 
				
				if ( trim($tronçon_actuel) != '' ) { // sauver le texte en cours, qui est déjà terminé		
					
					// le texte normal contient des balises de plugin, on va encore le découper pour ne pas s'introduire dans ces balises 
					if ( $type == 'html' && preg_match("`\[[^\]]+\]`i", $tronçon_actuel) ) {
						// appel recursif pour la balise wordpress
						ad_decoupe_texte($decoupe, $tronçon_actuel, '[', ']', 'plugin'); 
					}					
					
					// j'essaye de mettre de coté les textes dans certaines balises
					// pour ne pas mettre des liens n'importe où
					else if ( in_array($balise_en_cours, $exclusions) == true ) { 
						// sauver le texte normal comme du html pour qu'il ne soit pas traité par la suite
						$decoupe[] = array('contenu_dans_balise_speciale' => $tronçon_actuel); 
					}
					
					// c'est une adresse de site, vaut mieux la mettre de coté pour qu'elle ne soit pas modifiée par la suite 
					else if ( mb_strpos($tronçon_actuel, 'http://', 0, 'UTF-8') !== false ) { 
						$decoupe[] = array('lien' => $tronçon_actuel);
					} else { // c'est que du texte normal 
						// le texte normal va encore être découpé par sa ponctuation
						$decoupe[] = array('texte' => ad_mots_correspondances($tronçon_actuel)); // sauver le texte normal
					}
				}
				
				$inside_tag = true; // on est maintenance dans une balise 
				$stop_balise = false; // on dit qu'on peut commencer à mémoriser la balise 
				// on débute une nouvelle balise 
				$balise_en_cours = $tronçon_actuel = $car_courant; // le < ou le [ 
			}
		}
		
		else if ( $car_courant == $limiteur2 ) { // fermeture d'une balise 
							
			if ( $inside_tag == true ) { // on était dans une balise 
			
				$tronçon_actuel .= $car_courant;	// sauver le > ou le ] 
			
				if ( $tronçon_actuel != '' ) { // sauver la balise 
				
					if ( in_array($balise_en_cours, $exclusions_toutes) == true ) { 
						$decoupe[] = array('balise_speciale' => $tronçon_actuel); 
					}	else { 
						$decoupe[] = array($type => $tronçon_actuel); 
					}
				} 
			
				$tronçon_actuel = ''; // on repart de zéro
				$inside_tag = false; // on n'est plus dans une balise 
				// echo "\n<!-- debug $balise_en_cours -->";
			}	else { // on était dans du texte, prenons ça comme un caractère normal 
				$tronçon_actuel .= $car_courant;	
			}
		}
		// dernier caractère
		else if ( $curseur == ($longueur_texte-1) ) { // on est sur le dernier caractère 
			
			$tronçon_actuel .= $car_courant; // on prend ce dernier caractère 
			
			// il faudrait se demander si on était dans du texte ou dans une balise... 
			if ( $inside_tag == true ) { // on était dans une balise 
				$decoupe[] = array($type => $tronçon_actuel); 
			}	else { // on était dans du texte 
				$decoupe[] = array('texte' => ad_mots_correspondances($tronçon_actuel)); // sauver le texte normal
			}
		
		} else if ( $car_courant == $nl ) {
			$decoupe[] = array('hidden_car' => "\n"); 
			$tronçon_actuel .= $car_courant;	
		} else if ( $car_courant == $rc ) {
			$decoupe[] = array('hidden_car' => "\r"); 
			$tronçon_actuel .= $car_courant;	
		} else if ( $car_courant == $ta ) {
			$decoupe[] = array('hidden_car' => "\t");
			$tronçon_actuel .= $car_courant;	 
		} else { 
			// continuer ce texte ou cette balise 
			$tronçon_actuel .= $car_courant;	
		} 
		$curseur++;	
	}
}


// retourne un tableaux avec plusieurs tronçons: les mots, la ponctuation
// prend en paramètre un texte normal sans balise
function ad_mots_correspondances ($contenu = '') { 

	if ( !is_string($contenu) || $contenu == '' ) return array();
	
	$contenu = html_entity_decode($contenu, ENT_QUOTES, 'UTF-8'); // sans entités
	
	$len = mb_strlen($contenu);
	
	if ( $len == 0 ) return array();
	
	$curseur = 0;
	$correspondances = array();
	$nouveau_mot = '';
	
	while ( $curseur < $len ) {
	
		$car_courant = mb_substr($contenu, $curseur, 1, 'UTF-8');
		
		if ( in_array($car_courant, array(' ', '’', '(', ')', ',', '.', ':', ';', '_', '?', '!', "'", '"', '/', '\\', '+', '*') ) ) {
		
			// on sauve le mot précédent
			if ( $nouveau_mot != '' ) {
				$correspondances[] = array(
								'type' => 'mot', 
								'original' => htmlentities($nouveau_mot, ENT_QUOTES, 'UTF-8'), 
								'purge' => ad_purge_mot($nouveau_mot)
							);
			}
			// on enregistre cette ponctuation
			$correspondances[] = array(
						'type' => 'ponctuation', 
						'original' => htmlentities($car_courant, ENT_QUOTES, 'UTF-8'), 
						'purge' => $car_courant
					);
			// on se prépare pour enregistrer un nouveau mot
			$nouveau_mot = '';
		} else if ( $curseur == ($len-1) ) {
		// il faut penser à enregistrer le dernier mot
			$nouveau_mot .= $car_courant;
			$correspondances[] = array(
							'type' => 'mot', 
							'original' => htmlentities($nouveau_mot, ENT_QUOTES, 'UTF-8'), 
							'purge' => ad_purge_mot($nouveau_mot)
						);
		} else {
			$nouveau_mot .= $car_courant;			
		}
		$curseur++;	
	}	
	return $correspondances;
}


// retourne un mot avec que des caractères [a-z]
// prend en paramètre un mot quelconque
function ad_purge_mot($texte) { 

	$texte = html_entity_decode($texte, ENT_QUOTES, 'UTF-8'); 

	// passage en ISO
	$texte = utf8_decode($texte); 
	$texte = trim($texte);
	// sans accents
	$texte = strtr($texte, utf8_decode("ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ"), "AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy");
	// en minuscules 
	$texte = strtolower($texte); 
	// retour en UTF-8
	return utf8_encode($texte);
}


// prend en paramètre le tableau $decoupe qui contient le texte de l'article découpé
// et un tableau $tags_blog avec tous les tags du blog selectionnés
// retourne un tableau avec les tags en commun, extrait de $tags 
function ad_cherche_tags_du_texte($decoupe, $tags_blog) {

	$tags_communs = array(); 
	
	foreach ( $decoupe as $i => $segment ) {
		foreach ( $segment as $type => $content ) {
			// on ne cherche que dans les segments de type texte 
			if ( $type != 'texte' ) {
				continue;
			}
			foreach ( $content as $word ) {
				// if it is a word (not punctuation) and is part of the blog tags and it is not yet registered
				if ( $word['type'] == 'mot' && array_key_exists($word['purge'], $tags_blog) && !array_key_exists($word['purge'], $tags_communs) ) { 
					$tags_communs[$word['purge']] = $tags_blog[$word['purge']];
				} 
			}
		}	
	}
	return $tags_communs;
}


// son but est de recomposer le texte de l'article avec les liens vers les tags
// prend en paramètre le tableau $decoupe du texte découpé
// et les tags selectionnés dans le tableau $tags
function ad_recompose_text($decoupe, $tags) {

	global $atl_option;

	$recompose = '';
	$stop = count($tags);
	$cptr = 0;
	$last = '';
	$link_style = ($atl_option['link_color_active'] and $atl_option['link_color']!='') ? 'style="color:'.$atl_option['link_color'].'"' : '';
	
	foreach ( $decoupe as $i => $segment ) {
	
		foreach ( $segment as $type => $content ) {

			if ( $type == 'texte' ) {
			
				foreach ( $content as $word ) {
					
					$original = $word['original'];
					$purge = $word['purge'];
					
					if ( $word['type'] == 'ponctuation' ) {
						$recompose .= $original;
					} else if ( $cptr < $stop && array_key_exists($purge, $tags) ) { 
					
						$cptr++;
					
						// met le lien
						$recompose .= '<a title="'.__('See the tag', 'atl').': '.$purge.' ('. $tags[$purge]['count'].' '.($tags[$purge]['count']>1 ? __('posts', 'atl') : __('post', 'atl')).')" 
							class="autobesttag" '.$link_style.' rel="nofollow" href="'.get_tag_link($tags[$purge]['term_id']).'">'.$original.'</a>';
					
						// pour ne pas le reprendre une deuxième fois
						unset($tags[$purge]);
					} else {
						$recompose .= $original;
					}
				}			
			} else if ( $type == 'plugin' ) {
				$recompose .= $content . ' ';
			}	else if ( $type == 'lien' ) {
				$recompose .= $content;
			}	else if ( $type == 'html' ) { 
				$recompose .= $content;
			}	else {
				$recompose .= $content;
			}
		}	
	}
	return $recompose;
}



function atl_options() {
	if( isset($_POST['tag_frequency_threshold'])) {
		$option = array();
		$option['tag_frequency_threshold'] = esc_html($_POST['tag_frequency_threshold']);
		$option['tag_frequency_limit']     = esc_html($_POST['tag_frequency_limit']);
		$option['min_tag_count'] = esc_html($_POST['min_tag_count']);
		$option['max_tag_count'] = esc_html($_POST['max_tag_count']);
		$option['link_color_active'] = (isset($_POST['link_color_active']) and $_POST['link_color_active']=='on') ? true : false;
		$option['link_color'] = esc_html($_POST['link_color']);
		update_option('auto_tag_links', $option);
		// Put a settings updated message on the screen
		echo '<div class="updated"><p><strong>'.__('Settings saved.', 'menu-test' ).'</strong></p></div>';
	}
	
	//GET (EVENTUALLY UPDATED) ARRAY OF STORED VALUES
	$atl_option = atl_get_options_stored();
	$link_color_active   = ($option['link_color_active']=='on') ? 'checked="checked"' : '';
	$link_color_inactive = ($option['link_color_active']!='on') ? 'checked="checked"' : '';
		
	echo '
	<div class="wrap">
        <div id="icon-themes" class="icon32" ><br></div>
		<h2>Auto Tag Links</h2>	
		<div id="poststuff" style="padding-top:10px; position:relative;">
		<div style="float:left; width:74%; padding-right:1%;">
		
		<form method="post" name="atl_form" action="">
		';
	wp_nonce_field('update-options'); 
	
	$tag_frequency_fields = array(
		array(
			'text' => 'Frequency threshold',
			'field' => '<input type="text" name="tag_frequency_threshold" value="'.$atl_option['tag_frequency_threshold'].'" /><br />
				<span class="description">'.__('a tag should have been used MORE than ... in order to be kept').'</span>'
		),
		array(
			'text' => 'Frequency limit',
			'field' => '<input type="text" name="tag_frequency_limit" value="'.$atl_option['tag_frequency_limit'].'" /><br />
				<span class="description">'.__('a tag should have been used LESS than ... in order to be kept').'</span>'
		),
	);
	
	$tag_frequency = '<table class="form-table">';
	foreach ($tag_frequency_fields as $field) {
		$tag_frequency .= '
		<tr valign="top">
			<th scope="row">'.__($field['text'], 'atl').'</th><td>'.$field['field'].'</td>
		</tr>';
	}
	$tag_frequency .= '</table>';

	$tag_count_fields = array(
		array(
			'text' => 'Minimum number',
			'field' => '<input type="text" name="min_tag_count" value="'.$atl_option['min_tag_count'].'" /><br />
				<span class="description">'.__('at less, you wish that ... tags links appear in a post').'</span>'
		),
		array(
			'text' => 'Maximum number',
			'field' => '<input type="text" name="max_tag_count" value="'.$atl_option['max_tag_count'].'" /><br />
				<span class="description">'.__('at most, you wish that ... tags links appear in a post').'</span>'
		),
	);
	
	$tag_count = '<table class="form-table">';
	foreach ($tag_count_fields as $field) {
		$tag_count .= '
		<tr valign="top">
			<th scope="row">'.__($field['text'], 'atl').'</th><td>'.$field['field'].'</td>
		</tr>';
	}
	$tag_count .= '</table>';

	$other_options_fields = array(
		array(
			'text' => 'Link color',
			'field' => '
				<input type="radio" name="link_color_active" value="off" '.$link_color_inactive.' /> Default<br />
				<input type="radio" name="link_color_active" value="on"  '.$link_color_active.' /> 
				<input type="text" id="link_color" name="link_color" value="'.$atl_option['link_color'].'" /> <div id="ilctabscolorpicker"></div>'
		),
	);
	
	$other_options = '<table class="form-table">';
	foreach ($other_options_fields as $field) {
		$other_options .= '
		<tr valign="top">
			<th scope="row">'.__($field['text'], 'atl').'</th><td>'.$field['field'].'</td>
		</tr>';
	}
	$other_options .= '</table>';

	echo atl_box_content (__('Tag frequency'), $tag_frequency);
	echo atl_box_content (__('Tag count'), $tag_count);
	echo atl_box_content (__('Other options'), $other_options);
	?>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="atl_tag_frequency_threshold,atl_tag_frequency_limit,atl_min_tag_count,atl_max_tag_count" />
	
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'atl') ?>" />
	</p>
	
	</form>
	</div>

	<div style="float:right; width:25%;">
<?php
	echo atl_box_content('A Free Plugin by PMI Servizi', '
			<a target="_blank" href="http://www.pmiservizi.it/web-agency.html" title="PMI Servizi Web Agency">
				<img border="0" src="'.plugin_dir_url(__FILE__).'images/pmiservizi_logo.png" title="PMI Servizi Web Agency" alt="PMI Servizi Web Agency" style="display:block; margin:10px auto;">
			</a>
		')
		.atl_box_content('Spread the love!', '
			Do you like our plugin?<br />
			Rate it!<br /><br />
		')
		.atl_box_content('Support?', '
			Most of the actual plugin features were requested by users and developed for the sake of doing it.<br /><br />
			<b>Do you have any suggestions or requests?</b><br />
			Tell us about it in the support forum or using our 
			<a href="http://www.pmiservizi.it/contatti.html" target="_blank" title="PMI Servizi - Contact us">website form</a>.

		')
		.atl_box_content('News feed by PMI Servizi', atl_feed());
?>
	</div>

	</div>
	</div>
	<script type="text/javascript">
 	  jQuery(document).ready(function() {
	    jQuery('#ilctabscolorpicker').hide();
	    jQuery('#ilctabscolorpicker').farbtastic("#link_color");
	    jQuery("#link_color").click(function(){jQuery('#ilctabscolorpicker').slideToggle()});
	  });
 	</script>
<?php 
}


function atl_feed () {
	$feedurl = 'http://news.pmiservizi.it/feed/';
	$select = 8;

	$rss = fetch_feed($feedurl);
	if (!is_wp_error($rss)) { // Checks that the object is created correctly
		$maxitems = $rss->get_item_quantity($select);
		$rss_items = $rss->get_items(0, $maxitems);
	}
	if (!empty($maxitems)) {
		$out .= '
			<div class="rss-widget">
				<ul>';
    foreach ($rss_items as $item) {
			$out .= '
						<li><a class="rsswidget" href="'.$item->get_permalink().'">'.$item->get_title().'</a> 
							<span class="rss-date">'.date_i18n(get_option('date_format') ,strtotime($item->get_date('j F Y'))).'</span></li>';
		}
		$out .= '
				</ul>
			</div>';
	}
	$x = is_rtl() ? 'left' : 'right'; // This makes sure that the positioning is also correct for right-to-left languages
	$out .= '<style type="text/css">#plagiarism_id {float:'.$x.';}</style>';
	return $out;
}


function atl_box_content ($title, $content) {
	if (is_array($content)) {
		$content_string = '<table>';
		foreach ($content as $name=>$value) {
			$content_string .= '<tr>
				<td style="width:130px;">'.__($name, 'menu-test' ).':</td>	
				<td>'.$value.'</td>
				</tr>';
		}
		$content_string .= '</table>';
	} else {
		$content_string = $content;
	}

	$out = '
		<div class="postbox">
			<h3>'.__($title, 'menu-test' ).'</h3>
			<div class="inside">'.$content_string.'</div>
		</div>
		';
	return $out;
}


function atl_get_options_stored () {
	//GET ARRAY OF STORED VALUES
	$option = get_option('auto_tag_links');
	if (!is_array($option)) {
		$option = array();
	}
	// MERGE DEFAULT AND STORED OPTIONS
	$option_default = atl_get_options_default();
	$option = array_merge($option_default, $option);
	return $option;
}


function atl_get_options_default () {
	$option = array();
	$option['tag_frequency_threshold'] = 2;
	$option['tag_frequency_limit'] = 20;
	$option['min_tag_count'] = 2;
	$option['max_tag_count'] = 6;
	$option['link_color_active'] = false;
	$option['link_color'] = 'gray';
	return $option;
}
