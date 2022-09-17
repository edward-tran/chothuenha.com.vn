<?php
	class ModelBlogArticle extends Model {
		
		public function getTotalArticle($data = array()) {
			
			$sql = "SELECT COUNT(DISTINCT(sba.blog_article_id)) AS total FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "'";
			
			if(!empty($data['blog_search'])) {
				$sql .= " AND LCASE(sbad.article_title) LIKE '" . $this->db->escape(utf8_strtolower($data['blog_search'])) . "%'";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getArticles($data = array()) {
			
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . (int)$this->config->get('config_language_id') . "'";
			
			if(!empty($data['blog_search'])) {
				$sql .= " AND LCASE(sbad.article_title) LIKE '" . $this->db->escape(utf8_strtolower($data['blog_search'])) . "%'";
			}
			
			$sql .= " ORDER BY sba.date_modified DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
	
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getTotalCategories($parent_id = 0) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sbc.blog_category_id)) AS total FROM `" . DB_PREFIX . "blog_category` sbc LEFT JOIN `" . DB_PREFIX . "blog_category_description` sbcd ON(sbc.blog_category_id=sbcd.blog_category_id) LEFT JOIN `" . DB_PREFIX . "blog_category_to_store` sbcs ON(sbc.blog_category_id=sbcs.blog_category_id) WHERE sbc.parent_id='" . (int)$parent_id . "' AND sbcd.language_id='" . (int)$this->config->get('config_language_id') . "' AND sbcs.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbc.status=1 ORDER BY sbc.sort_order, LCASE(sbcd.name)");
			
			return $sql->row['total'];
		}
		
		public function getCategories($parent_id = 0) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category` sbc LEFT JOIN `" . DB_PREFIX . "blog_category_description` sbcd ON(sbc.blog_category_id=sbcd.blog_category_id) LEFT JOIN `" . DB_PREFIX . "blog_category_to_store` sbcs ON(sbc.blog_category_id=sbcs.blog_category_id) WHERE sbc.parent_id='" . (int)$parent_id . "' AND sbcd.language_id='" . (int)$this->config->get('config_language_id') . "' AND sbcs.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbc.status=1 ORDER BY sbc.sort_order, LCASE(sbcd.name)");
            return $sql->rows;
		}
		
		public function getTotalArticles($blog_category_id) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(blog_article_id)) AS total FROM `" . DB_PREFIX . "blog_article_to_category` WHERE blog_category_id='" . (int)$blog_category_id . "'");
			return $sql->row['total'];
		}	
		
		public function getTotalComments($blog_article_id) {
			$sql = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "blog_comment` WHERE blog_article_id='" . (int)$blog_article_id . "' AND status=1");
			return $sql->row['total'];
		}
		
		public function getAdditionalDescription($blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_article_description_additional` WHERE blog_article_id='" . (int)$blog_article_id . "'");
			return $sql->rows;	
		}	
		
		public function getArticle($blog_article_id) {
			$sql = $this->db->query("SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) WHERE sba.blog_article_id='" . (int)$blog_article_id . "' AND sbau.status=1 AND sba.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'");
			return $sql->row;
		}
		
		public function addBlogView($blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_view` WHERE blog_article_id='" . (int)$blog_article_id . "'");
			
			if($sql->num_rows) {
				$counter = $sql->row['view'];
				
				$counter++;
				
				$this->db->query("UPDATE `" . DB_PREFIX . "blog_view` SET view='" . (int)$counter . "', date_modified=NOW() WHERE blog_article_id='" . (int)$blog_article_id . "'");
				
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_view` SET blog_article_id='" . (int)$blog_article_id . "', view=1, date_added=NOW(), date_modified=NOW()");
			}
		}
		
		public function getArticleAdditionalDescription($blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_article_description_additional` WHERE language_id='" . (int)$this->config->get('config_language_id') . "' AND blog_article_id='" . (int)$blog_article_id . "'");
			return $sql->rows;
		}
		
		public function getArticleProductRelated($blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_article_product_related` WHERE blog_article_id='" . (int)$blog_article_id . "'");
			return $sql->rows;
		}
		
		public function getTotalCommentsByArticleId($blog_article_id) {
			$sql = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "blog_comment` WHERE blog_article_id='" . (int)$blog_article_id . "' AND status=1 AND blog_article_reply_id=0");
			return $sql->row['total'];
		}
		
		public function getCommentsByArticle($blog_article_id, $start = 0, $limit = 20, $blog_comment_id = 0) {
			if(!$blog_comment_id) {
				
				if ($start < 0) {
					$start = 0;
				}
				
				if ($limit < 1) {
					$limit = 20;
				}
				
				$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_comment` WHERE blog_article_id='" . (int)$blog_article_id . "' AND status=1 AND blog_article_reply_id='0' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
				return $sql->rows;
			} else {
						
				if ($start < 0) {
					$start = 0;
				}
				
				if ($limit < 1) {
					$limit = 1000;
				}	
				
				$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_comment` WHERE blog_article_reply_id='" . (int)$blog_comment_id . "' AND status=1 ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
				return $sql->rows;
			}
		}
		
		public function addArticleComment($blog_article_id, $data) {
					
			if($this->config->get('blog_comment_auto_approval')) {
				$status = 1;
			} else {
				$status = 0;
			}
			
			if($data['reply_id']) {
				//echo "INSERT INTO `" . DB_PREFIX . "blog_comment` SET blog_article_id='" . (int)$blog_article_id . "', blog_article_reply_id='" . (int)$data['reply_id'] . "', author='" . $this->db->escape($data['name']) . "', comment='" . $this->db->escape($data['text']) . "', status='" . (int)$status . "', date_added=NOW(), date_modified=NOW()";
				//echo $data['reply_id']; exit;
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_comment` SET blog_article_id='" . (int)$blog_article_id . "', blog_article_reply_id='" . (int)$data['reply_id'] . "', author='" . $this->db->escape($data['name']) . "', comment='" . $this->db->escape($data['text']) . "', status='" . (int)$status . "', date_added=NOW(), date_modified=NOW()");
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_comment` SET blog_article_id='" . (int)$blog_article_id . "', author='" . $this->db->escape($data['name']) . "', comment='" . $this->db->escape($data['text']) . "', status='" . (int)$status . "', date_added=NOW(), date_modified=NOW()");
			}
		}
		
		public function getCategory($blog_category_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "blog_category` sbc LEFT JOIN `" . DB_PREFIX . "blog_category_description` sbcd ON (sbc.blog_category_id = sbcd.blog_category_id) LEFT JOIN `" . DB_PREFIX . "blog_category_to_store` sbcs ON (sbc.blog_category_id = sbcs.blog_category_id) WHERE sbc.blog_category_id = '" . (int)$blog_category_id . "' AND sbcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND sbcs.store_id = '" . (int)$this->config->get('config_store_id') . "' AND sbc.status = '1'");
			
			return $query->row;
		}	
		
		public function getTotalArticleCategoryWise($data = array()) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sba.blog_article_id)) AS total FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_category` sbac ON(sba.blog_article_id=sbac.blog_article_id) WHERE sbac.blog_category_id='" . (int)$data['blog_article_id'] . "' AND sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'");
			
			return $sql->row['total'];
		}
		
		public function getArticleCategoryWise($data = array()) {
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_category` sbac ON(sba.blog_article_id=sbac.blog_article_id) WHERE sbac.blog_category_id='" . (int)$data['blog_article_id'] . "' AND sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "' ORDER BY sba.date_modified DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
	
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;			
		}
		
		public function getTotalArticleAuthorWise($blog_author_id) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sba.blog_article_id)) AS total FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) WHERE sba.blog_author_id='" . (int)$blog_author_id . "' AND sba.status=1 AND sbau.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'");
			
			return $sql->row['total'];
		}
		
		public function getArticleAuthorWise($data = array()) {
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) WHERE sba.blog_author_id='" . (int)$data['blog_author_id'] . "' AND sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "' ORDER BY sba.date_modified DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
	
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;	
		}		
		
		public function getAuthorInformation($blog_author_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_author` sba LEFT JOIN `" . DB_PREFIX . "blog_author_description` sbau ON(sba.blog_author_id=sbau.blog_author_id) WHERE sba.blog_author_id='" . (int)$blog_author_id . "' AND sba.status=1 AND sbau.language_id='" . $this->config->get('config_language_id') . "'");
			return $sql->row;
		}
		
		public function getArticleModuleWise($data = array()) {
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_category` sbac ON(sba.blog_article_id=sbac.blog_article_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'";
			
			if(!empty($data['filter_category_id'])) {
				$sql .= " AND sbac.blog_category_id='" . (int)$data['filter_category_id'] . "'";
			}
			
			$sql .= " GROUP BY sba.blog_article_id ORDER BY sba.date_added DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
	
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			//echo $sql; exit;
			
			$query = $this->db->query($sql);
			
			return $query->rows;	
		}
		
		public function getPopularArticlesModuleWise($data = array()) {
					
			$sql = "SELECT * FROM `" . DB_PREFIX . "blog_view`";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
	
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
				
			$query = $this->db->query($sql);
			
			if($query->num_rows) {
				$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_store` sbas ON(sba.blog_article_id=sbas.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) LEFT JOIN `" . DB_PREFIX . "blog_article_to_category` sbac ON(sba.blog_article_id=sbac.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_view` sbv ON(sbv.blog_article_id=sba.blog_article_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'";
			
				$sql .= "  GROUP BY sba.blog_article_id ORDER BY sbv.view DESC";
				
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}				
		
					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}	
		
					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}	
				
				//echo $sql; exit;
				
				$my_query = $this->db->query($sql);
			
				return $my_query->rows;
			} else {
				return '';
			}
			
		}
		
		public function getRelatedArticles($blog_article_id) {
					
			$this->load->model('tool/image');	
			
			$blog_related_article_data = array();	
			
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_related_article` WHERE blog_article_id='" . (int)$blog_article_id . "' AND status=1 ORDER BY sort_order");
			
			foreach($sql->rows as $row) {
				$article_info = $this->db->query("SELECT sba.*, sbad.article_title AS article_title, sbad.description AS description, sbad.short_description AS short_description,  sbau.blog_author_id AS blog_author_id, sbau.name AS author_name FROM `" . DB_PREFIX . "blog_article` sba LEFT JOIN `" . DB_PREFIX . "blog_article_description` sbad ON(sba.blog_article_id=sbad.blog_article_id) LEFT JOIN `" . DB_PREFIX . "blog_author` sbau ON(sba.blog_author_id=sbau.blog_author_id) WHERE sba.blog_article_id='" . (int)$row['blog_article_related_id'] . "' AND sbad.language_id='" . (int)$this->config->get('config_language_id') . "' AND sba.status=1 AND sbau.status=1");
				
				if($article_info->row) {
					
					$total_comment = $this->getTotalComments($row['blog_article_related_id']);
					
					$image = $this->model_tool_image->resize($article_info->row['featured_image'], 150, 150);
					
					$blog_related_article_data[] = array(
						'blog_article_id'	=> $article_info->row['blog_article_id'],
						'article_title'		=> $article_info->row['article_title'],
						'blog_author_id'	=> $article_info->row['blog_author_id'],
						'image'				=> $image,
						'description'		=> $article_info->row['description'],
						'short_description'		=> $article_info->row['short_description'],
						'author_name'		=> $article_info->row['author_name'],
						'date_added'		=> date('F jS, Y', strtotime($article_info->row['date_added'])),
						'date_modified'		=> date('F jS, Y', strtotime($article_info->row['date_modified'])),
						'total_comment'		=> $total_comment
					);	
				}				
			}
			
			
			return $blog_related_article_data;
		}
		
		public function getAuthors() {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_author` WHERE status=1");
			
			return $sql->rows;
		}
		
	}