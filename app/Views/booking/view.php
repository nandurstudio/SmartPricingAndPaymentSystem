<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('bookings') ?>">Bookings</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar-alt me-1"></i>
                            Booking Details
                        </div>
                        <div class="badge bg-<?= $statusClass ?> fs-6"><?= ucfirst($booking['status']) ?></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Booking Information</h6>
                            <p class="mb-1"><strong>Booking Code:</strong> <code><?= $booking['booking_code'] ?></code></p>
                            <p class="mb-1"><strong>Date:</strong> <?= date('l, F j, Y', strtotime($booking['booking_date'])) ?></p>
                            <p class="mb-1"><strong>Time:</strong> <?= date('h:i A', strtotime($booking['start_time'])) ?> - <?= date('h:i A', strtotime($booking['end_time'])) ?></p>
                            <p class="mb-1"><strong>Created:</strong> <?= date('M d, Y H:i', strtotime($booking['created_date'])) ?></p>
                            
                            <?php if ($booking['status'] == 'cancelled') : ?>
                                <div class="alert alert-danger mt-2">
                                    <strong>Cancelled:</strong> <?= date('M d, Y H:i', strtotime($booking['cancelled_date'])) ?><br>
                                    <strong>Reason:</strong> <?= $booking['cancelled_reason'] ?: 'No reason provided' ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> <?= esc($booking['customer_name'] ?? 'Guest') ?></p>
                            <?php if (!empty($booking['customer_email'])) : ?>
                                <p class="mb-1"><strong>Email:</strong> <?= esc($booking['customer_email']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($booking['customer_phone'])) : ?>
                                <p class="mb-1"><strong>Phone:</strong> <?= esc($booking['customer_phone']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Service Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Service:</strong> <?= esc($booking['service_name']) ?></p>
                                    <p class="mb-1"><strong>Tenant:</strong> <?= esc($booking['tenant_name']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Price:</strong> Rp <?= number_format($booking['price'], 2) ?></p>
                                    <p class="mb-1"><strong>Duration:</strong> <?= $booking['service_duration'] ?? '60' ?> minutes</p>
                                </div>
                            </div>
                            <?php if (!empty($booking['notes'])) : ?>
                                <div class="mt-2">
                                    <h6 class="fw-bold">Notes/Special Requests:</h6>
                                    <p><?= nl2br(esc($booking['notes'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                        <h6 class="fw-bold">Actions</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php if ($booking['status'] == 'pending') : ?>
                                <button type="button" class="btn btn-success update-status" data-id="<?= $booking['id'] ?>" data-status="confirmed">
                                    <i class="fas fa-check me-1"></i> Confirm Booking
                                </button>
                                <button type="button" class="btn btn-danger update-status" data-id="<?= $booking['id'] ?>" data-status="cancelled">
                                    <i class="fas fa-times me-1"></i> Cancel Booking
                                </button>
                            <?php elseif ($booking['status'] == 'confirmed') : ?>
                                <button type="button" class="btn btn-primary update-status" data-id="<?= $booking['id'] ?>" data-status="completed">
                                    <i class="fas fa-check-double me-1"></i> Mark as Completed
                                </button>
                                <button type="button" class="btn btn-danger update-status" data-id="<?= $booking['id'] ?>" data-status="cancelled">
                                    <i class="fas fa-times me-1"></i> Cancel Booking
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($booking['payment_status'] != 'paid' && in_array($booking['status'], ['pending', 'confirmed'])) : ?>
                                <a href="<?= base_url('booking/payment/' . $booking['id']) ?>" class="btn btn-info">
                                    <i class="fas fa-credit-card me-1"></i> Process Payment
                                </a>
                            <?php endif; ?>
                            
                            <a href="mailto:<?= $booking['customer_email'] ?>" class="btn btn-secondary">
                                <i class="fas fa-envelope me-1"></i> Contact Customer
                            </a>
                            
                            <a href="<?= base_url('booking') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-credit-card me-1"></i>
                            Payment Information
                        </div>
                        <div class="badge bg-<?= $paymentStatusClass ?>"><?= ucfirst($booking['payment_status']) ?></div>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <p class="mb-1"><strong>Amount:</strong> Rp <?= number_format($booking['price'], 2) ?></p>
                        <p class="mb-1"><strong>Payment Method:</strong> <?= esc($payment['payment_method'] ?? 'Not specified') ?></p>
                        <?php if (!empty($payment)) : ?>
                            <p class="mb-1"><strong>Transaction ID:</strong> <?= esc($payment['transaction_id'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Payment Date:</strong> <?= isset($payment['payment_date']) ? date('M d, Y H:i', strtotime($payment['payment_date'])) : 'N/A' ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($booking['payment_status'] == 'paid') : ?>
                        <div class="alert alert-success mt-3">
                            <i class="fas fa-check-circle me-1"></i> Payment has been completed
                        </div>
                    <?php elseif ($booking['payment_status'] == 'pending') : ?>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-circle me-1"></i> Payment is pending confirmation
                        </div>
                    <?php elseif ($booking['payment_status'] == 'failed') : ?>
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-times-circle me-1"></i> Payment failed
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-1"></i> No payment has been made yet
                        </div>
                    <?php endif; ?>
                    
                    <?php if (in_array($booking['status'], ['pending', 'confirmed']) && $booking['payment_status'] != 'paid') : ?>
                        <div class="d-grid gap-2 mt-3">                            <a href="<?= base_url('bookings/payment/' . $booking['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-credit-card me-1"></i> Process Payment
                            </a>
                            <?php if ($booking['payment_status'] == 'pending') : ?>
                                <button type="button" class="btn btn-success confirm-payment" data-id="<?= $booking['id'] ?>">
                                    <i class="fas fa-check me-1"></i> Confirm Payment
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($booking['payment_status'] == 'paid') : ?>
                        <div class="d-grid gap-2 mt-3">                                <a href="<?= base_url('bookings/receipt/' . $booking['id']) ?>" class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-file-invoice me-1"></i> View Receipt
                                </a>
                            <?php if (session()->get('roleID') == 1) : ?>
                                <button type="button" class="btn btn-outline-warning initiate-refund" data-id="<?= $booking['id'] ?>">
                                    <i class="fas fa-undo me-1"></i> Initiate Refund
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-bell me-1"></i>
                    Notifications
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Send Reminder to Customer</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary send-notification" data-type="email" data-id="<?= $booking['id'] ?>">
                                <i class="fas fa-envelope me-1"></i> Send Email
                            </button>
                            <?php if (!empty($booking['customer_phone'])) : ?>
                                <button type="button" class="btn btn-outline-success send-notification" data-type="whatsapp" data-id="<?= $booking['id'] ?>">
                                    <i class="fab fa-whatsapp me-1"></i> Send WhatsApp
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notification Templates</label>
                        <select class="form-select" id="notification-template">
                            <option value="reminder">Booking Reminder</option>
                            <option value="confirmation">Booking Confirmation</option>
                            <option value="cancellation">Booking Cancellation</option>
                            <option value="payment">Payment Reminder</option>
                            <option value="custom">Custom Message</option>
                        </select>
                    </div>
                    
                    <div id="custom-message" style="display: none;">
                        <div class="mb-3">
                            <label for="message-subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="message-subject">
                        </div>
                        <div class="mb-3">
                            <label for="message-body" class="form-label">Message</label>
                            <textarea class="form-control" id="message-body" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Booking Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('booking/update-status') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="booking_id" name="booking_id">
                <input type="hidden" id="status" name="status">
                <div class="modal-body">
                    <p id="status-message">Are you sure you want to update the status of this booking?</p>
                    <div class="mb-3" id="cancellation-reason-container" style="display: none;">
                        <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmPaymentModalLabel">Confirm Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('booking/confirm-payment') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                <div class="modal-body">
                    <p>Are you sure you want to confirm that payment has been received for this booking?</p>
                    <div class="mb-3">
                        <label for="payment_reference" class="form-label">Payment Reference</label>
                        <input type="text" class="form-control" id="payment_reference" name="payment_reference">
                        <div class="form-text">Enter a reference number or note for this payment.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="refundModalLabel">Initiate Refund</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('booking/refund') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        This will initiate a refund process. The booking will be marked as refunded.
                    </div>
                    <div class="mb-3">
                        <label for="refund_amount" class="form-label">Refund Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="refund_amount" name="refund_amount" value="<?= $booking['price'] ?>" min="0" max="<?= $booking['price'] ?>" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="refund_reason" class="form-label">Refund Reason</label>
                        <textarea class="form-control" id="refund_reason" name="refund_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notification Preview Modal -->
<div class="modal fade" id="notificationPreviewModal" tabindex="-1" aria-labelledby="notificationPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationPreviewModalLabel">Message Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <strong>To:</strong> <span id="preview-recipient"></span>
                </div>
                <div class="mb-2">
                    <strong>Subject:</strong> <span id="preview-subject"></span>
                </div>
                <div class="card mb-2">
                    <div class="card-body" id="preview-content">
                        <!-- Message preview content -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="send-message-btn">Send Message</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update functionality
    const updateStatusModal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    const updateStatusButtons = document.querySelectorAll('.update-status');
    
    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');
            
            document.getElementById('booking_id').value = bookingId;
            document.getElementById('status').value = status;
            
            let statusText = 'pending';
            if (status === 'confirmed') statusText = 'confirm';
            else if (status === 'completed') statusText = 'mark as completed';
            else if (status === 'cancelled') statusText = 'cancel';
            
            document.getElementById('status-message').textContent = `Are you sure you want to ${statusText} this booking?`;
            
            // Show cancellation reason field only for cancel status
            if (status === 'cancelled') {
                document.getElementById('cancellation-reason-container').style.display = 'block';
            } else {
                document.getElementById('cancellation-reason-container').style.display = 'none';
            }
            
            updateStatusModal.show();
        });
    });
    
    // Payment confirmation functionality
    const confirmPaymentModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
    const confirmPaymentButtons = document.querySelectorAll('.confirm-payment');
    
    confirmPaymentButtons.forEach(button => {
        button.addEventListener('click', function() {
            confirmPaymentModal.show();
        });
    });
    
    // Refund functionality
    const refundModal = new bootstrap.Modal(document.getElementById('refundModal'));
    const refundButtons = document.querySelectorAll('.initiate-refund');
    
    refundButtons.forEach(button => {
        button.addEventListener('click', function() {
            refundModal.show();
        });
    });
    
    // Notification template handling
    const templateSelect = document.getElementById('notification-template');
    const customMessageDiv = document.getElementById('custom-message');
    
    templateSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customMessageDiv.style.display = 'block';
        } else {
            customMessageDiv.style.display = 'none';
        }
    });
    
    // Send notification functionality
    const notificationButtons = document.querySelectorAll('.send-notification');
    const notificationPreviewModal = new bootstrap.Modal(document.getElementById('notificationPreviewModal'));
    let currentNotificationType = '';
    let currentBookingId = '';
    
    notificationButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const bookingId = this.getAttribute('data-id');
            const templateType = document.getElementById('notification-template').value;
            
            currentNotificationType = type;
            currentBookingId = bookingId;
            
            // Set recipient info
            document.getElementById('preview-recipient').textContent = type === 'email' 
                ? '<?= esc($booking['customer_email'] ?? 'customer@example.com') ?>'
                : '<?= esc($booking['customer_phone'] ?? '+628xxxxxxxxxx') ?>';
            
            // Set subject based on template
            let subject = '';
            let content = '';
            
            switch(templateType) {
                case 'reminder':
                    subject = 'Reminder: Your Upcoming Booking';
                    content = `
                        <p>Dear ${escapeHtml('<?= $booking['customer_name'] ?? 'Customer' ?>')},</p>
                        <p>We would like to remind you about your upcoming booking:</p>
                        <ul>
                            <li><strong>Service:</strong> ${escapeHtml('<?= $booking['service_name'] ?>')}</li>
                            <li><strong>Date:</strong> ${escapeHtml('<?= date('l, F j, Y', strtotime($booking['booking_date'])) ?>')}</li>
                            <li><strong>Time:</strong> ${escapeHtml('<?= date('h:i A', strtotime($booking['start_time'])) ?>')} - ${escapeHtml('<?= date('h:i A', strtotime($booking['end_time'])) ?>')}</li>
                            <li><strong>Booking Code:</strong> ${escapeHtml('<?= $booking['booking_code'] ?>')}</li>
                        </ul>
                        <p>We look forward to seeing you!</p>
                    `;
                    break;
                case 'confirmation':
                    subject = 'Booking Confirmation';
                    content = `
                        <p>Dear ${escapeHtml('<?= $booking['customer_name'] ?? 'Customer' ?>')},</p>
                        <p>Your booking has been confirmed:</p>
                        <ul>
                            <li><strong>Service:</strong> ${escapeHtml('<?= $booking['service_name'] ?>')}</li>
                            <li><strong>Date:</strong> ${escapeHtml('<?= date('l, F j, Y', strtotime($booking['booking_date'])) ?>')}</li>
                            <li><strong>Time:</strong> ${escapeHtml('<?= date('h:i A', strtotime($booking['start_time'])) ?>')} - ${escapeHtml('<?= date('h:i A', strtotime($booking['end_time'])) ?>')}</li>
                            <li><strong>Booking Code:</strong> ${escapeHtml('<?= $booking['booking_code'] ?>')}</li>
                        </ul>
                        <p>Thank you for choosing our service!</p>
                    `;
                    break;
                case 'cancellation':
                    subject = 'Booking Cancellation';
                    content = `
                        <p>Dear ${escapeHtml('<?= $booking['customer_name'] ?? 'Customer' ?>')},</p>
                        <p>Your booking has been cancelled:</p>
                        <ul>
                            <li><strong>Service:</strong> ${escapeHtml('<?= $booking['service_name'] ?>')}</li>
                            <li><strong>Date:</strong> ${escapeHtml('<?= date('l, F j, Y', strtotime($booking['booking_date'])) ?>')}</li>
                            <li><strong>Time:</strong> ${escapeHtml('<?= date('h:i A', strtotime($booking['start_time'])) ?>')} - ${escapeHtml('<?= date('h:i A', strtotime($booking['end_time'])) ?>')}</li>
                            <li><strong>Booking Code:</strong> ${escapeHtml('<?= $booking['booking_code'] ?>')}</li>
                        </ul>
                        <p>If you have any questions, please contact us.</p>
                    `;
                    break;
                case 'payment':
                    subject = 'Payment Reminder';
                    content = `
                        <p>Dear ${escapeHtml('<?= $booking['customer_name'] ?? 'Customer' ?>')},</p>
                        <p>This is a reminder that payment is due for your booking:</p>
                        <ul>
                            <li><strong>Service:</strong> ${escapeHtml('<?= $booking['service_name'] ?>')}</li>
                            <li><strong>Date:</strong> ${escapeHtml('<?= date('l, F j, Y', strtotime($booking['booking_date'])) ?>')}</li>
                            <li><strong>Amount:</strong> Rp ${escapeHtml('<?= number_format($booking['price'], 2) ?>')}</li>
                            <li><strong>Booking Code:</strong> ${escapeHtml('<?= $booking['booking_code'] ?>')}</li>
                        </ul>
                        <p>Please complete your payment to confirm your booking.</p>
                    `;
                    break;
                case 'custom':
                    subject = document.getElementById('message-subject').value || 'Message from <?= esc($booking['tenant_name']) ?>';
                    content = document.getElementById('message-body').value || 'No message content.';
                    content = `<p>${content.replace(/\n/g, '</p><p>')}</p>`;
                    break;
            }
            
            document.getElementById('preview-subject').textContent = subject;
            document.getElementById('preview-content').innerHTML = content;
            
            notificationPreviewModal.show();
        });
    });
    
    document.getElementById('send-message-btn').addEventListener('click', function() {
        const templateType = document.getElementById('notification-template').value;
        const subject = document.getElementById('preview-subject').textContent;
        const content = document.getElementById('preview-content').innerHTML;
        
        // Send notification via AJAX
        fetch('<?= base_url('api/send-notification') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                booking_id: currentBookingId,
                type: currentNotificationType,
                subject: subject,
                message: content,
                template: templateType
            })
        })
        .then(response => response.json())
        .then(data => {
            notificationPreviewModal.hide();
            
            if (data.success) {
                alert('Notification sent successfully!');
            } else {
                alert('Failed to send notification: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending the notification.');
        });
    });
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
<?= $this->endSection() ?>
