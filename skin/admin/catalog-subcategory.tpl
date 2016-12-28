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
    <p class="btn-row">
        <a class="btn btn-primary" href="#" id="open-add">
            <span class="icon-plus"></span> Ajouter une image
        </a>
    </p>
    <div id="list-slideshow-data" class="list-slideshow-data" data-list="{$reference}"></div>
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