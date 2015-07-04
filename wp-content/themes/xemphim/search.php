<?php get_header();?>
<div id="nav2"><div class="container"><h1 class="title"><?php echo get_cat_name($cat);?></h1></div></div>
</div></div>
<div id="body-wrap" class="container"><div id="content"><div class="block" id="page-list">
<div class="blocktitle breadcrumbs"><?php the_breadcrumb();?></div>
<div class="blockbody">
<ul class="list-film">
<?php while (have_posts()) : the_post(); ?>
	<li><div class="inner"><a href="<?php the_permalink();?>" title="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>"><img src="<?php img(146,195);?>" alt="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>" /></a><div class="info"><div class="name"><a href="<?php the_permalink();?>" title="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>"><?php the_title();?></a> <?php echo get_post_meta($post->ID, "phim_nsx", true);?></div><div class="name2"><?php echo get_post_meta($post->ID, "phim_en", true);?></div><div class="stats"><span class="liked"></span></div></div><div class="status"><?php echo get_post_meta($post->ID, "phim_tl", true);?></div></div>
	</li>
<?php endwhile;?>	
</ul><div>
<div class="blockbody">
<div id="cse" style="width: 100%;">
</div>
<!-- Put the following javascript before the closing </head> tag. -->
<script>
  (function() {
    var cx = '016181343022216312724:lqppfvm6w-k';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search>

</div>
<?php wp_pagenavi(); ?></div></div></div></div>
<?php get_sidebar();?>
<?php get_footer();?>