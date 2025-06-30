document.getElementById('export-pdf').addEventListener('click', function() {
    const element = document.getElementById('receipt-pdf-area');
    html2pdf().set({
        margin: 0.5,
        filename: 'receipt-' + (window.receiptBookingCode || 'export') + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    }).from(element).save();
});

// Generate QR code untuk akses invoice tanpa login
window.generateReceiptQR = function(qrValue) {
    var qr = new window.QRious({
        element: document.createElement('canvas'),
        size: 100,
        value: qrValue
    });
    var qrDiv = document.getElementById('receipt-qr');
    if (qrDiv) qrDiv.appendChild(qr.element);
};

document.addEventListener('DOMContentLoaded', function() {
    if (window.receiptQRValue) {
        window.generateReceiptQR(window.receiptQRValue);
    }
});
