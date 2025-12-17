</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASEURL; ?>/js/script.js"></script>

<script>
// Initialize tooltips for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover focus'
        });
    });

    // Form validation
    initializeFormValidation();

    // Auto-hide flash messages
    initializeFlashMessages();
});

// Role Selection Animation with Enhanced UX
document.querySelectorAll('.radio-card').forEach(card => {
    card.addEventListener('click', function(e) {
        // Prevent multiple triggers
        if (e.target.tagName === 'INPUT') return;

        // Remove active class from all cards with animation
        document.querySelectorAll('.radio-card').forEach(c => {
            c.classList.remove('active');
            c.style.transition = 'all 0.3s ease';
        });

        // Add active class to clicked card with visual feedback
        this.classList.add('active');

        // Add pulse effect
        this.style.animation = 'pulse 0.3s ease';
        setTimeout(() => {
            this.style.animation = '';
        }, 300);

        // Check the radio input inside
        const radioInput = this.querySelector('input[type="radio"]');
        if (radioInput) {
            radioInput.checked = true;
            radioInput.dispatchEvent(new Event('change', {
                bubbles: true
            }));
            toggleFamilyCodeInput();
        }
    });
});

// Toggle family code input with smooth animation
function toggleFamilyCodeInput() {
    const roleAnak = document.getElementById('role_anak');
    const codeInput = document.getElementById('family_code_input');
    const inputField = document.getElementById('input_family_code');

    if (!roleAnak || !codeInput) return;

    if (roleAnak.checked) {
        // Show with animation
        codeInput.classList.remove('d-none');
        codeInput.style.opacity = '0';
        codeInput.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            codeInput.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
            codeInput.style.opacity = '1';
            codeInput.style.transform = 'translateY(0)';
        }, 10);

        if (inputField) {
            inputField.required = true;
            inputField.focus();
        }
    } else {
        // Hide with animation
        codeInput.style.transition = 'all 0.3s ease';
        codeInput.style.opacity = '0';
        codeInput.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            codeInput.classList.add('d-none');
            codeInput.style.opacity = '';
            codeInput.style.transform = '';
        }, 300);

        if (inputField) {
            inputField.required = false;
            inputField.value = '';
        }
    }
}

// Enhanced Form Validation with Visual Feedback
function initializeFormValidation() {
    document.querySelectorAll('form').forEach(form => {
        // Real-time validation
        form.querySelectorAll('input[required]').forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });

        // Form submission handler
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                submitBtn.disabled = true;
                submitBtn.style.cursor = 'wait';

                // Re-enable button after 10 seconds (safety net)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.cursor = '';
                }, 10000);
            }
        });
    });
}

// Individual field validation
function validateField(field) {
    clearFieldError(field);

    if (!field.checkValidity()) {
        showFieldError(field, getValidationMessage(field));
        return false;
    }

    // Custom validation for specific fields
    if (field.type === 'email' && field.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            showFieldError(field, 'Format email tidak valid');
            return false;
        }
    }

    if (field.id === 'username' && field.value.length < 3) {
        showFieldError(field, 'Username minimal 3 karakter');
        return false;
    }

    return true;
}

// Form-wide validation
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('input[required], select[required]');

    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    return isValid;
}

// Show field error with animation
function showFieldError(field, message) {
    // Remove existing error
    clearFieldError(field);

    // Add error class to field
    field.classList.add('is-invalid');

    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback animated fadeIn';
    errorDiv.textContent = message;

    // Insert after field
    field.parentNode.appendChild(errorDiv);

    // Scroll to error if needed
    if (!isElementInViewport(field)) {
        field.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Get appropriate validation message
function getValidationMessage(field) {
    if (field.validity.valueMissing) {
        return 'Field ini wajib diisi';
    }
    if (field.validity.typeMismatch) {
        return 'Format tidak valid';
    }
    if (field.validity.tooShort) {
        return `Minimal ${field.minLength} karakter`;
    }
    if (field.validity.tooLong) {
        return `Maksimal ${field.maxLength} karakter`;
    }
    return 'Input tidak valid';
}

// Flash Messages Management
function initializeFlashMessages() {
    const flashMessages = document.querySelectorAll('.alert');

    flashMessages.forEach(msg => {
        // Add close button if not present
        if (!msg.querySelector('.btn-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.type = 'button';
            closeBtn.className = 'btn-close';
            closeBtn.setAttribute('data-bs-dismiss', 'alert');
            closeBtn.setAttribute('aria-label', 'Close');
            msg.appendChild(closeBtn);
        }

        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (msg.parentNode) {
                msg.style.transition = 'all 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    if (msg.parentNode) {
                        msg.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
}

// Utility: Check if element is in viewport
function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Utility: Add CSS for animations
function addAnimationStyles() {
    if (!document.getElementById('auth-animations')) {
        const style = document.createElement('style');
        style.id = 'auth-animations';
        style.textContent = `
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.02); }
                    100% { transform: scale(1); }
                }
                
                .animated {
                    animation-duration: 0.3s;
                    animation-fill-mode: both;
                }
                
                .fadeIn {
                    animation-name: fadeIn;
                }
                
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                .is-invalid {
                    border-color: var(--danger) !important;
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right calc(0.375em + 0.1875rem) center;
                    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                }
                
                .invalid-feedback {
                    display: block;
                    font-size: 0.875rem;
                    color: var(--danger);
                    margin-top: 0.25rem;
                }
            `;
        document.head.appendChild(style);
    }
}

// Initialize animation styles
addAnimationStyles();
</script>
</body>

</html>