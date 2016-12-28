<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * Smarty {widget_slideshow_data} function plugin
 *
 * Type:     function
 * Name:     widget_slideshow_data
 * Date:     07/09/2011
 * Update    28/11/2013
 * Purpose:  Slideshow
 * Dependance: Slideshow
 * Examples:
 * <div id="slider-home-wrapper" class="slider-wrapper theme-light container">
    <div id="slider-home" class="nivoSlider">
        {widget_slideshow_data}
        {if $collection_slideshow != null}
            {foreach $collection_slideshow as $key}
                {if $key.uri_slide != null}
                    <a href="{$key.uri_slide}" title="{$key.title_slide}">
                    <img src="/upload/slideshow/{$key.img_slide}" alt="{$key.title_slide}" />
                    </a>
                {else}
                    <img src="/upload/slideshow/{$key.img_slide}" alt="{$key.title_slide}" />
                {/if}
            {/foreach}
        {/if}
    </div>
  </div>
 * Output:   
 * @link 
 * @author   Gerits Aurelien
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_widget_slideshow_data($params, $template){
	plugins_Autoloader::register();
    if(isset($_GET['idcls'])){
        $id = $_GET['idcls'];
        $plugin = 'subcategory';
    }elseif(isset($_GET['idclc'])){
        $id = $_GET['idclc'];
        $plugin = 'category';
    }else{
        $id = frontend_model_template::current_Language();
        $plugin = 'root';
    }

    $collection = new plugins_slideshow_public();

    // Assign
    $template->assign(
        'collectionSlideshow',
        $collection->collectionData(
            $id,
            $plugin
        )
    );
}