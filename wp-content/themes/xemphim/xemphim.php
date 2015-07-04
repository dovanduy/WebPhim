<?php
/*
Template Name: Xem phim
*/
?>
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

<script type="text/javascript">function setActive(){aObj=document.getElementById("servers").getElementsByTagName("a");for(i=0;i<aObj.length;i++)0<=document.location.href.indexOf(aObj[i].href)&&(aObj[i].className="active")}window.onload=setActive;</script>	
<div id="content"><div id="page-watch" data-film-id="<?php echo $post->ID;?>" data-user-id="<?php echo get_current_user_id();?>"><div class="blocktitle breadcrumbs block">
<?php the_breadcrumb();?>
</div>
<div class="blockbody">
<div id="movie" class="block">
<?php include('ads/top_player.php');?>

<div class="blockbody">
<div id="media">
<?php echo $idphim;?>
<?php echo getlinkphim($idphim,$idtap);?>
</div>
</div>
</div>
<div id="movie-info" class="block"><div class="blockbody"><div class="action"><div class="add-bookmark"><i></i>Thêm vào hộp </div><div class="like"><i></i><span>Like phim</span></div><div class="remove-ad">Tắt quảng cáo</div><div title="Chức năng tự động chuyển tập tiếp theo khi xem hết 1 tập" class="auto-next">AutoNext: On</div><div class="resize-player">Phóng to</div><div class="turn-light"><i></i><span>Tắt đèn</span></div></div>
<fb:like action="recommend" show_faces="false" layout="button_count" send="false" href="<?php the_permalink();?>"></fb:like>
<?php include('ads/below_player.php');?>
<div class="note">
<ul>
<li style="color:#4F6FC2">Mấy ngày nay cáp biển Việt Nam ra quốc tế bị đứt nên tốc độ truy cập internet bị chậm. Các bạn vui lòng đọc kỹ dòng này trước khi comment kêu ca chất lượng load phim. Theo thông báo thì khoảng 9-10 ngày nữa thì cáp biển sẽ được nối lại và phim sẽ load nhanh như bình thường. Mong các bạn đọc kỹ và thông cảm.</li><li>Chức năng lưu tập phim đang xem chỉ có tác dụng trên 1 máy tính</li><li>Nếu bạn không xem được phim vui lòng nhấn <strong>Ctrl + F5</strong> vài lần. Hoặc chuyển sang Server khác để xem.</li><li>Xem phim tốt nhất trên trình duyệt Chrome và Firefox, nhấn <a rel="nofollow" href="http://www.mozilla.com/vi/">vào đây</a> để tải Firefox miễn phí</li><li>Nếu xem phim gặp lỗi gì vui lòng sử dụng chức năng bình luận tại [Phim 3s] ở bên dưới để ghi lại lỗi mà bạn gặp phải</li>
</ul>
</div>
<?php echo episode_show($idphim);?>
</div></div>
<div id="comment" class="block"><div class="blocktitle"><div class="title">Bình luận</div><div data-target="#comment .tabs-content" class="tabs"><div data-name="fb-comment" class="tab active">Facebook</div><div data-name="phim6789-comment" class="tab phim6789">hayhay24</div></div></div><div class="blockbody tabs-content"><div class="tab fb-comment"><div class="fb-comments" data-href="<?php the_permalink();?>" data-num-posts="20" data-width="660"></div></div><div class="tab phim6789-comment hide"><div class="comment-form"><div class="avatar"></div><form action="#" method="post"><div><textarea rows="1" cols="10" class="message" maxlength="500" data-minlength="10" style="resize: none; overflow: hidden; word-wrap: break-word; height: 45px;"></textarea></div><div><button class="submit" type="button">Bình luận</button><p class="warn">Bạn còn lại <span class="counter">500</span> ký tự</p></div></form></div><div class="comment-list"></div></div></div></div>
<div class="fb-like-box" data-href="https://www.facebook.com/pages/Phim6789Com/543956162308758?fref=ts" data-width="660" data-height="258" data-show-faces="true" data-stream="false" data-header="false"></div>
<div class="block" id="page-info" data-film-id="<?php echo $post->ID;?>">
<div class="blocktitle">
<div class="title">Phim liên quan</div>
<div class="blockbody">
    <?php
		$categories = get_the_category($post->ID);
		if ($categories) {
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
		
			$args=array(
				'category__in' => $category_ids,
				'post__not_in' => array($post->ID),
				'showposts'=>8, // Number of related posts that will be shown.
				'caller_get_posts'=>1
			);
		$my_query = new wp_query($args);
		if( $my_query->have_posts() ) {
			echo '<ul class="list-film">';
			while ($my_query->have_posts()) {
				$my_query->the_post();
			?>

	<li><div class="inner"><a title="<?php the_title();?>" href="<?php the_permalink();?>"><img alt="<?php the_title();?>" src="<?php img(146,195);?>"></a><div class="info"><div class="name"><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></div><div class="name2"><?php echo get_post_meta($post->ID, "phim_en", true);?></div><div class="stats"><span class="liked"></span></div></div><div class="status"><?php echo get_post_meta($post->ID, "phim_tl", true);?></div></div>
	</li>
			<?php
			}
			echo '</ul>';
			wp_reset_query();
		}
	}
	?>
<div>
</div></div>
</div>
<div class="blockbody">
<div class="detail">
<div class="blocktitle">
<div class="tabs" data-target="#info-film">
<div class="tab active" data-name="text">Thông tin phim</div>
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
					var s1 = new SWFObject('http://phim6789.com/player.swf','jwplayer','671','350','9','#000000');
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

</div>
<div style="display:none">
       <span class="geo">
        <span class="latitude">
           <span class="value-title" title="16.031425"></span>
        </span>
        <span class="longitude">
           <span class="value-title" title="108.228307"></span>
        </span>
     </span>
 </span>
</div>
</div>

<script type="text/javascript">phim6789.Watch.init('<?php echo $post->ID;?>');</script>
</div></div>

</div></div></div>

<?php endwhile;endif;?>
			<?php get_sidebar();?>
<?php get_footer();?>