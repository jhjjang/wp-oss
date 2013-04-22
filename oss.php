<?
/**
* Plugin Name: OSS Plugin
* Description: OSS Issue 개발 플러그인
* Author:	Jung Ho, Jang
*/

global $wpdb;
$plugin_dir = basename(dirname(__FILE__));

class OSS{

	function __construct(){
		add_action('init', array($this,'oss_product_register'));
		add_action('init', array($this,'oss_company_register'));
		add_action("admin_init", array($this,"admin_init"));
		add_action('save_post', array($this,'save_details'));
		add_action('post_edit_form_tag', array($this,'post_edit_form_tag'));


		//add_action("manage_posts_custom_column",  array($this,"oss_product_custom_columns"));
		add_action("manage_oss_product_posts_custom_column",  array($this,"oss_product_custom_columns"));
		add_filter("manage_edit-oss_product_columns", array($this,"oss_product_edit_columns"));

		//add_action("manage_posts_custom_column",  array($this,"oss_service_custom_columns"));
		add_action("manage_oss_service_posts_custom_column",  array($this,"oss_service_custom_columns"));
		add_filter("manage_edit-oss_service_columns", array($this,"oss_service_edit_columns"));

		add_action( 'init', array($this,'product_taxonomy'), 0 );
		add_action( 'init', array($this,'service_taxonomy'), 0 );

		add_shortcode('oss_twitt',array($this,'searchTwitter'));
		add_shortcode('oss_search',array($this,'searchGoogle'));
	}

	function post_edit_form_tag() {
		echo ' enctype="multipart/form-data"';
	}

	function oss_product_register() {
		$labels = array(
			'name' => _x('OSS Directory', 'post type general name'),
			'singular_name' => _x('Oss Product Item', 'post type singular name'),
			'add_new' => _x('Add New', 'Oss Product item'),
			'add_new_item' => __('Add New Oss Product Item'),
			'edit_item' => __('Edit Oss Product Item'),
			'new_item' => __('New Oss Product Item'),
			'view_item' => __('View Oss Product Item'),
			'search_items' => __('Search Oss Product'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		);
	 
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			//'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('thumbnail','comments','author')
		  ); 
	 
		register_post_type( 'oss_product' , $args );
	}

	function oss_company_register() {
		$labels = array(
			'name' => _x('Service Provider', 'post type general name'),
			'singular_name' => _x('Oss Service Provider Item', 'post type singular name'),
			'add_new' => _x('Add New', 'Oss Service Provider item'),
			'add_new_item' => __('Add New Oss Service Provider Item'),
			'edit_item' => __('Edit Oss Service Provider Item'),
			'new_item' => __('New Oss Service Provider Item'),
			'view_item' => __('View Oss Service Provider Item'),
			'search_items' => __('Search Oss Service Provider'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		);
	 
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			//'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			//'supports' => array('title','thumbnail','comments')
			'supports' => array('thumbnail','comments','author')
		  ); 
	 
		register_post_type( 'oss_service' , $args );
	}

	function admin_init(){

		/************** OSS Directory Part ******************************************************************************/
		// Add tag metabox
		add_meta_box('tagsdiv-post_tag', __('Tags'), 'post_tags_meta_box','oss_product', 'side', 'low');
		//register_taxonomy_for_object_type('post_tag', 'oss_product');
		 
		// Add category metabox
		//add_meta_box('authordiv', __('Author'), 'post_author_meta_box','oss_product', 'side', 'core');
		//add_meta_box('oss category',__('OSS Category'),'post_oss_category_meta_box','oss_product','side','core');

		 // custom attachment
		add_meta_box('wp_custom_attachment','제품 이미지',array($this,'wp_custom_attachment'),'oss_product','side'); 
		add_meta_box("item_info", "세부사항", array($this,"product_info"), "oss_product", "normal", "low");


		/************** Service Provider Part *****************************************************************************/

		// Add tag metabox
		add_meta_box('tagsdiv-post_tag', __('Tags'), 'post_tags_meta_box','oss_service', 'side', 'low');
		//register_taxonomy_for_object_type('post_tag', 'oss_service');
		 
		// Add category metabox
		//add_meta_box('categorydiv', __('Categories'), 'post_categories_meta_box','oss_service', 'side', 'core');
		//register_taxonomy_for_object_type('category', 'oss_service'); 
		//add_meta_box('authordiv', __('Author'), 'post_author_meta_box','oss_service', 'side', 'core');

		 // custom attachment
		//add_meta_box('wp_custom_attachment','제품 이미지','wp_custom_attachment','oss_service','side'); 
		add_meta_box("service_init_info", "고유정보", array($this,"service_init_info"), "oss_service", "normal", "low");
		add_meta_box("service_bb_info", "기본정보", array($this,"service_bb_info"), "oss_service", "normal", "low");
		add_meta_box("service_etc_info", "부가정보", array($this,"service_etc_info"), "oss_service", "normal", "low");
		add_meta_box("service_prd_info", "제품정보", array($this,"service_prd_info"), "oss_service", "normal", "low");
		add_meta_box("service_event_info", "타이틀이미지", array($this,"service_event_info"), "oss_service", "side", "low");
		add_meta_box("service_etc", "기타(구글지도주소)", array($this,"service_etc"), "oss_service", "side", "low");
	}

	/************** OSS Directory Part ************************************************************************************/
	// 제품정보
	function product_info(){
		global $post;
		
		$custom = get_post_custom($post->ID);
		$prd_nm = $post->post_title;
		$content = $post->post_content;
		$prd_short_desc = $custom["prd_short_desc"][0];
		$prd_short_desc = $custom["prd_short_desc"][0];
		$os = $custom["os"][0];
		$prd_license = $custom["prd_license"][0];
		$apply_company = $custom["apply_company"][0];
		$tech_company = $custom["tech_company"][0];
		$project_url = $custom["project_url"][0];
		$recom_num = $custom["recom_num"][0];
		$recom_point = $custom["recom_point"][0];
		$file_url = $custom["file_url"][0];
		

		//print_r($custom);
		include "inc/product_info.php";
	}

	// 첨부파일
	function wp_custom_attachment() {  
		global $post;

		$custom         = get_post_custom($post->ID);
		$download_id    = get_post_meta($post->ID, 'document_file_id', true);

		echo '<p><label for="document_file">Upload document:</label><br />';
		echo '<input type="file" name="document_file" id="document_file" /></p>';
		echo '</p>';

		if(!empty($download_id) && $download_id != '0') {
			echo '<p><a href="' . wp_get_attachment_url($download_id) . '">
			View document</a></p>';
		}

		//echo $html;  
	  
	}

	/************** Service Provider Part************************************************************************************/
	function service_init_info(){
		global $post;	
		$custom = get_post_custom($post->ID);

		$nation = $custom["nation"][0];
		$company_nm = $custom["company_nm"][0];
		$com_type = get_post_meta($post->ID,"com_type");
		$com_type = $com_type[0];

		include "inc/service_init_info.php";
	}


	function service_bb_info(){
		global $post;	
		$custom = get_post_custom($post->ID);


		$ceo_nm = $custom["ceo_nm"][0];
		$company_tel = $custom["company_tel"][0];
		$company_zip1 = $custom["company_zip1"][0];
		$company_zip2 = $custom["company_zip2"][0];
		$company_addr = $custom["company_addr"][0];
		$company_email = $custom["company_email"][0];
		$company_homepage = $custom["company_homepage"][0];


		$company_logo_id    = get_post_meta($post->ID, 'company_logo', true);	
		if(!empty($company_logo_id) && $company_logo_id != '0') {
			$company_logo =  '<a href="' . wp_get_attachment_url($company_logo_id) . '">회사로고</a>';
		}

		include "inc/service_basic_info.php";
	}

	function service_etc_info(){
		global $post;	
		$custom = get_post_custom($post->ID);

		$content = $post->post_content;
		$facebook_url = $custom["facebook_url"][0];
		$twitter_url = $custom["twitter_url"][0];

		$company_info_file_id    = get_post_meta($post->ID, 'company_info_file', true);
		$company_img_file_id    = get_post_meta($post->ID, 'company_img_file', true);

		if(!empty($company_info_file_id) && $company_info_file_id != '0') {
			$company_info_file =  '<a href="' . wp_get_attachment_url($company_info_file_id) . '">회사소개서</a>';
		}
		if(!empty($company_img_file_id) && $company_img_file_id != '0') {
			$company_img_file =  '<a href="' . wp_get_attachment_url($company_img_file_id) . '">회사소개 이미지</a>';
		}
		include "inc/service_etc_info.php";
	}

	function service_event_info(){
		global $post;	
		$custom = get_post_custom($post->ID);

		$title_img_id    = get_post_meta($post->ID, 'title_img', true);


		if(!empty($title_img_id) && $title_img_id != '0') {
			$title_img =  '<a href="' . wp_get_attachment_url($title_img_id) . '">타이틀이미지</a>';
		}
		
		include "inc/service_event_info.php";
	}

	function service_etc(){
		global $post;	
		$custom = get_post_custom($post->ID);

		$geoposition = $custom["geoposition"][0];
		//$latitude = $custom["latitude"][0];
		//$longitude = $custom["longitude"][0];
		include "inc/service_etc.php";
	}

	function service_prd_info(){
		global $post;	
		$custom = get_post_custom($post->ID);

		$prd_nm = get_post_meta($post->ID,"prd_nm");
		$prd_info = get_post_meta($post->ID,"prd_info");
		$prd_url = get_post_meta($post->ID,"prd_url");

		$prd_nm_array = $prd_nm[0];
		$prd_info_array = $prd_info[0];
		$prd_url_array = $prd_url[0];

		include "inc/service_prd_info.php";
	}


	/************** 공통 ****************************************************************************************************/
	function save_details(){
		global $post;

		if($_POST[post_type]=="oss_product"){
			update_post_meta($post->ID, "prd_nm", $_POST["prd_nm"]);
			update_post_meta($post->ID, "prd_short_desc", $_POST["prd_short_desc"]);
			update_post_meta($post->ID, "os", $_POST["os"]);
			update_post_meta($post->ID, "prd_license", $_POST["prd_license"]);
			update_post_meta($post->ID, "apply_company", $_POST["apply_company"]);
			update_post_meta($post->ID, "tech_company", $_POST["tech_company"]);
			update_post_meta($post->ID, "project_url", $_POST["project_url"]);
			update_post_meta($post->ID, "recom_num", $_POST["recom_num"]);
			update_post_meta($post->ID, "recom_point", $_POST["recom_point"]);
			update_post_meta($post->ID, "file_url", $_POST["file_url"]);




			if(!empty($_FILES['document_file'])) {
				$file   = $_FILES['document_file'];
				$upload = wp_handle_upload($file, array('test_form' => false));
				if(!isset($upload['error']) && isset($upload['file'])) {
					$filetype   = wp_check_filetype(basename($upload['file']), null);
					$title      = $file['name'];
					$ext        = strrchr($title, '.');
					$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
					$attachment = array(
						'post_mime_type'    => $wp_filetype['type'],
						'post_title'        => addslashes($title),
						'post_content'      => '',
						'post_status'       => 'inherit',
						'post_parent'       => $post->ID
					);

					$attach_key = 'document_file_id';
					$attach_id  = wp_insert_attachment($attachment, $upload['file']);
					$existing_download = (int) get_post_meta($post->ID, $attach_key, true);

					if(is_numeric($existing_download)) {
						wp_delete_attachment($existing_download);
					}

					update_post_meta($post->ID, $attach_key, $attach_id);
				}
			}
		}
		else if($_POST[post_type]=="oss_service"){
			
			/*
			echo "<pre>";
			//print_r($_POST);
			print_r($_FILES);
			echo "</pre>";

			exit;
			*/

			
			
			
			update_post_meta($post->ID, "nation", $_POST["nation"]);
			update_post_meta($post->ID, "company_nm", $_POST["company_nm"]);
			update_post_meta($post->ID, "com_type", $_POST["com_type"]);


			update_post_meta($post->ID, "ceo_nm", $_POST["ceo_nm"]);
			update_post_meta($post->ID, "company_tel", $_POST["company_tel"]);
			update_post_meta($post->ID, "company_zip1", $_POST["company_zip1"]);
			update_post_meta($post->ID, "company_zip2", $_POST["company_zip2"]);
			update_post_meta($post->ID, "company_addr", $_POST["company_addr"]);
			update_post_meta($post->ID, "company_email", $_POST["company_email"]);
			update_post_meta($post->ID, "company_homepage", $_POST["company_homepage"]);


			update_post_meta($post->ID, "company_info", $_POST["company_info"]);
			update_post_meta($post->ID, "facebook_url", $_POST["facebook_url"]);
			update_post_meta($post->ID, "twitter_url", $_POST["twitter_url"]);
			
			update_post_meta($post->ID, "geoposition", $_POST["geoposition"]);
			//update_post_meta($post->ID, "latitude", $_POST["latitude"]);
			//update_post_meta($post->ID, "longitude", $_POST["longitude"]);
			
			//update_post_meta($post->ID, "prd_cate", $_POST["prd_cate"]);
			update_post_meta($post->ID, "prd_nm", $_POST["prd_nm"]);
			update_post_meta($post->ID, "prd_info", $_POST["prd_info"]);
			update_post_meta($post->ID, "prd_url", $_POST["prd_url"]);



			// 타이틀이미지
			if(!empty($_FILES['title_img'])) {
				$file   = $_FILES['title_img'];
				$upload = wp_handle_upload($file, array('test_form' => false));
				if(!isset($upload['error']) && isset($upload['file'])) {
					$filetype   = wp_check_filetype(basename($upload['file']), null);
					$title      = $file['name'];
					$ext        = strrchr($title, '.');
					$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
					$attachment = array(
						'post_mime_type'    => $wp_filetype['type'],
						'post_title'        => addslashes($title),
						'post_content'      => '',
						'post_status'       => 'inherit',
						'post_parent'       => $post->ID
					);

					$attach_key = 'title_img';
					$attach_id  = wp_insert_attachment($attachment, $upload['file']);
					$existing_download = (int) get_post_meta($post->ID, $attach_key, true);

					if(is_numeric($existing_download)) {
						wp_delete_attachment($existing_download);
					}

					update_post_meta($post->ID, $attach_key, $attach_id);
				}
			}

			// 회사로고
			if(!empty($_FILES['company_logo'])) {
				$file   = $_FILES['company_logo'];
				$upload = wp_handle_upload($file, array('test_form' => false));
				if(!isset($upload['error']) && isset($upload['file'])) {
					$filetype   = wp_check_filetype(basename($upload['file']), null);
					$title      = $file['name'];
					$ext        = strrchr($title, '.');
					$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
					$attachment = array(
						'post_mime_type'    => $wp_filetype['type'],
						'post_title'        => addslashes($title),
						'post_content'      => '',
						'post_status'       => 'inherit',
						'post_parent'       => $post->ID
					);

					$attach_key = 'company_logo';
					$attach_id  = wp_insert_attachment($attachment, $upload['file']);
					$existing_download = (int) get_post_meta($post->ID, $attach_key, true);

					if(is_numeric($existing_download)) {
						wp_delete_attachment($existing_download);
					}

					update_post_meta($post->ID, $attach_key, $attach_id);
				}
			}
			
			/*
			// 타이틀이미지
			if(!empty($_FILES['title_img'])) {
				$file   = $_FILES['title_img'];
				$upload = wp_handle_upload($file, array('test_form' => false));
				if(!isset($upload['error']) && isset($upload['file'])) {
					$filetype   = wp_check_filetype(basename($upload['file']), null);
					$title      = $file['name'];
					$ext        = strrchr($title, '.');
					$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
					$attachment = array(
						'post_mime_type'    => $wp_filetype['type'],
						'post_title'        => addslashes($title),
						'post_content'      => '',
						'post_status'       => 'inherit',
						'post_parent'       => $post->ID
					);

					$attach_key = 'title_img';
					$attach_id  = wp_insert_attachment($attachment, $upload['file']);
					$existing_download = (int) get_post_meta($post->ID, $attach_key, true);

					if(is_numeric($existing_download)) {
						wp_delete_attachment($existing_download);
					}

					update_post_meta($post->ID, $attach_key, $attach_id);
				}
			}
			*/
			// 회사소개서
			if(!empty($_FILES['company_info_file'])) {
				$file   = $_FILES['company_info_file'];
				$upload = wp_handle_upload($file, array('test_form' => false));
				if(!isset($upload['error']) && isset($upload['file'])) {
					$filetype   = wp_check_filetype(basename($upload['file']), null);
					$title      = $file['name'];
					$ext        = strrchr($title, '.');
					$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
					$attachment = array(
						'post_mime_type'    => $wp_filetype['type'],
						'post_title'        => addslashes($title),
						'post_content'      => '',
						'post_status'       => 'inherit',
						'post_parent'       => $post->ID
					);

					$attach_key = 'company_info_file';
					$attach_id  = wp_insert_attachment($attachment, $upload['file']);
					$existing_download = (int) get_post_meta($post->ID, $attach_key, true);

					if(is_numeric($existing_download)) {
						wp_delete_attachment($existing_download);
					}

					update_post_meta($post->ID, $attach_key, $attach_id);
				}
			}
			
			// 회사소개 이미지
			if(!empty($_FILES['company_img_file'])) {
				$file   = $_FILES['company_img_file'];
				$upload = wp_handle_upload($file, array('test_form' => false));
				if(!isset($upload['error']) && isset($upload['file'])) {
					$filetype   = wp_check_filetype(basename($upload['file']), null);
					$title      = $file['name'];
					$ext        = strrchr($title, '.');
					$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
					$attachment = array(
						'post_mime_type'    => $wp_filetype['type'],
						'post_title'        => addslashes($title),
						'post_content'      => '',
						'post_status'       => 'inherit',
						'post_parent'       => $post->ID
					);

					$attach_key = 'company_img_file';
					$attach_id  = wp_insert_attachment($attachment, $upload['file']);
					$existing_download = (int) get_post_meta($post->ID, $attach_key, true);

					if(is_numeric($existing_download)) {
						wp_delete_attachment($existing_download);
					}

					update_post_meta($post->ID, $attach_key, $attach_id);
				}
			}


			// 제품이미지
			if(!empty($_FILES[prd_img])){
				$files = $_FILES[prd_img];

					
				foreach($files[name] as $key=>$value){
					

					if($files['name'][$key]){
						$file = array(
							'name'=>$files['name'][$key],
							'type'=>$files['type'][$key],
							'tmp_name'=>$files['tmp_name'][$key],
							'error'=>$files['error'][$key],
							'size'=>$files['size'][$key]
						);


						$upload = wp_handle_upload($file, array('test_form' => false));
						if(!isset($upload['error']) && isset($upload['file'])) {
							$filetype   = wp_check_filetype(basename($upload['file']), null);
							$title      = $file['name'];
							$ext        = strrchr($title, '.');
							$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
							$attachment = array(
								'post_mime_type'    => $wp_filetype['type'],
								'post_title'        => addslashes($title),
								'post_content'      => '',
								'post_status'       => 'inherit',
								'post_parent'       => $post->ID
							);

							$attach_key = 'prd_img_'.$key;

							$attach_id  = wp_insert_attachment($attachment, $upload['file']);
							$existing_download = (int) get_post_meta($post->ID, $attach_key, true);

							if(is_numeric($existing_download)) {
								wp_delete_attachment($existing_download);
							}

							update_post_meta($post->ID, $attach_key, $attach_id);
						}
					}

				}

			}
		}

	}


	/*************** List Column***************************************************************************************/


	function oss_product_edit_columns($columns){
		
		$columns = array(
			"cb" => "<input type='checkbox' />",
			"title" => "제품명",
			"taxonomy-prd_cate" => __("카테고리"),
			"author" => "작성자",
			"tags"=> __('Tags'),
			"comments" => __("Comments"),
			"date" => "Date",
			//"aaa"=>"AAA",
		);/**/


		return $columns;
	}

	function oss_product_custom_columns($column){
		global $post,$typenow;
		//echo "column-jhjang : $column";
		switch ($column) {
			case "aaa":
				echo "RRR";
				break;
			case "tag":
				echo get_the_term_list($post->ID, 'tags', '', ', ','');
				break;

		}
	}

	function oss_service_edit_columns($columns){
		
		$columns = array(
			"cb" => "<input type='checkbox' />",
			"title" => "회사명",
			"taxonomy-srv_cate" => __("Product Category"),
			"author" => "Author",
			"tags"=> __('Tags'),
			"comments" => __("Comments"),
			"date" => "Date",
		);/**/
		return $columns;
	}

	function oss_service_custom_columns($column){
		global $post,$typenow;

		switch ($column) {

			case "tag":
				echo get_the_term_list($post->ID, 'tags', '', ', ','');
			break;

		}
	}



	// Register Custom Product Taxonomy
	function product_taxonomy()  {
		$labels = array(
			'name'                       => _x( 'Product Category', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Product', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Category', 'text_domain' ),
			'all_items'                  => __( 'All Category', 'text_domain' ),
			'parent_item'                => __( 'Parent Category', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Category:', 'text_domain' ),
			'new_item_name'              => __( 'New Category Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Category', 'text_domain' ),
			'edit_item'                  => __( 'Edit Category', 'text_domain' ),
			'update_item'                => __( 'Update Category', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate Category with commas', 'text_domain' ),
			'search_items'               => __( 'Search Category', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or Remove Category', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from Most Used Category', 'text_domain' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => false,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'rewrite'                    => array('slug' => 'product_category'),
		);

		register_taxonomy( 'prd_cate', 'oss_product', $args );
	}


	// Register Custom Service Taxonomy
	function service_taxonomy()  {
		$labels = array(
			'name'                       => _x( 'Service Category', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Service', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Category', 'text_domain' ),
			'all_items'                  => __( 'All Category', 'text_domain' ),
			'parent_item'                => __( 'Parent Course', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Course:', 'text_domain' ),
			'new_item_name'              => __( 'New Category Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Course', 'text_domain' ),
			'edit_item'                  => __( 'Edit Course', 'text_domain' ),
			'update_item'                => __( 'Update Course', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate Category with commas', 'text_domain' ),
			'search_items'               => __( 'Search Category', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or Remove Category', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from Most Used Category', 'text_domain' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => false,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'rewrite'                    => array('slug' => 'servcie_category'),
		);

		register_taxonomy( 'srv_cate', 'oss_service', $args );
	}

	function showPageList($url, $curPage, $maxPage, $listSize, $onclick="", $anchor="") {
			$body = " <TABLE class=\"paging_table\"> <TR>
			";
			$maxPage = ceil($maxPage);
			$begin = $listSize * floor(($curPage-1) / $listSize) + 1;
			$end = $begin + $listSize < $maxPage ? $begin + $listSize : $maxPage+1;
			$first = $begin>1 ? 1 : 0;
			$last = $end < $maxPage ? $maxPage : 0;
			$prev = $begin > 2 ? $begin - 1 : 0;
			$next = $last > 0 && $end < $last ? $end : 0;

			if (!empty($onclick))
					$script = "onclick=\"$onclick($first)\"";
			if ($first)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($first)."\" class=\"paging_link\" $script> << </a></TD> ";
			if (!empty($onclick))
					$script = "onclick=\"$onclick($prev)\"";
			if ($prev)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($prev)."\" class=\"paging_link\" $script> < </a></TD> ";
			for ($i=$begin; $i<$end; $i++) {
					if (!empty($onclick))
							$script = "onclick=\"$onclick($i)\"";
					if ($i==$curPage)
							$body .= "<TD class=\"cur_paging_td navi_num\"><b>$i</b></TD>";
					else
							$body .= "<TD class=\"paging_td navi_num\"><a href=\"$url".($i)."\" class=\"paging_link\" $script>$i</a></TD> ";
			}
			if (!empty($onclick))
					$script = "onclick=\"$onclick($next)\"";
			if ($next)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($next)."\" class=\"paging_link\" $script> > </a></TD> ";
			if (!empty($onclick))
					$script = "onclick=\"$onclick($last)\"";
			if ($last)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($last)."\" class=\"paging_link\" $script> >> </a></TD> ";

			$body .= " </TR>
			</TABLE>";
			return $body;
	}


	function curl_fetch($Url){
		// is cURL installed yet?
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}
		 // OK cool - then let's create a new cURL resource handle
		$ch = curl_init();
		 // Now set some options (most are optional)
		 // Set URL to download
		curl_setopt($ch, CURLOPT_URL, $Url);
		 // Set a referer
	//   curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
		 // User agent
	//   curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		 // Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_HEADER, 0);
		 // Should cURL return or print out the data? (true = return, false = print)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 // Timeout in seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		 // Download the given URL, and return output
		$output = curl_exec($ch);
		 // Close the cURL resource, and free system resources
		curl_close($ch);
		 return $output;
	}

	function convertAddressToLocation($address){
		$addy = stripslashes($address);
		 
		$apicall = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($addy)."&sensor=false";
		 
		$ret = $this->curl_fetch($apicall);
		 
		$decoded = json_decode($ret,true);
		 
		$lat = $decoded['results'][0]['geometry']['location']['lat'];
		$lng = $decoded['results'][0]['geometry']['location']['lng'];

		$info['latitude'] = $lat;
		$info['longitude'] = $lng;

		return $info;
	}

	function searchTwitter($atts){
		extract(shortcode_atts(array(
			"keyword"=>"공개소프트웨어",
			"height"=>300,
			"width"=>200,
			"color"=>"#ddd"
			),$atts));
		include "inc/search-twitter.php";
	}

	function searchGoogle($atts){
		include "inc/search-google.php";
	}
}



/*************** 내부함수 **************************************************************************/

function paging($url, $curPage, $maxPage, $listSize, $onclick="", $anchor=""){
	global $wpdb,$post,$OSS;

	$ret = $OSS->showPageList($url, $curPage, $maxPage, $listSize, $onclick="", $anchor="");
	return $ret;
}


function getLocation($addr){
	global $wpdb,$post,$OSS;

	$ret = $OSS->convertAddressToLocation($addr);
	return $ret;
}

function retNation($val){
	$param = array("ko"=>"한국","ch"=>"중국","jp"=>"일본");
	return $param[$val];
}

function retComType($ar=array()){
	$param = array("rnd"=>"R&D","tech"=>"기술지원","usage"=>"활용","consulting"=>"컨설팅");
	
	$tmp = array();
	if(is_array($ar)){
		foreach($ar as $p) $tmp[] = $param[$p];
		return @join(",",$tmp);
	}
}


$OSS = new OSS();



/*
add_action( 'quick_edit_custom_box', 'quick_edit', 10, 2 );

function quick_edit( $column_name, $post_type ) {
	echo "$column_name, $post_type<BR>";
	switch ( $post_type ) {
		case 'oss_product':
			switch( $column_name ) {
				case 'taxonomy-prd_cate':

				break;
			}
		break;

	}
}
*/



?>