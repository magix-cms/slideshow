<?php

/**
 * Class plugins_gmap_db
 */
class plugins_slideshow_db
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 */
    public function fetchData($config, $params = false)
	{
        $sql = '';

        if(is_array($config)) {
            if($config['context'] === 'all') {
            	switch ($config['type']) {
					case 'slides':
						$sql = 'SELECT 
									id_slide,
									url_slide,
									img_slide,
									title_slide,
									desc_slide
 								FROM mc_slideshow as sl
								LEFT JOIN mc_slideshow_content as slc USING(id_slide)
								LEFT JOIN mc_lang as l USING(id_lang)
								WHERE id_lang = :default_lang
								ORDER BY order_slide';
						break;
					case 'homeSlides':
						$sql = 'SELECT 
									id_slide,
									url_slide,
									blank_slide,
									img_slide,
									title_slide,
									desc_slide
 								FROM mc_slideshow as sl
								LEFT JOIN mc_slideshow_content as slc USING(id_slide)
								LEFT JOIN mc_lang as l USING(id_lang)
								WHERE iso_lang = :lang
								AND published_slide = 1
								ORDER BY order_slide';
						break;
					case 'slide':
						$sql = 'SELECT a.*,c.*
                    			FROM mc_slideshow AS a
                    			JOIN mc_slideshow_content AS c USING(id_slide)
                    			JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    			WHERE c.id_lang = :default_lang';
						break;
					case 'slideContent':
						$sql = 'SELECT a.*,c.*
                    			FROM mc_slideshow AS a
                    			JOIN mc_slideshow_content AS c USING(id_slide)
                    			JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)
                    			WHERE c.id_slide = :id';
						break;
					case 'img':
						$sql = 'SELECT s.id_slide, s.img_slide
                        		FROM mc_slideshow AS s WHERE s.img_slide IS NOT NULL';
						break;
                    case 'WSslides':
                        $sql = 'SELECT 
									id_slide,
									slc.url_slide,
									sl.img_slide,
									slc.title_slide,
									slc.desc_slide,
									slc.id_lang,
									l.iso_lang,l.default_lang
 								FROM mc_slideshow as sl
								JOIN mc_slideshow_content as slc USING(id_slide)
								JOIN mc_lang as l USING(id_lang)';
                        break;
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
				switch ($config['type']) {
					case 'slideContent':
						$sql = 'SELECT * FROM mc_slideshow_content WHERE id_slide = :id AND id_lang = :id_lang';
						break;
					case 'lastSlide':
						$sql = 'SELECT * FROM mc_slideshow ORDER BY id_slide DESC LIMIT 0,1';
						break;
					case 'img':
						$sql = 'SELECT * FROM mc_slideshow WHERE id_slide = :id';
						break;
				}

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }

    /**
     * @param $config
     * @param array $params
	 * @return bool|string
     */
    public function insert($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'slide':
				$sql = 'INSERT INTO mc_slideshow(img_slide, order_slide) 
						SELECT img_slide, COUNT(id_slide) FROM mc_slideshow';
				break;
			case 'slideContent':
				$sql = 'INSERT INTO mc_slideshow_content(id_slide, id_lang, title_slide, desc_slide, url_slide, blank_slide, published_slide)
						VALUES (:id_slide, :id_lang, :title_slide, :desc_slide, :url_slide, :blank_slide, :published_slide)';
				break;
            case 'content':
                $sql = 'INSERT INTO mc_slideshow_content(id_slide, id_lang, title_slide, desc_slide, url_slide, blank_slide, published_slide)
						VALUES (:id_slide, :id_lang, :title_slide, :desc_slide, :url_slide, :blank_slide, :published_slide)';
                break;
			case 'img':
				$sql = 'UPDATE mc_slideshow 
						SET img_slide = :img_slide
						WHERE id_slide = :id_slide';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
    public function update($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'slideContent':
				$sql = 'UPDATE mc_slideshow_content
						SET 
							title_slide = :title_slide,
							desc_slide = :desc_slide,
							url_slide = :url_slide,
							blank_slide = :blank_slide,
							published_slide = :published_slide
						WHERE id_slide_content = :id 
						AND id_lang = :id_lang';
				break;
            case 'content':
                $sql = 'UPDATE mc_slideshow_content
						SET 
							title_slide = :title_slide,
							desc_slide = :desc_slide,
							url_slide = :url_slide,
							blank_slide = :blank_slide,
							published_slide = :published_slide
						WHERE id_slide = :id_slide 
						AND id_lang = :id_lang';
                break;
			case 'img':
				$sql = 'UPDATE mc_slideshow
						SET 
							img_slide = :img
						WHERE id_slide = :id';
				break;
			case 'order':
				$sql = 'UPDATE mc_slideshow 
						SET order_slide = :order_slide
						WHERE id_slide = :id_slide';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	protected function delete($config, $params = array()) {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'slide':
				$sql = 'DELETE FROM mc_slideshow
						WHERE id_slide = :id';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}