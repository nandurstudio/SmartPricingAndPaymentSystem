<!-- views/functional_competency/create.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-3"><?= esc($pageTitle) ?></h2>

<form action="<?= base_url('/functionalcompetency/store') ?>" method="POST">
    <?= csrf_field() ?>

    <!-- Competency -->
    <div class="mb-3">
        <label for="intCompetencyID" class="form-label">Competency</label>
        <select class="form-select" id="intCompetencyID" name="intCompetencyID" required>
            <option value="">Select Competency</option>
            <?php foreach ($competencies as $competency): ?>
                <option value="<?= esc($competency['intCompetencyID']) ?>" <?= old('intCompetencyID') == $competency['intCompetencyID'] ? 'selected' : '' ?>><?= esc($competency['txtCompetency']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Competency Definition -->
    <div class="mb-3">
        <label for="txtDefinition" class="form-label">Competency Definition</label>
        <textarea class="form-control" id="txtDefinition" name="txtDefinition" rows="3" readonly></textarea>
    </div>

    <!-- Indiactors -->
    <div class="mb-3">
        <div class="form-group">
            <label for="indicators">Indicators</label>
            <div id="indicators">
                <!-- Checkbox for each indicator will be populated here -->
            </div>
        </div>
    </div>

    <!-- Job Titles Accordion -->
    <div class="mb-3">
        <div class="accordion" id="accordionJobTitles">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingJobTitles">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJobTitles" aria-expanded="false" aria-controls="collapseJobTitles">
                        Job Titles
                    </button>
                </h2>
                <div id="collapseJobTitles" class="accordion-collapse collapse" aria-labelledby="headingJobTitles" data-bs-parent="#accordionJobTitles">
                    <div class="accordion-body">
                        <?php foreach ($jobTitles as $jobTitle): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= esc($jobTitle['intJobTitleID']) ?>" name="intJobTitleIDs[]" id="jobTitle<?= esc($jobTitle['intJobTitleID']) ?>" <?= in_array($jobTitle['intJobTitleID'], old('intJobTitleIDs', [])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jobTitle<?= esc($jobTitle['intJobTitleID']) ?>">
                                    <?= esc($jobTitle['txtJobTitle']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lines Accordion -->
    <div class="mb-3">
        <div class="accordion" id="accordionLines">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLines">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLines" aria-expanded="false" aria-controls="collapseLines">
                        Lines
                    </button>
                </h2>
                <div id="collapseLines" class="accordion-collapse collapse" aria-labelledby="headingLines" data-bs-parent="#accordionLines">
                    <div class="accordion-body">
                        <?php foreach ($lines as $line): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= esc($line['intLineID']) ?>" name="intLineIDs[]" id="line<?= esc($line['intLineID']) ?>" <?= in_array($line['intLineID'], old('intLineIDs', [])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="line<?= esc($line['intLineID']) ?>">
                                    <?= esc($line['txtLine']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tool (Radio Buttons Vertically) -->
    <div class="mb-3">
        <label class="form-label">Tool</label>
        <div class="form-check">
            <?php foreach ($tools as $tool): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="<?= esc($tool['intToolID']) ?>" name="intToolID" id="tool<?= esc($tool['intToolID']) ?>" <?= old('intToolID') == $tool['intToolID'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="tool<?= esc($tool['intToolID']) ?>">
                        <?= esc($tool['txtToolName']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Active Toggle -->
    <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" id="bitActive" name="bitActive" <?= old('bitActive') ? 'checked' : '' ?>>
        <label class="form-check-label" for="bitActive">Active</label>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="<?= base_url('/functionalcompetency') ?>" class="btn btn-secondary">Cancel</a>
</form>

<script>
    // Update Competency Definition when Competency is selected
    document.getElementById('intCompetencyID').addEventListener('change', function() {
        let competencyID = this.value;
        if (competencyID) {
            // Fetch the selected competency definition
            fetch(`/functionalcompetency/getCompetencyDefinition/${competencyID}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('txtDefinition').value = data.txtDefinition || '';
                })
                .catch(error => console.error('Error:', error));

            fetch('/functionalcompetency/getIndicators/' + competencyID)
                .then(response => response.json())
                .then(data => {
                    let indicatorsDiv = document.getElementById('indicators');
                    indicatorsDiv.innerHTML = ''; // Clear previous indicators
                    data.forEach(indicator => {
                        let checkbox = document.createElement('div');
                        checkbox.classList.add('form-check');
                        checkbox.innerHTML = `
                        <input class="form-check-input" type="checkbox" value="${indicator.intIndicatorID}" name="indicators[]" id="indicator${indicator.intIndicatorID}">
                        <label class="form-check-label" for="indicator${indicator.intIndicatorID}">
                            ${indicator.txtIndicator}
                        </label>
                    `;
                        indicatorsDiv.appendChild(checkbox);
                    });
                })
                .catch(error => console.error('Error fetching indicators:', error));
        }
    });
</script>

<?= $this->endSection() ?>