// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';
import 'sweetalert2/dist/sweetalert2.min.css';
import Swal from 'sweetalert2';

// Make Alpine available globally
window.Alpine = Alpine;

// Make SweetAlert2 available globally
window.Swal = Swal;

// Start Alpine
Alpine.start();

// Global JavaScript Functions
window.SKALAFA = {
    // Toast notification system
    toast: {
        success: function(message, title = 'Berhasil') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-toast-success'
                }
            });
        },
        
        error: function(message, title = 'Error') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                timer: 5000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-toast-error'
                }
            });
        },
        
        warning: function(message, title = 'Peringatan') {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                timer: 4000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-toast-warning'
                }
            });
        },
        
        info: function(message, title = 'Informasi') {
            Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                timer: 4000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-toast-info'
                }
            });
        }
    },
    
    // Confirmation dialog
    confirm: function(message, title = 'Konfirmasi', confirmText = 'Ya', cancelText = 'Batal') {
        return Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#003f7f',
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            customClass: {
                popup: 'swal-confirm-popup',
                confirmButton: 'swal-confirm-button',
                cancelButton: 'swal-cancel-button'
            }
        });
    },
    
    // Loading overlay
    loading: {
        show: function(message = 'Memproses...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: {
                    popup: 'swal-loading-popup'
                }
            });
        },
        
        hide: function() {
            Swal.close();
        }
    },
    
    // Form utilities
    form: {
        serialize: function(form) {
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }
            return data;
        },
        
        validate: function(form) {
            const inputs = form.querySelectorAll('[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                const errorElement = input.parentNode.querySelector('.form-error');
                
                if (!input.value.trim()) {
                    input.classList.add('form-input-error');
                    if (errorElement) {
                        errorElement.textContent = 'Field ini wajib diisi';
                    }
                    isValid = false;
                } else {
                    input.classList.remove('form-input-error');
                    if (errorElement) {
                        errorElement.textContent = '';
                    }
                }
            });
            
            return isValid;
        }
    },
    
    // URL utilities
    url: {
        current: window.location.href,
        base: document.querySelector('meta[name="app-url"]')?.getAttribute('content') || window.location.origin,
        
        route: function(name, params = {}) {
            // This would need to be integrated with Laravel's route() helper
            return this.base + '/' + name;
        }
    },
    
    // CSRF Token
    csrf: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
    
    // AJAX utilities
    ajax: {
        get: function(url, options = {}) {
            return fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    ...options.headers
                },
                ...options
            }).then(response => this.handleResponse(response));
        },
        
        post: function(url, data = {}, options = {}) {
            const formData = new FormData();
            
            // Add CSRF token
            if (window.SKALAFA.csrf) {
                formData.append('_token', window.SKALAFA.csrf);
            }
            
            // Add data
            Object.keys(data).forEach(key => {
                if (Array.isArray(data[key])) {
                    data[key].forEach(item => formData.append(key + '[]', item));
                } else {
                    formData.append(key, data[key]);
                }
            });
            
            return fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    ...options.headers
                },
                body: formData,
                ...options
            }).then(response => this.handleResponse(response));
        },
        
        put: function(url, data = {}, options = {}) {
            data._method = 'PUT';
            return this.post(url, data, options);
        },
        
        delete: function(url, options = {}) {
            const data = { _method: 'DELETE' };
            return this.post(url, data, options);
        },
        
        handleResponse: function(response) {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text();
            }
        }
    },
    
    // Storage utilities
    storage: {
        get: function(key, defaultValue = null) {
            try {
                const item = localStorage.getItem(key);
                return item ? JSON.parse(item) : defaultValue;
            } catch (e) {
                return defaultValue;
            }
        },
        
        set: function(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify(value));
                return true;
            } catch (e) {
                return false;
            }
        },
        
        remove: function(key) {
            try {
                localStorage.removeItem(key);
                return true;
            } catch (e) {
                return false;
            }
        },
        
        clear: function() {
            try {
                localStorage.clear();
                return true;
            } catch (e) {
                return false;
            }
        }
    },
    
    // Utility functions
    utils: {
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
        },
        
        formatNumber: function(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        },
        
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        },
        
        formatDate: function(date, options = {}) {
            return new Intl.DateTimeFormat('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                ...options
            }).format(new Date(date));
        },
        
        slugify: function(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-');
        },
        
        copyToClipboard: function(text) {
            return navigator.clipboard.writeText(text).then(() => {
                window.SKALAFA.toast.success('Berhasil disalin ke clipboard');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                window.SKALAFA.toast.success('Berhasil disalin ke clipboard');
            });
        }
    }
};

// Initialize global event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Handle CSRF token for AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.SKALAFA.csrf = token.getAttribute('content');
        
        // Set default headers for all AJAX requests
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            if (args[1] && args[1].method && args[1].method !== 'GET') {
                args[1].headers = {
                    'X-CSRF-TOKEN': window.SKALAFA.csrf,
                    ...args[1].headers
                };
            }
            return originalFetch.apply(this, args);
        };
    }
    
    // Handle delete confirmations
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-confirm-delete]')) {
            e.preventDefault();
            
            const message = e.target.getAttribute('data-confirm-delete') || 'Apakah Anda yakin ingin menghapus data ini?';
            
            window.SKALAFA.confirm(message, 'Konfirmasi Hapus', 'Hapus', 'Batal')
                .then((result) => {
                    if (result.isConfirmed) {
                        if (e.target.tagName === 'FORM') {
                            e.target.submit();
                        } else if (e.target.closest('form')) {
                            e.target.closest('form').submit();
                        } else if (e.target.href) {
                            window.location.href = e.target.href;
                        }
                    }
                });
        }
    });
    
    // Handle loading states for forms
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const submitButton = form.querySelector('[type="submit"]');
        
        if (submitButton && !form.hasAttribute('data-no-loading')) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50');
            
            const originalText = submitButton.textContent;
            submitButton.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            
            // Reset after 30 seconds as fallback
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50');
                submitButton.textContent = originalText;
            }, 30000);
        }
    });
    
    // Handle auto-save functionality
    document.addEventListener('input', window.SKALAFA.utils.debounce(function(e) {
        if (e.target.hasAttribute('data-auto-save')) {
            const key = e.target.getAttribute('data-auto-save');
            window.SKALAFA.storage.set(key, e.target.value);
        }
    }, 1000));
    
    // Load auto-saved data
    document.querySelectorAll('[data-auto-save]').forEach(element => {
        const key = element.getAttribute('data-auto-save');
        const savedValue = window.SKALAFA.storage.get(key);
        if (savedValue) {
            element.value = savedValue;
        }
    });
    
    // Handle smooth scrolling for anchor links
    document.addEventListener('click', function(e) {
        if (e.target.matches('a[href^="#"]')) {
            e.preventDefault();
            const targetId = e.target.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
    
    // Initialize tooltips
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg tooltip-element';
            tooltip.textContent = this.getAttribute('data-tooltip');
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
});

// Survey-specific JavaScript
window.SKALAFA.survey = {
    // Progress tracking
    progress: {
        update: function(current, total) {
            const percentage = Math.round((current / total) * 100);
            const progressBars = document.querySelectorAll('.progress-fill');
            const progressTexts = document.querySelectorAll('[data-progress-text]');
            
            progressBars.forEach(bar => {
                bar.style.width = percentage + '%';
            });
            
            progressTexts.forEach(text => {
                text.textContent = percentage + '%';
            });
        },
        
        save: function(data) {
            const token = document.querySelector('input[name="response_token"]')?.value;
            if (token) {
                window.SKALAFA.storage.set('survey_progress_' + token, data);
            }
        },
        
        load: function() {
            const token = document.querySelector('input[name="response_token"]')?.value;
            if (token) {
                return window.SKALAFA.storage.get('survey_progress_' + token, {});
            }
            return {};
        },
        
        clear: function() {
            const token = document.querySelector('input[name="response_token"]')?.value;
            if (token) {
                window.SKALAFA.storage.remove('survey_progress_' + token);
            }
        }
    },
    
    // Rating functionality
    rating: {
        select: function(questionId, rating) {
            const questionElement = document.querySelector(`[data-question="${questionId}"]`);
            if (!questionElement) return;
            
            // Update radio button
            const radioInput = questionElement.querySelector(`input[value="${rating}"]`);
            if (radioInput) {
                radioInput.checked = true;
            }
            
            // Update visual feedback
            const ratingCards = questionElement.querySelectorAll('.survey-rating-card');
            ratingCards.forEach((card, index) => {
                card.classList.remove('selected');
                if (index === rating - 1) {
                    card.classList.add('selected');
                }
            });
            
            // Auto-save
            this.autoSave(questionId, rating);
        },
        
        autoSave: function(questionId, rating) {
            const token = document.querySelector('input[name="response_token"]')?.value;
            if (!token) return;
            
            const data = {
                question_id: questionId,
                rating: rating,
                _token: window.SKALAFA.csrf
            };
            
            // Save to localStorage as backup
            const progressData = window.SKALAFA.survey.progress.load();
            progressData[questionId] = rating;
            window.SKALAFA.survey.progress.save(progressData);
            
            // Optional: Save to server
            window.SKALAFA.ajax.post('/survey/auto-save', data)
                .catch(error => {
                    console.warn('Auto-save failed:', error);
                });
        }
    },
    
    // Validation
    validate: function() {
        const requiredQuestions = document.querySelectorAll('[data-required="true"]');
        const errors = [];
        
        requiredQuestions.forEach(question => {
            const questionId = question.getAttribute('data-question');
            const ratingInput = question.querySelector('input[type="radio"]:checked');
            
            if (!ratingInput) {
                errors.push({
                    questionId: questionId,
                    message: 'Pertanyaan ini wajib dijawab'
                });
            }
        });
        
        return {
            isValid: errors.length === 0,
            errors: errors
        };
    },
    
    // Submission
    submit: function(formElement) {
        const validation = this.validate();
        
        if (!validation.isValid) {
            let errorMessage = 'Mohon lengkapi pertanyaan berikut:\n';
            validation.errors.forEach((error, index) => {
                errorMessage += `${index + 1}. Pertanyaan ${error.questionId}\n`;
            });
            
            window.SKALAFA.toast.warning(errorMessage, 'Validasi Gagal');
            
            // Scroll to first error
            const firstError = validation.errors[0];
            const errorElement = document.querySelector(`[data-question="${firstError.questionId}"]`);
            if (errorElement) {
                errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                errorElement.classList.add('shake');
                setTimeout(() => errorElement.classList.remove('shake'), 500);
            }
            
            return false;
        }
        
        // Clear saved progress
        this.progress.clear();
        
        return true;
    }
};

// Admin dashboard utilities
window.SKALAFA.admin = {
    // Chart utilities
    charts: {
        colors: {
            primary: '#003f7f',
            secondary: '#ff6b35',
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            info: '#3b82f6',
            gray: '#6b7280'
        },
        
        defaultOptions: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
        
        createBarChart: function(ctx, data, options = {}) {
            return new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    ...this.defaultOptions,
                    ...options
                }
            });
        },
        
        createLineChart: function(ctx, data, options = {}) {
            return new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    ...this.defaultOptions,
                    ...options
                }
            });
        },
        
        createPieChart: function(ctx, data, options = {}) {
            return new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    ...this.defaultOptions,
                    ...options,
                    scales: undefined
                }
            });
        }
    },
    
    // Data refresh
    refresh: {
        stats: function() {
            return window.SKALAFA.ajax.get('/admin/api/stats')
                .then(data => {
                    // Update stat cards
                    Object.keys(data).forEach(key => {
                        const element = document.querySelector(`[data-stat="${key}"]`);
                        if (element) {
                            element.textContent = window.SKALAFA.utils.formatNumber(data[key]);
                        }
                    });
                })
                .catch(error => {
                    console.error('Failed to refresh stats:', error);
                });
        },
        
        charts: function() {
            return window.SKALAFA.ajax.get('/admin/api/charts')
                .then(data => {
                    // Update charts with new data
                    window.dispatchEvent(new CustomEvent('charts:update', { detail: data }));
                })
                .catch(error => {
                    console.error('Failed to refresh charts:', error);
                });
        }
    },
    
    // Export utilities
    export: {
        pdf: function(url, filename = 'export.pdf') {
            window.SKALAFA.loading.show('Menyiapkan file PDF...');
            
            return fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.SKALAFA.csrf,
                    'Accept': 'application/pdf'
                }
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                window.SKALAFA.loading.hide();
                window.SKALAFA.toast.success('File PDF berhasil diunduh');
            })
            .catch(error => {
                window.SKALAFA.loading.hide();
                window.SKALAFA.toast.error('Gagal mengunduh file PDF');
                console.error('Export error:', error);
            });
        },
        
        excel: function(url, filename = 'export.xlsx') {
            window.SKALAFA.loading.show('Menyiapkan file Excel...');
            
            return fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.SKALAFA.csrf,
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                window.SKALAFA.loading.hide();
                window.SKALAFA.toast.success('File Excel berhasil diunduh');
            })
            .catch(error => {
                window.SKALAFA.loading.hide();
                window.SKALAFA.toast.error('Gagal mengunduh file Excel');
                console.error('Export error:', error);
            });
        }
    }
};

// Add shake animation CSS if not already present
if (!document.querySelector('#shake-animation-css')) {
    const shakeCSS = document.createElement('style');
    shakeCSS.id = 'shake-animation-css';
    shakeCSS.textContent = `
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
    `;
    document.head.appendChild(shakeCSS);
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('SKALAFA System initialized');
    });
} else {
    console.log('SKALAFA System initialized');
}