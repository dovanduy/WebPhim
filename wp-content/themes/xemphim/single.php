<?php get_header();?>
<?php		
$u = $wpdb->query("UPDATE ".DATA_FILM_META." 
							SET  
								film_viewed = film_viewed + 1 
								, film_viewed_d = film_viewed_d + 1 
								, film_viewed_w = film_viewed_w + 1 
								, film_viewed_m = film_viewed_m + 1 
							WHERE film_id = '".$post->ID."'");
?>		
<?php

$categories = get_the_category();
$seperator = ", ";
$output = '';
if($categories){
	foreach($categories as $category) {
		$output .= '<a href="'.get_category_link($category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$seperator;
	}

}
$idtap = get_query_var('ep');
$sv = get_query_var('sv');
$idphim=get_the_ID();
$permalink = get_permalink( $idphim );
?>
<div id="nav2"><div class="container"><h2 class="title"><?php the_title();?></h2></div></div>
<div id="body-wrap" class="container">
<?php if (have_posts()) : while (have_posts()) : the_post();?>
<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
<?php if($idtap=="" && $sv=="") { ?>
<?php include('phim-thongtin.php');?>
<?php } ?>
<?php if($idtap!="" || $sv!="") { ?>
<?php include('phim-play.php');?>
<?php } ?>

<?php endwhile;endif;?>
			<?php get_sidebar();?>
<?php get_footer();?>