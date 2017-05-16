{if is_array($getItemsData) && !empty($getItemsData)}
    {$editUrl = "{$pluginUrl}&amp;getlang={$smarty.get.getlang}&action=edit&edit="}
    {foreach $getItemsData as $key}
        <tr id="sliderorder_{$key.idslide}">
            <td>{$key.idslide}</td>
            <td><a href="{$editUrl}{$key.idslide}">{$key.title_slide}</a></td>
            <td>{if $key.img_slide != NULL}<span class="fa fa-check"></span>{else}<span class="fa fa-close"></span>{/if}</td>
            <td>{$key.uri_slide}</td>
            <td>{$key.desc_slide|strip_tags|truncate:100:'...'}</td>
            <td><a href="{$editUrl}{$key.idslide}"><span class="fa fa-edit"></span></a></td>
            <td><a class="toggleModal" data-toggle="modal" data-target="#deleteModal" href="#{$key.idslide}"><span class="fa fa-trash-o"></span></a></td>
        </tr>
    {/foreach}
{/if}