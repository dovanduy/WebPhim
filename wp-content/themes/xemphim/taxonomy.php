<?php include('header-tag.php');?>
<div id="nav2"><div class="container"><h2 class="title"><?php printf( __( '%s', 'phim' ), '<span>' . single_tag_title( '', false ) . '</span>' );?></h2></div></div>
<div class="ad_location above_of_content container">
<div id='div-gpt-ad-1349761813673-0' style='width:980px; height:120px;'>
</div></div>
<div id="body-wrap" class="container"><div id="content"><div class="block" id="page-list">
<div class="blocktitle breadcrumbs"><?php the_breadcrumb();?><div class="item" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope=""><?php printf( __( '%s', 'phim' ), '<span>' . single_tag_title( '', false ) . '</span>' );?></div></div>
<div class="blockbody">
<ul class="list-film">
<?php while (have_posts()) : the_post(); ?>
	<li><div class="inner"><a href="<?php the_permalink();?>" title="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>"><img src="<?php img(146,195);?>" alt="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>" /></a><div class="info"><div class="name"><h3><a href="<?php the_permalink();?>" title="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>"><?php the_title();?></a> </h3><?php echo get_post_meta($post->ID, "phim_nsx", true);?></div><div class="name2"><?php echo get_post_meta($post->ID, "phim_en", true);?></div><div class="stats"><span class="liked"></span></div></div><div class="status"><?php echo get_post_meta($post->ID, "phim_tl", true);?></div></div>
	</li>
<?php endwhile;?>	
</ul><div>
<?php wp_pagenavi(); ?></div></div></div></div>
<?php get_sidebar();?>
<?php get_footer();?>