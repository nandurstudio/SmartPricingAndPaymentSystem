<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= $title ?? 'E-Competency' ?></title>
    <!-- Tambahan CSS lainnya -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
</head>

<body>
    <?= $this->include('layouts/navbar') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layouts/footer') ?>
    <?= $this->include('layouts/scripts') ?>
</body>

</html>