{if $smarty.get.plugin}
    {assign var="plugin" value={$smarty.get.plugin} nocache}
{else}
    {assign var="plugin" value={$pluginName} nocache}
{/if}
{script src="/{baseadmin}/min/?f=plugins/{$plugin}/js/admin.js" concat={$concat} type="javascript"}
<script type="text/javascript">
$(function(){
    var plugin = "{$plugin}";
    var id = "{$smarty.get.id}";
	if (typeof MC_SlideShow == "undefined"){
	  console.log("MC_SlideShow is not defined");
	}else{
        {if $smarty.get.plugin}
        {if $smarty.get.id}
        MC_SlideShow.runEdit(baseadmin,getlang,iso,edit,id);
        {else}
        MC_SlideShow.run(baseadmin,getlang,iso,edit);
        {/if}
        {else}
        {if $smarty.get.edit}
        MC_SlideShow.runEdit(baseadmin,getlang,iso,edit,id);
        {else}
        MC_SlideShow.run(baseadmin,getlang,iso);
        {/if}
        {/if}
	}
});
</script>