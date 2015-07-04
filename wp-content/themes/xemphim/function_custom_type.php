<?php

add_theme_support('menus'); 
register_nav_menus(array(
	'top' => 'Top menu',
	'nav' => 'Nav menu',
	'footer' => 'Footer link'	
));

add_theme_support('post-thumbnails');

//nếu bạn muốn chỉ dùng với post
add_theme_support('post-thumbnails', array('post'));
//Nếu bạn muốn chỉ dùng với page
//add_theme_support('post-thumbnails', array('page')); 
/*Custom post type*/
 
function check_fiml_meta($filmId){
	global $wpdb;
	$q = " SELECT * 
	    FROM ".DATA_FILM_META."   
	    WHERE film_id = '$filmId'";
	$row = $wpdb->get_results($q);
if($row) {	
return true;} else{return false;}

}
function get_film_meta($filmId,$type=0) {
	global $wpdb;
	$q = " SELECT * 
	    FROM ".DATA_FILM_META."   
	    WHERE film_id = '$filmId'";
	$row = $wpdb->get_results($q);	
	if($row) {
		if($type!=0){
	foreach ($row as $r) {
		if($r->film_rating_total == 0) $rate_star = 0;
						else $rate_star = $r->film_rating/(5*$r->film_rating_total);			
						if($type==1){
						if ($rate_star <= 0)							{$star = '<span class="star small_null"></span><span class="star small_null"></span><span class="star small_null"></span><span class="star small_null"></span><span class="star small_null"></span>';}
						if ($rate_star >= 1  	&& $rate_star < 1.25)	{$star = '<span class="star small_full"></span><span class="star small_null"></span><span class="star small_null"></span><span class="star small_null"></span><span class="star small_null"></span>';}
						if ($rate_star >= 1.25 	&& $rate_star < 1.75)	{$star = '<span class="star small_full"></span><span class="star small_half"></span><span class="star small_null"></span><span class="star small_null"></span><span class="star small_null"></span>';}
						if ($rate_star >= 1.75 	&& $rate_star < 2.25)	{$star = '<span class="star small_full"></span><span class="star small_full"></span><span class="star small_null"></span><span class="star small_null"></span><span class="star small_null"></span>';}
						if ($rate_star >= 2.25  && $rate_star < 2.75)	{$star = '<span class="star small_full"></span><span class="star small_full"></span><span class="star small_half"></span><span class="star small_null"></span><span class="star small_null"></span>';}
						if ($rate_star >= 2.75 	&& $rate_star < 3.25)	{$star = '<span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_null"></span><span class="star small_null"></span>';}
						if ($rate_star >= 3.25  && $rate_star < 3.75)	{$star = '<span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_half"></span><span class="star small_null"></span>';}
						if ($rate_star >= 3.75 	&& $rate_star < 4.25)	{$star = '<span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_null"></span>';}
						if ($rate_star >= 4.25  && $rate_star < 4.75)	{$star = '<span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_half"></span>';}
						if ($rate_star >= 4.75)							{$star = '<span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span><span class="star small_full"></span>';}
						}else {
						if ($rate_star <= 0)							{$star = '<span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span>';}
								if ($rate_star >= 1  	&& $rate_star < 1.25)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 1.25 	&& $rate_star < 1.75)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_half"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 1.75 	&& $rate_star < 2.25)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 2.25  && $rate_star < 2.75)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_half"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 2.75 	&& $rate_star < 3.25)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_null"></span><span class="lage_star lage_null"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 3.25  && $rate_star < 3.75)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_half"></span><span class="lage_star lage_null"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 3.75 	&& $rate_star < 4.25)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_null"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 4.25  && $rate_star < 4.75)	{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_half"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
								if ($rate_star >= 4.75)							{$star = '<span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="lage_star lage_full"></span><span class="z">'.($r->film_rating/5).' Star | '.$r->film_rating_total.' Rates</span>';}
						}
		return $star;				
		}
		}
	} else  {
		return false;
	}
}
function add_film_meta ($film_id) {
	global $wpdb;
	$q = "INSERT INTO ".DATA_FILM_META." (
					film_id
					) 
				VALUES (
					'".$film_id."'
					)";
	$r = $wpdb->query($q);
	if($r) {		
		return "Thêm film meta thành công";
	}
	else return "Éo thêm dc film meta";
	
}
function update_film_meta ($film_id) {
	global $wpdb;
	if($_POST['film_drama'] != '') $drama = $_POST['film_drama'];
	else $drama = '0';
	$q = "UPDATE ".DATA_FILM_META." SET 
					film_thumbnail = '".$_POST['film_thumbnail']."'
					, film_ads_img = '".$_POST['film_ads_img']."'
					, film_decu = '".$_POST['film_decu']."'
					, film_hot = '".$_POST['film_hot']."'
					, film_cinema = '".$_POST['film_cinema']."'
					, film_drama = '".$drama."'
					, film_length = '".$_POST['film_length']."'
					, film_year = '".$_POST['film_year']."'
					, film_hd_download = '".$_POST['film_hd_download']."'
					, film_trailer = '".$_POST['film_trailer']."'
				WHERE film_id = '".$film_id."'";
	$r = $wpdb->query($q);
	if($r) {
		if($_POST ['film_thumbnail'] != '') {
			if(get_post_meta($film_id,'vietpro_thumb',true) != '') {
				$q_post_meta = "UPDATE ".DATA_POSTMETA." SET meta_value = '".$_POST['film_thumbnail']."' 
									WHERE post_id = '".$film_id."' AND  meta_key =  'vietpro_thumb'";
				$r_post_meta = $wpdb->query($q_post_meta);
			} else {
				$q_post_meta = "INSERT INTO ".DATA_POSTMETA." (post_id, meta_key, meta_value) VALUES ('".$film_id."', 'vietpro_thumb', '".$_POST['film_thumbnail']."')";
				$r_post_meta = $wpdb->query($q_post_meta);
			}
		}
		if($_POST ['film_episode'] != '') {
			$episode_post = $_POST ['film_episode'];
			$list_episode = explode ( "\n", $episode_post );
			$count_ep = count ( $list_episode );
			for($i = 0; $i < $count_ep; $i ++) {
				$tap [$i] = explode ( '#', trim ( $list_episode [$i] ) );
				$ten_tap [$i] = trim ( $tap [$i] [0] );
				$link_tap [$i] = trim ( $tap [$i] [1] );
				$thu_tu [$i] = trim ( $tap [$i] [2] );
				if(($link_tap [$i]!='') && $ten_tap [$i] != '')FilmEpisodeNewEpisode($ten_tap [$i],$film_id,$_POST['episode_server'],$link_tap [$i],$thu_tu [$i],time());
			}
		}
		return "Cập nhật film meta thành công";
	}
	else return "Éo Cập nhật dc film meta";
}
?>