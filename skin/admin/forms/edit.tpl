<div class="col-lg-6 col-sm-6">
    <h2>
        Informations de l'image
    </h2>
    <form method="post" action="#" id="forms-plugins-slideshow-udata" class="form-horizontal forms-slideshow-data" data-forms="{$reference}">
        <div class="form-group">
            <label for="title_slide">Titre :</label>
            <input type="text" id="title_slide" name="title_slide" class="form-control" value="{$title_slide}" size="40" />
        </div>
        <div class="form-group">
            <label for="uri_slide">URL :</label>
            <input type="text" id="uri_slide" name="uri_slide" class="form-control" value="{$uri_slide}" size="40" />
        </div>
        <div class="form-group">
            <label for="desc_slide">Description :</label>
            <textarea id="desc_slide" name="desc_slide" cols="65" rows="3" class="fulltext">{$desc_slide}</textarea>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="{#send#|ucfirst}" />
        </div>
    </form>
</div>
<div class="col-lg-5 col-sm-5">
    <h2>
        Remplacer l'image
    </h2>
    <form method="post" action="#" enctype="multipart/form-data" id="forms-plugins-slideshow-uimg" class="form-inline forms-slideshow-data" data-forms="{$reference}">
        <div class="form-group">
            <label for="img_slide">Image :</label>
            <input type="file" id="img_slide" name="img_slide" />
            <input type="hidden" name="MAX_FILE_SIZE" value="2048576" />
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="{#send#|ucfirst}" />
        </div>
    </form>
    <div id="slideshow_load_img">
        <div id="contener_image"></div>
    </div>
</div>