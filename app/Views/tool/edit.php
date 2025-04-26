<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tool</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Tool</h2>
        <form action="/tool/update/<?= $tool['intToolID'] ?>" method="post">
            <input type="hidden" name="_method" value="POST">
            <div class="form-group">
                <label for="txtToolName">Tool Name:</label>
                <input type="text" class="form-control" name="txtToolName" value="<?= $tool['txtToolName'] ?>" required>
            </div>
            <div class="form-group">
                <label for="txtToolDesc">Tool Description:</label>
                <input type="text" class="form-control" name="txtToolDesc" value="<?= $tool['txtToolDesc'] ?>" required>
            </div>
            <div class="form-group">
                <label for="bitActive">Active:</label>
                <input type="checkbox" name="bitActive" value="1" <?= $tool['bitActive'] ? 'checked' : '' ?>>
            </div>
            <button type="submit" class="btn btn-primary">Update Tool</button>
        </form>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>