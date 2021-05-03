<div id="add_question_modal" class="modal" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('/admin/cerdas-cermat/question/submit')}}" enctype="multipart/form-data">
                <div class="modal-body" style="max-height: 500px;overflow-y: auto;">
                    <div id="question_text" class="form-group mb-1">
                        <label for="company" class="col-form-label">Question*</label>
                        <textarea class="form-control" placeholder="Input Question" rows="2" name="question_text" id="question_text" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="company" class="col-form-label">Level*</label>
                                <select class="form-control custom-select" name="question_level" id="question_level">
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="company" class="col-form-label">Image</label>
                                <div>
                                    <img class="preview" onclick="document.getElementById('question_img').click();" id="preview_question_img" src="{{url('/assets/images/default.png')}}" style="max-height: 100px;object-fit: contain;object-position: left;">
                                    <small class="d-block mt-1">Click Image To Upload</small>
                                    <div class="custom-file hide">
                                        <input type="file" name="question_img" onchange="read_url(event)" class="custom-file-input" id="question_img">
                                        <label class="custom-file-label" for="question_img">Upload Image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="table_answer" class="form-group">
                        <table id="table_answer" class="table w-100">
                            <thead>
                            <tr>
                                <th>Answer</th>
                                <th width="100" class="text-center">Correct?</th>
                                <th width="50"><a class="add_field" data-row="2" href="javascript:void(0)"><i data-feather="plus"></i></a> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="hidden" value="0" name="answer[0][id]">
                                    <input type="text" class="form-control form-control-sm" name="answer[0][option]"></td>
                                <td class="vertical-align-middle" align="center">
                                    <input class="hdn_correct" type="hidden" value="false" name="answer[0][is_correct]">
                                    <input class="chk_correct" name="answer[0][is_correct]" value="true" type="checkbox">
                                </td>
                                <td align="center"><a class="delete_field" href="javascript:void(0)"><i data-feather="trash"></i></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" value="0" name="answer[1][id]">
                                    <input type="text" class="form-control form-control-sm" name="answer[1][option]">
                                </td>
                                <td class="vertical-align-middle" align="center">
                                    <input class="hdn_correct" type="hidden" value="false" name="answer[1][is_correct]">
                                    <input class="chk_correct" name="answer[1][is_correct]" value="true" type="checkbox">
                                </td>
                                <td align="center"><a class="delete_field" href="javascript:void(0)"><i data-feather="trash"></i></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-submit" type="submit">
                        <span class="text">SUBMIT</span>
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