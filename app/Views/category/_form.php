<!-- category/_form.php -->
<form action="<?= esc($formAction) ?>" method="POST">
    <?= csrf_field() ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="txtCategoryName">Category Name</label>
                <input type="text" class="form-control" id="txtCategoryName" name="txtCategoryName"
                    value="<?= esc($category['txtCategoryName'] ?? old('txtCategoryName')) ?>"
                    required maxlength="100">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="bitActive">Status</label><br>
                <input type="hidden" name="bitActive" value="0">
                <input type="checkbox" id="bitActive" name="bitActive" value="1"
                    <?= (isset($category['bitActive']) ? $category['bitActive'] : old('bitActive')) ? 'checked' : '' ?>>
                <label for="bitActive">Active</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="txtDesc">Description</label>
                <textarea class="form-control" id="txtDesc" name="txtDesc" maxlength="255"><?= esc($category['txtDesc'] ?? old('txtDesc')) ?></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">
                    <?= !empty($isEdit) ? 'Update Category' : 'Add Category' ?>
                </button>
                <a class="btn btn-secondary" href="<?= base_url('category'); ?>">Back to Category List</a>
            </div>
        </div>
    </div>
</form>
