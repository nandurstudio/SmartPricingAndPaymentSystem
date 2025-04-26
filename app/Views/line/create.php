<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Line</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Add Line</h1>
        <form action="<?php echo base_url('/line/store'); ?>" method="post">
            <div class="mb-3">
                <label for="txtLine" class="form-label">Line Name</label>
                <input type="text" class="form-control" id="txtLine" name="txtLine" required>
            </div>
            <div class="mb-3">
                <label for="txtDesc" class="form-label">Description</label>
                <textarea class="form-control" id="txtDesc" name="txtDesc"></textarea>
            </div>

            <!-- Hidden input untuk memastikan bitActive memiliki nilai 0 ketika toggle tidak dicentang -->
            <input type="hidden" name="bitActive" value="0">

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="bitActive" name="bitActive" value="1" checked>
                <label class="form-check-label" for="bitActive">Active</label>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?php echo base_url('/line'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>