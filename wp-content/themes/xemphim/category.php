<?php get_header();?>
<div id="nav2"><div class="container"><h2 class="title"> <?php echo get_cat_name($cat);?></h2></div></div>

<div id="body-wrap" class="container"><div id="content"><div class="block" id="page-list">
<div class="blocktitle breadcrumbs"><?php the_breadcrumb();?><?php echo get_cat_name($cat);?></div>

<div class="blockbody">
<ul class="list-film">
<?php 
while (have_posts()) : the_post(); 

	$html.='<li><div class="inner"><a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.img3(146,195).'" alt="'.get_the_title().'"/></a><div class="info"><div class="name"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a> </div><div class="name2"><h4>'.get_post_meta($post->ID, "phim_en", true).'</h4></div><div class="stats"><span class="liked">'.get_total_rating($post->ID,"like").' Like</span></div></div><div class="status">'.get_post_meta($post->ID, "phim_hd", true).'</div></div></li>';
endwhile;wp_reset_query();
echo $html;
?>	
</ul><div>
<?php wp_pagenavi(); ?></div></div>
</div></div>
<?php get_sidebar();?>
<?php get_footer();?>