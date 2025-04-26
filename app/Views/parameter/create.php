<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Parameter</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Add New Parameter</h2>
        <form action="/parameter/store" method="POST">
            <div class="mb-3">
                <label for="txtParameterName" class="form-label">Parameter Name</label>
                <input type="text" class="form-control" name="txtParameterName" required>
            </div>
            <div class="mb-3">
                <label for="txtParameterDesc" class="form-label">Description</label>
                <textarea class="form-control" name="txtParameterDesc" required></textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="bitActive" value="1" checked>
                <label class="form-check-label" for="bitActive">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Parameter</button>
            <a href="/parameter" class="btn btn-secondary">Back</a>
        </form>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>