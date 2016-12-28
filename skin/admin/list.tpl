{extends file="layout.tpl"}
{block name='body:id'}plugins-{$pluginName}{/block}
{block name="article:content"}
    {include file="section/nav.tpl"}
    <h1>
        <img src="/{$pluginPath}/icon.png" alt="{$pluginName}" width="16" height="16" />
        {$pluginName|ucfirst}
        <small>
            Gestion des images en {$header_lang}
        </small>
    </h1>
    {include file="section/tab.tpl"}
    <div class="mc-message clearfix"></div>

    <p class="btn-row">
        <a class="btn btn-primary" href="#" id="open-add">
            <span class="icon-plus"></span> Ajouter une image
        </a>
    </p>
    <div id="list-slideshow-data" class="list-slideshow-data" data-list="root"></div>
{/block}
{block name="modal"}
    <div id="window-dialog"></div>
    <div id="forms-add" class="hide-modal" title="Ajouter une image">
        {include file="forms/add.tpl"}
    </div>
{/block}
{block name='javascript'}
    {include file="js.tpl"}
{/block}