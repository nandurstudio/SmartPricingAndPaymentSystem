<?= $this->extend('layouts/starter/main'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <h2>Create New Indicator</h2>
    <form action="<?= base_url('UserJobTitleCompetencyIndicator/store'); ?>" method="post">
        <div class="mb-3">
            <label for="intUserID" class="form-label">User</label>
            <select class="form-select" id="intUserID" name="intUserID" required>
                <option value="">Select Name</option> <!-- Opsi default -->
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['intUserID']; ?>"><?= $user['txtFullName']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="intLineID" class="form-label">Line</label>
            <select class="form-select" id="intLineID" name="intLineID" required>
                <option value="">Select Line</option> <!-- Opsi default -->
                <?php foreach ($lines as $line): ?>
                    <option value="<?= $line['intLineID']; ?>"><?= $line['txtLine']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="intJobTitleID" class="form-label">Job Title</label>
            <select class="form-select" id="intJobTitleID" name="intJobTitleID" required onchange="fetchCompetencies(this.value)">
                <option value="">Select Job Title</option> <!-- Opsi default -->
                <?php foreach ($jobTitles as $jobTitle): ?>
                    <option value="<?= $jobTitle['intJobTitleID']; ?>"><?= $jobTitle['txtJobTitle']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="intCompetencyID" class="form-label">Competency</label>
            <select class="form-select" id="intCompetencyID" name="intCompetencyID" required onchange="fetchIndicators(this.value)">
                <option value="">Select Competency</option>
                <!-- Kompetensi akan diisi oleh JavaScript -->
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
            <div>
                <input type="checkbox" id="bitAchieved" name="bitAchieved" value="1">
                <label for="bitAchieved">Yes</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="bitActive" class="form-label">Active</label>
            <div>
                <input type="checkbox" id="bitActive" name="bitActive" value="1" checked> <!-- Secara otomatis tercentang -->
                <label for="bitActive">Yes</label>
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="/UserJobTitleCompetencyIndicator" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    function fetchCompetencies(jobTitleId) {
        // Hapus semua opsi dalam dropdown kompetensi
        const competencySelect = document.getElementById('intCompetencyID');
        competencySelect.innerHTML = '<option value="">Select Competency</option>'; // Reset pilihan

        // Ambil kompetensi berdasarkan Job Title
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
                    competencySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching competencies:', error));
    }

    function fetchIndicators(competencyID) {
        // Hapus semua opsi dalam dropdown indikator
        const indicatorSelect = document.getElementById('intIndicatorID');
        indicatorSelect.innerHTML = '<option value="">Select Indicator</option>'; // Reset pilihan

        // Ambil indikator berdasarkan Competency
        fetch(`<?= base_url('UserJobTitleCompetencyIndicator/getIndicatorsByCompetency/'); ?>` + competencyID)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.length === 0) {
                    // Jika tidak ada indikator, tambahkan opsi informasi
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No Indicators Available";
                    indicatorSelect.appendChild(option);
                } else {
                    data.forEach(indicator => {
                        const option = document.createElement('option');
                        option.value = indicator.intIndicatorID; // ID indikator
                        option.textContent = indicator.txtIndicator; // Nama indikator
                        indicatorSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error fetching indicators:', error));
    }
</script>

<?= $this->endSection(); ?>