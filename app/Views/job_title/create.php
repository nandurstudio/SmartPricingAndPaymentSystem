<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Title</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/npm/@docsearch/css@3.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Create Job Title</h1>

        <form action="/job_title/store" method="post">
            <div class="mb-3">
                <label for="txtJobTitle" class="form-label">Job Title</label>
                <input type="text" name="txtJobTitle" id="txtJobTitle" class="form-control" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="bitActive" value="1" checked>
                <label class="form-check-label" for="bitActive">Active</label>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="/job_title" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>