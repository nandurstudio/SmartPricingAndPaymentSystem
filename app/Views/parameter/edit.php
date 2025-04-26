<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parameter</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Parameter</h2>
        <form action="/parameter/update/<?= $parameter['intParameterID'] ?>" method="post">
            <input type="hidden" name="_method" value="POST">
            <div class="form-group">
                <label for="txtParameterName">Parameter Name:</label>
                <input type="text" class="form-control" name="txtParameterName" value="<?= $parameter['txtParameterName'] ?>" required>
            </div>
            <div class="form-group">
                <label for="txtParameterDesc">Parameter Description:</label>
                <input type="text" class="form-control" name="txtParameterDesc" value="<?= $parameter['txtParameterDesc'] ?>" required>
            </div>
            <div class="form-group">
                <label for="bitActive">Active:</label>
                <input type="checkbox" name="bitActive" value="1" <?= $parameter['bitActive'] ? 'checked' : '' ?>>
            </div>
            <button type="submit" class="btn btn-primary">Update Parameter</button>
        </form>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>