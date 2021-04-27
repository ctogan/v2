<div id="edit_product_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('/admin/product/update')}}" enctype="multipart/form-data">
                <input type="hidden" value="0" id="id" name="id">
                <div class="modal-body">
                    <div id="loading-content">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div id="form-content" class="hide">
                        <div class="form-group row">
                            <label for="company" class="col-sm-3 col-form-label">Code*</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Input Product Code" name="product_code" id="product_code">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company" class="col-sm-3 col-form-label">Product Name*</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Input Product Name" name="product_name" id="product_name">
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
                                    <input class="form-check-input" type="radio" name="row_status" id="inactive" value="inactive">
                                    <label class="form-check-label" for="inactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company" class="col-sm-3 col-form-label">Image</label>
                            <div class="col-sm-9">
                                <div class="d-block">
                                    <div class="preview-img-rectangle mb-2">
                                        <img class="preview" onclick="document.getElementById('edit_product').click();" id="preview_edit_product" src="{{url('/assets/images/default.png')}}" width="100%">
                                    </div>
                                </div>
                                <div class="d-block">
                                    <div class="custom-file hide">
                                        <input type="file" name="img" onchange="read_url(event)" class="custom-file-input" id="edit_product">
                                        <label class="custom-file-label" for="edit_product">Upload Image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-submit hide" type="submit">
                        <span class="text">UPDATE</span>
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