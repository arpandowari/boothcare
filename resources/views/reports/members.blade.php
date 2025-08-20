@extends('layouts.app')

@section('title', 'Members Report - Boothcare')
@section('page-title', 'Members Report')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">Members Report</h1>
                <p class="dashboard-subtitle">Comprehensive overview of all registered members</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="export-btn me-2" onclick="exportMembersData()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <a href="{{ route('reports.index') }}" class="export-btn">
                    <i class="fas fa-arrow-left me-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Members Table -->
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-title">
                    <span>All Members ({{ $members->total() }} total)</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>House</th>
                                <th>Location</th>
                                <th>Family Head</th>
                                <th>Problems</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">#{{ $member->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $member->name }}</div>
                                    <small class="text-muted">{{ $member->relation_to_head ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($member->phone)
                                        <div class="fw-bold">{{ $member->phone }}</div>
                                    @endif
                                    @if($member->email)
                                        <small class="text-muted">{{ $member->email }}</small>
                                    @endif
                                    @if(!$member->phone && !$member->email)
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->house)
                                        <div class="fw-bold">{{ $member->house->house_number }}</div>
                                        <small class="text-muted">{{ $member->house->booth->booth_name ?? 'N/A' }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->house && $member->house->booth && $member->house->booth->area)
                                        <div class="fw-bold">{{ $member->house->booth->area->area_name }}</div>
                                        <small class="text-muted">{{ $member->house->booth->area->district ?? 'N/A' }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->is_family_head)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->problems_count > 0)
                                        <span class="badge bg-warning">{{ $member->problems_count }}</span>
                                    @else
                                        <span class="badge bg-success">0</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $member->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $member->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('members.show', $member) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('members.edit'))
                                        <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <p>No members found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($members->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $members->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function exportMembersData() {
    const data = {
        members: @json($members->items()),
        exportDate: new Date().toISOString(),
        totalCount: {{ $members->total() }}
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'members-report-' + new Date().toISOString().split('T')[0] + '.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
@endsection