<!-- _form.php -->
<!-- Form fields shared between create and edit views -->
<div class="mb-3">
    <label for="intUserID" class="form-label">User ID</label>
    <input type="text" class="form-control" id="intUserID" name="intUserID" value="<?= $assessment['intUserID'] ?? '' ?>" required>
</div>
<div class="mb-3">
    <label for="intLineID" class="form-label">Line</label>
    <input type="text" class="form-control" id="intLineID" name="intLineID" value="<?= $assessment['intLineID'] ?? '' ?>" required>
</div>
<div class="mb-3">
    <label for="intJobTitleID" class="form-label">Job Title</label>
    <input type="text" class="form-control" id="intJobTitleID" name="intJobTitleID" value="<?= $assessment['intJobTitleID'] ?? '' ?>" required>
</div>
<div class="mb-3">
    <label for="intCompetencyID" class="form-label">Competency</label>
    <input type="text" class="form-control" id="intCompetencyID" name="intCompetencyID" value="<?= $assessment['intCompetencyID'] ?? '' ?>" required>
</div>
<div class="mb-3">
    <label for="intIndicatorID" class="form-label">Indicator</label>
    <input type="text" class="form-control" id="intIndicatorID" name="intIndicatorID" value="<?= $assessment['intIndicatorID'] ?? '' ?>" required>
</div>
<div class="mb-3">
    <label for="bitResult" class="form-label">Result</label>
    <select class="form-control" id="bitResult" name="bitResult" required>
        <!-- Select result as Pass or Fail -->
        <option value="1" <?= isset($assessment['bitResult']) && $assessment['bitResult'] == 1 ? 'selected' : '' ?>>Pass</option>
        <option value="0" <?= isset($assessment['bitResult']) && $assessment['bitResult'] == 0 ? 'selected' : '' ?>>Fail</option>
    </select>
</div>