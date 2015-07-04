<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'phim_';

global $meta_boxes;

$meta_boxes = array();

// 1st meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'personal',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => 'Thông tin phim',

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'post', 'slider' ),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'normal',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'high',

	// List of meta fields
	'fields' => array(
		// TEXT
		array(
			'name' => 'Tên phim (EN)',
			'id'   => "{$prefix}en",
			'type' => 'text',
			// Date format, default yy-mm-dd. Optional. See: http://goo.gl/po8vf
			'std' => 'Đang cập nhật',
		),
		array(
			'name' => 'Chất lượng',
			'id'   => "{$prefix}hd",
			'type' => 'text',
			// Date format, default yy-mm-dd. Optional. See: http://goo.gl/po8vf
			'std' => 'Đang cập nhật',
		),
		array(
			'name' => 'Năm sản xuất',
			'id'   => "{$prefix}nsx",
			'type' => 'text',
			// Date format, default yy-mm-dd. Optional. See: http://goo.gl/po8vf
			'std' => 'Đang cập nhật',
		),
		// RADIO BUTTONS
		array(
			'name' => 'Thời lượng',
			'id'   => "{$prefix}tl",
			'type' => 'text',
			// Array of 'value' => 'Label' pairs for radio options.
			// Note: the 'key' is stored in meta field, not the 'value'
			
			'std'  => 'Phút',
			
		),
		array(
			'name' => 'Tình trạng phim',
			'id'   => "{$prefix}tinhtrang",
			'type' => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options' => array('' => 'Tình trạng','phimdecu' => 'Đề cử','hoanthanh' => 'Hoàn thành','tophot'=>'hot'
			),
			// Select multiple values, optional. Default is false.
			
			// Default value, can be string (single value) or array (for both single and multiple values)
						
		),
		array(
			'name' => 'Quốc gia',
			'id'   => "{$prefix}qg",
			'type' => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options' => array('Việt Nam' => 'Việt Nam','Trung Quốc' => 'Trung Quốc','Hàn Quốc' => 'Hàn Quốc','Nhật Bản' => 'Nhật Bản','Mỹ' => 'Mỹ','Thái Lan' => 'Thái Lan','Đài Loan' => 'Đài Loan','Khác' => 'Khác'
			),
			// Select multiple values, optional. Default is false.
			
			// Default value, can be string (single value) or array (for both single and multiple values)
			
			'desc' => 'Tên quốc gia',
		),		
		array(
			'name' => 'Phim chiếu rạp',
			'id'   => "{$prefix}phim_rap",
			'type' => 'checkbox',
			'desc' => 'Phim chiếu rạp',
			// Value can be 0 or 1
			
		),
		array(
			'name' => 'Thể loại',
			'id'   => "{$prefix}loai",
			'type' => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options' => array('phimle' => 'Phim lẻ','phimbo' => 'Phim Bộ'
			),
			// Select multiple values, optional. Default is false.
			
			// Default value, can be string (single value) or array (for both single and multiple values)
			
			'desc' => 'Tên quốc gia',
		),
		array(
			'name' => 'WYSIWYG / Rich Text Editor',
			'id'   => "{$prefix}download",
			'type' => 'wysiwyg',

			// Editor settings, see wp_editor() function: look4wp.com/wp_editor
			'options' => array(
				'textarea_rows' => 4,
				'teeny'         => true,
				'media_buttons' => false,
			),
		),
		array(
			'name' => 'Trailer',
			'id'   => "{$prefix}trailer",
			'type' => 'text',
			// Date format, default yy-mm-dd. Optional. See: http://goo.gl/po8vf
			
		),
		array(
			'name' => 'Sub viet',
			'id'   => "{$prefix}sub",
			'type' => 'text',
			'desc' => 'Dien link chua subphim',			
			// Date format, default yy-mm-dd. Optional. See: http://goo.gl/po8vf
			
		),
		array(
			'name' => 'Thumbnail',
			'id'   => "Image",
			'type' => 'text',
			// Date format, default yy-mm-dd. Optional. See: http://goo.gl/po8vf
			
		),
		
	),
	
);




/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function YOUR_PREFIX_register_meta_boxes()
{
	global $meta_boxes;

	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'YOUR_PREFIX_register_meta_boxes' );