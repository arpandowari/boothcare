@extends('layouts.app')

@section('title', 'Problems Report - Boothcare')
@section('page-title', 'Problems Report')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">Problems Report</h1>
                <p class="dashboard-subtitle">Comprehensive overview of all reported problems</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="export-btn me-2" onclick="exportProblemsData()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <a href="{{ route('reports.index') }}" class="export-btn">
                    <i class="fas fa-arrow-left me-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Problems Table -->
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-title">
                    <span>All Problems ({{ $problems->total() }} total)</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Member</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($problems as $problem)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">#{{ $problem->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ Str::limit($problem->title, 30) }}</div>
                                    <small class="text-muted">{{ Str::limit($problem->description, 50) }}</small>
                                </td>
                                <td>
                                    @if($problem->familyMember)
                                        <div class="fw-bold">{{ $problem->familyMember->name }}</div>
                                        <small class="text-muted">{{ $problem->familyMember->phone ?? 'N/A' }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($problem->familyMember && $problem->familyMember->house)
                                        <div class="fw-bold">{{ $problem->familyMember->house->house_number }}</div>
                                        <small class="text-muted">
                                            {{ $problem->familyMember->house->booth->area->area_name ?? 'N/A' }}
                                        </small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $problem->status == 'resolved' ? 'success' : ($problem->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $problem->priority == 'urgent' ? 'danger' : ($problem->priority == 'high' ? 'warning' : 'info') }}">
                                        {{ ucfirst($problem->priority ?? 'Normal') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $problem->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $problem->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('problems.show', $problem) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.edit'))
                                        <a href="{{ route('problems.edit', $problem) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No problems found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($problems->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $problems->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function exportProblemsData() {
    const data = {
        problems: @json($problems->items()),
        exportDate: new Date().toISOString(),
        totalCount: {{ $problems->total() }}
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'problems-report-' + new Date().toISOString().split('T')[0] + '.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
@endsection