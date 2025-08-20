@extends('layouts.app')

@section('title', 'Provide Feedback')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star text-warning"></i>
                        Provide Feedback
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('problems.show', $problem) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Problem
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Problem Summary -->
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle"></i> Problem Resolved!</h5>
                        <p class="mb-0">Your problem "<strong>{{ $problem->title }}</strong>" has been successfully resolved. We would appreciate your feedback to help us improve our services.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('problems.store-feedback', $problem) }}" method="POST">
                                @csrf
                                
                                <!-- Rating -->
                                <div class="form-group">
                                    <label for="user_rating" class="form-label">
                                        <i class="fas fa-star text-warning"></i>
                                        Rate Our Service <span class="text-danger">*</span>
                                    </label>
                                    <div class="rating-container">
                                        <div class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" name="user_rating" id="star{{ $i }}" value="{{ $i }}" {{ old('user_rating') == $i ? 'checked' : '' }} required>
                                                <label for="star{{ $i }}" class="star">⭐</label>
                                            @endfor
                                        </div>
                                        <small class="form-text text-muted">Click on stars to rate (1 = Poor, 5 = Excellent)</small>
                                    </div>
                                    @error('user_rating')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Feedback Text -->
                                <div class="form-group">
                                    <label for="user_feedback" class="form-label">
                                        <i class="fas fa-comment"></i>
                                        Your Feedback (Optional)
                                    </label>
                                    <textarea name="user_feedback" id="user_feedback" class="form-control" rows="5" 
                                              placeholder="Please share your experience, suggestions, or any additional comments...">{{ old('user_feedback') }}</textarea>
                                    <small class="form-text text-muted">Maximum 1000 characters</small>
                                    @error('user_feedback')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane"></i>
                                        Submit Feedback
                                    </button>
                                    <a href="{{ route('problems.show', $problem) }}" class="btn btn-secondary ml-2">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <!-- Problem Details Card -->
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h5 class="card-title">Problem Details</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Title:</strong> {{ $problem->title }}</p>
                                    <p><strong>Category:</strong> {{ ucfirst($problem->category) }}</p>
                                    <p><strong>Priority:</strong> 
                                        <span class="badge badge-{{ $problem->priority_color }}">
                                            {{ ucfirst($problem->priority) }}
                                        </span>
                                    </p>
                                    <p><strong>Reported:</strong> {{ $problem->reported_date ? $problem->reported_date->format('d M Y') : $problem->created_at->format('d M Y') }}</p>
                                    @if($problem->actual_resolution_date)
                                        <p><strong>Resolved:</strong> {{ $problem->actual_resolution_date->format('d M Y') }}</p>
                                        <p><strong>Days to Resolve:</strong> {{ $problem->days_open }} days</p>
                                    @elseif($problem->status === 'resolved')
                                        <p><strong>Resolved:</strong> {{ $problem->updated_at->format('d M Y') }}</p>
                                        <p><strong>Days to Resolve:</strong> {{ $problem->created_at->diffInDays($problem->updated_at) }} days</p>
                                    @else
                                        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $problem->status)) }}</p>
                                        <p><strong>Days Open:</strong> {{ $problem->created_at->diffInDays(now()) }} days</p>
                                    @endif
                                    
                                    @if($problem->resolution_notes)
                                    <div class="mt-3">
                                        <strong>Resolution Notes:</strong>
                                        <p class="text-muted">{{ $problem->resolution_notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    margin-bottom: 10px;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    cursor: pointer;
    font-size: 2rem;
    color: #ddd;
    transition: color 0.2s;
    margin-right: 5px;
}

.star-rating input[type="radio"]:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}

.rating-container {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}
</style>
@endsection