import Swal from 'sweetalert2';

// Make SweetAlert2 globally available
window.Swal = Swal;

/**
 * Global SweetAlert Toast Configuration
 */
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Toast = Toast;

/**
 * Show success message
 */
const showSuccess = function(message, title = 'Success!') {
    Toast.fire({
        icon: 'success',
        title: title,
        text: message
    });
};
window.showSuccess = showSuccess;

/**
 * Show error message
 */
const showError = function(message, title = 'Error!') {
    Toast.fire({
        icon: 'error',
        title: title,
        text: message
    });
};
window.showError = showError;

/**
 * Show info message
 */
const showInfo = function(message, title = 'Info') {
    Toast.fire({
        icon: 'info',
        title: title,
        text: message
    });
};
window.showInfo = showInfo;

/**
 * Show warning message
 */
const showWarning = function(message, title = 'Warning!') {
    Toast.fire({
        icon: 'warning',
        title: title,
        text: message
    });
};
window.showWarning = showWarning;

/**
 * Show confirmation dialog
 */
const showConfirm = function(message, title = 'Are you sure?', confirmButtonText = 'Yes', cancelButtonText = 'No') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        reverseButtons: true
    });
};
window.showConfirm = showConfirm;

/**
 * Show delete confirmation
 */
const showDeleteConfirm = function(itemName = 'this item') {
    return Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete ${itemName}? This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });
};
window.showDeleteConfirm = showDeleteConfirm;

/**
 * Show loading spinner
 */
const showLoading = function(message = 'Please wait...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};
window.showLoading = showLoading;

/**
 * Close loading spinner
 */
const closeLoading = function() {
    Swal.close();
};
window.closeLoading = closeLoading;

/**
 * Show modal with custom content
 */
const showModal = function(title, content, options = {}) {
    return Swal.fire({
        title: title,
        html: content,
        showConfirmButton: options.showConfirmButton !== false,
        showCancelButton: options.showCancelButton || false,
        confirmButtonText: options.confirmButtonText || 'OK',
        cancelButtonText: options.cancelButtonText || 'Cancel',
        width: options.width || '600px',
        ...options
    });
};
window.showModal = showModal;

/**
 * Universal Form Modal Handler
 */
const openFormModal = function(url, title, onSuccess = null) {
    showLoading('Loading form...');
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            // Extract form from the loaded HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const form = doc.querySelector('form');
            
            if (!form) {
                throw new Error('No form found in the response');
            }
            
            Swal.fire({
                title: title,
                html: form.outerHTML,
                width: '800px',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Close',
                didOpen: () => {
                    const modalForm = Swal.getHtmlContainer().querySelector('form');
                    
                    // Handle form submission
                    modalForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        const submitBtn = this.querySelector('[type="submit"]');
                        const originalText = submitBtn ? submitBtn.innerHTML : '';
                        
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        }
                        
                        try {
                            const response = await fetch(this.action, {
                                method: this.method || 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            
                            const data = await response.json();
                            
                            if (response.ok && data.success) {
                                Swal.close();
                                showSuccess(data.message || 'Operation completed successfully!');
                                
                                if (onSuccess) {
                                    onSuccess(data);
                                } else {
                                    // Reload page after short delay
                                    setTimeout(() => window.location.reload(), 1000);
                                }
                            } else {
                                // Show validation errors
                                if (data.errors) {
                                    let errorHtml = '<ul class="text-start">';
                                    Object.values(data.errors).forEach(errors => {
                                        errors.forEach(error => {
                                            errorHtml += `<li>${error}</li>`;
                                        });
                                    });
                                    errorHtml += '</ul>';
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Validation Error',
                                        html: errorHtml
                                    });
                                } else {
                                    showError(data.message || 'An error occurred');
                                }
                            }
                        } catch (error) {
                            showError('Network error. Please try again.');
                            console.error('Form submission error:', error);
                        } finally {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        }
                    });
                }
            });
        })
        .catch(error => {
            closeLoading();
            showError('Failed to load form. Please try again.');
            console.error('Error loading form:', error);
        });
};

/**
 * Handle delete action with confirmation
 */
const handleDelete = function(url, itemName = 'this item', onSuccess = null) {
    showDeleteConfirm(itemName).then((result) => {
        if (result.isConfirmed) {
            showLoading('Deleting...');
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                closeLoading();
                
                if (data.success) {
                    showSuccess(data.message || 'Deleted successfully!');
                    
                    if (onSuccess) {
                        onSuccess(data);
                    } else {
                        setTimeout(() => window.location.reload(), 1000);
                    }
                } else {
                    showError(data.message || 'Failed to delete');
                }
            })
            .catch(error => {
                closeLoading();
                showError('An error occurred while deleting');
                console.error('Delete error:', error);
            });
        }
    });
};
window.handleDelete = handleDelete;

/**
 * Replace all standard alerts on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    // Replace window.alert
    window.alert = function(message) {
        Swal.fire({
            icon: 'info',
            text: message
        });
    };
    
    // Replace window.confirm
    const originalConfirm = window.confirm;
    window.confirm = function(message) {
        // For immediate response needed, use synchronous approach
        return originalConfirm.call(this, message);
    };
    
    // Handle delete forms with confirmation
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = this.dataset.confirm;
            
            showDeleteConfirm(message).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
    
    // Convert all links with data-modal attribute
    document.querySelectorAll('[data-modal]').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href') || this.dataset.url;
            const title = this.dataset.modalTitle || 'Form';
            openFormModal(url, title);
        });
    });
});

export { Toast, showSuccess, showError, showInfo, showWarning, showConfirm, showDeleteConfirm, showLoading, closeLoading, showModal, openFormModal, handleDelete };
