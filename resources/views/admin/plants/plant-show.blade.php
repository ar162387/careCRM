<div class="modal fade" id="show-data-collection-setup" tabindex="-1" aria-labelledby="scrollingLongModalLabel2" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollingLongModalLabel2">Plant Details</h5>
                <button class="btn btn-close p-1" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="tab-pane active" role="tabpanel" aria-labelledby="bootstrap-vertical-wizard-tab1" id="bootstrap-vertical-wizard-tab1">
                    <form id="wizardVerticalForm1" novalidate="novalidate" data-wizard-form="1">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="plant-name" class="form-label">Plant Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="plant-name" name="plant_name" readonly>
                            </div>
                            <div class="col-md-12">
                                <label for="plant-status" class="form-label">Plant Status<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="plant-status" name="plant_status" readonly>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" role="tabpanel" aria-labelledby="bootstrap-vertical-wizard-tab2" id="bootstrap-vertical-wizard-tab2">
                    <form id="wizardVerticalForm2" novalidate="novalidate" data-wizard-form="2" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="notes" class="form-label">Notes<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="notes" name="notes" rows="4" readonly></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="pictures" class="form-label">Pictures</label>
                                <input type="file" class="form-control" id="pictures" name="pictures[]" multiple>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" role="tabpanel" aria-labelledby="bootstrap-vertical-wizard-tab3" id="bootstrap-vertical-wizard-tab3">
                    <form id="wizardVerticalForm3" novalidate="novalidate" data-wizard-form="3" enctype="multipart/form-data">
                        <Label for="service-rep" class="form-label mb-2">Service Representative</Label>
                        <select class="form-select" id="service-rep" name="service_rep" required>
                            <option value="">Select Service Representative</option>
                        </select>
                    </form>
                </div>

            <div class="modal-footer">
                <button class="btn btn-primary" type="button">Okay</button>
                <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
</div>
