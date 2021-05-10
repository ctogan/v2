<div id="send_new_notification" class="modal" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send New Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('/admin/notification/submit')}}" enctype="multipart/form-data">
                <div class="modal-body" style="max-height: 500px;overflow-y: auto;">
                    <div class="form-group mb-1">
                        <label for="company" class="col-form-label">Title*</label>
                        <input type="text" class="form-control" placeholder="Input Title" name="title" id="title" autocomplete="off">
                    </div>
                    <div class="form-group mb-1">
                        <label for="body" class="col-form-label">Message*</label>
                        <textarea class="form-control" placeholder="Input Question" rows="2" name="body" id="body" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="company" class="col-form-label">Send To*</label>
                        <select class="form-control custom-select" name="target" id="target">
                            <option value="all">All User</option>
                            <option value="uid">Uid</option>
                        </select>
                    </div>
                    <div id="target_uid" class="form-group mb-1 hide">
                        <label for="uid" class="col-form-label">List UID*</label>
                        <textarea class="form-control" placeholder="Input UID" rows="2" name="uid" id="uid" autocomplete="off"></textarea>
                        <small>Please separate with coma (,) ex : 1234234,1234534,93873</small>
                    </div>
                    <div class="form-group mb-1">
                        <label for="deeplink" class="col-form-label">Deeplink*</label>
                        <input type="text" class="form-control" placeholder="Input Deeplink" name="deeplink" id="deeplink" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-form-label">Image</label>
                        <div>
                            <img class="preview" onclick="document.getElementById('notification_img').click();" id="preview_notification_img" src="{{url('/assets/images/default2.png')}}" style="max-height: 100px;object-fit: contain;object-position: left;">
                            <small class="d-block mt-1">Click Image To Upload</small>
                            <div class="custom-file hide">
                                <input type="file" name="notification_img" onchange="read_url(event)" class="custom-file-input" id="notification_img">
                                <label class="custom-file-label" for="question_img">Upload Image</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-submit" type="submit">
                        <span class="text">SEND</span>
                        <span class="show-loading">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Sending...
                    </span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>