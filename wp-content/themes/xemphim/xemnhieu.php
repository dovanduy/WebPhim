<?php
/*
Template Name: Xem nhiều
*/
?>
<?php get_header();?>
<div id="nav2"><div class="container"><h1 class="title"><?php the_title();?></h1></div></div>

<div id="body-wrap" class="container"><div id="content"><div class="block" id="page-list">
<div class="blocktitle breadcrumbs"><?php the_breadcrumb();?></div>
<div class="blockbody">
<ul class="list-film">
<?php 
			$where = "post_type = 'post'";
		$most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT 0,20");

	if($most_viewed) { 
		
			 foreach ($most_viewed as $post) { 
				$post_views = number_format_i18n(intval($post->views));
				$post_url = get_permalink($post->ID);
				$post_title = get_the_title();

				
			
					$html .= '<li><div class="inner"><a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.img3(146,195).'" alt="'.get_the_title().'"/></a><div class="info"><div class="name"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a> </div><div class="name2">'.get_post_meta($post->ID, "phim_en", true).'</div><div class="stats"></div></div><div class="status">'.get_post_meta($post->ID, "phim_hd", true).'</div></div></li>';
			 } 
		
		 }
echo $html;		 
	?>	
</ul><div>
<?php wp_pagenavi(); ?></div></div></div></div>
<?php get_sidebar();?>
<?php get_footer();?>