<?php

/**
 * Class plugins_gmap_db
 */
class plugins_slideshow_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
    public function fetchData(array $config, array $params = []) {
        if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'slides':
					$query = 'SELECT 
								id_slide,
								link_url_slide,
								link_label_slide,
								link_title_slide,
								img_slide,
								title_slide,
								desc_slide,
								order_slide
							FROM mc_slideshow as sl
							LEFT JOIN mc_slideshow_content as slc USING(id_slide)
							LEFT JOIN mc_lang as l USING(id_lang)
							WHERE id_lang = :default_lang
							ORDER BY order_slide';
					break;
				case 'homeSlides':
					$query = 'SELECT 
								id_slide,
								link_url_slide,
								link_label_slide,
								link_title_slide,
								blank_slide,
								img_slide,
								title_slide,
								desc_slide,
								order_slide
							FROM mc_slideshow as sl
							LEFT JOIN mc_slideshow_content as slc USING(id_slide)
							LEFT JOIN mc_lang as l USING(id_lang)
							WHERE iso_lang = :lang
							AND published_slide = 1
							ORDER BY order_slide';
					break;
				case 'slide':
					$query = 'SELECT a.*,c.*
							FROM mc_slideshow AS a
							JOIN mc_slideshow_content AS c USING(id_slide)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_lang = :default_lang';
					break;
				case 'slideContent':
					$query = 'SELECT a.*,c.*
							FROM mc_slideshow AS a
							JOIN mc_slideshow_content AS c USING(id_slide)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
							WHERE c.id_slide = :id';
					break;
				case 'img':
					$query = 'SELECT s.id_slide, s.img_slide
							FROM mc_slideshow AS s WHERE s.img_slide IS NOT NULL';
					break;
				case 'WSslides':
					$query = 'SELECT 
								id_slide,
								slc.link_url_slide,
								slc.link_label_slide,
								slc.link_title_slide,
								sl.img_slide,
								slc.title_slide,
								slc.desc_slide,
								slc.id_lang,
								l.iso_lang,l.default_lang
							FROM mc_slideshow as sl
							JOIN mc_slideshow_content as slc USING(id_slide)
							JOIN mc_lang as l USING(id_lang)';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'slideContent':
					$query = 'SELECT * FROM mc_slideshow_content WHERE id_slide = :id AND id_lang = :id_lang';
					break;
				case 'lastSlide':
					$query = 'SELECT * FROM mc_slideshow ORDER BY id_slide DESC LIMIT 0,1';
					break;
				case 'img':
					$query = 'SELECT * FROM mc_slideshow WHERE id_slide = :id';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

    /**
     * @param array $config
     * @param array $params
	 * @return bool
     */
    public function insert(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'slide':
				$query = "INSERT INTO mc_slideshow(img_slide, order_slide) SELECT '', COUNT(id_slide) FROM mc_slideshow";
				break;
			case 'slideContent':
            case 'content':
                $query = 'INSERT INTO mc_slideshow_content(id_slide, id_lang, title_slide, desc_slide, link_url_slide, link_label_slide, link_title_slide, blank_slide, published_slide)
						VALUES (:id_slide, :id_lang, :title_slide, :desc_slide, :link_url_slide, :link_label_slide, :link_title_slide, :blank_slide, :published_slide)';
                break;
			case 'img':
				$query = 'UPDATE mc_slideshow SET img_slide = :img_slide WHERE id_slide = :id_slide';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
    public function update(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'slideContent':
				$query = 'UPDATE mc_slideshow_content
						SET 
							title_slide = :title_slide,
							desc_slide = :desc_slide,
							link_url_slide = :link_url_slide,
							link_label_slide = :link_label_slide,
							link_title_slide = :link_title_slide,
							blank_slide = :blank_slide,
							published_slide = :published_slide
						WHERE id_slide_content = :id 
						AND id_lang = :id_lang';
				break;
            case 'content':
                $query = 'UPDATE mc_slideshow_content
						SET 
							title_slide = :title_slide,
							desc_slide = :desc_slide,
							link_url_slide = :link_url_slide,
							link_label_slide = :link_label_slide,
							link_title_slide = :link_title_slide,
							blank_slide = :blank_slide,
							published_slide = :published_slide
						WHERE id_slide = :id_slide 
						AND id_lang = :id_lang';
                break;
			case 'img':
				$query = 'UPDATE mc_slideshow
						SET 
							img_slide = :img
						WHERE id_slide = :id';
				break;
			case 'order':
				$query = 'UPDATE mc_slideshow 
						SET order_slide = :order_slide
						WHERE id_slide = :id_slide';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	protected function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'slide':
				$query = 'DELETE FROM mc_slideshow WHERE id_slide = :id';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
	}
}