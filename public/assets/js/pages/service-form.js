$(document).ready(function() {
    // Status toggle
    $('#bitActiveToggle').on('change', function() {
        $('#bitActive').val(this.checked ? 1 : 0);
        if(this.checked) {
            $('#statusLabel').text('Active').removeClass('text-danger').addClass('text-success');
        } else {
            $('#statusLabel').text('Inactive').removeClass('text-success').addClass('text-danger');
        }
    });

    // Set initial label
    if($('#bitActiveToggle').length) {
        $('#statusLabel').text($('#bitActiveToggle').is(':checked') ? 'Active' : 'Inactive')
            .toggleClass('text-success', $('#bitActiveToggle').is(':checked'))
            .toggleClass('text-danger', !$('#bitActiveToggle').is(':checked'));
    }

    // Image preview & conversion
    $('#txtImagePath').on('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            $('#image-preview').attr('src', '#').addClass('d-none');
        }
    });
});
