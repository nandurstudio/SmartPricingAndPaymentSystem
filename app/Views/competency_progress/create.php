<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <title>Tambah Progress Kompetensi</title>
</head>

<body>
    <div class="container mt-5">
        <h1>Tambah Progress Kompetensi</h1>

        <!-- Form untuk menambah progress kompetensi -->
        <form action="<?= base_url('competency_progress/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="user_id" class="form-label">Pilih Pengguna</label>
                <select id="user_id" name="intUserID" class="form-select" required>
                    <option value="">-- Pilih Pengguna --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['intUserID'] ?>"><?= $user['txtFullName'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="competency_id" class="form-label">Pilih Kompetensi</label>
                <select id="competency_id" name="intCompetencyID" class="form-select" required>
                    <option value="">-- Pilih Kompetensi --</option>
                    <?php foreach ($competencies as $competency): ?>
                        <option value="<?= $competency['intCompetencyID'] ?>"><?= $competency['txtCompetency'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="indicatorsContainer" class="mb-3">
                <label for="indicators" class="form-label">Indikator</label>
                <div id="indicatorsList">
                    <!-- Indikator akan ditambahkan di sini secara dinamis -->
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Simpan Progress</button>
                <a href="<?= base_url('competency_progress') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

    <!-- Script untuk menambahkan indikator dinamis -->
    <script>
        document.getElementById('competency_id').addEventListener('change', function() {
            const competencyID = this.value;
            const userID = document.getElementById('user_id').value; // Ambil user ID
            const indicatorsList = document.getElementById('indicatorsList');
            indicatorsList.innerHTML = ''; // Kosongkan daftar indikator

            if (competencyID && userID) {
                fetch(`<?= base_url('competency_progress/get_indicators/') ?>${competencyID}/${userID}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(indicator => {
                                // Buat elemen checkbox untuk setiap indikator
                                const indicatorItem = document.createElement('div');
                                indicatorItem.className = 'form-check';

                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.className = 'form-check-input';
                                checkbox.value = '1'; // Menyatakan bisa
                                checkbox.checked = (selectedIndicators[indicator.intIndicatorID] === 1); // Set checked jika nilai 1
                                checkbox.name = 'indicators[' + indicator.intIndicatorID + ']';

                                const label = document.createElement('label');
                                label.className = 'form-check-label';
                                label.textContent = indicator.txtIndicator;

                                indicatorItem.appendChild(checkbox);
                                indicatorItem.appendChild(label);
                                indicatorsList.appendChild(indicatorItem);
                            });
                        } else {
                            indicatorsList.innerHTML = '<p class="text-danger">Tidak ada indikator untuk kompetensi ini.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching indicators:', error);
                    });
            }
        });

        document.getElementById('yourFormID').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah pengiriman form default

            const formData = new FormData(this);

            fetch('<?= base_url('competency_progress/store') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tampilkan pop-up sukses
                        alert(data.message); // Anda bisa menggunakan library pop-up yang lebih baik seperti SweetAlert

                        // Redirect ke halaman list
                        window.location.href = '<?= base_url('competency_progress') ?>'; // Ubah dengan URL yang sesuai
                    } else {
                        // Tampilkan error
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data.');
                });
        });
    </script>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>