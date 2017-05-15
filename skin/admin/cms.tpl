{extends file="cms/{$smarty.get.section}/edit.tpl"}
{block name="forms"}
{assign var="reference" value="cms"}
{if $smarty.get.id}
    {include file="forms/edit.tpl" reference=$reference nocache}
{else}
    <p class="btn-row" id="addbtn">
        <a class="toggleModal btn btn-primary" data-toggle="modal" data-target="#add-page" href="#">
            <span class="fa fa-plus"></span>
            Ajouter une image
        </a>
    </p>
    <div id="list-slideshow-data" class="list-slideshow-data" data-list="{$reference}">
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
            {$editUrl = "/{baseadmin}/cms.php?getlang={$smarty.get.getlang}&action=edit&edit={$smarty.get.edit}&plugin=slideshow&id="}
            {include file="loop/items.tpl" editUrl=$editUrl}
            {include file="no-entry.tpl"}
            </tbody>
        </table>
    </div>
    {include file="modal/addpage.tpl"}
{/if}
{/block}
{block name="javascript"}
    {include file="js.tpl"}
{/block}
{block name="modal"}
    {assign var="reference" value="cms"}
<div id="window-dialog"></div>
{if !$smarty.get.id}
    {include file="modal/delete.tpl"}
{/if}
{/block}