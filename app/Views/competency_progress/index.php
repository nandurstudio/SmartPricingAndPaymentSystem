<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competency Progress</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Daftar Progress Kompetensi</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Progress</th>
                    <th>ID User</th>
                    <th>ID Kompetensi</th>
                    <th>Jumlah Indikator yang Dicapai</th>
                    <th>Tanggal Progress</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($competencyProgress) && is_array($competencyProgress)): ?>
                    <?php foreach ($competencyProgress as $progress): ?>
                        <tr>
                            <td><?= esc($progress['intProgressID']); ?></td>
                            <td><?= esc($progress['intUserID']); ?></td>
                            <td><?= esc($progress['intCompetencyID']); ?></td>
                            <td><?= esc($progress['intAchievedIndicators']); ?></td>
                            <td><?= esc($progress['dtmProgressDate']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data progress kompetensi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tambahkan tombol untuk membuat progress baru -->
        <a href="<?= site_url('competency_progress/create'); ?>" class="btn btn-primary mt-3">Tambah Progress Kompetensi</a>
    </div>

    <!-- Link Bootstrap JS -->
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>