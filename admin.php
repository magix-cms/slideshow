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
    protected $message,$template;
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
    public static $notify = array('plugin'=>'true','template'=>'message-slideshow.tpl','method'=>'display','assignFetch'=>'notifier');
	/**
	 * 
	 * Constructor
	 */
	public function __construct(){
        if(class_exists('backend_model_message')){
            $this->message = new backend_model_message();
        }
        $this->template = new backend_controller_plugins();

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
     * @param $type
     * @param $img_slide
     * @param $confimg
     * @param bool $update
     * @return string
     */
    private function uploadImg($type,$img_slide,$confimg,$update=false){
		if(isset($this->img_slide)){
			try{
                $initImg = new backend_model_image();
				$makeFiles = new magixcjquery_files_makefiles();
				//Si on demande une modification de l'image
				if($update == true){
                    switch($type){
                        case 'root':
                            $vimage = parent::fetch(array(
                                'context'   =>  'img',
                                'type'      =>  $type,
                                'id'    =>  $this->edit
                            ));
                            break;
                        case 'category':
                        case 'subcategory':
                            $vimage = parent::fetch(array(
                                'context'   =>  'img',
                                'type'      =>  $type,
                                'id'    =>  $this->id
                            ));
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
     * @param $data
     */
    private function add($data){
		if(isset($this->title_slide)){
            if(isset($this->img_slide)){
                $img = $this->uploadImg($data['type'],$this->img_slide,'img_slide',false);
                parent::insert(
                    array(
                        'type'      =>  $data['type'],
                        'id'	    =>	$data['id'],
                        'url'	    =>	$this->uri_slide,
                        'img'	    =>	$img,
                        'name'	    =>	$this->title_slide,
                        'content'   =>	$this->desc_slide
                    )
                );
                $this->getLastItemData($data['type']);
                $this->message->json_post_response(true,'save',$this->template->fetch('loop/items.tpl'),self::$notify);
            }
		}
	}

    /**
     * @access private
     * Edition des données du slider
     * @param $data
     */
    private function setUpdateData($data){
        if(isset($this->title_slide)){
            parent::update(
                array(
                    'context'   =>  'data',
                    'type'      =>  $data['type'],
                    'id'	    =>	$data['id'],
                    'url'	    =>	$this->uri_slide,
                    'name'	    =>	$this->title_slide,
                    'content'   =>	$this->desc_slide
                )
            );
            $this->message->getNotify('update');
        }
    }

    /**
     * Save data
     * @param $data
     */
    private function save($data){
        switch($data['type']){
            case 'root':
                if (isset($this->edit)){
                    $this->setUpdateData($data);
                }else{
                    $this->add($data);
                }
                break;
            case 'cms':
            case 'category':
            case 'subcategory':
                if (isset($this->id)){
                    $this->setUpdateData($data);
                }else{
                    $this->add($data);
                }
                break;

        }

    }

    /**
     * @access private
     * Edite l'image du slider ou écrase l'image du slider
     * @param $data
     */
    private function setUpdateImg($data){
        if(isset($this->img_slide)){
            switch($data['type']){
                case 'root':
                    $img = $this->uploadImg($data['type'],$this->img_slide,'img_slide',true);
                    $id = $this->edit;
                    break;
                case 'cms':
                case 'category':
                case 'subcategory':
                    $img = $this->uploadImg($data['type'],$this->img_slide,'img_slide',true);
                    $id = $this->id;
                    break;
            }
            parent::update(
                array(
                    'context'   =>  'img',
                    'type'      =>  $data['type'],
                    'id'	    =>	$data['id'],
                    'img'	    =>	$img
                )
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
	 * Chargement des données du slideshow
	 */
	private function setData($data){
        switch($data['type']){
            case 'root':

                $setData = parent::fetch(array(
                    'context'   =>  'img',
                    'type'      =>  $data['type'],
                    'id'    =>  $this->edit
                ));
                $this->template->assign(
                    array(
                        'title_slide'   =>  $setData['title_slide'],
                        'desc_slide'    =>  $setData['desc_slide'],
                        'uri_slide'     =>  $setData['uri_slide'],
                        'header_lang'   =>  $this->slide_language($setData['idlang'])
                    )
                );
                break;
            case 'category':
            case 'subcategory':
                $setData = parent::fetch(array(
                    'context'   =>  'img',
                    'type'      =>  $data['type'],
                    'id'    =>  $this->id
                ));
                $this->template->assign(
                    array(
                        'title_slide'   =>  $setData['title_slide'],
                        'desc_slide'    =>  $setData['desc_slide'],
                        'uri_slide'     =>  $setData['uri_slide']
                    )
                );
                break;

        }


	}
    /**
     * Retourne la liste des records
     * @param $type integer
     * @return array
     */
    public function setItemsData($type){
        if($type === 'root'){
            return parent::fetch(array(
                'context'   =>  'all',
                'type'      =>  $type,
                'id'    =>  $this->getlang
            ));
        }elseif($type === 'cms' OR $type === 'category' OR $type === 'subcategory'){
            return parent::fetch(array(
                'context'   =>  'all',
                'type'      =>  $type,
                'id'    =>  $this->edit
            ));
        }
    }

    /**
     * @param $type
     */
    public function getItemsData($type){
        $data = $this->setItemsData($type);
        $this->template->assign('getItemsData',$data);
    }

    /**
     * Retourne le dernier record
     * @param $type
     * @return array
     */
    private function setLastItemData($type){
        if($type === 'root'){
            return parent::fetch(array(
                'context'   =>  'last',
                'type'      =>  $type,
                'id'    =>  $this->getlang
            ));
        }elseif($type === 'cms' OR $type === 'category' OR $type === 'subcategory'){
            return parent::fetch(array(
                'context'   =>  'last',
                'type'      =>  $type,
                'id'    =>  $this->edit
            ));
        }
    }
    /**
     * @param $type
     */
    private function getLastItemData($type){
        $data = $this->setLastItemData($type);
        $this->template->assign('getItemsData',$data);
    }
    /**
     * @access private
     * Charge les données de l'image du slideshow courant
     */
	private function json_slider_image($data){
        switch($data['type']){
            case 'root':

                $setData = parent::fetch(array(
                    'context'   =>  'img',
                    'type'      =>  $data['type'],
                    'id'    =>  $this->edit
                ));
                break;
            case 'cms':
            case 'category':
            case 'subcategory':
            $setData = parent::fetch(array(
                    'context'   =>  'img',
                    'type'      =>  $data['type'],
                    'id'        =>  $this->id
                ));
                break;

        }

		if($setData['img_slide'] != null){
			$img = '<a class="img-zoom" title="'.$setData['title_slide'].'" href="'.$this->dir_img_slide($setData['img_slide']).'">
			<img class="img-thumbnail" src="'.$this->dir_img_slide('s_'.$setData['img_slide']).'" alt="" /></a>';
		}else{
			$img = '<img data-src="holder.js/140x140?text=Thumbnail" class="ajax-image img-thumbnail" />';
		}
		print $img;
	}

	/**
	 * Execute Update AJAX FOR order hmenu
	 * Modifie l'ordre des pages
	 * @access private
	 *
	 */
	public function executeOrderSlider($data){
		if(isset($this->sliderorder)){
			$slides = $this->sliderorder;
            $i = 0;
			foreach ($slides as $slide) {
				//parent::u_order_slider($table,$i,$slide);
                parent::update(
                    array(
                        'context'   =>  'sortable',
                        'type'      =>  $data['type'],
                        'id'	    =>	$slide,
                        'item'	    =>	$i
                    )
                );
                $i++;
			}
		}
	}

    /**
     * Suppression d'une image + données slider
     * @access private
     * @param $data
     * @throws Exception
     */
    private function removeItem($data){
		if(isset($this->del_slide)){
			$makeFiles = new magixcjquery_files_makefiles();

            $vimage = parent::fetch(array(
                'context'   =>  'img',
                'type'      =>  $data['type'],
                'id'        =>  $this->del_slide
            ));

			if(file_exists(self::dir_upload_img().$vimage['img_slide'])){
				$makeFiles->removeFile(self::dir_upload_img(),$vimage['img_slide']);
				$makeFiles->removeFile(self::dir_upload_img(),'s_'.$vimage['img_slide']);
			}else{
				throw new Exception('file: '.$vimage['img_slide'].' is not found');
			}
			parent::d_slider($data['type'],$this->del_slide);
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
                if(isset($this->action)){
                    if($this->action == 'list'){
                        if(isset($this->sliderorder)){
                            $this->executeOrderSlider(array('type'=>'root'));
                        }else{
                            $this->getItemsData('root');
                            $this->template->assign('header_lang',$this->slide_language($this->getlang));
                            $this->template->display('list.tpl');
                        }
                    }elseif($this->action == 'add'){
                        if(isset($this->title_slide)){
                            $header->set_json_headers();
                            $this->save(
                                array(
                                    'type'=>'root',
                                    'id'=>$this->getlang
                                )
                            );
                        }
                    }elseif($this->action == 'edit'){
                        if(isset($this->edit) && !isset($_GET['plugin'])){
                            //Edition des données ou de l'image
                            if(isset($this->title_slide)){
                                $this->save(
                                    array(
                                        'type'=>'root',
                                        'id'=>$this->edit
                                    )
                                );
                            }elseif(magixcjquery_filter_request::isGet('jsonimg')){
                                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                                $header->pragma();
                                $header->cache_control("nocache");
                                $header->getStatus('200');
                                $header->html_header("UTF-8");
                                $this->json_slider_image(array('type'=>'root'));
                            }elseif($this->img_slide){
                                $this->setUpdateImg(
                                    array(
                                        'type'=>'root',
                                        'id'=>$this->edit
                                    )
                                );
                            }else{
                                $this->setData(array('type'=>'root'));
                                $this->template->display('edit.tpl');
                            }
                        }
                    }elseif($this->action == 'remove'){
                        if(isset($this->del_slide)){
                            $this->removeItem(array('type'=>'root','id'=>$this->del_slide));
                        }
                    }
                }elseif(isset($this->tab)){
                    $this->template->assign('header_lang',$this->slide_language($this->getlang));
                    $this->template->display('about.tpl');
                }
			}
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
        $header= new magixglobal_model_header();
        $json = new magixglobal_model_json();
        if(isset($_GET['plugin'])){
            if(isset($this->sliderorder)){
                $this->executeOrderSlider(array('type'=>'category'));
            }elseif(magixcjquery_filter_request::isGet('jsoncatimg')){
                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                $header->pragma();
                $header->cache_control("nocache");
                $header->getStatus('200');
                $header->html_header("UTF-8");
                $this->json_slider_image(array('type'=>'category'));
            }else{
                if(isset($this->title_slide)){
                    if(isset($this->id)){
                        $this->save(
                            array(
                                'type'  =>  'category',
                                'id'    =>  $this->id
                            )
                        );
                    }else{
                        $header->set_json_headers();
                        $this->save(
                            array(
                                'type'  =>  'category',
                                'id'    =>  $this->edit
                            )
                        );
                    }

                }elseif($this->img_slide){
                    $this->setUpdateImg(
                        array(
                            'type'=>'category',
                            'id'=>$this->id
                        )
                    );
                }elseif(isset($this->del_slide)){
                    $this->removeItem(array('type'=>'category','id'=>$this->del_slide));
                }else{
                    $this->setData(array('type'=>'category'));
                    $this->getItemsData('category');
                    $this->template->display('catalog-category.tpl');
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
        $header= new magixglobal_model_header();
        $json = new magixglobal_model_json();
        if(isset($_GET['plugin'])){
            if(isset($this->sliderorder)){
                $this->executeOrderSlider(array('type'=>'subcategory'));
            }elseif(magixcjquery_filter_request::isGet('jsoncatimg')){
                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                $header->pragma();
                $header->cache_control("nocache");
                $header->getStatus('200');
                $header->html_header("UTF-8");
                $this->json_slider_image(array('type'=>'subcategory'));
            }else{
                if(isset($this->title_slide)){
                    if(isset($this->id)){
                        $this->save(
                            array(
                                'type'  =>  'subcategory',
                                'id'    =>  $this->id
                            )
                        );
                    }else{
                        $header->set_json_headers();
                        $this->save(
                            array(
                                'type'  =>  'subcategory',
                                'id'    =>  $this->edit
                            )
                        );
                    }

                }elseif($this->img_slide){
                    $this->setUpdateImg(
                        array(
                            'type'=>'subcategory',
                            'id'=>$this->id
                        )
                    );
                }elseif(isset($this->del_slide)){
                    $this->removeItem(array('type'=>'subcategory','id'=>$this->del_slide));
                }else{
                    $this->setData(array('type'=>'subcategory'));
                    $this->getItemsData('subcategory');
                    $this->template->display('catalog-subcategory.tpl');
                }
            }
        }
    }
    /**
     * Intégration du plugin dans les catégories du catalogue
     * @param $plugin
     * @param $getlang
     * @param $edit
     */
    public function cms($plugin,$getlang,$edit){
        $header= new magixglobal_model_header();
        $json = new magixglobal_model_json();
        if(isset($_GET['plugin'])){
            if(isset($this->sliderorder)){
                $this->executeOrderSlider(array('type'=>'cms'));
            }elseif(magixcjquery_filter_request::isGet('jsoncatimg')){
                $header->head_expires("Mon, 26 Jul 1997 05:00:00 GMT");
                $header->head_last_modified(gmdate( "D, d M Y H:i:s" ) . "GMT");
                $header->pragma();
                $header->cache_control("nocache");
                $header->getStatus('200');
                $header->html_header("UTF-8");
                $this->json_slider_image(array('type'=>'cms'));
            }else{
                if(isset($this->title_slide)){
                    if(isset($this->id)){
                        $this->save(
                            array(
                                'type'  =>  'cms',
                                'id'    =>  $this->id
                            )
                        );
                    }else{
                        $header->set_json_headers();
                        $this->save(
                            array(
                                'type'  =>  'cms',
                                'id'    =>  $this->edit
                            )
                        );
                    }

                }elseif($this->img_slide){
                    $this->setUpdateImg(
                        array(
                            'type'=>'cms',
                            'id'=>$this->id
                        )
                    );
                }elseif(isset($this->del_slide)){
                    $this->removeItem(array('type'=>'cms','id'=>$this->del_slide));
                }else{
                    $this->setData(array('type'=>'cms'));
                    $this->getItemsData('cms');
                    $this->template->display('cms.tpl');
                }
            }
        }
    }
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
     * fetch Data
     * @param $data
     * @return array
     */
    protected function fetch($data)
    {
        if (is_array($data)) {
            if ($data['context'] === 'all') {
                switch($data['type']){
                    case 'cms':
                        $table = 'mc_plugins_slideshow_cms';
                        $join = '';
                        $where = ' WHERE sl.idpage = :id';
                        break;
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
                        $join = ' JOIN mc_lang AS lang ON ( sl.idlang = lang.idlang ) ';
                        $where = ' WHERE sl.idlang = :id ';
                        break;
                }
                $sql =
                    'SELECT sl.* FROM '.$table.' AS sl '.$join.' '.$where.'
                        ORDER BY sl.pos_slide';
                return magixglobal_model_db::layerDB()->select($sql,array(
                    ':id'   =>	$data['id'],
                ));
            }elseif($data['context'] === 'last'){
                switch($data['type']){
                    case 'cms':
                        $table = 'mc_plugins_slideshow_cms';
                        $join = '';
                        $where = ' WHERE sl.idpage = :id ORDER BY sl.idslide DESC LIMIT 0,1';
                        break;
                    case 'category':
                        $table = 'mc_plugins_slideshow_category';
                        $join = '';
                        $where = ' WHERE sl.idclc = :id ORDER BY sl.idslide DESC LIMIT 0,1';
                        break;
                    case 'subcategory':
                        $table = 'mc_plugins_slideshow_subcategory';
                        $join = '';
                        $where = ' WHERE sl.idcls = :id ORDER BY sl.idslide DESC LIMIT 0,1';
                        break;
                    case 'root':
                        $table = 'mc_plugins_slideshow';
                        $join = ' JOIN mc_lang AS lang ON ( sl.idlang = lang.idlang ) ';
                        $where = ' WHERE sl.idlang = :id ORDER BY sl.idslide DESC LIMIT 0,1';
                        break;
                }
                $sql =
                    'SELECT sl.* FROM '.$table.' AS sl '.$join.' '.$where;
                return magixglobal_model_db::layerDB()->select($sql,array(
                    ':id'   =>	$data['id']
                ));
            }elseif ($data['context'] === 'img') {
                switch($data['type']){
                    case 'root':
                        $table = 'mc_plugins_slideshow';
                        break;
                    case 'cms':
                        $table = 'mc_plugins_slideshow_cms';
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
                    ':id'   =>	$data['id']
                ));
            }
        }
    }
    /**
     * Ajoute un enregistrement
     * @param $data
     */
    protected function insert($data){
        if(is_array($data)) {
            switch($data['type']){
                case 'root':
                    $table = 'mc_plugins_slideshow';
                    $column = 'idlang';
                    break;
                case 'cms':
                    $table = 'mc_plugins_slideshow_cms';
                    $column = 'idpage';
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
                ':id'		    =>	$data['id'],
                ':uri_slide'	=>	$data['url'],
                ':img_slide'	=>	$data['img'],
                ':title_slide'	=>	$data['name'],
                ':desc_slide'	=>	$data['content']
            ));
        }
    }
    /**
     * Ajoute un enregistrement
     * @param $data
     */
    protected function update($data)
    {
        if (is_array($data)) {
            if ($data['context'] === 'data') {
                switch($data['type']){
                    case 'root':
                        $table = 'mc_plugins_slideshow';
                        break;
                    case 'cms':
                        $table = 'mc_plugins_slideshow_cms';
                        break;
                    case 'category':
                        $table = 'mc_plugins_slideshow_category';
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
                    idslide = :id';

                magixglobal_model_db::layerDB()->update($sql,array(
                    ':id'	        =>	$data['id'],
                    ':uri_slide'	=>	$data['url'],
                    ':title_slide'	=>	$data['name'],
                    ':desc_slide'	=>	$data['content']
                ));
            }elseif ($data['context'] === 'img') {
                switch($data['type']){
                    case 'cms':
                        $table = 'mc_plugins_slideshow_cms';
                        break;
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
                    ':img_slide'	=>	$data['img'],
                    ':id'		    =>	$data['id']
                ));
            }elseif ($data['context'] === 'sortable') {
                /**
                 * switch table
                 */
                switch($data['type']){
                    case 'category':
                        $table = 'mc_plugins_slideshow_category';
                        break;
                    case 'cms':
                        $table = 'mc_plugins_slideshow_cms';
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
                        ':i'    =>  $data['item'],
                        ':id'   =>	$data['id']
                    )
                );
            }
        }
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
            case 'cms':
                $table = 'mc_plugins_slideshow_cms';
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