<!-- edit.php -->
<div class="container">
    <!-- Form for editing an existing assessment -->
    <h1>Edit Assessment</h1>
    <form action="/assessment/update/<?= $assessment['intAssessmentID'] ?>" method="post">
        <?php include('_form.php'); ?>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
