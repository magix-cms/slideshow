{if !isset($type)}
    {$type = 'owl-carousel'}
{/if}
{if is_array($slides) && $slides != null}
    {if $amp}
        {$ref = $slides|end}
        <amp-carousel id="{$id_slider}" class="carousel2"
                      type="slides"
                      autoplay
                      delay="3000"
                      layout="responsive"
                      height="{$ref.img['small']['h']}"
                      width="{$ref.img['small']['w']}"
                      type="slides">
            {foreach $slides as $slide}
                <div class="slide">
                    <amp-img src="{$slide.img['small']['src']}"
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
        {if $type === 'bootstrap'}
        <div id="{$id_slider}" class="carousel slide{if isset($transition)} carousel-{$transition}{/if}" data-ride="carousel"{if isset($interval)} data-interval="{$interval}"{/if}>
            {if count($slides) > 1}
                <div class="carousel-controls">
                <a class="left carousel-control" href="#{$id_slider}" role="button" data-slide="prev">
                    <span class="fa fa-angle-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <ol class="carousel-indicators">
                    {foreach $slides as $slide}
                        <li data-target="#home-slidehow" data-slide-to="{$slide@index}"{if $slide@first} class="active"{/if}></li>
                    {/foreach}
                </ol>
                <a class="right carousel-control" href="#{$id_slider}" role="button" data-slide="next">
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
                                    media="(min-width: {$slide.img['medium']['w']}px)"
                                    srcset="{$slide.img['large']['src']} {$slide.img['large']['w']}w">
                            <source sizes="100vw"
                                    media="(min-width: {$slide.img['small']['w']}px)"
                                    srcset="{$slide.img['medium']['src']} {$slide.img['medium']['w']}w">
                            <source sizes="100vw"
                                    srcset="{$slide.img['small']['src']} {$slide.img['small']['w']}w">
                            <!--[if IE 9]></video><![endif]-->
                            <img src="{$slide.imgSrc['small']}"
                                 sizes="100vw"
                                 srcset="{$slide.img['large']['src']} {$slide.img['large']['w']}w,
                                    {$slide.img['medium']['src']} {$slide.img['medium']['w']}w,
                                    {$slide.img['small']['src']} {$slide.img['small']['w']}w"
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
        {elseif $type === 'owl-carousel'}
        <div id="{$id_slider}" class="owl-slideshow">
            <div class="owl-carousel owl-theme">
            {foreach $slides as $k => $slide}
                <div class="slide" data-dot="<span><span>Slide {$k}</span></span>">
                    {strip}<picture>
                    <!--[if IE 9]><video style="display: none;"><![endif]-->
                    <source type="image/webp" sizes="{$slide.img['large']['w']}px" media="(min-width: 1200px)" srcset="{$slide.img['large']['src_webp']} {$slide.img['large']['w']}w">
                    <source type="image/webp" sizes="{$slide.img['medium']['w']}px" media="(min-width: 768px)" srcset="{$slide.img['medium']['src_webp']} {$slide.img['medium']['w']}w">
                    <source type="image/webp" sizes="{$slide.img['small']['w']}px" srcset="{$slide.img['small']['src_webp']} {$slide.img['small']['w']}w">
                    <source type="image/png" sizes="{$slide.img['large']['w']}px" media="(min-width: 1200px)" srcset="{$slide.img['large']['src']} {$slide.img['large']['w']}w">
                    <source type="image/png" sizes="{$slide.img['medium']['w']}px" media="(min-width: 768px)" srcset="{$slide.img['medium']['src']} {$slide.img['medium']['w']}w">
                    <source type="image/png" sizes="{$slide.img['small']['w']}px" srcset="{$slide.img['small']['src']} {$slide.img['small']['w']}w">
                    <!--[if IE 9]></video><![endif]-->
                    <img src="{$slide.img['small']['src']}" sizes="(min-width: 1200px) {$slide.img['large']['w']}px, (min-width: 768px) {$slide.img['medium']['w']}px, {$slide.img['small']['w']}px" srcset="{$slide.img['large']['src']} {$slide.img['large']['w']}w,
                                {$slide.img['medium']['src']} {$slide.img['medium']['w']}w,
                                {$slide.img['small']['src']} {$slide.img['small']['w']}w" alt="{$slide.title_slide}" title="{$slide.title_slide}" class="img-responsive lazyload" />
                    </picture>{/strip}
                    <div class="carousel-caption">
                        <h3>{$slide.title_slide}</h3>
                        {if !empty($slide.desc_slide)}
                            <p>{$slide.desc_slide}</p>
                        {/if}
                    </div>
                    {if isset($slide.url_slide) && !empty($slide.url_slide)}
                        <a href="{$slide.url_slide}" title="{$key.title_slide}" class="all-hover{if $slide.blank_slide} targetblank{/if}">{$slide.title_slide}</a>
                    {/if}
                </div>
            {/foreach}
            </div>
            <div class="owl-slideshow-nav">
                <div class="owl-slideshow-dots"></div>
            </div>
        </div>
        {/if}
    {/if}
{/if}