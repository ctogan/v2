<div id="view_question_modal" class="modal" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 500px;overflow-y: auto;">
                <div id="loading-content">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div id="form-content" class="hide">
                    <div id="question_text" class="form-group mb-1">
                        <label for="company" class="col-form-label">Question*</label>
                        <textarea class="form-control" placeholder="Input Question" rows="2" name="question_text" id="question_text" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company" class="col-form-label">Level*</label>
                                    <select class="form-control custom-select" name="question_level" id="question_level">
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
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
                            </div>
                            <div class="col-md-6">
                                <label for="company" class="col-form-label">Image</label>
                                <div>
                                    <img class="preview" onclick="document.getElementById('edit_question_img').click();" id="preview_edit_question_img" src="{{url('/assets/images/default.png')}}" style="max-height: 100px;object-fit: contain;object-position: left;">
                                    <small class="d-block mt-1">Click Image To Upload</small>
                                    <div class="custom-file hide">
                                        <input type="file" name="question_img" onchange="read_url(event)" class="custom-file-input" id="edit_question_img">
                                        <label class="custom-file-label" for="question_img">Upload Image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="table_answer" class="form-group">
                        <table id="table_edit_answer" class="table w-100">
                            <thead>
                            <tr>
                                <th>Answer</th>
                                <th width="100" class="text-center">Correct?</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>