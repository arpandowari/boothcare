@extends('layouts.app')

@section('title', 'System Reports - Boothcare')
@section('page-title', 'System Reports')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --shadow-soft: 0 4px 20px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 30px rgba(0,0,0,0.15);
        --border-radius: 16px;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    /* Modern Header */
    .reports-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem 0;
        margin: -1rem -1rem 2rem -1rem;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .reports-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
    }

    /* Modern Cards */
    .modern-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }

    .card-header-modern {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
    }

    .card-icon.primary { background: var(--primary-gradient); }
    .card-icon.success { background: var(--success-gradient); }
    .card-icon.danger { background: var(--danger-gradient); }
    .card-icon.warning { background: var(--warning-gradient); }
    .card-icon.info { background: var(--info-gradient); }

    .card-title-modern {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .card-body-modern {
        padding: 1.5rem;
    }

    /* Statistics Cards */
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-align: center;
        box-shadow: var(--shadow-soft);
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: all 0.3s ease;
    }

    .stat-card.requests::before { background: var(--primary-gradient); }
    .stat-card.problems::before { background: var(--danger-gradient); }
    .stat-card.users::before { background: var(--success-gradient); }
    .stat-card.performance::before { background: var(--warning-gradient); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin: 0 auto 1rem;
    }

    .stat-icon.requests { background: var(--primary-gradient); }
    .stat-icon.problems { background: var(--danger-gradient); }
    .stat-icon.users { background: var(--success-gradient); }
    .stat-icon.performance { background: var(--warning-gradient); }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-sublabel {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.25rem;
    }

    /* Chart Container */
    .chart-container {
        height: 400px;
        position: relative;
    }

    /* Date Range Picker */
    .date-range-picker {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: var(--shadow-soft);
        margin-bottom: 1.5rem;
    }

    .date-input {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .date-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    /* Export Buttons */
    .export-btn {
        background: var(--success-gradient);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .export-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: white;
    }

    .export-btn.csv {
        background: var(--info-gradient);
    }

    .export-btn.json {
        background: var(--warning-gradient);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-title {
            font-size: 2rem;
        }
        
        .reports-header {
            padding: 1.5rem 0;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .chart-container {
            height: 300px;
        }
    }

    /* Loading Animation */
    .loading {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Modern Header -->
    <div class="reports-header">
        <div class="container">
            <div class="header-content">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="header-title">
                            <i class="fas fa-chart-line me-3"></i>
                            System Reports
                        </h1>
                        <p class="header-subtitle">
                            Comprehensive analytics and insights for {{ $startDate }} to {{ $endDate }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex gap-2 justify-content-end flex-wrap">
                            <a href="{{ route('reports.export', ['format' => 'csv', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                               class="export-btn csv">
                                <i class="fas fa-file-csv"></i>
                                Export CSV
                            </a>
                            <a href="{{ route('reports.export', ['format' => 'json', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                               class="export-btn json">
                                <i class="fas fa-file-code"></i>
                                Export JSON
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Picker -->
    <div class="date-range-picker">
        <form method="GET" action="{{ route('reports.index') }}" class="row align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-bold">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                       class="form-control date-input" required>
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-bold">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                       class="form-control date-input" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px; padding: 0.75rem;">
                    <i class="fas fa-search me-2"></i>
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card requests">
                <div class="stat-icon requests">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="stat-number">{{ $stats['update_requests']['total'] }}</div>
                <div class="stat-label">Update Requests</div>
                <div class="stat-sublabel">{{ $stats['update_requests']['approval_rate'] }}% approval rate</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card problems">
                <div class="stat-icon problems">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-number">{{ $stats['problems']['total'] }}</div>
                <div class="stat-label">Problems Reported</div>
                <div class="stat-sublabel">{{ $stats['problems']['resolution_rate'] }}% resolved</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card users">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $stats['users']['total_active'] }}</div>
                <div class="stat-label">Active Users</div>
                <div class="stat-sublabel">{{ $stats['users']['new_registrations'] }} new registrations</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card performance">
                <div class="stat-icon performance">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-number">{{ $stats['performance']['response_rate'] }}%</div>
                <div class="stat-label">Response Rate</div>
                <div class="stat-sublabel">{{ $stats['performance']['avg_approval_time'] }}h avg approval</div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row">
        <!-- Update Requests Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon primary">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="card-title-modern">Update Requests Breakdown</h3>
                </div>
                <div class="card-body-modern">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-warning">{{ $stats['update_requests']['pending'] }}</div>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-success">{{ $stats['update_requests']['approved'] }}</div>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-danger">{{ $stats['update_requests']['rejected'] }}</div>
                                <small class="text-muted">Rejected</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-primary">{{ $stats['update_requests']['total'] }}</div>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="progress" style="height: 8px; border-radius: 4px;">
                            @php
                                $total = $stats['update_requests']['total'];
                                $approvedPercent = $total > 0 ? ($stats['update_requests']['approved'] / $total) * 100 : 0;
                                $rejectedPercent = $total > 0 ? ($stats['update_requests']['rejected'] / $total) * 100 : 0;
                                $pendingPercent = $total > 0 ? ($stats['update_requests']['pending'] / $total) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $approvedPercent }}%"></div>
                            <div class="progress-bar bg-danger" style="width: {{ $rejectedPercent }}%"></div>
                            <div class="progress-bar bg-warning" style="width: {{ $pendingPercent }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success">{{ round($approvedPercent, 1) }}% Approved</small>
                            <small class="text-danger">{{ round($rejectedPercent, 1) }}% Rejected</small>
                            <small class="text-warning">{{ round($pendingPercent, 1) }}% Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Problems Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="card-title-modern">Problems Breakdown</h3>
                </div>
                <div class="card-body-modern">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-info">{{ $stats['problems']['reported'] }}</div>
                                <small class="text-muted">Reported</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-warning">{{ $stats['problems']['in_progress'] }}</div>
                                <small class="text-muted">In Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-success">{{ $stats['problems']['resolved'] }}</div>
                                <small class="text-muted">Resolved</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <div class="h4 text-primary">{{ $stats['problems']['total'] }}</div>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="progress" style="height: 8px; border-radius: 4px;">
                            @php
                                $total = $stats['problems']['total'];
                                $reportedPercent = $total > 0 ? ($stats['problems']['reported'] / $total) * 100 : 0;
                                $progressPercent = $total > 0 ? ($stats['problems']['in_progress'] / $total) * 100 : 0;
                                $resolvedPercent = $total > 0 ? ($stats['problems']['resolved'] / $total) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-info" style="width: {{ $reportedPercent }}%"></div>
                            <div class="progress-bar bg-warning" style="width: {{ $progressPercent }}%"></div>
                            <div class="progress-bar bg-success" style="width: {{ $resolvedPercent }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-info">{{ round($reportedPercent, 1) }}% Reported</small>
                            <small class="text-warning">{{ round($progressPercent, 1) }}% In Progress</small>
                            <small class="text-success">{{ round($resolvedPercent, 1) }}% Resolved</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="card-title-modern">Activity Trends</h3>
                </div>
                <div class="card-body-modern">
                    <div class="chart-container">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mt-4">
        <div class="col-lg-4 mb-3">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="card-title-modern">Average Response Time</h3>
                </div>
                <div class="card-body-modern text-center">
                    <div class="h2 text-warning">{{ $stats['performance']['avg_approval_time'] }}</div>
                    <p class="text-muted mb-0">Hours to approval</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="card-title-modern">Resolution Time</h3>
                </div>
                <div class="card-body-modern text-center">
                    <div class="h2 text-success">{{ $stats['performance']['avg_resolution_time'] }}</div>
                    <p class="text-muted mb-0">Days to resolution</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon primary">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3 class="card-title-modern">Admin Actions</h3>
                </div>
                <div class="card-body-modern text-center">
                    <div class="h2 text-primary">{{ $stats['users']['admin_actions'] }}</div>
                    <p class="text-muted mb-0">Administrative actions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon primary">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="card-title-modern">Quick Actions</h3>
                </div>
                <div class="card-body-modern">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.detailed') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-2"></i>
                                Detailed Reports
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('update-requests.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-edit me-2"></i>
                                Manage Requests
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('problems.index') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                View Problems
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button onclick="refreshReport()" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from Laravel
const chartData = {
    dates: {!! json_encode($charts['dates']) !!},
    updateRequests: {!! json_encode($charts['update_requests']) !!},
    problems: {!! json_encode($charts['problems']) !!},
    approvals: {!! json_encode($charts['approvals']) !!},
    rejections: {!! json_encode($charts['rejections']) !!}
};

// Initialize chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [
                {
                    label: 'Update Requests',
                    data: chartData.updateRequests,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Problems',
                    data: chartData.problems,
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Approvals',
                    data: chartData.approvals,
                    borderColor: '#11998e',
                    backgroundColor: 'rgba(17, 153, 142, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4
                },
                {
                    label: 'Rejections',
                    data: chartData.rejections,
                    borderColor: '#ee5a52',
                    backgroundColor: 'rgba(238, 90, 82, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        padding: 8
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // Animate cards on load
    const cards = document.querySelectorAll('.modern-card, .stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Refresh report function
function refreshReport() {
    const refreshBtn = document.querySelector('[onclick="refreshReport()"]');
    const icon = refreshBtn.querySelector('i');
    
    icon.classList.add('loading');
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Auto-refresh every 5 minutes
setInterval(() => {
    if (document.visibilityState === 'visible') {
        window.location.reload();
    }
}, 300000);
</script>
@endpush

@endsection