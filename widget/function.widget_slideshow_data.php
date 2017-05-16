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
 * Update    16/05/2017
 * Purpose:  Slideshow
 * Dependance: Slideshow
 * Examples with nivoslider:
 * <div id="slider-home-wrapper" class="slider-wrapper theme-light container">
    <div id="slider-home" class="nivoSlider">
        {widget_slideshow_data id={getlang} type="root"}
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
 * Example with revolutionslider
 * {widget_slideshow_data}
    {if $collectionSlideshow != null}
        <div class="tp-banner-container hidden-xs">
        {if !isset($delay)}
        {assign var='delay' value="3000"}
        {/if}
            <div class="tp-banner">
            <ul>
            {foreach $collectionSlideshow as $key}
                <li data-transition="fade" data-slotamount="7" data-masterspeed="500" data-delay="{$delay}"{if isset($key.uri_slide) && !empty($key.uri_slide)} data-link="{$key.uri_slide}" data-target="_blank"{/if} title="{$key.title_slide}">
                    <img src="/skin/{template}/img/slider/dummy.png"  data-lazyload="/upload/slideshow/{$key.img_slide}" alt="{$key.title_slide}" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat" title="{$key.title_slide}" />
                    <div class="tp-caption fade slider"
                    data-x="center"
                    data-y="bottom"
                    data-speed="800"
                    data-start="800"
                    data-easing="Power4.easeOut"
                    data-endspeed="500"
                    data-endeasing="Power4.easeIn"
                    data-captionhidden="on"
                    style="z-index: 4">
                        <div class="slider-desc">
                            <h5>{$key.title_slide}</h5>
                            {if $key.desc_slide}
                            <p>{$key.desc_slide}</p>
                            {/if}
                        </div>
                    </div>
                </li>
            {/foreach}
            </ul>
            </div>
        </div>
    {/if}
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

    if(isset($params['id']) && isset($params['type'])){
        $id = $params['id'];
        $plugin = $params['type'];
    }else{
        if(isset($_GET['idcls'])){
            $id = $_GET['idcls'];
            $plugin = 'subcategory';
        }elseif(isset($_GET['idclc'])){
            $id = $_GET['idclc'];
            $plugin = 'category';
        }elseif(isset($_GET['getidpage'])){
            $id = $_GET['getidpage'];
            $plugin = 'cms';
        }else{
            $id = frontend_model_template::current_Language();
            $plugin = 'root';
        }
    }

    $collection = new plugins_slideshow_public();
    $assign = isset($params['assign']) ? $params['assign'] : 'collectionSlideshow';
    // Assign
    $template->assign(
        $assign,
        $collection->collectionData(
            $id,
            $plugin
        )
    );
}