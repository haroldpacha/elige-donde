// Elige Donde Peru Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin dashboard
    initSidebar();
    initTooltips();
    initToasts();
    initDataTables();
    initFormValidation();
    initFileUploads();
    initConfirmDialogs();
    initAutoSave();
});

// Sidebar functionality
function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.querySelector('.navbar-toggler');
    const body = document.body;

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                body.classList.toggle('sidebar-open');
            }
        });
    }

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggle?.contains(e.target)) {
                sidebar.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
            body.classList.remove('sidebar-open');
        }
    });
}

// Initialize Bootstrap tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Toast notifications system
function initToasts() {
    window.showToast = function(type, message, title = '') {
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) return;

        const toastId = 'toast_' + Date.now();
        const iconClass = getToastIcon(type);
        const bgClass = getToastBgClass(type);

        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="${iconClass} me-2"></i>
                        ${title ? `<strong>${title}</strong><br>` : ''}
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);

        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: type === 'error' ? 8000 : 5000
        });

        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    };

    // Toast helper functions
    function getToastIcon(type) {
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    function getToastBgClass(type) {
        const classes = {
            success: 'bg-success',
            error: 'bg-danger',
            warning: 'bg-warning',
            info: 'bg-info'
        };
        return classes[type] || classes.info;
    }
}

// DataTables initialization
function initDataTables() {
    const tables = document.querySelectorAll('.data-table');

    tables.forEach(table => {
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $(table).DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [-1] } // Disable ordering on last column (actions)
                ]
            });
        }
    });
}

// Form validation
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();

                // Focus on first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    showToast('error', 'Por favor, completa todos los campos requeridos');
                }
            }

            form.classList.add('was-validated');
        });
    });

    // Real-time validation
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            this.classList.add('was-validated');
        });
    });
}

// File upload handling
function initFileUploads() {
    // Image uploads
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleImageUpload(this);
        });
    });

    // PDF uploads
    const pdfInputs = document.querySelectorAll('input[type="file"][accept*="pdf"]');
    pdfInputs.forEach(input => {
        input.addEventListener('change', function() {
            handlePdfUpload(this);
        });
    });

    // Drag and drop
    const dropZones = document.querySelectorAll('.image-upload-area');
    dropZones.forEach(zone => {
        zone.addEventListener('dragover', handleDragOver);
        zone.addEventListener('dragleave', handleDragLeave);
        zone.addEventListener('drop', handleDrop);
    });
}

function handleImageUpload(input) {
    const files = input.files;
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    for (let file of files) {
        if (file.size > maxSize) {
            showToast('error', `El archivo ${file.name} es demasiado grande. Máximo 5MB.`);
            input.value = '';
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            showToast('error', `El archivo ${file.name} no es un formato válido. Solo JPG, PNG, WEBP.`);
            input.value = '';
            return;
        }
    }

    if (files.length > 20) {
        showToast('error', 'Máximo 20 imágenes permitidas');
        input.value = '';
        return;
    }

    showToast('success', `${files.length} imagen(es) seleccionada(s)`);
}

function handlePdfUpload(input) {
    const file = input.files[0];
    if (!file) return;

    const maxSize = 10 * 1024 * 1024; // 10MB

    if (file.size > maxSize) {
        showToast('error', 'El archivo PDF es demasiado grande. Máximo 10MB.');
        input.value = '';
        return;
    }

    if (file.type !== 'application/pdf') {
        showToast('error', 'Solo se permiten archivos PDF.');
        input.value = '';
        return;
    }

    showToast('success', `PDF seleccionado: ${file.name}`);
}

function handleDragOver(e) {
    e.preventDefault();
    this.classList.add('dragover');
}

function handleDragLeave(e) {
    e.preventDefault();
    this.classList.remove('dragover');
}

function handleDrop(e) {
    e.preventDefault();
    this.classList.remove('dragover');

    const files = e.dataTransfer.files;
    const input = this.querySelector('input[type="file"]');

    if (input && files.length > 0) {
        input.files = files;
        input.dispatchEvent(new Event('change'));
    }
}

// Confirmation dialogs
function initConfirmDialogs() {
    window.confirmAction = function(message, callback, title = '¿Estás seguro?') {
        const result = confirm(`${title}\n\n${message}\n\nEsta acción no se puede deshacer.`);
        if (result && typeof callback === 'function') {
            callback();
        }
        return result;
    };

    // Delete buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.delete-btn, .btn-delete') || e.target.closest('.delete-btn, .btn-delete')) {
            e.preventDefault();

            const button = e.target.closest('.delete-btn, .btn-delete');
            const itemName = button.dataset.itemName || 'este elemento';
            const deleteUrl = button.dataset.deleteUrl || button.href;

            confirmAction(
                `¿Estás seguro de que quieres eliminar ${itemName}?`,
                function() {
                    if (deleteUrl) {
                        if (button.dataset.method === 'delete') {
                            // AJAX delete
                            deleteAjax(deleteUrl, button);
                        } else {
                            // Regular form submit or redirect
                            window.location.href = deleteUrl;
                        }
                    }
                },
                'Confirmar eliminación'
            );
        }
    });
}

// AJAX delete function
function deleteAjax(url, button) {
    const row = button.closest('tr, .card, .list-group-item');

    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (row) {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0.5';
                setTimeout(() => row.remove(), 300);
            }
            showToast('success', data.message || 'Elemento eliminado exitosamente');
        } else {
            showToast('error', data.message || 'Error al eliminar el elemento');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error de conexión al eliminar el elemento');
    });
}

// Auto-save functionality
function initAutoSave() {
    const autoSaveForms = document.querySelectorAll('[data-autosave]');

    autoSaveForms.forEach(form => {
        const interval = parseInt(form.dataset.autosave) || 30000; // Default 30 seconds
        let autoSaveTimer;
        let hasChanges = false;

        // Track changes
        form.addEventListener('input', function() {
            hasChanges = true;

            // Reset timer
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                if (hasChanges) {
                    autoSave(form);
                }
            }, interval);
        });

        // Save on page unload
        window.addEventListener('beforeunload', function(e) {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = '¿Estás seguro de que quieres salir? Los cambios no guardados se perderán.';
            }
        });
    });

    function autoSave(form) {
        const formData = new FormData(form);
        formData.append('auto_save', '1');

        fetch(form.action || window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hasChanges = false;
                showToast('info', 'Borrador guardado automáticamente', 'Auto-guardado');
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
        });
    }
}

// Utility functions
window.AdminUtils = {
    // Format currency
    formatCurrency: function(amount, currency = 'PEN') {
        const formatter = new Intl.NumberFormat('es-PE', {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        return formatter.format(amount);
    },

    // Format number
    formatNumber: function(number) {
        return new Intl.NumberFormat('es-PE').format(number);
    },

    // Format date
    formatDate: function(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const formatOptions = { ...defaultOptions, ...options };
        return new Date(date).toLocaleDateString('es-PE', formatOptions);
    },

    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Throttle function
    throttle: function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Copy to clipboard
    copyToClipboard: function(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('success', 'Copiado al portapapeles');
        }, function(err) {
            console.error('Could not copy text: ', err);
            showToast('error', 'Error al copiar al portapapeles');
        });
    },

    // Generate random ID
    generateId: function(prefix = 'id') {
        return prefix + '_' + Math.random().toString(36).substr(2, 9);
    },

    // Validate email
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    // Get file size in human readable format
    getFileSize: function(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
};

// Global AJAX setup
if (typeof $ !== 'undefined') {
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
}

// Handle session timeout
let sessionTimeoutWarning = false;
document.addEventListener('ajaxError', function(e, xhr) {
    if (xhr.status === 401 && !sessionTimeoutWarning) {
        sessionTimeoutWarning = true;
        showToast('warning', 'Tu sesión ha expirado. Serás redirigido al login.', 'Sesión Expirada');
        setTimeout(() => {
            window.location.href = '/admin/login';
        }, 3000);
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const saveBtn = document.querySelector('button[type="submit"], .btn-save');
        if (saveBtn && !saveBtn.disabled) {
            saveBtn.click();
        }
    }

    // Escape to close modals
    if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal.show');
        if (openModal) {
            const modal = bootstrap.Modal.getInstance(openModal);
            if (modal) modal.hide();
        }
    }
});

// Print functionality
window.printElement = function(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>Imprimir</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="/assets/css/admin.css" rel="stylesheet">
            <style>
                @media print {
                    body { margin: 0; }
                    .no-print { display: none !important; }
                }
            </style>
        </head>
        <body>
            ${element.outerHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
};

// Export functionality
window.exportToCSV = function(data, filename = 'export.csv') {
    const csvContent = "data:text/csv;charset=utf-8," + data.map(row => row.join(",")).join("\n");
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Initialize everything when DOM is loaded
console.log('Elige Donde Admin Dashboard JavaScript Loaded');

// Auto-hide alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        if (alert.classList.contains('show') || alert.classList.contains('fade')) {
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }
            }, 5000);
        }
    });
}, 1000);
