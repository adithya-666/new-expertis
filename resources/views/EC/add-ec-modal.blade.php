<div class="modal" tabindex="-1" id="add-ec-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Expenses Claim Form</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ url('ec/add-ec') }}" method="POST" id="formCreateEC">
            <input type="hidden" class="ec-id" value="" name="ec_id">
        <div class="modal-body">
            <label class="form-label"> Transportation Expenses</label>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="transportasi" id="thousand-separator-transportasi"
                        placeholder="Transportation Expenses">
                      </div>
                </div>
            </div>
            <label class="form-label"> Evidence</label>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <input type="file" name="file_transportasi" class="form-control">
                      </div>
                </div>
            </div>
            <label class="form-label">Parking & Tol Expenses</label>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <input type="text" name="parkir_tol" class="form-control" id="thousand-separator-parkir-tol"
                        placeholder="Parking & Tol Expenses">
                      </div>
                </div>
            </div>
            <label class="form-label"> Evidence</label>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <input type="file" name="file_parkir_tol"  class="form-control">
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