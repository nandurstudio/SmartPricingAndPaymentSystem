<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Line</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Edit Line</h1>
        <form action="<?php echo base_url('/line/update/' . $line['intLineID']); ?>" method="post">
            <div class="mb-3">
                <label for="txtLine" class="form-label">Line Name</label>
                <input type="text" class="form-control" id="txtLine" name="txtLine" value="<?= esc($line['txtLine']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="txtDesc" class="form-label">Description</label>
                <textarea class="form-control" id="txtDesc" name="txtDesc"><?= esc($line['txtDesc']) ?></textarea>
            </div>

            <!-- Hidden input untuk bitActive nilai 0 jika tidak dicentang -->
            <input type="hidden" name="bitActive" value="0">

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="bitActive" name="bitActive" value="1" <?= $line['bitActive'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="bitActive">Active</label>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo base_url('/line'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>