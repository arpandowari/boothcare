@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Public Reports</h1>
                <div>
                    <span class="badge bg-warning me-2">{{ $reports->where('status', 'pending')->count() }} Pending</span>
                    <span class="badge bg-info me-2">{{ $reports->where('status', 'verified')->count() }} Verified</span>
                    <span class="badge bg-primary me-2">{{ $reports->where('status', 'in_progress')->count() }} In Progress</span>
                    <span class="badge bg-success">{{ $reports->where('status', 'resolved')->count() }} Resolved</span>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booth</th>
                                    <th>Reporter</th>
                                    <th>Problem</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr class="{{ $report->status === 'pending' ? 'table-warning' : '' }}">
                                        <td>
                                            <strong>{{ $report->booth->booth_name }}</strong><br>
                                            <small class="text-muted">{{ $report->booth->area->area_name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $report->reporter_name }}</strong><br>
                                            <small class="text-muted">{{ $report->reporter_phone }}</small>
                                            @if($report->reporter_email)
                                                <br><small class="text-muted">{{ $report->reporter_email }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $report->problem_title }}</strong>
                                            <div style="max-width: 300px;">
                                                <small class="text-muted">{{ Str::limit($report->problem_description, 100) }}</small>
                                            </div>
                                            @if($report->photos && count($report->photos) > 0)
                                                <br><small class="text-info">
                                                    <i class="bi bi-camera me-1"></i>{{ count($report->photos) }} photo(s)
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($report->category) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $report->priority_color }}">{{ ucfirst($report->priority) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $report->status_color }}">{{ ucfirst($report->status) }}</span>
                                            @if($report->is_verified && $report->verifiedBy)
                                                <br><small class="text-muted">by {{ $report->verifiedBy->name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $report->created_at->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($report->status === 'pending')
                                                    <form action="{{ route('admin.reports.verify', $report) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verify this report?')">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="bi bi-flag display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">No reports found</h5>
                                            <p class="text-muted">Public reports will appear here when users submit them.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($reports->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast notifications will be handled by the layout -->
@endsection