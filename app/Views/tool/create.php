<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tool</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Add New Tool</h2>
        <form action="/tool/store" method="POST">
            <div class="mb-3">
                <label for="txtToolName" class="form-label">Tool Name</label>
                <input type="text" class="form-control" name="txtToolName" required>
            </div>
            <div class="mb-3">
                <label for="txtToolDesc" class="form-label">Description</label>
                <textarea class="form-control" name="txtToolDesc" required></textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="bitActive" value="1" checked>
                <label class="form-check-label" for="bitActive">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Tool</button>
            <a href="/tool" class="btn btn-secondary">Back</a>
        </form>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>