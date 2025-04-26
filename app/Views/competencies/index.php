<!-- File: app/Views/competencies/index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<a href="<?= base_url('competencies/create'); ?>" class="btn btn-primary mb-3">Add Competency</a>
<div id="liveAlertPlaceholder"></div>
<div class="col-12 mb-1">
    <table id="competencyTable" class="table table-responsive">
        <thead>
            <tr>
                <th>ID</th>
                <th>Competency</th>
                <th>Active</th>
                <th>Inserted By</th>
                <th>Inserted Date</th>
                <th>Updated By</th>
                <th>Updated Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan dimuat secara dinamis oleh DataTable -->
        </tbody>
    </table>
</div>
<!-- Modal Edit Competency -->
<div class="modal fade" id="editCompetencyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCompetencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompetencyModalLabel">Edit Competency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCompetencyForm">
                    <input type="hidden" id="competencyId" name="competencyId">
                    <div class="mb-3">
                        <label for="txtCompetency" class="form-label">Competency Name</label>
                        <input type="text" class="form-control" id="txtCompetency" name="txtCompetency" required>
                    </div>
                    <div class="mb-3">
                        <label for="txtDefinition" class="form-label">Definition</label>
                        <textarea class="form-control" id="txtDefinition" name="txtDefinition" required></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="bitActive" name="bitActive">
                        <label class="form-check-label" for="bitActive">Active</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>