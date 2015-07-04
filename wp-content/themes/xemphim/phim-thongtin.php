<?php 
$taxonomy = 'dien-vien';
  $terms = get_the_terms( $post->ID, $taxonomy );
  if ($terms) {
    foreach($terms as $term) {
        $dienvien.= '<span itemprop="actor" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><a href="' . esc_attr(get_term_link($term, $taxonomy)) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" itemprop="url">' . $term->name.'</a></span></span> , ';
    }
  }
$daodien = 'dao-dien';
  $terms = get_the_terms( $post->ID, $daodien );
  if ($terms) {
    foreach($terms as $term) {
        $dd.= '<span itemprop="director" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><a href="' . esc_attr(get_term_link($term, $daodien)) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" itemprop="url">' . $term->name.'</a></span></span> , ';
    }
  }  
?>
<div class="ad_location above_of_content container"></div>
<div id="content"><div class="block" id="page-info" data-film-id="<?php echo $post->ID;?>"><div class="blocktitle breadcrumbs block">
<?php the_breadcrumb();?>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <link type="text/css"
        href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" rel="stylesheet" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_directory');?>/jquery.youtubepopup.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $("a.youtube").YouTubePopup({ hideTitleBar: true });
        });
    </script>

<div class="blockbody">
<div class="info hrecipe">
<div class="poster">
<img src="<?php img(300,400);?>" alt="<?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?>" class="photo">
<div class="like-stats hreview-aggregate"><span class="rating"><span class="average">10</span>/<span class="best">10</span></span><span class="like-icon"></span>Lượt thích: <span class="votes count"><?php if(function_exists('the_views')) { the_views(); } ?></span>

</div>
</div>
<div class="col2">
<h2 class="title item"><span class="fn"><a href="<?php echo xemphim($idphim);?>" title="xem phim <?php the_title();?>"><?php the_title();?> - <?php echo get_post_meta($post->ID, "phim_en", true);?></a></span></h2>
<dl>
<dt>Status:</dt>
<dd class="red"><?php echo get_post_meta($post->ID, "phim_hd", true);?></dd>    
<div itemscope itemtype="http://schema.org/Movie"><dt>Đạo diễn:</dt>
<dd><?php if($dd) echo $dd;else echo"updating";?></dd>
<dt>Diễn viên:</dt>
<dd><?php if($dienvien)echo $dienvien;else echo"updating";?></dd></div>
<dt>Thể loại:</dt>
<dd><?php echo trim($output, $seperator);?>
</dd>
<dt>Quốc gia:</dt><dd>
<a href="<?php bloginfo('siteurl');?>/<?php echo strtolower(str_replace(" ","-",get_ascii(get_post_meta($post->ID, "phim_qg", true))));?>" title="Phim <?php echo get_post_meta($post->ID, "phim_qg", true);?>"><?php echo get_post_meta($post->ID, "phim_qg", true);?></a></dd>
<dt>Thời lượng:</dt>
<dd><?php echo get_post_meta($post->ID, "phim_tl", true);?></dd>
<dt>Năm phát hành:</dt>
<dd><?php if(get_post_meta($post->ID, "phim_nsx", true)) echo get_post_meta($post->ID, "phim_nsx", true);else echo "2012";?> </dd>
<dt>Lượt xem:</dt>
<dd><?php if(function_exists('the_views')) { the_views(); } ?></dd>
<dt>Người đăng:</dt>
<dd><a href="https://plus.google.com/107935470498853487124?rel=author"><?php the_author();?></a> </dd>
<!-- AddThis Button BEGIN -->
<dt><div class="addthis_toolbox addthis_default_style" style="margin-top:10px;margin-bottom:10px;">
<a class="addthis_button_facebook_like">FB</a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5112753846d6d791"></script>
<!-- AddThis Button END --></dt>
</dl>
<?php $trailer=str_replace('http://www.youtube.com/watch?v=',"", get_post_meta($post->ID, "phim_trailer", true));?>
<div class="btn-groups"><h3>
<a href="<?php echo xemphim($idphim);?>" class="btn-watch" title="xem phim <?php the_title();?>"></a></h3> </div>
</div>
</div>
<div class="detail">
<div class="blocktitle">
<div class="tabs" data-target="#info-film">
<div class="tab active" data-name="text">Thông tin phim</div>
<div class="tab" data-name="trailer"><a class="youtube" href="#" style="color:#fff;" rel="<?php echo $trailer;?>" title="Trailer <?php the_title();?>">Trailer</a></div>
</div>
</div>
<div class="tabs-content" id="info-film">
<div class="tab text">
<?php the_content();?>
<em>Chúc các bạn <a href="<?php the_permalink();?>">xem phim <strong><span style="color: #ff0000;"><?php the_title();?></span></strong></a> vui vẻ.</em>
</div>

</div>
<div class="tags">

</div>
</div>


</div></div></div>