<form method="post" action="#" enctype="multipart/form-data" id="forms_plugins_slideshow_add" class="forms-slideshow-data" data-forms="{$reference}">
    <div class="form-group">
        <label for="title_slide" class="control-label">Titre :</label>
        <div class="controls">
            <input type="text" id="title_slide" name="title_slide" class="form-control" value="" size="40" />
        </div>
    </div>
    <div class="form-group">
        <label for="uri_slide" class="control-label">URL :</label>
        <div class="controls">
            <input type="text" id="uri_slide" name="uri_slide" class="form-control" value="" size="40" />
        </div>
    </div>
    <div class="form-group">
        <label for="desc_slide" class="control-label">Description :</label>
        <div class="controls">
            <textarea id="desc_slide" name="desc_slide" cols="50" rows="3" class="fulltext"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="img_slide" class="control-label">Image :</label>
        <div class="controls">
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" id="img_slide" name="img_slide" />
        </div>
    </div>
</form>