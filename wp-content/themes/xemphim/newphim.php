<?php
/*
Template Name: New phim
*/
?><?php get_header();?>
<div id="nav2"><div class="container"><h2 class="title"><?php the_title();?></h2></div></div>
<div id="body-wrap" class="container"><div id="content"><div class="block" id="page-list">
<div class="blocktitle breadcrumbs"><?php the_breadcrumb();?></div>
<div class="blockbody"><ul class="list-film"><?php
$page = (get_query_var('paged')) ? get_query_var('paged') : 1;	query_posts('post_type=post&showposts=40&paged='.$page.'&order=desc');	while (have_posts()) : the_post();?><li><div class="inner"><a href="<?php the_permalink();?>" title="<?php the_title();?>"><img src="<?php echo img3(146,195);?>" alt="<?php the_title();?>"/></a><div class="info"><div class="name"><h3><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></h3> </div><div class="name2"><h4><?php echo get_post_meta($post->ID, "phim_en", true);?></h4></div><div class="stats"><span class="liked"><?php echo get_total_rating($post->ID,"like");?> Like</span></div></div><div class="status"><?php echo get_post_meta($post->ID, "phim_hd", true);?></div></div></li><?php endwhile;?></ul><div><?php wp_pagenavi(); ?>
</div></div></div></div>
<?php get_sidebar();?>
<?php get_footer();?>