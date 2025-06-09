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
            <label for="txtIcon" class="form-label">Icon</label>
            <div class="input-group">
                <span class="input-group-text"><i id="iconPreview" class="fas fa-icons"></i></span>
                <input type="text" class="form-control <?php if (session('errors.txtIcon')) : ?>is-invalid<?php endif ?>"
                    id="txtIcon" name="txtIcon" placeholder="e.g. fas fa-home"
                    value="<?= old('txtIcon') ?? ($isEdit ? $menu['txtIcon'] : '') ?>">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#iconPickerModal">
                    <i class="fas fa-search"></i> Browse
                </button>
            </div>
            <?php if (session('errors.txtIcon')) : ?>
                <div class="invalid-feedback">
                    <?= session('errors.txtIcon') ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="intParentID" class="form-label">Parent Menu</label>
            <select class="form-select <?php if (session('errors.intParentID')) : ?>is-invalid<?php endif ?>"
                id="intParentID" name="intParentID">
                <option value="">None (Top Level)</option>
                <?php foreach ($parentMenus as $parent) : ?>
                    <option value="<?= $parent['intMenuID'] ?>"
                        <?= (old('intParentID') ?? ($isEdit ? $menu['intParentID'] : '')) == $parent['intMenuID'] ? 'selected' : '' ?>>
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
                    <!-- Icons will be loaded here via JavaScript -->
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
    
    function updateIconPreview(iconClass) {
        iconPreview.className = iconClass || 'fas fa-icons';
    }
    
    // Initialize icon preview
    updateIconPreview(iconInput.value);
    
    // Icon picker functionality
    const iconSearch = document.getElementById('iconSearch');
    const iconGrid = document.getElementById('iconGrid');
    
    // FontAwesome icon list (you can extend this)
    const icons = [
        'fas fa-home', 'fas fa-user', 'fas fa-cog', 'fas fa-users',
        'fas fa-chart-bar', 'fas fa-calendar', 'fas fa-envelope',
        'fas fa-bell', 'fas fa-file', 'fas fa-folder', 'fas fa-search',
        'fas fa-star', 'fas fa-heart', 'fas fa-bookmark', 'fas fa-edit',
        'fas fa-trash', 'fas fa-download', 'fas fa-upload', 'fas fa-link',
        'fas fa-image', 'fas fa-video', 'fas fa-music', 'fas fa-comments',
        'fas fa-tasks', 'fas fa-list', 'fas fa-check', 'fas fa-times',
        'fas fa-plus', 'fas fa-minus', 'fas fa-info-circle', 'fas fa-question-circle'
    ];
    
    function renderIcons(filter = '') {
        iconGrid.innerHTML = '';
        icons.filter(icon => icon.includes(filter.toLowerCase()))
            .forEach(icon => {
                const div = document.createElement('div');
                div.className = 'col text-center';
                div.innerHTML = `
                    <div class="p-3 border rounded icon-item" role="button" data-icon="${icon}">
                        <i class="${icon} fa-2x mb-2"></i>
                        <div class="small text-muted">${icon}</div>
                    </div>
                `;
                iconGrid.appendChild(div);
            });
        
        // Add click handlers
        document.querySelectorAll('.icon-item').forEach(item => {
            item.addEventListener('click', function() {
                const icon = this.dataset.icon;
                iconInput.value = icon;
                updateIconPreview(icon);
                bootstrap.Modal.getInstance(document.getElementById('iconPickerModal')).hide();
            });
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
