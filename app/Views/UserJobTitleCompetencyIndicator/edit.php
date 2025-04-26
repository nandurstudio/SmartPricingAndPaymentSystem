<?= $this->extend('layouts/starter/main'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <h2>Edit Indicator</h2>
    <form action="<?= base_url('UserJobTitleCompetencyIndicator/update/' . $indicator['intUserJobTitleCompetencyIndicatorID']); ?>" method="post">
        <div class="mb-3">
            <label for="intUserID" class="form-label">User</label>
            <select class="form-select" id="intUserID" name="intUserID" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['intUserID']; ?>" <?= $user['intUserID'] == $indicator['intUserID'] ? 'selected' : ''; ?>>
                        <?= $user['txtFullName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="intJobTitleID" class="form-label">Job Title</label>
            <select class="form-select" id="intJobTitleID" name="intJobTitleID" required onchange="fetchCompetencies(this.value)">
                <option value="">Select Job Title</option>
                <?php foreach ($jobTitles as $jobTitle): ?>
                    <option value="<?= $jobTitle['intJobTitleID']; ?>" <?= $jobTitle['intJobTitleID'] == $indicator['intJobTitleID'] ? 'selected' : ''; ?>>
                        <?= $jobTitle['txtJobTitle']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="intCompetencyID" class="form-label">Competency</label>
            <select class="form-select" id="intCompetencyID" name="intCompetencyID" required onchange="fetchIndicators(this.value)">
                <option value="">Select Competency</option>
                <!-- Kompetensi akan diisi melalui JavaScript -->
            </select>
        </div>

        <div class="mb-3">
            <label for="intIndicatorID" class="form-label">Indicator</label>
            <select class="form-select" id="intIndicatorID" name="intIndicatorID" required>
                <option value="">Select Indicator</option>
                <!-- Opsi indikator akan diisi setelah memilih kompetensi -->
            </select>
        </div>

        <div class="mb-3">
            <label for="bitAchieved" class="form-label">Achieved</label>
            <select class="form-select" id="bitAchieved" name="bitAchieved" required>
                <option value="1" <?= $indicator['bitAchieved'] ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?= !$indicator['bitAchieved'] ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="bitActive" class="form-label">Active</label>
            <select class="form-select" id="bitActive" name="bitActive" required>
                <option value="1" <?= $indicator['bitActive'] ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?= !$indicator['bitActive'] ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <!-- Cancel Button -->
        <a href="<?= base_url('/UserJobTitleCompetencyIndicator') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    // Memasukkan kompetensi yang sudah ada berdasarkan job title yang dipilih
    document.addEventListener('DOMContentLoaded', function() {
        const jobTitleId = document.getElementById('intJobTitleID').value;
        if (jobTitleId) {
            fetchCompetencies(jobTitleId);
        }
    });

    function fetchCompetencies(jobTitleId) {
        const competencySelect = document.getElementById('intCompetencyID');
        competencySelect.innerHTML = '<option value="">Select Competency</option>'; // Reset pilihan

        fetch(`<?= base_url('UserJobTitleCompetencyIndicator/getCompetenciesByJobTitle/'); ?>` + jobTitleId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                data.forEach(comp => {
                    const option = document.createElement('option');
                    option.value = comp.intCompetencyID; // ID kompetensi
                    option.textContent = comp.txtCompetency; // Nama kompetensi
                    if (comp.intCompetencyID == <?= $indicator['intCompetencyID']; ?>) {
                        option.selected = true; // Menandai kompetensi yang sudah ada
                    }
                    competencySelect.appendChild(option);
                });
                fetchIndicators(<?= $indicator['intCompetencyID']; ?>); // Ambil indikator berdasarkan kompetensi yang ada
            })
            .catch(error => console.error('Error fetching competencies:', error));
    }

    function fetchIndicators(competencyID) {
        const indicatorSelect = document.getElementById('intIndicatorID');
        indicatorSelect.innerHTML = '<option value="">Select Indicator</option>'; // Reset pilihan

        fetch(`<?= base_url('UserJobTitleCompetencyIndicator/getIndicatorsByCompetency/'); ?>` + competencyID)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                data.forEach(indicator => {
                    const option = document.createElement('option');
                    option.value = indicator.intIndicatorID; // ID indikator
                    option.textContent = indicator.txtIndicator; // Nama indikator
                    if (indicator.intIndicatorID == <?= $indicator['intIndicatorID']; ?>) {
                        option.selected = true; // Menandai indikator yang sudah ada
                    }
                    indicatorSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching indicators:', error));
    }
</script>

<?= $this->endSection(); ?>