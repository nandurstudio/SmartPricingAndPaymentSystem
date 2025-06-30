<!-- Simple refund form for booking -->
<div class="container" style="max-width:500px;margin:40px auto;">
    <h2>Refund Booking</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Booking Code</label>
            <input type="text" class="form-control" value="<?= esc($booking['txtBookingCode'] ?? '-') ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Refund Reason <span style="color:red">*</span></label>
            <textarea name="refund_reason" class="form-control" required placeholder="Enter reason for refund"></textarea>
        </div>
        <button type="submit" class="btn btn-danger">Process Refund</button>
        <a href="/bookings/view/<?= esc($booking['intBookingID']) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
