<?php
class ControllerExtensionModuleBlogLatest extends Controller {
	public function index($setting) {
	
		$this->load->language('extension/module/blog_latest');
		
		
		/*Config default*/
		if(!isset($setting['pre_text']))
		{
			$setting['pre_text'] = '';		
		}
		else {
			$setting['pre_text'] = html_entity_decode($setting['pre_text'], ENT_QUOTES, 'UTF-8');
		}
		if(!isset($setting['post_text']))
		{
			 $setting['post_text'] = '';
		}
		else {
			$setting['post_text'] = html_entity_decode($setting['post_text'], ENT_QUOTES, 'UTF-8');
		}
		$data['tag_id']	= 'blog_latest_'.$setting['moduleid'].'_'.rand().time();

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$this->load->model('blog/article');
		$data['banner'] = array();

		$banner_result = $this->model_design_banner->getBanner(19);

		$active_category_id = $setting['category'][0];
		$data['categorys'] = array();
		foreach($setting['category'] as $category) {
			$children = $this->model_blog_article->getCategory($category);
			$data['categorys'][] = array(
				'name' 				=> $children['name'],
				'blog_category_id' 	=> $children['blog_category_id'],
			);
		}

		$category = $this->model_blog_article->getCategory($active_category_id);

		

		$filter_data = array(
			'blog_article_id' => $active_category_id
		);

		$results = $this->model_blog_article->getArticleCategoryWise($filter_data);

		$data['blogs'] = array();
		foreach($results  as $key => $result) {
			if($key >=3) {
				continue;
			}
			if($result['featured_image']) {
				$image = HTTP_SERVER . 'image/' . $result['featured_image'];
				$featured_found = 1;
			} else if($result['image']) {
				$image = HTTP_SERVER . 'image/' . $result['image'];
				$featured_found = '';
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 873, 390);
				$featured_found = '';
			}

			$description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 300) . '...';

			$data['blogs'][] = array(
				'blog_article_id'	=> $result['blog_article_id'],
				'name'		=> $result['article_title'],
				'image'				=> $image,
				'featured_found'	=> $featured_found,
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'description'		=> $description,
				'href'				=> $this->url->link('blog/article/view', 'blog_article_id=' . $result['blog_article_id'], true)
			);
		}


		if (is_file(DIR_IMAGE . $banner_result[0]['image'])) {
			$data['banner']= array(
				'title' => $banner_result[0]['title'],
				'link'  => $banner_result[0]['link'],
				'image' => URL_HOME.'image/'.$banner_result[0]['image']
			);
		}


		return $this->load->view('extension/module/blog_latest/default', $data);
	
	}
	
	//=== Theme Custom Code====
	public function getLayoutMod($name=null,$data,$type_layout){
		$log_directory  = DIR_TEMPLATE.$this->config->get('theme_default_directory').'/template/extension/module/'.$name;
		$fileNames = array();
		$type_morelayout = '';
		if (is_dir($log_directory)) {
			$files = scandir($log_directory);
			foreach ($files as  $value) {
				if (strpos($value, '.twig') !== false && strpos($value, '_') === false) {
					$fileNames[] = str_replace('.twig', '', $value);
				}
			}
			$fileNames = isset($fileNames) ? $fileNames : '';
			foreach($fileNames as $option_id => $option_value){
				if($option_id == $type_layout){
					$type_morelayout = $this->load->view('extension/module/'.$name.'/'.$option_value, $data);
				}
			}
		}
		return $type_morelayout;
	}
	
	public function checkDatabase() {
		$database_not_found = $this->validateTable();

		if(!$database_not_found) {
			return true;
		}

		return false;
	}
	
	public function validateTable() {
		$table_name = $this->db->escape('blog_article');

		$table = DB_PREFIX . $table_name;

		$query = $this->db->query("SHOW TABLES LIKE '{$table}'");

		return $query->num_rows;
	}
	
	public function getListBlogs($setting){
		if (!isset($setting['limit'])) {
			$setting['limit'] = 9;
		}
		if (!isset($setting['width'])) {
			$setting['width'] = 100;
		}
		if (!isset($setting['height'])) {
			$setting['height'] = 200;
		}
		
		// Get Category list
		$str_categorys = self::getCategoryChild($setting);
		
		$blogs =  array();
		if( $str_categorys != "")
		{
			$filter_data = array(
				'category_id'	=> $str_categorys,
				'sort'  		=> $setting['sort'],
				'order' 		=> $setting['order'],
				'start' 		=> 0,
				'limit' 		=> $setting['limit']
			);
			$blogs = $this->model_extension_module_blog_latest->getListBlogs($filter_data);
			
			$users = $this->model_extension_module_blog_latest->getUsers();
			
			foreach( $blogs as $key => $blog ){
				if ($blogs[$key]['featured_image'] && $setting['blog_get_featured_image']){
					$blogs[$key]['thumb'] = $this->model_tool_image->resize($blog['featured_image'], (int)$setting['width'], (int)$setting['height'] );
				}else {
					$url = file_exists("image/catalog/blog_latest/images/".$setting['blog_placeholder_path']);
					
					if ($url) {
						$image_name = "catalog/blog_latest/images/".$setting['blog_placeholder_path'];
					} else {
						$image_name = "no_image.png";
					}
					$blogs[$key]['thumb'] = $this->model_tool_image->resize($image_name, (int)$setting['width'], (int)$setting['height']);
					
				}					
				// Title
				$title = $blog['article_title'];
				$title_maxlength = ((strlen($blog['article_title']) > $setting['title_maxlength'] && $setting['title_maxlength'] !=0)  ? utf8_substr(strip_tags(html_entity_decode($blog['article_title'], ENT_QUOTES, 'UTF-8')), 0, $setting['title_maxlength']) . '..' : $blog['article_title']);
				
				// Description
				$description 	= ((strlen($blog['description']) > $setting['description_maxlength'] && $setting['description_maxlength'] != 0) ? utf8_substr(strip_tags(html_entity_decode($blog['description'], ENT_QUOTES, 'UTF-8')), 0, $setting['description_maxlength']) . '..' : $blog['description']);
				
				$blogs[$key]['title'] 			= $title;
				$blogs[$key]['title_maxlength'] = $title_maxlength;
				$blogs[$key]['description'] 	= $description;
				$blogs[$key]['author'] 			= isset($users[$blog['blog_author_id']])? $users[$blog['blog_author_id']]:$this->language->get('text_none_author');
				$blogs[$key]['date_added']      = strtotime($blog['date_added']); 
				$blogs[$key]['date_modified']   = strtotime($blog['date_modified']);
				$blogs[$key]['comment_count'] 	= $blog['comment'];
				$blogs[$key]['view_count'] 		= $blog['view'];
				$blogs[$key]['link'] 			= $this->url->link( 'blog/article/view','blog_article_id='.$blog['blog_article_id'] );
				// text comment
				if($blog['comment'] > 1)
				{
					$blogs[$key]['text_comment']   = $this->language->get('text_comments');
				}else{
					$blogs[$key]['text_comment']   = $this->language->get('text_comment');
				}
				
				// text view
				if($blog['view'] > 1)
				{
					$blogs[$key]['text_view']   = $this->language->get('text_views');
				}else{
					$blogs[$key]['text_view']   = $this->language->get('text_view');
				}
			}
		}
		
		$data['blogs'] = $blogs;
		
		return $data['blogs'];
	}

	public function getCategoryChild($setting){
		// check lại category nếu người dùng unpublic category sau khi cấu hình
		$category_list = array();
		
		foreach($setting['category'] as $category_item)
		{
			$checkCategory = $this->model_extension_module_blog_latest->checkCategory($category_item);
			if(isset($checkCategory) && $checkCategory[0]['status'] == 1 && $checkCategory != null)
			{
				$category_list[] =  $category_item;
			}
		}
		if($category_list != null)
		{
			if($setting['child_category'])
			{
				for($i=1; $i<=$setting['category_depth'];$i++)
				{
					foreach ($category_list as $categorys)
					{
						$categoryss = $this->model_extension_module_blog_latest->getCategories_son($categorys);
						foreach ($categoryss as $category)
						{
							$category_list[]  = $category['blog_category_id'];
						}
					}
					
				}
			}
			$category_list = array_unique($category_list);
		}
		
		$str_categorys = implode(",",$category_list);
		return $str_categorys;
	}
}