<div id="add_new_banner" class="modal" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('/admin/operation/banner/submit')}}" enctype="multipart/form-data">
            <div class="modal-body">
                    <div class="form-group row">
                        <label for="company" class="col-sm-3 col-form-label">Name*</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Input Banner Description" name="banner_name" id="banner_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company" class="col-sm-3 col-form-label">Deeplink</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ex : cashtree:://shop" name="deeplink" id="deeplink">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9 d-flex align-center">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="row_status" id="active" value="active" checked>
                                <label class="form-check-label" for="active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="row_status" id="inactive" value="inactive" disabled>
                                <label class="form-check-label" for="inactive">Inactive</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <div class="d-block">
                                <div class="preview-img-rectangle mb-2">
                                    <img id="preview_img" src="{{url('/assets/images/default.png')}}" width="100%">
                                </div>
                            </div>
                            <div class="d-block">
                                <div class="custom-file">
                                    <input type="file" name="img" onchange="read_url(event)" class="custom-file-input" id="img">
                                    <label class="custom-file-label" for="img">Upload Image</label>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-submit" type="submit">
                    <span class="text">POST</span>
                    <span class="show-loading">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading
                    </span>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>