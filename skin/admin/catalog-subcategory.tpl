{extends file="catalog/{$smarty.get.section}/edit.tpl"}
{block name="forms"}
{if $smarty.get.section eq 'category'}
    {assign var="reference" value={$smarty.get.section}}
{elseif $smarty.get.section eq 'subcategory'}
    {assign var="reference" value={$smarty.get.section}}
{else}
    {assign var="reference" value="root"}
{/if}
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
            {$editUrl = "/{baseadmin}/catalog.php?section={$reference}&getlang={$smarty.get.getlang}&action=edit&edit={$smarty.get.edit}&plugin=slideshow&id="}
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
{if $smarty.get.section eq 'category'}
    {assign var="reference" value={$smarty.get.section}}
{elseif $smarty.get.section eq 'subcategory'}
    {assign var="reference" value={$smarty.get.section}}
{else}
    {assign var="reference" value="root"}
{/if}
<div id="window-dialog"></div>
{if !$smarty.get.id}
<div id="forms-add" class="hide-modal" title="Ajouter une image">
    {include file="forms/add.tpl" reference=$reference}
</div>
{/if}
{/block}