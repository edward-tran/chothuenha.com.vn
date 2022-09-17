<?php
	class ControllerExtensionModuleBlogCategory extends Controller {
		public function index($setting) {
            $this->load->language('extension/module/blog_category');

			$data['heading_title'] = $this->language->get('heading_title');
			$this->document->addStyle('catalog/view/javascript/blog/css/style.css');
			$this->load->model('blog/article');
			
			$data['text_search_article'] = $this->language->get('text_search_article');
			$data['button_search'] = $this->language->get('button_search');
			
			if (isset($this->request->get['blog_category_id'])) {
				$parts = explode('_', (string)$this->request->get['blog_category_id']);
			} else {
				$parts = array();
			}
			
			if (isset($parts[0])) {
				$data['category_id'] = $parts[0];
			} else {
				$data['category_id'] = 0;
			}
			
			if (isset($parts[1])) {
				$data['child_id'] = $parts[1];
			} else {
				$data['child_id'] = 0;
			}
			
			$data['categories'] = array();

			$categories = $this->model_blog_article->getCategories(0);

            foreach ($categories as $category) {
				
				$children_data = array();
	
				$children = $this->model_blog_article->getCategories($category['blog_category_id']);
				
				foreach ($children as $child) {
					
					$article_total = $this->model_blog_article->getTotalArticles($child['blog_category_id']);
			
					$children_data[] = array(
						'category_id' => $child['blog_category_id'],
						'name'  => $child['name'],
                        'external_link' => $child['external_link'],

                        'href'  => $this->url->link('blog/category', 'blog_category_id=' . $category['blog_category_id'] . '_' . $child['blog_category_id'])
					);		
				}
	
				$data['categories'][] = array(
					'blog_category_id' => $category['blog_category_id'],
					'name'     => $category['name'],
					'children' => $children_data,
					'href'     => $this->url->link('blog/category', 'blog_category_id=' . $category['blog_category_id'])
				);
			}
			
            //print "<pre>"; print_r($data['categories']); die;
            
            if($this->config->has('blog_category_search_article')) {
                $data['blog_category_search_article'] = $this->config->get('blog_category_search_article');
            }
            
			//print "<pre>"; print_r($data['categories']); exit;
			if (isset($this->request->get['blog_search'])) {
				$data['blog_search'] = $this->request->get['blog_search'];
			} else {
				$data['blog_search'] = '';
			}
            return $this->load->view('extension/module/blog_category', $data);
		}
	}