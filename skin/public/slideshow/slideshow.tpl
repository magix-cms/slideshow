{if is_array($slides) && $slides != null}
    {if $amp}
    <amp-carousel id="home-slidehow" class="carousel2"
                  layout="responsive"
                  height="200"
                  width="500"
                  type="slides">
        {foreach $slides as $slide}
            <div class="slide">
                <amp-img src="{$slide.imgSrc.small}"
                         alt="{$slide.title_slide}"
                         title="{$item.name}"
                         layout="fill" itemprop="image"></amp-img>

                <div class="caption">
                    <div class="text">
                        <h3 class="h2">{$slide.title_slide}</h3>
                        {if !empty($slide.desc_slide)}
                            <p class="lead">{$slide.desc_slide}</p>
                        {/if}
                    </div>
                </div>
            </div>
        {/foreach}
    </amp-carousel>
    {else}
    <div id="home-slidehow" class="carousel slide hidden-xs carousel-fade" data-ride="carousel">
        {if count($slides) > 1}
            <div class="carousel-controls">
            <a class="left carousel-control" href="#home-slidehow" role="button" data-slide="prev">
                <span class="fa fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <ol class="carousel-indicators">
                {foreach $slides as $slide}
                    <li data-target="#home-slidehow" data-slide-to="{$slide@index}"{if $slide@first} class="active"{/if}></li>
                {/foreach}
            </ol>
            <a class="right carousel-control" href="#home-slidehow" role="button" data-slide="next">
                <span class="fa fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>{/if}
        <div class="carousel-inner" role="listbox">
            {foreach $slides as $slide}
                <div class="item{if $slide@first} active{/if}">
                    {strip}
                    <picture>
                        <!--[if IE 9]><video style="display: none;"><![endif]-->
                        <source sizes="100vw"
                                srcset="{$slide.imgSrc['large']} 1920w,
                                    {$slide.imgSrc['medium']} 960w,
                                    {$slide.imgSrc['small']} 500w">
                        <!--[if IE 9]></video><![endif]-->
                        <img src="{$slide.imgSrc['small']}"
                             sizes="100vw"
                             srcset="{$slide.imgSrc['large']} 1920w,
                                {$slide.imgSrc['medium']} 960w,
                                {$slide.imgSrc['small']} 500w"
                             alt="{$slide.title_slide}" title="{$slide.title_slide}" />
                    </picture>{/strip}
                    <div class="carousel-caption">
                        <div>
                            <h3 class="h2">{$slide.title_slide}</h3>
                            {if !empty($slide.desc_slide)}
                                <p class="lead">{$slide.desc_slide}</p>
                            {/if}
                        </div>
                    </div>
                    {if isset($slide.url_slide) && !empty($slide.url_slide)}
                        <a href="{$slide.url_slide}" title="{$key.title_slide}" class="all-hover{if $slide.blank_slide} targetblank{/if}">{$slide.title_slide}</a>
                    {/if}
                </div>
            {/foreach}
        </div>
    </div>
    {/if}
{/if}