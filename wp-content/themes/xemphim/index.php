<?php get_header();?>
<div id="body-wrap" class="container">
<div id="content">
	<div id="movie-hot" class="viewport">
		<div class="prev">
		</div>
		<ul class="listfilm overview">
			<?php 
				$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
				query_posts('post_type=post&meta_key=phim_tinhtrang&meta_value=tophot&showposts=20&paged='.$page.'&order=desc');
				while (have_posts()) : the_post(); ?>
			<li>
				<a href="<?php the_permalink();?>" title="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>"><img alt="<?php the_title();?>" src="<? img(168,250);?>" title="<?php the_title();?>"/></a>
				<div class="overlay">
					<div class="name"><a href="<?php the_permalink();?>" title="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>"><?php the_title();?></a> <?php echo get_post_meta($post->ID, "nsx", true);?></div>
					<div class="name2"><?php echo get_post_meta($post->ID, "phim_en", true);?></div>
				</div>
				<div class="status"><?php echo get_post_meta($post->ID, "phim_hd", true);?></div>
			</li>
			<?php endwhile;wp_reset_query();?>
		</ul>
		<div class="next"></div>
	</div>
	<div class="divider"></div>
	<div class="block" id="movie-recommend">
		<div class="col1">
			<div class="blocktitle slide">
				<div class="icon movie1"></div>
				<h2 class="title">	Xem Phim HD Online </h2>
			</div>
			<div class="blockbody">
				<div style="display:block;height:250px;"></div>
			</div>
		</div>
		<div class="col2">
			<div class="blocktitle">
				<div class="tabs" data-target="#phim-bo-hay">
					<div class="tab active" data-name="phim-bo-moi">Phim &#273;&#7873; c&#7917;</div>
					<div class="tab" data-name="phim-bo-full">&#272;&atilde; ho&agrave;n th&agrave;nh</div>
				</div>
			</div>
			<div class="blockbody" id="phim-bo-hay">
				<ul class="list tab phim-bo-moi">
					<?php 
						$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
						query_posts('post_type=post&meta_key=phim_tinhtrang&meta_value=phimdecu&showposts=10&paged='.$page.'&order=desc');
						while (have_posts()) : the_post();
						?>
					<li><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a><span><?php echo get_post_meta($post->ID, "phim_tl", true);?></span></li>
					<?php endwhile;wp_reset_query();?>
				</ul>
				<ul class="list tab phim-bo-full hide">
					<?php 
						$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
						query_posts('post_type=post&meta_key=phim_tinhtrang&meta_value=hoanthanh&showposts=10&paged='.$page.'&order=desc');
						while (have_posts()) : the_post();
						?>
					<li><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a><span><?php echo get_post_meta($post->ID, "tl", true);?></span></li>
					<?php endwhile;wp_reset_query();?>
				</ul>
			</div>
		</div>
	</div>
	<div class="divider"></div>
	<div class="block" id="movie-update">
		<div class="blocktitle">
			<div class="icon movie1"></div>
			<h2 class="title">Phim m&#7899;i c&#7853;p nh&#7853;t</h2>
			<div class="types" data-target="#list-movie-update">
				<div class="type"><span data-name="toan-bo" class="btn active">Phim l&#7867;</span></div>
				<h3 class="type"><a data-name="phim-le" class="btn " href="phim-bo/" title="Phim l&#7867;">Phim b&#7897;</a></h3>
				<h3 class="type"><a data-name="phim-bo" class="btn" href="phim-xem-nhieu-nhat/" title="Xem nhi&#7873;u">Xem nhi&#7873;u</a></h3>
			</div>
		</div>
		<div class="blockbody" id="list-movie-update">
			<div class="tab toan-bo ddg">
				<ul class="list-film tab toan-bo">
					<?php echo get_film_home('phimle');?> 
				</ul>
				<div class="more"><a href="http://hayhay24.com/phim-le/" title="Xem Th&ecirc;m">&rsaquo;&rsaquo; Xem Th&ecirc;m </a></div>
			</div>
			<div class="tab phim-le hide">
				<ul class="list-film">
					<?php echo get_film_home('phimbo');?>
				</ul>
				<div class="more"><a href="http://hayhay24.com/phim-bo/" title="Xem Th&ecirc;m">&rsaquo;&rsaquo; Xem Th&ecirc;m </a></div>
			</div>
			<div class="tab phim-bo hide">
				<ul class="list-film">
					<?php echo get_most_viewed_2();?>
				</ul>
				<div class="more"><a href="http://hayhay24.com/phim-moi/" title="Xem Th&ecirc;m">&rsaquo;&rsaquo; Xem Th&ecirc;m </a></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">phim6789.Home.init();</script>           
</div>
<?php get_sidebar();?>
</div>
<?php get_footer();?>


