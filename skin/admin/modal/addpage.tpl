<div class="modal fade" id="add-page" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Ajouter une image</h4>
            </div>
            <form enctype="multipart/form-data" id="forms_plugins_slideshow_add" method="post" action="">
                <div class="modal-body row">
                    <div class="form-group col-xs-12">
                        <label for="title_slide" class="control-label">Titre &nbsp;*:</label>
                        <input type="text" id="title_slide" name="title_slide" class="form-control" value="" placeholder="Titre de l'image" />
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="uri_slide" class="control-label">Image &nbsp;*:</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                        <input type="file" id="img_slide" name="img_slide" />
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="uri_slide" class="control-label">URL &nbsp;:</label>
                        <input type="text" id="uri_slide" name="uri_slide" class="form-control" value="" placeholder="URL" />
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="uri_slide" class="control-label">Description &nbsp;:</label>
                        <textarea id="desc_slide" name="desc_slide" cols="50" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{#cancel#|ucfirst}</button>
                    <input type="submit" class="btn btn-primary" value="{#save#|ucfirst}" />
                </div>
            </form>
        </div>
    </div>
</div>