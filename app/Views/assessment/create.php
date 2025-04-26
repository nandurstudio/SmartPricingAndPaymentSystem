<!-- create.php -->
<div class="container">
    <!-- Form for creating a new assessment -->
    <h1>Create Assessment</h1>
    <form action="/assessment/store" method="post">
        <?php include('_form.php'); ?>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>