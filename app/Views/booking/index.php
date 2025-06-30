<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
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
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-alt me-1"></i>
                    <?= $pageTitle ?>
                </div>                <a href="<?= base_url('bookings/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Create New Booking
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter controls -->
            <div class="row mb-3">
                <?php if (isset($tenants) && count($tenants) > 1) : ?>
                <div class="col-md-3 mb-2">
                    <select class="form-select form-select-sm" id="tenant-filter">
                        <option value="">All Tenants</option>
                        <?php foreach ($tenants as $tenant) : ?>
                            <option value="<?= $tenant['id'] ?>" <?= (isset($_GET['tenant_id']) && $_GET['tenant_id'] == $tenant['id']) ? 'selected' : '' ?>>
                                <?= esc($tenant['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <?php if (isset($services) && count($services) > 0) : ?>
                <div class="col-md-3 mb-2">
                    <select class="form-select form-select-sm" id="service-filter">
                        <option value="">All Services</option>
                        <?php foreach ($services as $service) : ?>
                            <option value="<?= $service['id'] ?>" <?= (isset($_GET['service_id']) && $_GET['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                <?= esc($service['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="col-md-2 mb-2">
                    <select class="form-select form-select-sm" id="status-filter">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= (isset($_GET['status']) && $_GET['status'] == 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                        <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-2 mb-2">
                    <input type="date" class="form-control form-control-sm" id="date-filter" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" placeholder="Filter by date">
                </div>
                
                <div class="col-md-2 mb-2">
                    <button class="btn btn-sm btn-outline-primary w-100" id="apply-filters">Apply Filters</button>
                </div>
            </div>
            
            <!-- Bookings table -->
            <div class="table-responsive">
                <table id="bookingsTable" class="table table-striped table-bordered align-middle">
                    <thead>
                        <tr>
                            <th class="text-center"><i class="bi bi-gear"></i> Actions</th>
                            <th><i class="bi bi-hash"></i> Booking Code</th>
                            <th><i class="bi bi-person"></i> Customer</th>
                            <th><i class="bi bi-briefcase"></i> Service</th>
                            <th><i class="bi bi-calendar-event"></i> Date & Time</th>
                            <th><i class="bi bi-cash"></i> Price</th>
                            <th><i class="bi bi-info-circle"></i> Status</th>
                            <th><i class="bi bi-credit-card"></i> Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)) : ?>
                            <?php foreach ($bookings as $booking) : ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= base_url('bookings/view/' . $booking['id']) ?>" class="btn btn-info btn-sm" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($booking['status'] == 'pending') : ?>
                                                <button type="button" class="btn btn-success btn-sm update-status" data-id="<?= $booking['id'] ?>" data-status="confirmed" title="Confirm">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($booking['status'] == 'confirmed') : ?>
                                                <button type="button" class="btn btn-primary btn-sm update-status" data-id="<?= $booking['id'] ?>" data-status="completed" title="Mark as Completed">
                                                    <i class="bi bi-check2-all"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (in_array($booking['status'], ['pending', 'confirmed'])) : ?>
                                                <button type="button" class="btn btn-danger btn-sm update-status" data-id="<?= $booking['id'] ?>" data-status="cancelled" title="Cancel">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><code><?= esc($booking['booking_code']) ?></code></td>
                                    <td><?= esc($booking['customer_name']) ?></td>
                                    <td><?= esc($booking['service_name']) ?></td>
                                    <td>
                                        <?= date('M d, Y', strtotime($booking['booking_date'])) ?><br>
                                        <small><?= date('H:i', strtotime($booking['start_time'])) ?> - <?= date('H:i', strtotime($booking['end_time'])) ?></small>
                                    </td>
                                    <td>Rp <?= number_format($booking['price'], 2) ?></td>
                                    <td>
                                        <?php if ($booking['status'] == 'confirmed') : ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Confirmed</span>
                                        <?php elseif ($booking['status'] == 'pending') : ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                        <?php elseif ($booking['status'] == 'cancelled') : ?>
                                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Cancelled</span>
                                        <?php elseif ($booking['status'] == 'completed') : ?>
                                            <span class="badge bg-info text-dark"><i class="bi bi-check2-circle"></i> Completed</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary"><i class="bi bi-question-circle"></i> <?= ucfirst($booking['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($booking['payment_status'] == 'paid') : ?>
                                            <span class="badge bg-success"><i class="bi bi-cash-coin"></i> Paid</span>
                                        <?php elseif ($booking['payment_status'] == 'pending') : ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                        <?php elseif ($booking['payment_status'] == 'failed') : ?>
                                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Failed</span>
                                        <?php elseif ($booking['payment_status'] == 'refunded') : ?>
                                            <span class="badge bg-info text-dark"><i class="bi bi-arrow-counterclockwise"></i> Refunded</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary"><i class="bi bi-credit-card"></i> Not Paid</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center">No bookings found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    document.getElementById('apply-filters').addEventListener('click', function() {
        const tenantFilter = document.getElementById('tenant-filter')?.value || '';
        const serviceFilter = document.getElementById('service-filter')?.value || '';
        const statusFilter = document.getElementById('status-filter')?.value || '';
        const dateFilter = document.getElementById('date-filter')?.value || '';
        
        let url = '<?= base_url('booking') ?>?';
        
        if (tenantFilter) url += `tenant_id=${tenantFilter}&`;
        if (serviceFilter) url += `service_id=${serviceFilter}&`;
        if (statusFilter) url += `status=${statusFilter}&`;
        if (dateFilter) url += `date=${dateFilter}`;
        
        window.location.href = url;
    });

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
});
</script>
<?= $this->endSection() ?>
