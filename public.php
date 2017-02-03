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
class plugins_slideshow_public extends db_slideshow{
	/**
	 * @access private
	 * retourne le chemin public de l'image
	 */
	public function dir_img_slide($img_slide){
		$filter = new magixglobal_model_imagepath();
		return $filter->filterPathImg(array('img'=>'upload/slideshow/'.$img_slide));
	}

    /**
     * @access private
     * Retourne un tableau des valeurs du slider suivant la langue
     * @param $id
     * @param $plugin
     * @return array
     */
    public static function collectionData($id,$plugin){
		return parent::s_slideshow_data($id,$plugin);
	}
}
class db_slideshow{
    /**
     * @access protected
     * Retourne les éléments suivant le type sélectionné
     * @param $id
     * @param $plugin
     * @return array
     */
    protected function s_slideshow_data($id,$plugin){
        /**
         * switch table
         */
        switch($plugin){
            case 'category':
                $table = 'mc_plugins_slideshow_category';
                $join = '';
                $where = ' WHERE sl.idclc = :id';
                break;
            case 'subcategory':
                $table = 'mc_plugins_slideshow_subcategory';
                $join = '';
                $where = ' WHERE sl.idcls = :id';
                break;
            case 'root':
                $table = 'mc_plugins_slideshow';
                $join = ' JOIN
		        mc_lang AS lang ON ( sl.idlang = lang.idlang ) ';
                $where = ' WHERE lang.iso = :id ';
                break;
        }
        $sql =
            'SELECT
              sl.*
            FROM
            '.$table.' AS sl
		    '.$join.'
		    '.$where.'
		    ORDER BY
		      sl.pos_slide
		';
        return magixglobal_model_db::layerDB()->select($sql,array(
            ':id'   =>	$id,
        ));
    }
}
?>