/**
 * MAGIX CMS
 * @category   Slideshow
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2009 - 2013 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com, http://www.magix-dev.be
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * @author Gérits Aurélien <aurelien[at]magix-cms.com>|<contact[at]magix-dev.be>
 * Slideshow
 *
 */
var MC_SlideShow = (function($, window, document, undefined){
    /**
     * set url construct for array HTML
     * @param type
     * @param baseadmin
     * @param getlang
     * @param iso
     * @param edit
     * @returns {string}
     */
    function setUrlConstruct(type,baseadmin,getlang,iso,edit){
        switch(type){
            case 'root':
                return '/'+baseadmin+'/plugins.php?name=slideshow&getlang='+getlang+'&action=edit&edit=';
                break;
            case 'category':
                return '/'+baseadmin+'/catalog.php?section=category&getlang='+getlang+'&action=edit&edit='+edit+'&plugin=slideshow&id=';
                break;
            case 'subcategory':
                return '/'+baseadmin+'/catalog.php?section=subcategory&getlang='+getlang+'&action=edit&edit='+edit+'&plugin=slideshow&id=';
                break;
        }
    }
    /**
     * set ajax load data
     * @param type
     * @param baseadmin
     * @param getlang
     * @param edit
     * @returns {string}
     */
    function setAjaxUrlLoad(type,baseadmin,getlang,iso,edit){
        switch(type){
            case 'root':
                return '/'+baseadmin+'/plugins.php?name=slideshow&getlang='+getlang;
                break;
            case 'category':
                return '/'+baseadmin+'/catalog.php?section=category&getlang='+getlang+'&action=edit&edit='+edit+'&plugin=slideshow';
                break;
            case 'subcategory':
                return '/'+baseadmin+'/catalog.php?section=subcategory&getlang='+getlang+'&action=edit&edit='+edit+'&plugin=slideshow';
                break;
        }
    }

    /**
     * get ajax action
     * @param type
     * @param baseadmin
     * @param getlang
     * @param edit
     * @param action
     * @returns {string}
     */
    function setAjaxUrlAction(type,baseadmin,getlang,iso,edit,action){
        switch(type){
            case 'root':
                if(action === 'add'){
                    return '/'+baseadmin+'/plugins.php?name=slideshow&getlang='+getlang+'&action=add';
                }else if(action === 'edit'){
                    return '/'+baseadmin+'/plugins.php?name=slideshow&getlang='+getlang+'&action=edit&edit='+edit
                }else if(action === 'remove'){
                    return '/'+baseadmin+'/plugins.php?name=slideshow&getlang='+getlang+'&action=remove';
                }
                break;
            case 'category':
                return '/'+baseadmin+'/catalog.php?section=category&getlang='+getlang+'&action=edit&edit='+edit+'&plugin=slideshow';
                break;
            case 'subcategory':
                return '/'+baseadmin+'/catalog.php?section=subcategory&getlang='+getlang+'&action=edit&edit='+edit+'&plugin=slideshow';
                break;
        }
    }

    /**
     * HTML Table
     * @param collection
     * @param data
     * @param contener
     */
    function setHtmlData(collection,type,data,contener){
        var getAjaxSortable;
        var editUrl = setUrlConstruct.call(this,type,baseadmin,getlang,iso,edit);
        var geturl = setAjaxUrlLoad.call(this,type,baseadmin,getlang,iso,edit);
        var tblListing = $(document.createElement('table')),
            tbody = $(document.createElement('tbody'));
        tblListing
            .addClass("table table-bordered table-condensed table-hover")
            .attr("id", "table_slider")
            .append(
                $(document.createElement("thead"))
                    .append(
                        $(document.createElement("tr"))
                            .append(
                                $(document.createElement("th")).append("ID"),
                                $(document.createElement("th")).append("Title"),
                                $(document.createElement("th")).append("Image"),
                                $(document.createElement("th")).append("URL"),
                                $(document.createElement("th")).append("Description"),
                                $(document.createElement("th")).append(
                                    $(document.createElement("span"))
                                        .addClass("fa fa-edit")
                                ),
                                $(document.createElement("th"))
                                    .append(
                                        $(document.createElement("span"))
                                            .addClass("fa fa-trash-o")
                                )
                            )
                    ),
                tbody
            );
        tblListing.appendTo(contener);
        if(data === undefined){
            console.log(data);
        }
        if(data !== null){
            $.each(data, function(i,item) {
                tbody.append(
                    $(document.createElement("tr"))
                        .attr("id","sliderorder_"+item.idslide)
                        .append(
                            $(document.createElement("td")).append(item.idslide),
                            $(document.createElement("td")).append(
                                $(document.createElement("a"))
                                    .attr("href", editUrl+item.idslide)
                                    .attr("title", "Edit")
                                    .append(item.title_slide)
                            ),
                            $(document.createElement("td")).append(
                                $(document.createElement("a"))
                                    .attr("href", item.img_slide)
                                    .addClass('img-zoom')
                                    .append(
                                        $(document.createElement("span")).addClass("fa fa-search-plus")
                                    )
                                    .append(
                                        ' Preview'
                                    )
                            ),
                            $(document.createElement("td")).append(item.uri_slide),
                            $(document.createElement("td")).append(item.desc_slide),
                            $(document.createElement("td")).append(
                                $(document.createElement("a"))
                                    .attr("href", editUrl+item.idslide)
                                    .attr("title", "Edit")
                                    .append(
                                        $(document.createElement("span"))
                                            .addClass("fa fa-edit")
                                    )
                            ),
                            $(document.createElement("td")).append(
                                $(document.createElement("a"))
                                    .addClass("d-plugin-slide")
                                    .attr("href", "#")
                                    .attr("data-delete", item.idslide)
                                    .attr("title", "Remove")
                                    .append(
                                        $(document.createElement("span")).addClass("fa fa-trash-o")
                                    )
                            )
                        )
                )
            });
            switch(type){
                case 'root':
                    getAjaxSortable = geturl+'&action=list';
                    break;
                case 'category':
                    getAjaxSortable = geturl;
                    break;
            }
            $('#table_slider > tbody').sortable({
                items: "> tr",
                placeholder: "ui-state-highlight",
                cursor: "move",
                axis: "y",
                update : function() {
                    var serial = $('#table_slider > tbody').sortable('serialize');
                    $.nicenotify({
                        ntype: "ajax",
                        uri: getAjaxSortable,
                        typesend: 'post',
                        noticedata : serial,
                        successParams:function(e){
                            $.nicenotify.initbox(e,{
                                display:false
                            });
                        }
                    });
                }
            });
            $('#table_slider > tbody').disableSelection();
            $(".img-zoom").fancybox();
        }else{
            tbody.append(
                $(document.createElement("tr"))
                    .append(
                        $(document.createElement("td")).append(
                            $(document.createElement("span")).addClass("fa fa-minus")
                        ),
                        $(document.createElement("td")).append(
                            $(document.createElement("span")).addClass("fa fa-minus")
                        ),
                        $(document.createElement("td")).append(
                            $(document.createElement("span")).addClass("fa fa-minus")
                        ),
                        $(document.createElement("td")).append(
                            $(document.createElement("span")).addClass("fa fa-minus")
                        )
                    )
            )
        }
    }
    
    /**
     * Retourne le tableau html suivant l'identifiant
     * @param data
     * @param id
     * @returns {*}
     */
    function setTableData(collection,type,data){
        var contener = '#list-slideshow-data';
        switch(type){
            case 'root':
                return setHtmlData(collection,type,data,contener);
                break;
            case 'category':
            case 'subcategory':
                return setHtmlData(collection,type,data,contener);
                break;
        }
    }

    /**
     * Requête Json pour les données utilisant le formatage de tableau
     * @param id
     */
    function getTableData(collection,type){
        var contener = '#list-slideshow-data';
        var json_url = 'json_list_records';
        //collection.unshift(type);
        var geturl = setAjaxUrlLoad.call(this,type,baseadmin,getlang,iso,edit);

        /*console.log(collection);
        var test = collection.push("test");
        console.log(test.join(", "));*/
        $.nicenotify({
            ntype: "ajax",
            uri: geturl+'&'+json_url+'=true',
            typesend: 'get',
            dataType: 'json',
            beforeParams:function(){
                var loader = $(document.createElement("span")).addClass("loader offset5").append(
                    $(document.createElement("img"))
                        .attr('src','/'+collection[0]+'/template/img/loader/small_loading.gif')
                        .attr('width','20px')
                        .attr('height','20px')
                )
                $(contener).html(loader);
            },
            successParams:function(data){
                $(contener).empty();
                $.nicenotify.initbox(data,{
                    display:false
                });
                setTableData(collection,type,data);
            }
        });
    }

    /**
     * Add new record
     * @param collection
     * @param type
     */
    function add(collection,type){
        var geturl = setAjaxUrlAction.call(this,type,baseadmin,getlang,'',edit,'add');

        var formsAdd = $("#forms_plugins_slideshow_add").validate({
            onsubmit: true,
            event: 'submit',
            rules: {
                title_slide: {
                    required: true,
                    minlength: 2
                },
                img_slide: {
                    required: true,
                    minlength: 1,
                    accept: "(jpe?g|gif|png|JPE?G|GIF|PNG)"
                }
            },
            submitHandler: function(form) {
                $.nicenotify({
                    ntype: "submit",
                    uri: geturl,
                    typesend: 'post',
                    idforms: $(form),
                    resetform: true,
                    beforeParams:function(){},
                    successParams:function(e){
                        $.nicenotify.initbox(e,{
                            display:true
                        });
                        $('#forms-add').dialog('close');
                        getTableData(collection,type);
                    }
                });
                return false;
            }
        });
        $(document).on('click','#open-add',function(){
            $('#forms-add').dialog({
                modal: true,
                resizable: true,
                width: 400,
                height:'auto',
                minHeight: 210,
                buttons: {
                    'Save': function() {
                        $("#forms_plugins_slideshow_add").submit();
                    },
                    Cancel: function() {
                        $(this).dialog('close');
                        formsAdd.resetForm();
                    }
                }
            });
            return false;
        });
    }

    /**
     * Load image record
     * @param collection
     * @param type
     */
    function getImage(collection,type){
        var getImg,
            geturl = setAjaxUrlAction.call(this,type,baseadmin,getlang,iso,edit,'edit');

        switch(type){
            case 'root':
                getImg = '&'+'jsonimg=true';
                    break;
            case 'category':
            case 'subcategory':
                getImg = '&id='+collection[4]+'&'+'jsoncatimg=true';
                break;
        }
        $.nicenotify({
            ntype: "ajax",
            uri: geturl+getImg,
            typesend: 'get',
            datatype: 'html',
            successParams:function(e){
                $.nicenotify.initbox(e,{
                    display:false
                });
                $('#slideshow_load_img #contener_image').html(e);
                $(".img-zoom").fancybox();
            }
        });
    }

    /**
     * Update Record
     * @param collection
     * @param type
     */
    function update(collection,type){
        //console.log(collection);
        var geturl = setAjaxUrlAction.call(this,type,baseadmin,getlang,iso,edit,'edit');
        switch(type){
            case 'root':
                getParams = '';
                break;
            case 'category':
            case 'subcategory':
                getParams = '&id='+collection[4];
                break;
        }
        // Validate data forms
        $("#forms-plugins-slideshow-udata").validate({
            onsubmit: true,
            event: 'submit',
            rules: {
                title_slide: {
                    required: true,
                    minlength: 2
                }
            },
            submitHandler: function(form) {
                $.nicenotify({
                    ntype: "submit",
                    uri: geturl+getParams,
                    typesend: 'post',
                    idforms: $(form),
                    successParams:function(e){
                        $.nicenotify.initbox(e,{
                            display:true
                        });
                    }
                });
                return false;
            }
        });
        // Validate update img
        $("#forms-plugins-slideshow-uimg").validate({
            onsubmit: true,
            event: 'submit',
            rules: {
                img_slide: {
                    required: true,
                    minlength: 1,
                    accept: "(jpe?g|gif|png|JPE?G|GIF|PNG)"
                }
            },
            submitHandler: function(form) {
                $.nicenotify({
                    ntype: "submit",
                    uri: geturl+getParams,
                    typesend: 'post',
                    idforms: $(form),
                    beforeParams:function(){
                        var loader = $(document.createElement("span")).addClass("loader offset5").append(
                            $(document.createElement("img"))
                                .attr('src','/'+collection[0]+'/template/img/loader/small_loading.gif')
                                .attr('width','20px')
                                .attr('height','20px')
                        );
                        $('#plugins_load_img #contener_image').html(loader);
                    },
                    successParams:function(e){
                        $.nicenotify.initbox(e,{
                            display:false
                        });
                        $('#img_slide:file').val('');
                        getImage(collection,type);
                    }
                });
                return false;
            }
        });
    }

    /**
     * Remove record
     * @param collection
     * @param type
     */
    function remove(collection,type){
        var geturl = setAjaxUrlAction.call(this,type,baseadmin,getlang,iso,edit,'remove');
        $(document).on('click','.d-plugin-slide',function (event){
            event.preventDefault();
            var elem = $(this).data("delete");
            $("#window-dialog:ui-dialog").dialog( "destroy" );
            $('#window-dialog').dialog({
                bgiframe: true,
                resizable: false,
                height:140,
                modal: true,
                title: Globalize.localize( "delete_item", collection[2] ),
                buttons: {
                    'Delete item': function() {
                        $(this).dialog('close');
                        $.nicenotify({
                            ntype: "ajax",
                            uri: geturl,
                            typesend: 'post',
                            noticedata : {del_slide:elem},
                            beforeParams:function(){},
                            successParams:function(e){
                                $.nicenotify.initbox(e,{
                                    display:false
                                });
                                getTableData(collection,type);
                            }
                        });
                    },
                    Cancel: function() {
                        $(this).dialog('close');
                    }
                }
            });
        });
    }
    return {
        //Fonction Public
        run:function(baseadmin,getlang,iso,edit){
            if($('.list-slideshow-data').length != 0){
                var type = $('.list-slideshow-data').attr("data-list");
            }else if($('.list-slideshow-data').length != 0){
                var type = $('.forms-slideshow-data').attr("data-forms");
            }
            var collection = new Array(baseadmin,getlang,iso,edit);
            if($.isArray(collection)){
                //collection.push(type);
                add(collection,type);
                remove(collection,type);
                getTableData(collection,type);
            }else{
                console.log('Collection is not array : run');
            }
        },
        runEdit:function(baseadmin,getlang,iso,edit,id){
            if($('.list-slideshow-data').length != 0){
                var type = $('.list-slideshow-data').attr("data-list");
            }else if($('.forms-slideshow-data').length != 0){
                var type = $('.forms-slideshow-data').attr("data-forms");
            }
            var collection = new Array(baseadmin,getlang,iso,edit,id);

            if($.isArray(collection)){
                //collection.push(type);
                //console.log(collection);
                getImage(collection,type);
                update(collection,type);
            }else{
                console.log('Collection is not array : runEdit');
            }
        }
    }
})(jQuery, window, document);