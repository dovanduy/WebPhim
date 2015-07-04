<?php get_header();?>
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
$idphim=get_the_ID();
$permalink = get_permalink( $idphim );
?>
<div id="nav2"><div class="container"><h1 class="title"><?php the_title();?></h1></div></div>
<div class="ad_location above_of_content container">
<div id='div-gpt-ad-1349761813673-0' style='width:980px; height:120px;'>
<script type='text/javascript'>
googletag.cmd.push(function() { googletag.display('div-gpt-ad-1349761813673-0'); });
</script>
</div></div>
<div id="body-wrap" class="container">
<?php if (have_posts()) : while (have_posts()) : the_post();?>
<div id="content"><div class="block" id="page-info" data-film-id="<?php echo $post->ID;?>"><div class="blocktitle breadcrumbs"><?php the_breadcrumb();?></div>
<div class="blockbody">
<?php if($idtap=="") { ?>
<?php include('phim-thongtin.php');?>
<?php } ?>
<?php if($idtap!="") { ?>
<?php include('phim-play.php');?>
<?php } ?>
<div class="detail">
<div class="blocktitle">
<div class="tabs" data-target="#info-film">
<div class="tab active" data-name="text">Movies Info</div>
<div class="tab" data-name="trailer">Trailer</div>
</div>
</div>
<div class="tabs-content" id="info-film">
<div class="tab text">
<?php the_content();?>
</div>
<div class="tab trailer hide">
		<?php if ( get_post_meta($post->ID, 'phim_trailer', true) ) : ?>
        <div id="mediaplayer"></div>
				<script type="text/javascript">
					var s1 = new SWFObject('http://player.v1vn.com/player.swf','jwplayer','671','350','9','#000000');
					s1.addParam("allowfullscreen","true");
					s1.addParam("allowscriptaccess","always");
					s1.addParam("wmode","transparent");
					s1.addParam("flashvars","plugins=http://media.adnetwork.vn/flash/jwplayer/ova-jw.swf&config=http://delivery.adnetwork.vn/247/ovavideoad/wid_1290833222/zid_1332924631/&file=<?php echo get_post_meta($post->ID, "phim_trailer", true);?>&autostart=false");
					s1.write("mediaplayer");
				</script>
		<?php else : ?>
		TẬP PHIM NÀY CHƯA CẬP NHẬT TRAILER
		<?php endif;?>
</div>
</div>
<div class="tags">
 <?php the_tags('<span class="label">Tags: </span>', ', ', ''); ?> 

</div>
<div style="display:none">

<span class="item vcard" >
  <h3 class="fn">Nuyễn Thanh Ánh</h3>
  <span class="adr">
   <span class="street-address">#</span>
   <span><span class="locality">Nghệ An</span>, <span class="region">Việt Nam</span></span>
   <span class="tel">0972 517 343</span>
   <a href="http://phim6789.com/" class="url">Thiết kế web Phim6789.Com</a> 
  </span>
       <span class="geo">
        <span class="latitude">
           <span class="value-title" title="16.031425"></span>
        </span>
        <span class="longitude">
           <span class="value-title" title="108.228307"></span>
        </span>
     </span>
 </span>
 <a href="http://plus.google.com/107935470498853487124?​r​e​l​=​a​u​thor">Xúi Minh Cường</a> <a href="http://plus.google.com/107935470498853487124" rel="publisher">Tìm chúng tôi trên Google+</a>
</div>
</div>
</div>
</div>

</div>
<?php endwhile;endif;?>
			<?php get_sidebar();?>
<?php get_footer();?>