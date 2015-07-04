<?php
/*
  Template Name: Search Phim
 */
?>
<?php get_header(); ?>
<?php
$categories = get_the_category();
$seperator = ", ";
$output = '';
if ($categories) {
    foreach ($categories as $category) {
        $output .= '<a href="' . get_category_link($category->term_id) . '" title="' . esc_attr(sprintf(__("View all posts in %s"), $category->name)) . '">' . $category->cat_name . '</a>' . $seperator;
    }
}
$idtap = get_query_var('ep');
$idphim = get_the_ID();
$permalink = get_permalink($idphim);
?>
<div id="nav2"><div class="container"><h1 class="title"><?php echo get_cat_name($cat); ?></h1></div></div>
<div class="ad_location above_of_content container">
    <div id='div-gpt-ad-1349761813673-0' style='width:980px; height:120px;'>
        <script type='text/javascript'>
            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1349761813673-0'); });
        </script>
    </div>
</div>
<div id="body-wrap" class="container">
            <div id="content"><div class="block" id="page-info" data-film-id="<?php echo $post->ID; ?>"><div class="blocktitle breadcrumbs"><?php the_breadcrumb(); ?></div>
                <div class="blockbody">    
                    <div id="cse" style="width: 100%;">    
                    </div>    
                    <!-- Put the following javascript before the closing </head> tag. -->    
                    <script>    
                        (function() {    
                            var cx = '016181343022216312724:lqppfvm6w-k';    
                            var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;    
                            gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +    
                                '//www.google.com/cse/cse.js?cx=' + cx;    
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);    
                        })();    
                    </script>    
    
                    <!-- Place this tag where you want both of the search box and the search results to render -->    
                    <gcse:search></gcse:search>    
    
                </div>    
            </div>    
    
        </div>    
    <?php get_sidebar(); ?>
    <?php get_footer(); ?>