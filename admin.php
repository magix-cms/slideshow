<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
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

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * MAGIX CMS
 * @category   Slideshow
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2011 - 2013 Gerits Aurelien,
 * http://www.magix-dev.be, http://www.magix-cms.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * @create    26-08-2011
 * @Update    27-11-2013
 * @author Gérits Aurélien <contact@magix-dev.be>
 * @name slideshow
 * Administration du module slideshow
 *
 */
class plugins_slideshow_admin extends db_slideshow{
	/**
	 * 
	 * POST
	 * @var $idlang,
	 * @var $img_slide,
	 * @var $uri_slide,
	 * @var $title_slide,
	 * @var $desc_slide,
	 * @var $pos_slide
	 */
	public $idlang,
	$img_slide,
	$uri_slide,
	$title_slide,
	$desc_slide,
	$sliderorder;
	/**
	 * 
	 * GET
	 * @var $getlang,
	 * @var $edit
	 */
	public $getlang,$action,$edit,$tab,$del_slide,$id;
	/**
	 * 
	 * Constructor
	 */
	public function __construct(){
        if(magixcjquery_filter_request::isGet('getlang')){
            $this->getlang = magixcjquery_filter_isVar::isPostNumeric($_GET['getlang']);
        }
		if(isset($_FILES['img_slide']["name"])){
			$this->img_slide = magixcjquery_url_clean::rplMagixString($_FILES['img_slide']["name"]);
		}
		if(magixcjquery_filter_request::isPost('uri_slide')){
			$this->uri_slide = magixcjquery_form_helpersforms::inputClean($_POST['uri_slide']);
		}
		if(magixcjquery_filter_request::isPost('title_slide')){
			$this->title_slide = magixcjquery_form_helpersforms::inputClean($_POST['title_slide']);
		}
		if(magixcjquery_filter_request::isPost('desc_slide')){
			$this->desc_slide = magixcjquery_form_helpersforms::inputClean($_POST['desc_slide']);
		}
		if(magixcjquery_filter_request::isPost('sliderorder')){
			$this->sliderorder = magixcjquery_form_helpersforms::arrayClean($_POST['sliderorder']);
		}
		if(magixcjquery_filter_request::isGet('getlang')){
			$this->getlang = (integer) magixcjquery_filter_isVar::isPostNumeric($_GET['getlang']);
		}
		if(magixcjquery_filter_request::isGet('edit')){
			$this->edit = magixcjquery_filter_isVar::isPostNumeric($_GET['edit']);
		}
        if(magixcjquery_filter_request::isGet('id')){
            $this->id = (integer) magixcjquery_filter_isVar::isPostNumeric($_GET['id']);
        }
		if(magixcjquery_filter_request::isPost('del_slide')){
			$this->del_slide = (integer) magixcjquery_filter_isVar::isPostNumeric($_POST['del_slide']);
		}
        if(magixcjquery_filter_request::isGet('action')){
            $this->action = magixcjquery_form_helpersforms::inputClean($_GET['action']);
        }
        if(magixcjquery_filter_request::isGet('tab')){
            $this->tab = magixcjquery_form_helpersforms::inputClean($_GET['tab']);
        }
	}
	/**
	 * @access private
	 * load sql file
	 */
	private function load_sql_file(){
		return backend_controller_plugins::create()->pluginDir().'sql'.DIRECTORY_SEPARATOR.'db.sql';
	}
	/**
	 * @access private
	 * Installation des tables mysql du plugin
	 */
	private function install_table(){
		if(parent::c_show_table() == 0){
			$makeFiles = new magixcjquery_files_makefiles();
			$upload_dir = magixglobal_model_system::base_path().'upload/';
			$makeFiles->createDir($upload_dir,'slideshow');
			backend_controller_plugins::create()->db_install_table('db.sql', 'request/install.tpl');
		}else{
			//magixcjquery_debug_magixfire::magixFireInfo('Les tables mysql sont installés', 'Statut des tables mysql du plugin');
			return true;
		}
	}
	/**
	 * @access private
	 * Le dossier images des slides 
	 */
	private function dir_upload_img(){
		return magixglobal_model_system::base_path().'upload/slideshow/';
	}
	/**
	 * @access private
	 * retourne le chemin public de l'image
	 */
	private function dir_img_slide($img_slide){
		$filter = new magixglobal_model_imagepath();
		return $filter->filterPathImg(array('img'=>'upload/slideshow/'.$img_slide));
	}
	/**
	 * @access private
	 * Génération d'un identifiant alphanumérique avec une longueur définie
	 * @param integer $numString
     * @return string
     */
	private function extract_random_idslide($numString){
		return magixglobal_model_cryptrsa::short_alphanumeric_id($numString);
	}

    /**
     * @access private
     * Insert une image dans le slide
     * @param $table
     * @param $img_slide
     * @param $confimg
     * @param bool $update
     * @return string
     */
    private function insert_image_slide($table,$img_slide,$confimg,$update=false){
		if(isset($this->img_slide)){
			try{
                $initImg = new backend_model_image();
				$makeFiles = new magixcjquery_files_makefiles();
				//Si on demande une modification de l'image
				if($update == true){
                    switch($table){
                        case 'root':
                            $vimage = parent::s_edit_img($this->edit,$table);
                            break;
                        case 'category':
                        case 'subcategory':
                            $vimage = parent::s_edit_img($this->id,$table);
                            break;
                    }

					if(file_exists(self::dir_upload_img().$vimage['img_slide'])){
						$makeFiles->removeFile(self::dir_upload_img(),$vimage['img_slide']);
						$makeFiles->removeFile(self::dir_upload_img(),'s_'.$vimage['img_slide']);
					}else{
						throw new Exception('file: '.$vimage['img_slide'].' is not found');
					}
				}
				/**
				 * Envoi une image dans le dossier "racine" catalogimg
				 */
				backend_model_image::upload_img($confimg,'upload'.DIRECTORY_SEPARATOR.'slideshow'.DIRECTORY_SEPARATOR);
				/**
				 * Analyze l'extension du fichier en traitement
				 * @var $fileextends
				 */
				$fileextends = backend_model_image::image_analyze(self::dir_upload_img().$img_slide);
				/**
				 * @var
				 */
				$rimage = magixglobal_model_cryptrsa::uniq_id();
				/**
				 * Initialisation de la classe phpthumb 
				 * @var void
				 */
				try{
					$thumb = PhpThumbFactory::create(self::dir_upload_img().$img_slide,array('jpegQuality'=>70));
				}catch (Exception $e)
				{
				     magixglobal_model_system::magixlog('An error has occured :',$e);
				}
				//Réécriture de l'image
				$imageuri = $rimage.$fileextends;
				$imgsetting = new backend_model_setting();
				$imgsize = $initImg->dataImgSize('plugins','slideshow','large');
				//Redimensionnement suivant les paramètres défini dans l'administration des tailles
				switch($imgsize['img_resizing']){
					case 'basic':
						$thumb->resize($imgsize['width'],$imgsize['height'])->save(self::dir_upload_img().$imageuri);
					break;
					case 'adaptive':
						$thumb->adaptiveResize($imgsize['width'],$imgsize['height'])->save(self::dir_upload_img().$imageuri);
					break;
				}
				//Création de la miniature pour l'administration !!!
				$thumb->resize(200)->save(self::dir_upload_img().'s_'.$imageuri);
				//Supprime le fichier original pour gagner en espace
				if(file_exists(self::dir_upload_img().$img_slide)){
					$makeFiles->removeFile(self::dir_upload_img(),$img_slide);
				}else{
					throw new Exception('file: '.$img_slide.' is not found');
				}
				return $imageuri;
			}catch (Exception $e){
				magixglobal_model_system::magixlog('An error has occured :',$e);
			}
		}
	}

    /**
     * @access private
     * Insertion d'un nouveau slide
     * @param $create
     * @param $table
     */
    private function add($create,$id,$table){
		if(isset($this->title_slide)){
			if(empty($this->title_slide) OR empty($this->img_slide)){
				$create->display('request/empty_slide.tpl');
			}else{
				if(isset($this->img_slide)){
					$img = $this->insert_image_slide($table,$this->img_slide,'img_slide',false);
					parent::i_slideshow(
                        $table,
						$id,
						$this->uri_slide, 
						$img, 
						$this->title_slide, 
						$this->desc_slide
					);
					$create->display('request/success_add.tpl');
				}
			}
		}
	}

    /**
     * @access private
     * Edition des données du slider
     * @param $create
     * @param $id
     * @param $table
     */
    private function update_data($create,$id,$table){
        if(empty($this->title_slide)){
            $create->display('request/empty.tpl');
        }else{
            parent::u_slideshow_data(
                $table,
                $this->uri_slide,
                $this->title_slide,
                $this->desc_slide,
                $id
            );
            $create->display('request/success_update.tpl');
        }
    }

    /**
     * Save data
     * @param $create
     * @param $table
     */
    private function save($create,$id,$table){
        switch($table){
            case 'root':
                if (isset($this->edit)){
                    $this->update_data($create,$id,$table);
                }else{
                    $this->add($create,$id,$table);
                }
                break;
            case 'category':
            case 'subcategory':
                if (isset($this->id)){
                    $this->update_data($create,$id,$table);
                }else{
                    $this->add($create,$id,$table);
                }
                break;

        }

    }

    /**
     * @access private
     * Edite l'image du slider ou écrase l'image du slider
     * @param $create
     * @param $table
     */
    private function update_img($create,$table){
        if(empty($this->img_slide)){
            $create->display('request/empty.tpl');
        }else{
            switch($table){
                case 'root':
                    $img = $this->insert_image_slide($table,$this->img_slide,'img_slide',true);
                    $id = $this->edit;
                    break;
                case 'category':
                case 'subcategory':
                    $img = $this->insert_image_slide($table,$this->img_slide,'img_slide',true);
                    $id = $this->id;
                    break;
            }
            parent::u_slideshow_img(
                $table,
                $img,
                $id
            );
        }
    }

	/**
	 * @access private
	 * Retourne l'image et la langue suivant l'identifiant
	 * @param integer $idlang
     * @return string
     */
	private function slide_language($idlang){
		$db = backend_db_block_lang::s_data_iso($idlang);
		return magixcjquery_string_convert::ucFirst($db['language']);
	}

	/**
	 * @access private
	 * Retourne les slideshows sous format JSON
	 */
	private function json_slideshow($table){
        switch($table){
            case 'root':
                $query = parent::s_slideshow_data($this->getlang,$table);
                break;
            case 'category':
            case 'subcategory':
            $query = parent::s_slideshow_data($this->edit,$table);
                break;
        }
        $json = new magixglobal_model_json();
		if($query != null){
			foreach ($query as $key){
				$slide[]= '{"idslide":'.json_encode($key['idslide']).
				',"uri_slide":'.json_encode($key['uri_slide']).',"img_slide":'.json_encode($this->dir_img_slide($key['img_slide'])).
				',"title_slide":'.json_encode($key['title_slide']).',"desc_slide":'.json_encode($key['desc_slide']).'}';
			}
            $json->encode($slide,array('[',']'));
		}else{
            print '{}';
        }
	}

	/**
	 * @access private
	 * Chargement des données du slideshow
	 */
	private function load_data($create,$table){
        switch($table){
            case 'root':

                $data = parent::s_edit_img($this->edit,$table);
                $create->assign(
                    array(
                        'title_slide'   =>  $data['title_slide'],
                        'desc_slide'    =>  $data['desc_slide'],
                        'uri_slide'     =>  $data['uri_slide'],
                        'header_lang'   =>  $this->slide_language($data['idlang'])
                    )
                );
                break;
            case 'category':
            case 'subcategory':
                $data = parent::s_edit_img($this->id,$table);
                $create->assign(
                    array(
                        'title_slide'   =>  $data['title_slide'],
                        'desc_slide'    =>  $data['desc_slide'],
                        'uri_slide'     =>  $data['uri_slide']
                    )
                );
                break;

        }


	}

    /**
     * @access private
     * Charge les données de l'image du slideshow courant
     */
	private function json_slider_image($table){
        switch($table){
            case 'root':

                $data = parent::s_edit_img($this->edit,$table);
                break;
            case 'category':
            case 'subcategory':
                $data = parent::s_edit_img($this->id,$table);
                break;

        }

		if($data['img_slide'] != null){
			$img = '<a class="img-zoom" title="'.$data['title_slide'].'" href="'.$this->dir_img_slide($data['img_slide']).'">
			<img class="img-thumbnail" src="'.$this->dir_img_slide('s_'.$data['img_slide']).'" alt="" /></a>';
		}else{
			$img = '<img data-src="holder.js/140x140/text:Thumbnails" class="img-thumbnail" />';
		}
		print $img;
	}

	/**
	 * Execute Update AJAX FOR order hmenu
	 * Modifie l'ordre des pages
	 * @access private
	 *
	 */
	public function executeOrderSlider($table){
		if(isset($this->sliderorder)){
			$slides = $this->sliderorder;
            $i = 0;
			foreach ($slides as $slide) {
				parent::u_order_slider($table,$i,$slide);
                $i++;
			}
		}
	}

    /**
     * Suppression d'une image + données slider
     * @access private
     * @param $table
     * @param $del_slide
     * @throws Exception
     */
    private function delete_slider($table,$del_slide){
		if(isset($del_slide)){
			$makeFiles = new magixcjquery_files_makefiles();

            $vimage = parent::s_edit_img($del_slide,$table);

			if(file_exists(self::dir_upload_img().$vimage['img_slide'])){
				$makeFiles->removeFile(self::dir_upload_img(),$vimage['img_slide']);
				$makeFiles->removeFile(self::dir_upload_img(),'s_'.$vimage['img_slide']);
			}else{
				throw new Exception('file: '.$vimage['img_slide'].' is not found');
			}
			parent::d_slider($table,$del_slide);
		}
	}
	/**
	 * Affiche les pages de l'administration du plugin
	 * @access public
	 */
	public function run(){
		$header= new magixglobal_model_header();
		$create = new backend_controller_plugins();
		//Installation des tables mysql
		if(self::install_table() == true){
            if(magixcjquery_filter_request::isGet('getlang')){
				//Ajout d'un élément suivant la langue
				if(magixcjquery_filter_request::isGet('json_list_records')){
					$header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
					$header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
					$header->pragma();
					$header->cache_control("nocache");
					$header->getStatus('200');
					$header->json_header("UTF-8");
					$this->json_slideshow('root');
				}else{
                    if(isset($this->action)){
                        if($this->action == 'list'){
                            if(isset($this->sliderorder)){
                                $this->executeOrderSlider('root');
                            }else{
                                $create->assign('header_lang',$this->slide_language($this->getlang));
                                $create->display('list.tpl');
                            }
                        }elseif($this->action == 'add'){
                            if(isset($this->title_slide)){
                                $this->save($create,$this->getlang,'root');
                            }
                        }elseif($this->action == 'edit'){
                            if(isset($this->edit) && !isset($_GET['plugin'])){
                                //Edition des données ou de l'image
                                if(isset($this->title_slide)){
                                    $this->save($create,$this->edit,'root');
                                }elseif(magixcjquery_filter_request::isGet('jsonimg')){
                                    $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                                    $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                                    $header->pragma();
                                    $header->cache_control("nocache");
                                    $header->getStatus('200');
                                    $header->html_header("UTF-8");
                                    $this->json_slider_image('root');
                                }elseif($this->img_slide){
                                    $this->update_img($create,'root');
                                }else{
                                    $this->load_data($create,'root');
                                    $create->display('edit.tpl');
                                }
                            }
                        }elseif($this->action == 'remove'){
                            if(isset($this->del_slide)){
                                $this->delete_slider('root',$this->del_slide);
                            }
                        }
                    }elseif(isset($this->tab)){
                        $create->display('about.tpl');
                    }
				}
			}
		}else{
			// Retourne la page index.tpl
			$create->display('index.tpl');
		}
	}

    /**
     * Configuration du plugin
     * @return array
     */
    public function setConfig(){
        return array(
            'url'=> array(
                'lang'=>'list',
                'action'=>'list'
            )
        );
    }

    /**
     * Intégration du plugin dans les catégories du catalogue
     * @param $plugin
     * @param $getlang
     * @param $edit
     */
    public function catalog_category($plugin,$getlang,$edit){
        $create = new backend_controller_plugins();
        $header= new magixglobal_model_header();
        $json = new magixglobal_model_json();
        if(isset($_GET['plugin'])){
            if(magixcjquery_filter_request::isGet('json_list_records')){
                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                $header->pragma();
                $header->cache_control("nocache");
                $header->getStatus('200');
                $header->json_header("UTF-8");
                $this->json_slideshow('category');
            }elseif(isset($this->sliderorder)){
                $this->executeOrderSlider('category');
            }elseif(magixcjquery_filter_request::isGet('jsoncatimg')){
                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                $header->pragma();
                $header->cache_control("nocache");
                $header->getStatus('200');
                $header->html_header("UTF-8");
                $this->json_slider_image('category');
            }else{
                if(isset($this->title_slide)){
                    if(isset($this->id)){
                        $this->save($create,$this->id,'category');
                    }else{
                        $this->save($create,$this->edit,'category');
                    }

                }elseif($this->img_slide){
                    $this->update_img($create,'category');
                }elseif(isset($this->del_slide)){
                    $this->delete_slider('category',$this->del_slide);
                }else{
                    $this->load_data($create,'category');
                    $create->display('catalog-category.tpl');
                }
            }
        }
    }

    /**
     * Intégration du plugin dans les sous catégories du catalogue
     * @param $plugin
     * @param $getlang
     * @param $edit
     */
    public function catalog_subcategory($plugin,$getlang,$edit){
        $create = new backend_controller_plugins();
        $header= new magixglobal_model_header();
        $json = new magixglobal_model_json();
        if(isset($_GET['plugin'])){
            if(magixcjquery_filter_request::isGet('json_list_records')){
                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                $header->pragma();
                $header->cache_control("nocache");
                $header->getStatus('200');
                $header->json_header("UTF-8");
                $this->json_slideshow('subcategory');
            }elseif(isset($this->sliderorder)){
                $this->executeOrderSlider('subcategory');
            }elseif(magixcjquery_filter_request::isGet('jsoncatimg')){
                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                $header->pragma();
                $header->cache_control("nocache");
                $header->getStatus('200');
                $header->html_header("UTF-8");
                $this->json_slider_image('subcategory');
            }else{
                if(isset($this->title_slide)){
                    if(isset($this->id)){
                        $this->save($create,$this->id,'subcategory');
                    }else{
                        $this->save($create,$this->edit,'subcategory');
                    }
                }elseif($this->img_slide){
                    $this->update_img($create,'subcategory');
                }elseif(isset($this->del_slide)){
                    $this->delete_slider('subcategory',$this->del_slide);
                }else{
                    $this->load_data($create,'subcategory');
                    $create->display('catalog-subcategory.tpl');
                }
            }
        }
    }
    /*public function catalog_product($plugin,$getlang,$edit){
        $create = new backend_controller_plugins();
        $header= new magixglobal_model_header();
        $json = new magixglobal_model_json();
        if(isset($_GET['plugin'])){
            $create->display('catalog.tpl');
        }
    }*/
}
class db_slideshow{
	/**
	 * Vérifie si les tables du plugin sont installé
	 * @access protected
	 * return integer
	 */
	protected function c_show_table(){
		$table = 'mc_plugins_slideshow';
		return magixglobal_model_db::layerDB()->showTable($table);
	}

    /**
     * @access protected
     * Retourne les données de l'élément
     * @param $id
     * @param $plugin
     * @return array
     */
    protected function s_edit_img($id,$plugin){
        switch($plugin){
            case 'root':
                $table = 'mc_plugins_slideshow';
                break;
            case 'category':
                $table = 'mc_plugins_slideshow_category';
                break;
            case 'subcategory':
                $table = 'mc_plugins_slideshow_subcategory';
                break;
        }
		$sql ='SELECT sl.*
		FROM '.$table.' AS sl
		WHERE idslide = :id';
		return magixglobal_model_db::layerDB()->selectOne($sql,array(
			':id'=>$id
		));
	}

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
                $where = ' WHERE sl.idlang = :id ';
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

    /**
     * * @access protected
     * Insertion d'un nouveau slideshow
     * @param $plugin
     * @param $id
     * @param $uri_slide
     * @param $img_slide
     * @param $title_slide
     * @param $desc_slide
     */
    protected function i_slideshow($plugin,$id,$uri_slide,$img_slide,$title_slide,$desc_slide){
        /**
         * switch table
         */
        switch($plugin){

            case 'root':
                $table = 'mc_plugins_slideshow';
                $column = 'idlang';
                break;
            case 'category':
                $table = 'mc_plugins_slideshow_category';
                $column = 'idclc';
                break;
            case 'subcategory':
                $table = 'mc_plugins_slideshow_subcategory';
                $column = 'idcls';
                break;
        }
		$sql = 'INSERT INTO
		'.$table.'
		('.$column.',uri_slide,img_slide,title_slide,desc_slide)
		VALUE(:id,:uri_slide,:img_slide,:title_slide,:desc_slide)';

		magixglobal_model_db::layerDB()->insert($sql,array(
			':id'		    =>	$id,
			':uri_slide'	=>	$uri_slide,
			':img_slide'	=>	$img_slide,
			':title_slide'	=>	$title_slide,
			':desc_slide'	=>	$desc_slide
		));
	}

    /**
     * @access protected
     * Edite les données d'un élément
     * @param $plugin
     * @param $uri_slide
     * @param $title_slide
     * @param $desc_slide
     * @param $edit
     */
    protected function u_slideshow_data($plugin,$uri_slide,$title_slide,$desc_slide,$edit){
        /**
         * switch table
         */
        switch($plugin){
            case 'category':
                $table = 'mc_plugins_slideshow_category';
                break;
            case 'root':
                $table = 'mc_plugins_slideshow';
                break;
            case 'subcategory':
                $table = 'mc_plugins_slideshow_subcategory';
                break;
        }
		$sql =
            'UPDATE
                '.$table.'
            SET
              uri_slide     =   :uri_slide,
              title_slide   =   :title_slide,
              desc_slide    =   :desc_slide
		    WHERE
		    idslide = :edit';

		magixglobal_model_db::layerDB()->update($sql,array(
			':uri_slide'	=>	$uri_slide,
			':title_slide'	=>	$title_slide,
			':desc_slide'	=>	$desc_slide,
			':edit'         =>  $edit
		));
	}

    /**
     * @access protected
     * Modifie le nom de l'image de l'élément
     * @param $plugin
     * @param $img_slide
     * @param $id
     */
    protected function u_slideshow_img($plugin,$img_slide,$id){
        switch($plugin){
            case 'category':
                $table = 'mc_plugins_slideshow_category';
                break;
            case 'root':
                $table = 'mc_plugins_slideshow';
                break;
            case 'subcategory':
                $table = 'mc_plugins_slideshow_subcategory';
                break;
        }
		$sql = 'UPDATE '.$table.'
		SET img_slide=:img_slide
		WHERE idslide = :id';
		magixglobal_model_db::layerDB()->update($sql,array(
			':img_slide'	=>	$img_slide,
			':id'=>$id
		));
	}

    /**
     * @access protected
     * Met à jour l'ordre d'affichage des sliders
     * @param $plugin
     * @param $i
     * @param $id
     */
    protected function u_order_slider($plugin,$i,$id)
    {
        /**
         * switch table
         */
        switch($plugin){
            case 'category':
                $table = 'mc_plugins_slideshow_category';
                break;
            case 'root':
                $table = 'mc_plugins_slideshow';
                break;
            case 'subcategory':
                $table = 'mc_plugins_slideshow_subcategory';
                break;
        }
		$sql = 'UPDATE
		            '.$table.'
		       SET
    		       pos_slide = :i
		       WHERE
		        idslide = :id';
		magixglobal_model_db::layerDB()->update($sql,
			array(
                ':i'=>$i,
                ':id'=>$id
			)
		);
	}

    /**
     * @access protected
     * Suppression d'une carte
     * @param $plugin
     * @param $idslide
     */
    protected function d_slider($plugin,$idslide){
        switch($plugin){
            case 'root':
                $table = 'mc_plugins_slideshow';
                break;
            case 'category':
                $table = 'mc_plugins_slideshow_category';
                break;
            case 'subcategory':
                $table = 'mc_plugins_slideshow_subcategory';
                break;
        }
		$sql = 'DELETE FROM
		'.$table.'
		WHERE idslide = :idslide';
		magixglobal_model_db::layerDB()->delete($sql,
			array(
			':idslide'=>$idslide
			)
		);
	}
}