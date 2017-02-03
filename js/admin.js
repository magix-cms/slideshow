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
     * set ajax load data
     * @param type
     * @param baseadmin
     * @param getlang
     * @param edit
     * @returns {string}
     */
    function setAjaxUrlLoad(collection){
        var type = collection['type'];
        var baseadmin = collection['baseadmin'];
        var getlang = collection['getlang'];
        var edit = collection['edit'];
        var iso = collection['iso'];
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
     * @param collection
     * @returns {string}
     */
    function setAjaxUrlAction(collection){
        var type = collection['type'];
        var baseadmin = collection['baseadmin'];
        var getlang = collection['getlang'];
        var edit = collection['edit'];
        var action = collection['action'];
        var iso = collection['iso'];

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
     * Load image record
     * @param collection
     * @param type
     */
    function getImage(collection){
        var getImg,
            geturl = setAjaxUrlAction.call(this,collection);
        switch(collection['type']){
            case 'root':
                getImg = '&'+'jsonimg=true';
                    break;
            case 'category':
            case 'subcategory':
                getImg = '&id='+collection['id']+'&'+'jsoncatimg=true';
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

    function del(collection) {
        var geturl = setAjaxUrlAction.call(this,collection);
        // *** Set required fields for validation
        //alert(geturl);
        $(collection['form']).validate({
            onsubmit: true,
            event: 'submit',
            rules: {
                delete: {
                    required: true,
                    number: true,
                    minlength: 1
                }
            },
            submitHandler: function (form) {
                $.nicenotify({
                    ntype: "submit",
                    uri: geturl,
                    typesend: 'post',
                    idforms: $(form),
                    resetform: true,
                    successParams: function (data) {
                        $(collection['modal']).modal('hide');
                        window.setTimeout(function () {
                            $(".alert-success").alert('close');
                        }, 4000);
                        $.nicenotify.initbox(data, {
                            display: true
                        });
                        $('#sliderorder_' + $('#delete').val()).remove();
                        updateList();
                    }
                });
                return false;
            }
        });
    }
    function save(collection){
        switch(collection['action']){
            case 'edit':
                var geturl = setAjaxUrlAction.call(this,collection);
                switch(collection['type']){
                    case 'root':
                        var getParams = '';
                        break;
                    case 'category':
                    case 'subcategory':
                        var getParams = '&id='+collection['id'];
                        break;
                }
                if(collection['save'] === 'data'){
                    // Validate data forms
                    $(collection['form']).validate({
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
                                successParams:function(data){
                                    window.setTimeout(function() { $(".alert-success").alert('close'); }, 4000);
                                    $.nicenotify.initbox(data,{
                                        display:true
                                    });
                                }
                            });
                            return false;
                        }
                    });
                }else if(collection['save'] === 'img'){
                    // Validate update img
                    $(collection['form']).validate({
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
                                            .attr('src','/'+collection['baseadmin']+'/template/img/loader/small_loading.gif')
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
                                    getImage(collection);
                                }
                            });
                            return false;
                        }
                    });
                }
                break;
            case 'add':
                var geturl = setAjaxUrlAction.call(this,collection);
                //alert(geturl);
                //#forms_plugins_slideshow_add
                $(collection['form']).validate({
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
                            datatype: 'json',
                            beforeParams:function(){},
                            successParams:function(data){
                                $(collection['modal']).modal('hide');
                                window.setTimeout(function() { $(".alert-success").alert('close'); }, 4000);
                                $.nicenotify.initbox(data.notify,{
                                    display:true
                                });
                                if(data.statut && data.result != null) {
                                    $('#no-entry').before(data.result);
                                    updateList();
                                }
                            }
                        });
                        return false;
                    }
                });
                break;
        }
    }
    /**
     * Update List
     * @param type
     */
    function updateList() {
        var rows = $('#list_page tr');
        if (rows.length > 1) {
            $('#no-entry').addClass('hide');

            $('a.toggleModal').off();
            $('a.toggleModal').click(function () {
                if ($(this).attr('href') != '#') {
                    var id = $(this).attr('href').slice(1);
                    $('#delete').val(id);
                }
            });
        } else {
            $('#no-entry').removeClass('hide');
        }
    }

    /**
     * Sortable items
     * @param collection
     */
    function itemSortable(collection){
        var geturl = setAjaxUrlLoad.call(this,collection);
        switch(collection['type']){
            case 'root':
                getAjaxSortable = geturl+'&action=list';
                break;
            case 'category':
                getAjaxSortable = geturl;
                break;
        }
        $( ".ui-sortable" ).sortable({
            items: "> tr",
            placeholder: "ui-state-highlight",
            cursor: "move",
            axis: "y",
            update : function() {
                var serial = $( ".ui-sortable" ).sortable('serialize');
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
        $( ".ui-sortable" ).disableSelection();
        $(".img-zoom").fancybox();
    }
    return {
        //Fonction Public
        run:function(baseadmin,getlang,iso,edit){
            if($('.list-slideshow-data').length != 0){
                var type = $('.list-slideshow-data').attr("data-list");
            }else if($('.list-slideshow-data').length != 0){
                var type = $('.forms-slideshow-data').attr("data-forms");
            }
            //if($.isPlainObject(newCollection)){}
            save({baseadmin:baseadmin, getlang:getlang, action:'add', edit: edit, type:type, form:'#forms_plugins_slideshow_add', modal: '#add-page'});
            del({baseadmin:baseadmin, getlang:getlang, action:'remove', type:type, form:'#del_img', modal: '#deleteModal'});
            itemSortable({baseadmin:baseadmin, getlang:getlang, type:type, edit: edit});
            updateList();
        },
        runEdit:function(baseadmin,getlang,iso,edit,id){
            if($('.list-slideshow-data').length != 0){
                var type = $('.list-slideshow-data').attr("data-list");
            }else if($('.forms-slideshow-data').length != 0){
                var type = $('.forms-slideshow-data').attr("data-forms");
            }

            getImage({baseadmin:baseadmin, getlang:getlang, action:'edit', type:type, edit: edit, id:id});
            save({baseadmin:baseadmin, getlang:getlang, action:'edit', type:type,edit: edit, id:id, form:'#forms-plugins-slideshow-udata', save: 'data'});
            save({baseadmin:baseadmin, getlang:getlang, action:'edit', type:type,edit: edit, id:id, form:'#forms-plugins-slideshow-uimg', save: 'img'});
        }
    }
})(jQuery, window, document);