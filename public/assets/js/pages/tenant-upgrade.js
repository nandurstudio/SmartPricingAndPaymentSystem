// Track modal state
let isModalTransitioning = false;

// Monitor body style and class changes to handle modal state
const bodyObserver = new MutationObserver((mutations) => {
    // Don't process if we're in the middle of a transition
    if (isModalTransitioning) return;

    try {
        mutations.forEach((mutation) => {
            // Skip if no target
            if (!mutation.target) return;

            let shouldRestore = false;

            // Safely check style changes
            if (mutation.attributeName === 'style' && mutation.target.style) {
                const style = mutation.target.style;
                shouldRestore = style.overflow === 'hidden' ||
                              style.position === 'fixed' ||
                              style.paddingRight !== '';
            }

            // Safely check class changes
            if (mutation.attributeName === 'class' && 
                mutation.target.classList &&
                mutation.target.classList.contains('swal2-shown')) {
                shouldRestore = true;
            }

            // Only restore if needed and no visible modal
            if (shouldRestore && typeof Swal !== 'undefined' && !Swal.isVisible()) {
                forceRestoreScroll();
            }
        });
    } catch (e) {
        console.log('Error in mutation observer:', e);
    }
});

// Observe both style and class changes
bodyObserver.observe(document.body, { 
    attributes: true,
    attributeFilter: ['style', 'class']
});

// Helper function to restore scroll
function forceRestoreScroll() {
    // Set transitioning flag
    isModalTransitioning = true;
      // Safely handle SweetAlert state
    try {
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.close();
        }
    } catch (e) {
        console.log('Error handling Swal:', e);
    }

    // First hide modals without removing them
    try {
        const modalElements = document.querySelectorAll('.swal2-container, .swal2-backdrop');
        if (modalElements && modalElements.length) {
            modalElements.forEach(el => {
                if (el && el.style) {
                    el.style.display = 'none';
                    // Schedule removal after all event handlers have run
                    setTimeout(() => {
                        if (el && el.parentNode) {
                            el.parentNode.removeChild(el);
                        }
                    }, 100);
                }
            });
        }
    } catch (e) {
        console.log('Error handling modal elements:', e);
    }

    // Remove modal-related classes and reset styles safely
    const classesToRemove = [
        'swal2-shown',
        'swal2-height-auto',
        'modal-open',
        'swal2-noanimation'
    ];
    
    // Safe function to remove classes and reset styles
    function safeResetElement(el) {
        if (!el) return;
        
        // Safely remove classes
        classesToRemove.forEach(className => {
            if (el.classList && el.classList.contains(className)) {
                el.classList.remove(className);
            }
        });
        
        // Safely reset styles
        if (el.style) {
            try {
                el.style.removeProperty('overflow');
                el.style.removeProperty('padding-right');
                el.style.removeProperty('position');
                el.style.removeProperty('top');
                el.style.removeProperty('margin-right');
                el.style.overflow = 'auto';
            } catch (e) {
                console.log('Error resetting styles:', e);
            }
        }
    }
    
    // Apply to both body and html element
    safeResetElement(document.body);
    safeResetElement(document.documentElement);
    
    // Use jQuery for additional cleanup if available
    if (window.jQuery) {
        try {
            $('body, html').css({
                'overflow': 'auto',
                'padding-right': '',
                'position': '',
                'top': '',
                'margin-right': ''
            });
        } catch (e) {
            console.log('Error applying jQuery styles:', e);
        }
    }

    // Reset transitioning flag after a delay
    setTimeout(() => {
        isModalTransitioning = false;
    }, 150);
}

// Global variables to track upgrade state
let _currentTenantId = null;
let _currentOriginalPlan = null;

function showUpgradeOptions(_tenantId, _originalPlan) {
    _currentTenantId = _tenantId;
    _currentOriginalPlan = _originalPlan;

    // If a modal is already visible, close it properly first
    if (Swal.isVisible()) {
        Swal.close();
        return setTimeout(() => showUpgradeOptions(_tenantId, _originalPlan), 100);
    }

    // Clean up any leftover modal elements and restore scroll
    forceRestoreScroll();
    
    Swal.fire({
        title: '<i class="bi bi-stars text-primary"></i> Upgrade Your Plan',
        html: `
            <div class="mb-4">
                <h5 class="fw-bold">Ready to grow your business?</h5>
                <p class="text-muted mb-0">Choose a plan that best fits your needs</p>
            </div>
            <div class="upgrade-options">
                <button data-plan="basic" class="upgrade-option">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-star text-primary me-3 mt-1"></i>
                        <div>
                            <h6 class="mb-1">Basic Plan</h6>
                            <p class="text-muted mb-2">Perfect for small businesses</p>
                            <div class="plan-features">
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="bi bi-check2 me-2"></i>
                                    All essential features
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-check2 me-2"></i>
                                    14-day trial
                                </div>
                            </div>
                        </div>
                        <div class="ms-auto ps-3">
                            <span class="fw-bold">Rp 99K</span>
                            <small class="text-muted">/mo</small>
                        </div>
                    </div>
                </button>
                <button data-plan="premium" class="upgrade-option">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-star-fill text-primary me-3 mt-1"></i>
                        <div>
                            <h6 class="mb-1">Premium Plan</h6>
                            <p class="text-muted mb-2">For growing businesses</p>
                            <div class="plan-features">
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="bi bi-check2 me-2"></i>
                                    Advanced features
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-check2 me-2"></i>
                                    14-day trial
                                </div>
                            </div>
                        </div>
                        <div class="ms-auto ps-3">
                            <span class="fw-bold">Rp 199K</span>
                            <small class="text-muted">/mo</small>
                        </div>
                    </div>
                </button>
                <button data-plan="enterprise" class="upgrade-option">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-stars text-primary me-3 mt-1"></i>
                        <div>
                            <h6 class="mb-1">Enterprise Plan</h6>
                            <p class="text-muted mb-2">For large organizations</p>
                            <div class="plan-features">
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="bi bi-check2 me-2"></i>
                                    All premium features
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-check2 me-2"></i>
                                    30-day trial
                                </div>
                            </div>
                        </div>
                        <div class="ms-auto ps-3">
                            <span class="fw-bold">Rp 499K</span>
                            <small class="text-muted">/mo</small>
                        </div>
                    </div>
                </button>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        width: '32rem',
        backdrop: true,
        allowOutsideClick: true,
        allowEscapeKey: true,        willClose: () => {
            // Immediate cleanup
            forceRestoreScroll();
            
            // Additional cleanup after animation
            setTimeout(forceRestoreScroll, 300);
        },
        didOpen: (popup) => {
            popup.querySelectorAll('.upgrade-option').forEach(btn => {
                btn.addEventListener('click', function() {
                    changePlan(this.getAttribute('data-plan'));
                });
            });
        }
    });
}

function changePlan(plan) {
    // Set transitioning flag to prevent interference
    isModalTransitioning = true;
    
    // Store plan info before closing dialog
    const currentTenantId = _currentTenantId;
    const originalPlan = _currentOriginalPlan;
    
    // Close first dialog
    Swal.close();
    
    // Wait for first dialog to fully close and cleanup
    setTimeout(() => {
        forceRestoreScroll(); // Clean up any leftover modal elements
        
        // Small delay before showing new modal
        setTimeout(() => {
            isModalTransitioning = false;
            
            Swal.fire({
                title: '<i class="bi bi-arrow-right-circle text-primary"></i> Confirm Plan Change',
                html: `
                    <div class="mb-3">
                        <p>You are about to upgrade to the <strong>${plan.charAt(0).toUpperCase() + plan.slice(1)} Plan</strong></p>
                        <p class="text-muted small">You will be redirected to the payment page.</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Continue to Payment',
                cancelButtonText: 'Cancel',
                reverseButtons: false,
                allowOutsideClick: true,
                allowEscapeKey: true,
                backdrop: true,
                willOpen: () => {
                    isModalTransitioning = true;
                },
                didOpen: () => {
                    isModalTransitioning = false;
                },
                willClose: () => {
                    // Only rollback if plan change was started but not confirmed
                    if (sessionStorage.getItem('plan_change_started') && 
                        !sessionStorage.getItem('plan_confirmed') && 
                        currentTenantId && 
                        originalPlan) {
                        rollbackPlan(currentTenantId, originalPlan);
                        sessionStorage.removeItem('plan_change_started');
                    }
                    forceRestoreScroll();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Start the plan change process
                    sessionStorage.setItem('plan_change_started', true);
                    
                    // Initiate plan change and redirect to payment
                    fetch(`${baseUrl}/tenants/initiate-plan-change/${currentTenantId}/${plan}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            [csrfName]: csrfToken
                        },
                        body: JSON.stringify({
                            originalPlan: originalPlan
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to payment/activation page
                            window.location.href = `${baseUrl}/tenants/activate-subscription/${currentTenantId}`;
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.error || 'Failed to initiate plan change',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error initiating plan change:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to initiate plan change',
                            icon: 'error'
                        });
                    });
                } else {
                    // User clicked Cancel or closed the dialog
                    sessionStorage.removeItem('plan_change_started');
                    if (currentTenantId && originalPlan) {
                        rollbackPlan(currentTenantId, originalPlan);
                    }
                }
            });
        }, 100);
    }, 300);
}

function checkPaymentStatus(tenantId, originalPlan) {
    // If we have transaction_id in URL, this means we just got redirected back from payment
    const urlParams = new URLSearchParams(window.location.search);
    const transactionId = urlParams.get('transaction_id');
    
    if (transactionId) {
        // Clear any existing intervals
        if (window._paymentCheckInterval) {
            clearInterval(window._paymentCheckInterval);
        }
        
        // This is a redirect back from payment gateway
        fetch(`${baseUrl}/tenants/verify-payment/${tenantId}?transaction_id=${transactionId}`, {
            method: 'POST',
            headers: {
                [csrfName]: csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Payment Successful!',
                    text: 'Your plan has been upgraded successfully.',
                    icon: 'success'
                }).then(() => {
                    // Clear URL parameters and reload
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload();
                });
            } else {
                // Payment failed
                rollbackPlan(tenantId, originalPlan);
                Swal.fire({
                    title: 'Payment Failed',
                    text: data.message || 'Failed to verify payment.',
                    icon: 'error'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload();
                });
            }
        })
        .catch(error => {
            console.error('Error verifying payment:', error);
            rollbackPlan(tenantId, originalPlan);
        });
        return;
    }

    // Set a timeout for payment completion (15 minutes)
    const paymentDeadline = Date.now() + (15 * 60 * 1000);
    sessionStorage.setItem('paymentDeadline', paymentDeadline.toString());
    sessionStorage.setItem('originalPlan', originalPlan);

    // Start periodic checks only if not redirected from payment
    window._paymentCheckInterval = setInterval(() => {
        // Check if we've passed the deadline
        if (Date.now() > paymentDeadline) {
            clearInterval(window._paymentCheckInterval);
            rollbackPlan(tenantId, originalPlan);
            Swal.fire({
                title: 'Payment Timeout',
                text: 'The payment session has expired. Please try again.',
                icon: 'warning'
            }).then(() => {
                window.location.reload();
            });
            return;
        }

        // Check payment status
        fetch(`${baseUrl}/tenants/check-payment-status/${tenantId}`, {
            method: 'POST',
            headers: {
                [csrfName]: csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'completed') {
                clearInterval(window._paymentCheckInterval);
                sessionStorage.removeItem('paymentDeadline');
                sessionStorage.removeItem('originalPlan');
                
                Swal.fire({
                    title: 'Upgrade Successful!',
                    text: 'Your plan has been upgraded successfully.',
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            } else if (data.status === 'failed') {
                clearInterval(window._paymentCheckInterval);
                rollbackPlan(tenantId, originalPlan);
                
                Swal.fire({
                    title: 'Payment Failed',
                    text: data.message || 'Payment failed. Please try again.',
                    icon: 'error'
                }).then(() => {
                    window.location.reload();
                });
            }
            // If pending, continue checking
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
        });
    }, 10000); // Check every 10 seconds
}

function rollbackPlan(tenantId, originalPlan) {
    // Validate inputs
    if (!tenantId || !originalPlan) {
        console.warn('Skipping rollback - missing required data:', { tenantId, originalPlan });
        return;
    }

    // Clear any ongoing checks
    if (window._paymentCheckInterval) {
        clearInterval(window._paymentCheckInterval);
    }

    // Clear session storage
    sessionStorage.removeItem('paymentDeadline');
    sessionStorage.removeItem('originalPlan');
    sessionStorage.removeItem('plan_change_started');

    return fetch(`${baseUrl}/tenants/rollback-plan/${tenantId}/${originalPlan}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            [csrfName]: csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Plan rolled back successfully');
            return true;
        } else {
            console.warn('Plan rollback failed:', data.error || 'Unknown error');
            return false;
        }
    })
    .catch(error => {
        console.error('Plan rollback failed:', error);
        return false;
    });
}

// Clean up any ongoing payment checks and storage
function cleanupPaymentCheck() {
    if (window._paymentCheckInterval) {
        clearInterval(window._paymentCheckInterval);
        window._paymentCheckInterval = null;
    }
    
    sessionStorage.removeItem('paymentDeadline');
    sessionStorage.removeItem('originalPlan');
    sessionStorage.removeItem('plan_change_started');
}

// Add event listener for page unload to cleanup
window.addEventListener('beforeunload', cleanupPaymentCheck);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check for payment status on load if we have required parameters
    const urlParams = new URLSearchParams(window.location.search);
    const transactionId = urlParams.get('transaction_id');
    const tenantId = document.querySelector('[data-tenant-id]')?.dataset.tenantId;
    const originalPlan = document.querySelector('[data-original-plan]')?.dataset.originalPlan;
    
    if (transactionId && tenantId && originalPlan) {
        checkPaymentStatus(tenantId, originalPlan);
    }
});
