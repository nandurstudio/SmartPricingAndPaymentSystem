<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Create User</h2>
    <form action="<?= base_url('/user/create') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?= $this->include('user/_form') ?>
        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="<?= base_url('/user') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('previewPhoto');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result; // Set preview image
            };

            reader.readAsDataURL(input.files[0]); // Read file as data URL
        }
    }
</script>
<?= $this->endSection() ?>