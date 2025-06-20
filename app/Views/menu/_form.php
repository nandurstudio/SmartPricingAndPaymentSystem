<?php
$isEdit = isset($menu);
?>

<div class="row g-3">
    <div class="col-md-6">
        <div class="form-group">
            <label for="txtMenuName" class="form-label">Menu Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control <?php if (session('errors.txtMenuName')) : ?>is-invalid<?php endif ?>"
                id="txtMenuName" name="txtMenuName" required
                value="<?= old('txtMenuName') ?? ($isEdit ? $menu['txtMenuName'] : '') ?>">
            <?php if (session('errors.txtMenuName')) : ?>
                <div class="invalid-feedback">
                    <?= session('errors.txtMenuName') ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="txtMenuLink" class="form-label">Menu Link</label>
            <input type="text" class="form-control <?php if (session('errors.txtMenuLink')) : ?>is-invalid<?php endif ?>"
                id="txtMenuLink" name="txtMenuLink"
                value="<?= old('txtMenuLink') ?? ($isEdit ? $menu['txtMenuLink'] : '') ?>">
            <?php if (session('errors.txtMenuLink')) : ?>
                <div class="invalid-feedback">
                    <?= session('errors.txtMenuLink') ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="txtIcon" class="form-label">Icon (Bootstrap Icons)</label>
            <div class="input-group">
                <span class="input-group-text"><i id="iconPreview" class="bi bi-file"></i></span>
                <input type="text" class="form-control <?php if (session('errors.txtIcon')) : ?>is-invalid<?php endif ?>"
                    id="txtIcon" name="txtIcon" placeholder="e.g. file-text or folder"
                    value="<?= old('txtIcon') ?? ($isEdit ? $menu['txtIcon'] : '') ?>">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#iconPickerModal">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <?php if (session('errors.txtIcon')) : ?>
                <div class="invalid-feedback">
                    <?= session('errors.txtIcon') ?>
                </div>
            <?php endif ?>
            <small class="form-text text-muted">
                Enter the Bootstrap Icon name without 'bi-' prefix (e.g. 'file-text' or 'folder')
            </small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="intParentID" class="form-label">Parent Menu</label>
            <select class="form-select <?php if (session('errors.intParentID')) : ?>is-invalid<?php endif ?>"
                id="intParentID" name="intParentID">
                <option value="">None (Top Level)</option>
                <?php foreach ($parentMenus as $parent) : ?>
                    <option value="<?= $parent['intMenuID'] ?>" <?= (old('intParentID') ?? ($isEdit ? $menu['intParentID'] : '')) == $parent['intMenuID'] ? 'selected' : '' ?>>
                        <?= esc($parent['txtMenuName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (session('errors.intParentID')) : ?>
                <div class="invalid-feedback">
                    <?= session('errors.intParentID') ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="intSortOrder" class="form-label">Sort Order</label>
            <input type="number" class="form-control <?php if (session('errors.intSortOrder')) : ?>is-invalid<?php endif ?>"
                id="intSortOrder" name="intSortOrder" min="0"
                value="<?= old('intSortOrder') ?? ($isEdit ? $menu['intSortOrder'] : '0') ?>">
            <?php if (session('errors.intSortOrder')) : ?>
                <div class="invalid-feedback">
                    <?= session('errors.intSortOrder') ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" id="bitActive" name="bitActive" value="1"
                    <?= (old('bitActive') ?? ($isEdit ? $menu['bitActive'] : '1')) == '1' ? 'checked' : '' ?>>
                <label class="form-check-label" for="bitActive">Active</label>
            </div>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">Choose Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Search icons...">
                </div>
                <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3" id="iconGrid">
                    <!-- Icons will be loaded here by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Icon preview functionality
    const iconInput = document.getElementById('txtIcon');
    const iconPreview = document.getElementById('iconPreview');
    
    iconInput.addEventListener('input', function() {
        updateIconPreview(this.value);
    });
    
    function updateIconPreview(iconName) {
        iconPreview.className = `bi bi-${iconName || 'file'}`;
    }
    
    // Initialize icon preview
    updateIconPreview(iconInput.value);
    
    // Icon picker functionality
    const iconSearch = document.getElementById('iconSearch');
    const iconGrid = document.getElementById('iconGrid');
    
    // Bootstrap Icons list (commonly used icons)
    const icons = [
        'file-text', 'folder', 'house', 'person', 'gear', 'people',
        'bar-chart', 'calendar', 'envelope', 'bell', 'search',
        'star', 'heart', 'bookmark', 'pencil', 'trash', 'download',
        'upload', 'link', 'image', 'camera', 'music', 'chat',
        'list-check', 'list', 'check-circle', 'x-circle',
        'plus-circle', 'dash-circle', 'info-circle', 'question-circle',
        'grid', 'table', 'key', 'lock', 'unlock', 'shield',
        'cart', 'bag', 'credit-card', 'cash', 'wallet', 'tag',
        'tags', 'flag', 'bookmark-star', 'award', 'lightning',
        'box', 'archive', 'folder-plus', 'folder-minus', 'file-plus',
        'file-minus', 'file-excel', 'file-pdf', 'file-word',
        'file-zip', 'clock', 'calendar-date', 'printer', 'eye',
        'eye-slash', 'globe', 'map', 'pin-map', 'compass', 'graph-up',
        'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down',
        'chevron-left', 'chevron-right', 'chevron-up', 'chevron-down'
    ];
    
    function renderIcons(filter = '') {
        iconGrid.innerHTML = '';
        icons.filter(icon => icon.includes(filter.toLowerCase()))
             .forEach(icon => {
                const div = document.createElement('div');
                div.className = 'col text-center icon-item';
                div.innerHTML = `
                    <div class="p-2 border rounded icon-preview" style="cursor: pointer">
                        <i class="bi bi-${icon} fs-3"></i><br>
                        <small class="text-muted">${icon}</small>
                    </div>`;
                div.onclick = () => {
                    iconInput.value = icon;
                    updateIconPreview(icon);
                    bootstrap.Modal.getInstance(document.getElementById('iconPickerModal')).hide();
                };
                iconGrid.appendChild(div);
             });
    }
    
    // Initial render
    renderIcons();
    
    // Search functionality
    iconSearch.addEventListener('input', function() {
        renderIcons(this.value);
    });
});
</script>
<?= $this->endSection() ?>
