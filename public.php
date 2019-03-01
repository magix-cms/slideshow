<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Magix CMS.
# Magix CMS, a CMS optimized for SEO
# Copyright (C) 2010 - 2011  Gerits Aurelien <aurelien@magix-cms.com>
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# -- END LICENSE BLOCK -----------------------------------
/**
 * MAGIX CMS
 * @category   Slideshow 
 * @package    plugin
 * @copyright  MAGIX CMS Copyright (c) 2011 Gerits Aurelien, 
 * http://www.magix-dev.be, http://www.magix-cms.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.0
 * @create    26-08-2011
 * @Update     12-09-2011
 * @Update     
 * @author Gérits Aurélien <aurelien@magix-cms.com>
 * @name Slideshow
 *
 */
class plugins_slideshow_public extends plugins_slideshow_db {
	protected $template, $data, $getlang, $imagesComponent;

	/**
	 * plugins_slideshow_public constructor.
	 */
	public function __construct(){
		$this->template = new frontend_model_template();
		$this->data = new frontend_model_data($this);
		$this->getlang = $this->template->currentLanguage();
		$this->imagesComponent = new component_files_images($this->template);
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @param $data
	 * @return array
	 */
	private function setItemSlideData($data)
	{
		$arr = array();
        $extwebp = 'webp';
		foreach ($data as $slide) {
			$arr[$slide['id_slide']] = array();
			$arr[$slide['id_slide']]['id_slide'] = $slide['id_slide'];
			$arr[$slide['id_slide']]['id_lang'] = $slide['id_lang'];
			$arr[$slide['id_slide']]['title_slide'] = $slide['title_slide'];
			$arr[$slide['id_slide']]['desc_slide'] = $slide['desc_slide'];
			$arr[$slide['id_slide']]['url_slide'] = $slide['url_slide'];
			$arr[$slide['id_slide']]['blank_slide'] = $slide['blank_slide'];

			$imgPrefix = $this->imagesComponent->prefix();
			$fetchConfig = $this->imagesComponent->getConfigItems(array(
				'module_img'    =>'plugins',
				'attribute_img' =>'slideshow'
			));
            $imgData = pathinfo($slide['img_slide']);
            $filename = $imgData['filename'];
			foreach ($fetchConfig as $key => $value) {
				$arr[$slide['id_slide']]['img'][$value['type_img']]['src'] = '/upload/slideshow/'.$slide['id_slide'].'/'.$imgPrefix[$value['type_img']] . $slide['img_slide'];
                $arr[$slide['id_slide']]['img'][$value['type_img']]['src_webp'] = '/upload/slideshow/'.$slide['id_slide'].'/'.$imgPrefix[$value['type_img']] . $filename . '.'.$extwebp;
				$arr[$slide['id_slide']]['img'][$value['type_img']]['w'] = $value['width_img'];
				$arr[$slide['id_slide']]['img'][$value['type_img']]['h'] = $value['height_img'];
				$arr[$slide['id_slide']]['img'][$value['type_img']]['crop'] = $value['resize_img'];
			}
		}
		return $arr;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	public function getSlides($params = array())
	{
		if(!is_array($params) || empty($params)) {
			$slides = $this->getItems('homeSlides',array('lang' => $this->getlang),'all', false);
			return $this->setItemSlideData($slides);
		}
	}
}