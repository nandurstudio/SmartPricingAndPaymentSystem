<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latihan 1</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container text-center mt-5">
        <?php
        echo "<h1>Latihan 010</h1>";
        $x1 = 20;
        $x2 = "Budi Raharjo";
        $arr = array("Suzuki", "Honda", "Yamaha", "Kawasaki");
        $arr_hari = array("Senin", "Selasa", "Rabu", "Kamis");
        $x = "Selamat datang $x2 di kampus Pelita Bangsa";
        echo "<p>$x, dengan umur $x1 tahun, ke kampus hari $arr_hari[0] naik motor $arr[0]</p>";
        echo "<p>" . $x . ", dengan umur " . $x1 . " tahun, ke kampus hari " . $arr_hari[1] . " naik motor " . $arr[1] . "</p>";
        echo "<p>${x}, dengan umur ${x1} tahun, ke kampus hari $arr_hari[2] naik motor $arr[2]</p>";
        print "<p>$x, dengan umur $x1 tahun, ke kampus hari $arr_hari[3] naik motor $arr[3]</p>";
        ?>
    </div>
    <script src="<?php echo base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>