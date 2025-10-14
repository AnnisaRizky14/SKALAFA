import Chart from 'chart.js/auto';

// Chart Data from Backend - dengan error handling
let chartsData;
try {
    chartsData = JSON.parse(document.getElementById('charts-data-json').textContent);
    console.log('Charts data loaded successfully:', chartsData);
} catch (e) {
    console.error('Charts data error:', e);
    chartsData = {
        satisfaction_by_faculty: [],
        monthly_responses: [],
        satisfaction_distribution: {1: 0, 2: 0, 3: 0, 4: 0, 5: 0},
        subcategory_ratings: []
    };
    console.log('Using fallback charts data:', chartsData);
}

// Check if Chart.js is loaded
if (typeof Chart === 'undefined') {
    console.error('Chart.js tidak terload!');
    document.querySelectorAll('canvas').forEach(canvas => {
        const container = canvas.closest('.relative');
        if (container) {
            container.innerHTML = '<div class="flex items-center justify-center h-64 text-red-500"><p>Chart.js gagal dimuat</p></div>';
        }
    });
} else {
    console.log('Chart.js berhasil dimuat, versi:', Chart.version);
}

document.addEventListener('DOMContentLoaded', function() {
    // Faculty Satisfaction Chart
    const facultySatisfactionCtx = document.getElementById('faculty-satisfaction-chart');
    if (facultySatisfactionCtx) {
        console.log('Canvas element found:', facultySatisfactionCtx);
        console.log('Canvas dimensions:', facultySatisfactionCtx.width, facultySatisfactionCtx.height);

        // Temporary sample data for testing
        const sampleData = [
            { faculty: 'FTI', average_rating: 4.2, total_responses: 15, color: '#003f7f' },
            { faculty: 'FEB', average_rating: 3.8, total_responses: 12, color: '#10b981' },
            { faculty: 'FISIP', average_rating: 4.0, total_responses: 18, color: '#f59e0b' }
        ];

        const dataToUse = chartsData.satisfaction_by_faculty.length > 0 ? chartsData.satisfaction_by_faculty : sampleData;
        console.log('Using data for faculty chart:', dataToUse);

        try {
            const facultyChart = new Chart(facultySatisfactionCtx, {
                type: 'bar',
                data: {
                    labels: dataToUse.map(item => item.faculty || 'Unknown'),
                    datasets: [{
                        label: 'Rating Kepuasan',
                        data: dataToUse.map(item => item.average_rating || 0),
                        backgroundColor: dataToUse.map(item => (item.color || '#003f7f') + '40'),
                        borderColor: dataToUse.map(item => item.color || '#003f7f'),
                        borderWidth: 2,
                        borderRadius: 4,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: function(context) {
                                    const faculty = dataToUse[context.dataIndex];
                                    return `${faculty.total_responses || 0} responden`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + '/5';
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                }
            });
            console.log('Faculty satisfaction chart created successfully');
        } catch (error) {
            console.error('Error creating faculty chart:', error);
        }
    } else {
        console.error('Faculty satisfaction canvas not found');
    }

    // Monthly Responses Chart
    const monthlyResponsesCtx = document.getElementById('monthly-responses-chart');
    if (monthlyResponsesCtx && chartsData.monthly_responses) {
        new Chart(monthlyResponsesCtx, {
            type: 'line',
            data: {
                labels: chartsData.monthly_responses.map(item => item.month || ''),
                datasets: [{
                    label: 'Jumlah Respon',
                    data: chartsData.monthly_responses.map(item => item.count || 0),
                    borderColor: '#003f7f',
                    backgroundColor: '#003f7f20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#003f7f',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 6
                    }
                }
            }
        });
    }

    // Satisfaction Distribution Chart
    const satisfactionDistributionCtx = document.getElementById('satisfaction-distribution-chart');
    if (satisfactionDistributionCtx && chartsData.satisfaction_distribution) {
        new Chart(satisfactionDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sangat Tidak Puas', 'Tidak Puas', 'Cukup Puas', 'Puas', 'Sangat Puas'],
                datasets: [{
                    data: Object.values(chartsData.satisfaction_distribution),
                    backgroundColor: ['#df0d0dff', '#ffb93fff', '#52555cff', '#2174faff', '#10b926ff'],
                    borderWidth: 0,
                    hoverBorderWidth: 2,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Subcategory Ratings Chart
    const subcategoryRatingsCtx = document.getElementById('subcategory-ratings-chart');
    if (subcategoryRatingsCtx && chartsData.subcategory_ratings) {
        new Chart(subcategoryRatingsCtx, {
            type: 'radar',
            data: {
                labels: chartsData.subcategory_ratings.map(item => item.name || ''),
                datasets: [{
                    label: 'Rating',
                    data: chartsData.subcategory_ratings.map(item => item.average_rating || 0),
                    backgroundColor: '#003f7f20',
                    borderColor: '#003f7f',
                    borderWidth: 2,
                    pointBackgroundColor: '#003f7f',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value;
                            }
                        },
                        grid: {
                            color: '#e5e7eb'
                        },
                        angleLines: {
                            color: '#e5e7eb'
                        },
                        pointLabels: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // Faculty chart period filter
    const facultyPeriodSelect = document.getElementById('faculty-chart-period');
    if (facultyPeriodSelect) {
        facultyPeriodSelect.addEventListener('change', function() {
            // Implement filtering logic here if needed
            console.log('Filter changed to:', this.value);
        });
    }
});

// Auto-refresh data every 5 minutes
setInterval(function() {
    // Only reload if user has been inactive
    if (document.hidden === false) {
        location.reload();
    }
}, 300000);

// Real-time updates simulation
function updateStats() {
    // Add subtle animation to indicate data refresh
    document.querySelectorAll('.text-2xl.font-bold').forEach(el => {
        el.style.transition = 'color 0.3s ease';
        el.style.color = '#10b981';
        setTimeout(() => {
            el.style.color = '';
        }, 1000);
    });
}

    // Show loading state for quick actions
    document.querySelectorAll('a[href*="admin"]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!e.ctrlKey && !e.metaKey) {
                const spinner = document.createElement('div');
                spinner.className = 'inline-block ml-2 animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent';
                this.appendChild(spinner);
            }
        });
    });

// Responsive chart handling
window.addEventListener('resize', function() {
    Chart.helpers.each(Chart.instances, function(instance) {
        instance.resize();
    });
});

// Error handling for charts
window.addEventListener('error', function(e) {
    if (e.message.includes('Chart')) {
        console.error('Chart error:', e);
        // Hide chart containers and show fallback message
        document.querySelectorAll('canvas').forEach(canvas => {
            const container = canvas.closest('.relative');
            if (container) {
                container.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500"><p>Grafik tidak dapat dimuat</p></div>';
            }
        });
    }
});

// Accessibility improvements
document.querySelectorAll('canvas').forEach(canvas => {
    canvas.setAttribute('role', 'img');
    canvas.setAttribute('aria-label', 'Chart visualization');
});

// Real-time clock function
function updateDateTime() {
    const now = new Date();
    const options = {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    };
    const formattedDateTime = now.toLocaleDateString('id-ID', options) + ' WIB';
    document.getElementById('current-date-time').textContent = formattedDateTime;
}

// Update time every second
setInterval(updateDateTime, 1000);

// Initial call
updateDateTime();

// Notification functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mark individual notification as read
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-notification-id');
            const notificationElement = this.closest('[data-notification-id]');

            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    notificationElement.classList.add('opacity-75');
                    this.remove();
                    updateUnreadCount();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Mark all notifications as read
    const markAllBtn = document.getElementById('mark-all-read');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    document.querySelectorAll('[data-notification-id]').forEach(el => {
                        el.classList.add('opacity-75');
                    });
                    document.querySelectorAll('.mark-read-btn').forEach(btn => {
                        btn.remove();
                    });
                    updateUnreadCount();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    function updateUnreadCount() {
        const unreadElements = document.querySelectorAll('[data-notification-id] .bg-red-500');
        const count = unreadElements.length;
        const badge = document.getElementById('unread-badge');

        if (count > 0) {
            if (badge) {
                badge.textContent = `${count} belum dibaca`;
            }
        } else {
            if (badge) {
                badge.remove();
            }
        }
    }
});
