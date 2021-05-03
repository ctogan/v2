<div id="edit_dynamic_section" class="modal" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('/admin/dynamic-section/update')}}" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="0">
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
                            <label for="company" class="col-sm-3 col-form-label">Title*</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Input Title" name="title" id="title" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company" class="col-sm-3 col-form-label">Sub Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="1500P" name="sub_title" id="sub_title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company" class="col-sm-3 col-form-label">Target*</label>
                            <div class="col-sm-9">
                                <select class="form-control custom-select" name="target" id="target_edit">
                                    <option value="">Choose</option>
                                    <option value="inapp">In App Browser</option>
                                    <option value="default_browser">Default Browser</option>
                                    <option value="deeplink">Deeplink</option>
                                    <option value="snapcash">SnapCash</option>
                                    <option value="campaign">Campaign</option>
                                </select>
                            </div>
                        </div>
                        <div id="url" class="form-group row hide">
                            <label for="company" class="col-sm-3 col-form-label">URL</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="ex : https::casthree.id" name="url" id="url" autocomplete="off">
                            </div>
                        </div>
                        <div id="deeplink" class="form-group row hide">
                            <label for="company" class="col-sm-3 col-form-label">Deeplink</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="ex : cashtree:://shop" name="deeplink" id="deeplink" autocomplete="off">
                            </div>
                        </div>
                        <div id="snapcash" class="form-group row hide">
                            <label for="company" class="col-sm-3 col-form-label">Snapcash ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="ex : 150" name="snapcash_id" id="snapcash_id" autocomplete="off">
                            </div>
                        </div>
                        <div id="campaign" class="form-group row hide">
                            <label for="company" class="col-sm-3 col-form-label">ADID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="ex : 1490" name="adid" id="adid" autocomplete="off">
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
                                    <div class="preview-img-square mb-2">
                                        <img class="preview" onclick="document.getElementById('dynamic_section_img').click();" id="preview_edit_dynamic_section_img" src="{{url('/assets/images/default.png')}}" width="100%">
                                    </div>
                                </div>
                                <div class="d-block">
                                    <div class="custom-file hide">
                                        <input type="file" name="dynamic_section_img" onchange="read_url(event)" class="custom-file-input" id="edit_dynamic_section_img">
                                        <label class="custom-file-label" for="section_img">Upload Image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-submit" type="submit">
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