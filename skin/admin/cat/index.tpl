{extends file="layout.tpl"}
{block name='head:title'}slideshow{/block}
{block name='body:id'}slideshow{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                {#nbr_slide#|ucfirst}: {$slides|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=slide&amp;action=add" title="{#add_slide#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_slide#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">Slideshow</h1>
{/block}
{block name='article:content'}
{if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
    <section class="panel col-xs-12 col-md-12">
    {if $debug}
        {$debug}
    {/if}
    <header class="panel-header panel-nav">
        <h2 class="panel-heading h5">Gestion de slideshow</h2>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#slides" aria-controls="general" role="tab" data-toggle="tab">{#slides#|ucfirst}</a></li>
        </ul>
    </header>
    <div class="panel-body panel-body-form">
        <div class="mc-message-container clearfix">
            <div class="mc-message"></div>
        </div>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="slides">
                {include file="section/form/table-form-2.tpl" data=$slides idcolumn='id_slide' activation=false search=false sortable=true controller="slideshow"}
            </div>
        </div>
    </div>
    </section>
    </div>
    {include file="modal/delete.tpl" data_type='slide' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_slideshow_message#}}
    {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js,
        plugins/slideshow/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof slideshow == "undefined")
            {
                console.log("slideshow is not defined");
            }else{
                slideshow.run();
            }
        });
    </script>
{/block}
