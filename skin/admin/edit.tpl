{extends file="layout.tpl"}
{block name='body:id'}plugins-{$pluginName}{/block}
{block name="article:content"}
    {assign var="reference" value="root"}
    {include file="section/nav.tpl"}
    <h1>
        <img src="/{$pluginPath}/icon.png" alt="{$pluginName}" width="16" height="16" />
        {$pluginName|ucfirst}
        <small>
            Modifier l'image {$title_slide}
        </small>
    </h1>
    {include file="section/tab.tpl"}
    <div class="mc-message clearfix"></div>
    {include file="forms/edit.tpl"}
{/block}
{block name="modal"}
    <div id="window-dialog"></div>
{/block}
{block name='javascript'}
    {include file="js.tpl"}
{/block}