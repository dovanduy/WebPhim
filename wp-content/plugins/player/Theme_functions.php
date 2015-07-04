<?php
if(function_exists('current_user_can')){
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
} else {
	die('Access Denied');
}
function add_theme(){
	global $wpdb;
    $row=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE `default`=1");
	html_add_theme($row);
}

function show_theme(){
global $wpdb;
	$sort["default_style"]="manage-column column-autor sortable desc";
	$sort["custom_style"]='manage-column column-autor sortable desc';
	$sort["1_or_2"]=1;
	$where='';
	$order='';
	$search_tag='';
	$sort["sortid_by"]='title';
	if(isset($_POST['page_number']))
	{
			
			if($_POST['asc_or_desc'])
			{
				$sort["sortid_by"]=$_POST['order_by'];
				if($_POST['asc_or_desc']==1)
				{
					$sort["custom_style"]="manage-column column-title sorted asc";
					$sort["1_or_2"]="2";
					$order="ORDER BY ".$wpdb->escape($sort["sortid_by"])." ASC";
				}
				else
				{
					$sort["custom_style"]="manage-column column-title sorted desc";
					$sort["1_or_2"]="1";
					$order="ORDER BY ".$wpdb->escape($sort["sortid_by"])." DESC";
				}
			}
			
	if($_POST['page_number'])
		{
			$limit=($_POST['page_number']-1)*20; 
		}
		else
		{
			$limit=0;
		}
	}
	else
		{
			$limit=0;
		}
	if(isset($_POST['search_events_by_title'])){
		$search_tag=$_POST['search_events_by_title'];
		}
		
		else
		{
		$search_tag="";
		}
	if ( $search_tag ) {
		$where= ' WHERE title LIKE "%'.$wpdb->escape($search_tag).'%"';
	}
	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."Spider_Video_Player_theme". $where;
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);
	html_show_theme($rows, $pageNav, $sort);
}

function save_theme(){
	 global $wpdb;
	 $save_or_no= $wpdb->insert($wpdb->prefix.'Spider_Video_Player_theme', array(
		'id'	           => NULL,
        'title'            => esc_html(stripslashes($_POST["title"])),
        'appWidth'         => esc_html($_POST["appWidth"]),
		'appHeight'        => esc_html($_POST["appHeight"]),
		'playlistWidth'    => esc_html($_POST["playlistWidth"]),
		'startWithLib'     => $_POST["startWithLib"],
		'show_trackid'     => $_POST["show_trackid"],
		'autoPlay'         => $_POST["autoPlay"],		
		'autoNext'         => $_POST["autoNext"],
		'autoNextAlbum'    => $_POST["autoNextAlbum"],
		'defaultVol'       => $_POST["defaultVol"],
		'defaultRepeat'    => $_POST["defaultRepeat"],
		'defaultShuffle'   => $_POST["defaultShuffle"],
		'autohideTime'     => esc_html($_POST["autohideTime"]),
		'centerBtnAlpha'   => $_POST["centerBtnAlpha"],
		'loadinAnimType'   => $_POST["loadinAnimType"],
		'keepAspectRatio'  => $_POST["keepAspectRatio"],
		'clickOnVid'       => $_POST["clickOnVid"],
		'spaceOnVid'       => $_POST["spaceOnVid"],
		'mouseWheel'       => $_POST["mouseWheel"],
		'ctrlsPos'         => $_POST["ctrlsPos"],
		'ctrlsStack'       => $_POST["ctrlsStack"],
		'ctrlsOverVid'     => $_POST["ctrlsOverVid"],
		'ctrlsSlideOut'    => $_POST["ctrlsSlideOut"],
		'watermarkUrl'     => esc_html($_POST["watermarkUrl"]),
		'watermarkPos'     => $_POST["watermarkPos"],
		'watermarkSize'    => esc_html($_POST["watermarkSize"]),
		'watermarkSpacing' => esc_html($_POST["watermarkSpacing"]),
		'watermarkAlpha'   => $_POST["watermarkAlpha"],
		'playlistPos'      => $_POST["playlistPos"],
		'playlistOverVid'  => $_POST["playlistOverVid"],
		'openPlaylistAtStart' => $_POST["openPlaylistAtStart"],
		'playlistAutoHide' => $_POST["playlistAutoHide"],
		'playlistTextSize' => esc_html($_POST["playlistTextSize"]),
		'libCols'          => esc_html($_POST["libCols"]),
		'libRows'          => esc_html($_POST["libRows"]),
		'libListTextSize'  => esc_html($_POST["libListTextSize"]),
		'libDetailsTextSize'  => esc_html($_POST["libDetailsTextSize"]),
		'appBgColor'       => $_POST["appBgColor"],
		'vidBgColor'       => $_POST["vidBgColor"],
		'framesBgColor'    => $_POST["framesBgColor"],
		'ctrlsMainColor'   => $_POST["ctrlsMainColor"],
		'ctrlsMainHoverColor' => $_POST["ctrlsMainHoverColor"],
		'slideColor'       => $_POST["slideColor"],
		'itemBgHoverColor' => $_POST["itemBgHoverColor"],
		'itemBgSelectedColor' => $_POST["itemBgSelectedColor"],
		'textColor'        => $_POST["textColor"],
		'textHoverColor'   => $_POST["textHoverColor"],
		'textSelectedColor'   => $_POST["textSelectedColor"],
		'framesBgAlpha'    => $_POST["framesBgAlpha"],
		'ctrlsMainAlpha'   => $_POST["ctrlsMainAlpha"],
		'itemBgAlpha'      => $_POST["framesBgAlpha"],
                ),
				array(
				'%d',
				'%s',
				'%d',
				'%d',	
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%s',
				'%s',	
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',	
				'%d',
				'%s',
				'%d',
				'%d',
				'%s',
				'%d',
				'%d',	
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',	
				'%d',
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',	
				'%d',
				'%d',
				'%d'				
				)
                );
					if(!$save_or_no)
	{
		?>
	<div class="updated"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
	
}
function apply_theme($id){
	 global $wpdb;
	 $save_or_no= $wpdb->update($wpdb->prefix.'Spider_Video_Player_theme', array(
        'title'              => esc_html(stripslashes($_POST["title"])),
        'appWidth'           => esc_html($_POST["appWidth"]),
		'appHeight'          => esc_html($_POST["appHeight"]),
		'playlistWidth'      => esc_html($_POST["playlistWidth"]),
		'startWithLib'       => $_POST["startWithLib"],
		'show_trackid'       => $_POST["show_trackid"],
		'autoPlay'           => $_POST["autoPlay"],
		'autoNext'           => $_POST["autoNext"],
		'autoNextAlbum'      => $_POST["autoNextAlbum"],
		'defaultVol'         => $_POST["defaultVol"],
		'defaultRepeat'      => $_POST["defaultRepeat"],
		'defaultShuffle'     => $_POST["defaultShuffle"],
		'autohideTime'       => esc_html($_POST["autohideTime"]),
		'centerBtnAlpha'     => $_POST["centerBtnAlpha"],
		'loadinAnimType'     => $_POST["loadinAnimType"],
		'keepAspectRatio'    => $_POST["keepAspectRatio"],
		'clickOnVid'         => $_POST["clickOnVid"],
		'spaceOnVid'         => $_POST["spaceOnVid"],
		'mouseWheel'         => $_POST["mouseWheel"],
		'ctrlsPos'           => $_POST["ctrlsPos"],
		'ctrlsStack'         => $_POST["ctrlsStack"],
		'ctrlsOverVid'       => $_POST["ctrlsOverVid"],
		'ctrlsSlideOut'      => $_POST["ctrlsSlideOut"],
		'watermarkUrl'       => esc_html($_POST["watermarkUrl"]),
		'watermarkPos'       => $_POST["watermarkPos"],
		'watermarkSize'      => esc_html($_POST["watermarkSize"]),
		'watermarkSpacing'   => esc_html($_POST["watermarkSpacing"]),
		'watermarkAlpha'     => $_POST["watermarkAlpha"],
		'playlistPos'        => $_POST["playlistPos"],
		'playlistOverVid'    => $_POST["playlistOverVid"],
		'openPlaylistAtStart' => $_POST["openPlaylistAtStart"],
		'playlistAutoHide'   => $_POST["playlistAutoHide"],
		'playlistTextSize'   => esc_html($_POST["playlistTextSize"]),
		'libCols'            => esc_html($_POST["libCols"]),
		'libRows'            => esc_html($_POST["libRows"]),
		'libListTextSize'    => esc_html($_POST["libListTextSize"]),
		'libDetailsTextSize' => esc_html($_POST["libDetailsTextSize"]),
		'appBgColor'         => $_POST["appBgColor"],
		'vidBgColor'         => $_POST["vidBgColor"],
		'framesBgColor'      => $_POST["framesBgColor"],
		'ctrlsMainColor'     => $_POST["ctrlsMainColor"],
		'ctrlsMainHoverColor' => $_POST["ctrlsMainHoverColor"],
		'slideColor'         => $_POST["slideColor"],
		'itemBgHoverColor'   => $_POST["itemBgHoverColor"],
		'itemBgSelectedColor' => $_POST["itemBgSelectedColor"],
		'textColor'          => $_POST["textColor"],
		'textHoverColor'     => $_POST["textHoverColor"],
		'textSelectedColor'  => $_POST["textSelectedColor"],
		'framesBgAlpha'      => $_POST["framesBgAlpha"],
		'ctrlsMainAlpha'     => $_POST["ctrlsMainAlpha"],
		'itemBgAlpha'        => $_POST["framesBgAlpha"],
                ),
				 array('id'=>$id),
				array(
				'%s',
				'%d',
				'%d',	
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%s',
				'%s',	
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',	
				'%d',
				'%s',
				'%d',
				'%d',
				'%s',
				'%d',
				'%d',	
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',	
				'%d',
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',	
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',	
				'%d',
				'%d',
				'%d'				
				)
                );
				?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
	
}

function edit_theme($id){
	global $wpdb;
	if(!$id)
	{
		$id=$wpdb->get_var("SELECT MAX( id ) FROM ".$wpdb->prefix."Spider_Video_Player_theme");
	}
	
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE `id`=%d",$id);
	
	$row =$wpdb->get_row($query);
	// load the row from the db table
	
	// display function 
		html_edit_theme($row, $id);
}

function remove_theme($id){
   global $wpdb;
   if($wpdb->get_var($wpdb->prepare("SELECT `default` FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE id=%d",$id)))
   {
	   ?>
	 <div class="updated"><p><strong><?php _e("You can't delete default theme"); ?></strong></p></div>
     <?php
	 return;
   }
 $sql_remov_tag=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE id=%d",$id);
 if(!$wpdb->query($sql_remov_tag))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Video Player Theme Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
 <?php
 }
}

function default_theme($id){
	global $wpdb;
	$ids_for=$wpdb->get_col("SELECT id FROM ".$wpdb->prefix."Spider_Video_Player_theme WHERE `default`=1");
	for($i=0;$i<count($ids_for);$i++)
	{
		 $savedd=$wpdb->update($wpdb->prefix.'Spider_Video_Player_theme', array(
			'default'    =>0,
              ), 
              array('id'=>$ids_for[$i]),
			  array(  '%d' )
			  );
	}
	$savedd=$wpdb->update($wpdb->prefix.'Spider_Video_Player_theme', array(
			'default'    =>1,
              ), 
              array('id'=>$id),
			  array(  '%d' )
			  );
}
?>