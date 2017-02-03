{extends file="layout.tpl"}
{block name='body:id'}plugins-{$pluginName}{/block}
{block name="article:content"}
    {include file="section/nav.tpl"}
    <h1>
        {$pluginName|ucfirst}
        <small>
            Gestion des images en {$header_lang}
        </small>
    </h1>
    {include file="section/tab.tpl"}
    <div class="mc-message clearfix"></div>
    <p class="btn-row" id="addbtn">
        <a class="toggleModal btn btn-primary" data-toggle="modal" data-target="#add-page" href="#">
            <span class="fa fa-plus"></span>
            Ajouter une image
        </a>
    </p>
<div id="list-slideshow-data" class="list-slideshow-data" data-list="root">
    <table class="table table-bordered table-condensed table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Image</th>
            <th>Url</th>
            <th>Contenu</th>
            <th><span class="fa fa-edit"></span></th>
            <th><span class="fa fa-trash-o"></span></th>
        </tr>
        </thead>
        <tbody id="list_page" class="ui-sortable">
        {$editUrl = "{$pluginUrl}&amp;getlang={$smarty.get.getlang}&action=edit&edit="}
        {include file="loop/items.tpl" editUrl=$editUrl}
        {include file="no-entry.tpl"}
        </tbody>
    </table>
</div>
    {include file="modal/addpage.tpl"}
{/block}
{block name="modal"}
    {include file="modal/delete.tpl"}
{/block}
{block name='javascript'}
    {include file="js.tpl"}
{/block}