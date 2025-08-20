@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Manage Reviews</h1>
                <div>
                    <span class="badge bg-warning me-2">{{ $reviews->where('is_approved', false)->count() }} Pending</span>
                    <span class="badge bg-success">{{ $reviews->where('is_approved', true)->count() }} Approved</span>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booth</th>
                                    <th>Reviewer</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr class="{{ !$review->is_approved ? 'table-warning' : '' }}">
                                        <td>
                                            <strong>{{ $review->booth->booth_name }}</strong><br>
                                            <small class="text-muted">{{ $review->booth->area->area_name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $review->reviewer_name }}</strong>
                                            @if($review->reviewer_phone)
                                                <br><small class="text-muted">{{ $review->reviewer_phone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $review->rating >= $i ? '-fill text-warning' : ' text-muted' }}"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted">{{ $review->rating }}/5</small>
                                        </td>
                                        <td>
                                            <div style="max-width: 300px;">
                                                {{ Str::limit($review->comment, 100) }}
                                                @if(strlen($review->comment) > 100)
                                                    <button class="btn btn-link btn-sm p-0" data-bs-toggle="tooltip" title="{{ $review->comment }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($review->is_approved)
                                                <span class="badge bg-success">Approved</span>
                                                @if($review->approvedBy)
                                                    <br><small class="text-muted">by {{ $review->approvedBy->name }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $review->created_at->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if(!$review->is_approved)
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this review?')">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject and delete this review?')">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-star display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">No reviews found</h5>
                                            <p class="text-muted">Reviews will appear here when users submit them.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($reviews->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast notifications will be handled by the layout -->
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush