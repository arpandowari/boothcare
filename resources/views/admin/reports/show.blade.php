@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Report Details</h5>
                        <div>
                            <span class="badge bg-{{ $report->priority_color }} me-2">{{ ucfirst($report->priority) }} Priority</span>
                            <span class="badge bg-{{ $report->status_color }}">{{ ucfirst($report->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Problem Title</h6>
                            <p class="fw-bold">{{ $report->problem_title }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Category</h6>
                            <span class="badge bg-secondary">{{ ucfirst($report->category) }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Description</h6>
                        <p>{{ $report->problem_description }}</p>
                    </div>

                    @if($report->photos && count($report->photos) > 0)
                        <div class="mb-4">
                            <h6>Photos</h6>
                            <div class="row">
                                @foreach($report->photos as $photo)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ Storage::url($photo) }}" class="img-fluid rounded" alt="Report Photo" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo="{{ Storage::url($photo) }}" style="cursor: pointer;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Booth Information</h6>
                            <p class="mb-1"><strong>{{ $report->booth->booth_name }}</strong></p>
                            <p class="text-muted">{{ $report->booth->area->area_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Reporter Information</h6>
                            <p class="mb-1"><strong>{{ $report->reporter_name }}</strong></p>
                            <p class="mb-1">{{ $report->reporter_phone }}</p>
                            @if($report->reporter_email)
                                <p class="text-muted">{{ $report->reporter_email }}</p>
                            @endif
                        </div>
                    </div>

                    @if($report->admin_response)
                        <div class="mt-4">
                            <h6>Admin Response</h6>
                            <div class="alert alert-info">
                                {{ $report->admin_response }}
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <small class="text-muted">
                            Reported on {{ $report->created_at->format('M d, Y \a\t g:i A') }}
                            @if($report->is_verified && $report->verifiedBy)
                                <br>Verified by {{ $report->verifiedBy->name }} on {{ $report->verified_at->format('M d, Y \a\t g:i A') }}
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.update-status', $report) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ $report->status === 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Admin Response</label>
                            <textarea name="admin_response" class="form-control" rows="4" placeholder="Add your response or update...">{{ $report->admin_response }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Reports
                        </a>
                        
                        @if($report->status === 'pending')
                            <form action="{{ route('admin.reports.verify', $report) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Verify this report?')">
                                    <i class="bi bi-check-circle me-2"></i>Quick Verify
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" class="img-fluid" alt="Report Photo">
            </div>
        </div>
    </div>
</div>

<!-- Toast notifications will be handled by the layout -->
@endsection

@push('scripts')
<script>
    // Handle photo modal
    document.addEventListener('DOMContentLoaded', function() {
        const photoModal = document.getElementById('photoModal');
        const modalPhoto = document.getElementById('modalPhoto');
        
        photoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const photoUrl = button.getAttribute('data-photo');
            modalPhoto.src = photoUrl;
        });
    });
</script>
@endpush