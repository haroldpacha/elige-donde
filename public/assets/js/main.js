// RE/MAX Peru Main JavaScript

document.addEventListener('DOMContentLoaded', function() {

    // Initialize all components
    initSearchForm();
    initPropertyFilters();
    initContactForms();
    initImageGalleries();
    initTooltips();
    initSmoothScrolling();

});

// Search Form Functionality
function initSearchForm() {
    const searchForm = document.getElementById('propertySearchForm');
    const transactionButtons = document.querySelectorAll('.transaction-buttons button');

    // Transaction type button handlers
    transactionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            transactionButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Update hidden input
            const transactionInput = document.getElementById('transaction_type');
            if (transactionInput) {
                transactionInput.value = this.dataset.type;
            }

            // Update form action or other logic if needed
            updateSearchResults();
        });
    });

    // Search form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>BUSCANDO...';
                submitBtn.disabled = true;
            }
        });
    }

    // Auto-complete for search
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performAutoSearch(this.value);
            }, 500);
        });
    }
}

// Property Filters
function initPropertyFilters() {
    const filterSelects = document.querySelectorAll('.property-filters select, .property-filters input');

    filterSelects.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.name === 'location_id' || this.name === 'property_type_id') {
                updateSearchResults();
            }
        });
    });

    // Price range validation
    const priceMinInput = document.querySelector('input[name="price_min"]');
    const priceMaxInput = document.querySelector('input[name="price_max"]');

    if (priceMinInput && priceMaxInput) {
        priceMinInput.addEventListener('blur', validatePriceRange);
        priceMaxInput.addEventListener('blur', validatePriceRange);
    }
}

// Contact Forms
function initContactForms() {
    // Property inquiry form
    const inquiryForm = document.getElementById('inquiryForm');
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', handleInquirySubmission);
    }

    // General contact forms
    const contactForms = document.querySelectorAll('.contact-form');
    contactForms.forEach(form => {
        form.addEventListener('submit', handleContactSubmission);
    });
}

// Image Galleries
function initImageGalleries() {
    // Property image carousel
    const carousel = document.getElementById('propertyCarousel');
    if (carousel) {
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                const prevBtn = carousel.querySelector('.carousel-control-prev');
                if (prevBtn) prevBtn.click();
            } else if (e.key === 'ArrowRight') {
                const nextBtn = carousel.querySelector('.carousel-control-next');
                if (nextBtn) nextBtn.click();
            }
        });
    }

    // Property card image lazy loading
    const propertyImages = document.querySelectorAll('.property-card img');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        propertyImages.forEach(img => imageObserver.observe(img));
    }
}

// Initialize Bootstrap tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Smooth scrolling for anchor links
function initSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Handle inquiry form submission
function handleInquirySubmission(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');

    // Show loading state
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>ENVIANDO...';
    submitBtn.disabled = true;

    fetch(form.action || '/property/inquiry', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Consulta enviada correctamente', 'success');
            form.reset();
        } else {
            showNotification(data.message || 'Error al enviar la consulta', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al enviar la consulta', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Handle general contact form submission
function handleContactSubmission(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('/contacto', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Mensaje enviado correctamente', 'success');
            form.reset();
        } else {
            showNotification(data.message || 'Error al enviar el mensaje', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al enviar el mensaje', 'error');
    });
}

// Auto-search functionality
function performAutoSearch(query) {
    if (query.length < 2) return;

    fetch(`/property/search-suggestions?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(suggestions => {
            displaySearchSuggestions(suggestions);
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}

// Display search suggestions
function displaySearchSuggestions(suggestions) {
    const existingSuggestions = document.querySelector('.search-suggestions');
    if (existingSuggestions) {
        existingSuggestions.remove();
    }

    if (suggestions.length === 0) return;

    const searchInput = document.querySelector('input[name="search"]');
    const suggestionBox = document.createElement('div');
    suggestionBox.className = 'search-suggestions';
    suggestionBox.style.cssText = `
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 5px 5px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    `;

    suggestions.forEach(suggestion => {
        const item = document.createElement('a');
        item.href = suggestion.url;
        item.className = 'suggestion-item';
        item.style.cssText = `
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #eee;
        `;
        item.innerHTML = `
            <i class="fas fa-${suggestion.type === 'property' ? 'home' : 'map-marker-alt'} me-2"></i>
            ${suggestion.text}
        `;

        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });

        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'white';
        });

        suggestionBox.appendChild(item);
    });

    searchInput.parentNode.style.position = 'relative';
    searchInput.parentNode.appendChild(suggestionBox);

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.remove();
        }
    });
}

// Update search results
function updateSearchResults() {
    // This function would be called when filters change
    // Implementation depends on whether you want AJAX updates or page refresh
    console.log('Updating search results...');
}

// Validate price range
function validatePriceRange() {
    const priceMin = document.querySelector('input[name="price_min"]');
    const priceMax = document.querySelector('input[name="price_max"]');

    if (priceMin && priceMax && priceMin.value && priceMax.value) {
        const min = parseFloat(priceMin.value);
        const max = parseFloat(priceMax.value);

        if (min > max) {
            showNotification('El precio mínimo no puede ser mayor al máximo', 'warning');
            priceMin.value = '';
            priceMax.value = '';
        }
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;

    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Geolocation for "Search Near Me"
function searchNearMe() {
    if (!navigator.geolocation) {
        showNotification('La geolocalización no está soportada en este navegador', 'error');
        return;
    }

    showNotification('Obteniendo tu ubicación...', 'info');

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Redirect to search with coordinates
            window.location.href = `/buscar-propiedades?lat=${lat}&lng=${lng}&radius=5`;
        },
        function(error) {
            let message = 'No se pudo obtener tu ubicación';

            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message = 'Permiso de ubicación denegado';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'Información de ubicación no disponible';
                    break;
                case error.TIMEOUT:
                    message = 'Tiempo de espera agotado para obtener ubicación';
                    break;
            }

            showNotification(message, 'error');
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        }
    );
}

// Property comparison functionality
const propertyComparison = {
    properties: [],

    add: function(propertyId) {
        if (this.properties.length >= 3) {
            showNotification('Solo puedes comparar hasta 3 propiedades', 'warning');
            return;
        }

        if (this.properties.includes(propertyId)) {
            showNotification('Esta propiedad ya está en comparación', 'warning');
            return;
        }

        this.properties.push(propertyId);
        this.updateComparisonBar();
        showNotification('Propiedad agregada a comparación', 'success');
    },

    remove: function(propertyId) {
        this.properties = this.properties.filter(id => id !== propertyId);
        this.updateComparisonBar();
    },

    updateComparisonBar: function() {
        let comparisonBar = document.querySelector('.comparison-bar');

        if (this.properties.length === 0) {
            if (comparisonBar) comparisonBar.remove();
            return;
        }

        if (!comparisonBar) {
            comparisonBar = document.createElement('div');
            comparisonBar.className = 'comparison-bar';
            comparisonBar.style.cssText = `
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: var(--remax-primary);
                color: white;
                padding: 15px;
                z-index: 1000;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            `;
            document.body.appendChild(comparisonBar);
        }

        comparisonBar.innerHTML = `
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Comparando ${this.properties.length} propiedades</span>
                    <div>
                        <button class="btn btn-light btn-sm me-2" onclick="propertyComparison.compare()">
                            Comparar
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="propertyComparison.clear()">
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        `;
    },

    compare: function() {
        if (this.properties.length < 2) {
            showNotification('Selecciona al menos 2 propiedades para comparar', 'warning');
            return;
        }

        const propertyIds = this.properties.join(',');
        window.location.href = `/comparar-propiedades?ids=${propertyIds}`;
    },

    clear: function() {
        this.properties = [];
        this.updateComparisonBar();
        showNotification('Comparación limpiada', 'info');
    }
};

// Property favorites functionality
const propertyFavorites = {
    favorites: JSON.parse(localStorage.getItem('remax-favorites') || '[]'),

    toggle: function(propertyId) {
        const index = this.favorites.indexOf(propertyId);

        if (index > -1) {
            this.favorites.splice(index, 1);
            showNotification('Propiedad removida de favoritos', 'info');
        } else {
            this.favorites.push(propertyId);
            showNotification('Propiedad agregada a favoritos', 'success');
        }

        this.save();
        this.updateFavoriteButtons();
    },

    save: function() {
        localStorage.setItem('remax-favorites', JSON.stringify(this.favorites));
    },

    updateFavoriteButtons: function() {
        const favoriteButtons = document.querySelectorAll('.favorite-btn');
        favoriteButtons.forEach(btn => {
            const propertyId = btn.dataset.propertyId;
            const isFavorite = this.favorites.includes(propertyId);

            btn.classList.toggle('active', isFavorite);
            btn.innerHTML = isFavorite ?
                '<i class="fas fa-heart"></i>' :
                '<i class="far fa-heart"></i>';
        });
    }
};

// Initialize favorites on page load
document.addEventListener('DOMContentLoaded', function() {
    propertyFavorites.updateFavoriteButtons();
});

// Utility functions
const utils = {
    formatPrice: function(price, currency = 'PEN') {
        const formatter = new Intl.NumberFormat('es-PE', {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        return formatter.format(price);
    },

    formatArea: function(area) {
        return `${area.toLocaleString('es-PE')} m²`;
    },

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
    }
};

// Global functions for window scope
window.searchNearMe = searchNearMe;
window.propertyComparison = propertyComparison;
window.propertyFavorites = propertyFavorites;
window.showNotification = showNotification;
