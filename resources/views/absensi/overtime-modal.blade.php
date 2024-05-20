<div class="modal" tabindex="-1" id="overtime-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Overtime Form</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ url('absensi/add-ot') }}" method="POST" id="formCreateOT">
        <div class="modal-body">
            <label class="form-label">Comannder</label>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="commander" 
                        placeholder="Transportation Expenses">
                      </div>
                </div>
            </div>
            <label class="form-label">Location</label>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="location" 
                        placeholder="Transportation Expenses">
                      </div>
                </div>
            </div>
            <label class="form-label">Desciption</label>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <textarea type="text" class="form-control" name="description" 
                        placeholder="Desciption"></textarea>
                      </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning ">Submit</button>
        </div>
    </form>
      </div>
    </div>
  </div>