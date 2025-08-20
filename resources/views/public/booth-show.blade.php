@extends('layouts.public')

@section('title', $booth->booth_name)

@section('content')
<!-- Booth Header -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @if($booth->images->count() > 0)
                    <div id="boothCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                            @foreach($booth->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->image_url }}" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="{{ $image->title }}">
                                    @if($image->title)
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>{{ $image->title }}</h5>
                                            @if($image->description)
                                                <p>{{ $image->description }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($booth->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#boothCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#boothCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @endif

                <h1 class="display-4 fw-bold mb-3">{{ $booth->booth_name }}</h1>
                
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <span class="badge bg-primary fs-6">
                        <i class="bi bi-geo-alt me-1"></i>{{ $booth->area->area_name ?? 'N/A' }}
                    </span>
                    <span class="badge bg-success fs-6">
                        <i class="bi bi-people me-1"></i>{{ $booth->total_members }} Members
                    </span>
                    <span class="badge bg-info fs-6">
                        <i class="bi bi-house me-1"></i>{{ $booth->total_houses }} Houses
                    </span>
                </div>

                @if($booth->description)
                    <p class="lead text-muted">{{ $booth->description }}</p>
                @endif

                <div class="rating mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $booth->average_rating >= $i ? '-fill' : '' }} fs-4"></i>
                    @endfor
                    <span class="ms-2 fs-5">{{ number_format($booth->average_rating, 1) }} ({{ $booth->total_reviews }} reviews)</span>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Booth Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Booth Number:</strong> {{ $booth->booth_number }}
                        </div>
                        @if($booth->constituency)
                            <div class="mb-3">
                                <strong>Constituency:</strong> {{ $booth->constituency }}
                            </div>
                        @endif
                        @if($booth->location)
                            <div class="mb-3">
                                <strong>Location:</strong> {{ $booth->location }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <strong>Total Houses:</strong> {{ $booth->total_houses }}
                        </div>
                        <div class="mb-3">
                            <strong>Total Members:</strong> {{ $booth->total_members }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Members Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4"><i class="bi bi-people me-2"></i>Community Members</h2>
        
        @if(session('authenticated_member'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                You are authenticated and can view member details.
            </div>
        @endif

        <div class="row">
            @forelse($members as $member)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class="card member-card h-100" data-member-id="{{ $member->id }}">
                        <div class="card-body text-center">
                            @if($member->profile_photo_url)
                                <img src="{{ $member->profile_photo_url }}" class="rounded-circle mb-3" width="60" height="60" style="object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-person text-white fs-4"></i>
                                </div>
                            @endif
                            
                            <h6 class="card-title mb-2">{{ $member->name }}</h6>
                            <small class="text-muted">{{ $member->relation_to_head ?? 'Member' }}</small>
                            
                            @if(session('authenticated_member') == $member->id)
                                <div class="mt-2">
                                    <a href="{{ route('public.member.details', $member->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No members found for this booth.</p>
                </div>
            @endforelse
        </div>

        @if(!session('authenticated_member') && $members->count() > 0)
            <div class="text-center mt-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-key me-2"></i>Login to View Member Details
                </button>
            </div>
        @endif
    </div>
</section>

<!-- Reviews Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="mb-4"><i class="bi bi-star me-2"></i>Reviews & Feedback</h2>
                
                @forelse($booth->reviews as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $review->reviewer_name }}</h6>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $review->rating >= $i ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-star display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No reviews yet</h5>
                        <p class="text-muted">Be the first to share your experience!</p>
                    </div>
                @endforelse
            </div>

            <div class="col-lg-4">
                @if(session('authenticated_member'))
                    <!-- Authenticated User Review Form -->
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Add Your Review</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $authenticatedMember = \App\Models\FamilyMember::find(session('authenticated_member'));
                            @endphp
                            
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>Logged in as:</strong> {{ $authenticatedMember->name ?? 'Member' }}
                            </div>
                            
                            <form action="{{ route('public.booth.review', $booth) }}" method="POST">
                                @csrf
                                <input type="hidden" name="reviewer_name" value="{{ $authenticatedMember->name ?? 'Authenticated Member' }}">
                                <input type="hidden" name="reviewer_phone" value="{{ $authenticatedMember->phone ?? '' }}">
                                <input type="hidden" name="family_member_id" value="{{ session('authenticated_member') }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating" value="{{ $i }}" id="auth_star{{ $i }}" required>
                                            <label for="auth_star{{ $i }}" class="star-label">
                                                <i class="bi bi-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Your Review</label>
                                    <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience with this booth..." required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-send me-2"></i>Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest User Review Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add Review</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Member Login Available:</strong> 
                                <button class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    Login as Member
                                </button>
                            </div>
                            
                            <form action="{{ route('public.booth.review', $booth) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Your Name</label>
                                    <input type="text" name="reviewer_name" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Phone (Optional)</label>
                                    <input type="text" name="reviewer_phone" class="form-control">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating" value="{{ $i }}" id="guest_star{{ $i }}" required>
                                            <label for="guest_star{{ $i }}" class="star-label">
                                                <i class="bi bi-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Comment</label>
                                    <textarea name="comment" class="form-control" rows="4" required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send me-2"></i>Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Problem Reports Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="mb-4"><i class="bi bi-flag me-2"></i>Community Reports</h2>
                
                <div class="problem-board">
                    @forelse($recentReports as $report)
                        <div class="problem-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-1">{{ $report->problem_title }}</h6>
                                <div>
                                    <span class="badge bg-{{ $report->priority_color }}">{{ ucfirst($report->priority) }}</span>
                                    <span class="badge bg-{{ $report->status_color }}">{{ ucfirst($report->status) }}</span>
                                </div>
                            </div>
                            <p class="mb-2 text-muted">{{ Str::limit($report->problem_description, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>{{ $report->reporter_name }}
                                </small>
                                <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                            </div>
                            @if($report->admin_response)
                                <div class="mt-2 p-2 bg-light rounded">
                                    <small class="text-success">
                                        <i class="bi bi-reply me-1"></i><strong>Admin Response:</strong> {{ $report->admin_response }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-flag display-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No reports yet</h5>
                            <p class="text-muted">Help improve the community by reporting issues!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-4">
                @if(session('authenticated_member'))
                    <!-- Authenticated User Report Form -->
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Report Community Issue</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $authenticatedMember = \App\Models\FamilyMember::find(session('authenticated_member'));
                            @endphp
                            
                            <div class="alert alert-warning">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>Reporting as:</strong> {{ $authenticatedMember->name ?? 'Member' }}
                            </div>
                            
                            <form action="{{ route('public.booth.report', $booth) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="reporter_name" value="{{ $authenticatedMember->name ?? 'Authenticated Member' }}">
                                <input type="hidden" name="reporter_phone" value="{{ $authenticatedMember->phone ?? '' }}">
                                <input type="hidden" name="reporter_email" value="{{ $authenticatedMember->email ?? '' }}">
                                <input type="hidden" name="family_member_id" value="{{ session('authenticated_member') }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Problem Title</label>
                                    <input type="text" name="problem_title" class="form-control" placeholder="Brief description of the issue" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <option value="infrastructure">🏗️ Infrastructure</option>
                                        <option value="sanitation">🧹 Sanitation</option>
                                        <option value="electricity">⚡ Electricity</option>
                                        <option value="water">💧 Water Supply</option>
                                        <option value="security">🔒 Security</option>
                                        <option value="other">📝 Other</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Priority Level</label>
                                    <select name="priority" class="form-select" required>
                                        <option value="">Select Priority</option>
                                        <option value="low">🟢 Low - Minor issue</option>
                                        <option value="medium">🟡 Medium - Moderate concern</option>
                                        <option value="high">🔴 High - Urgent attention needed</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Detailed Description</label>
                                    <textarea name="problem_description" class="form-control" rows="4" placeholder="Please provide detailed information about the issue..." required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Upload Photos (Optional)</label>
                                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">
                                        <i class="bi bi-camera me-1"></i>Upload photos to help illustrate the problem
                                    </small>
                                </div>
                                
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-flag me-2"></i>Submit Community Report
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest User Report Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Report Issue</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Member Login Available:</strong> 
                                <button class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    Login as Member
                                </button>
                            </div>
                            
                            <form action="{{ route('public.booth.report', $booth) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Your Name</label>
                                    <input type="text" name="reporter_name" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="reporter_phone" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email (Optional)</label>
                                    <input type="email" name="reporter_email" class="form-control">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Problem Title</label>
                                    <input type="text" name="problem_title" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <option value="infrastructure">Infrastructure</option>
                                        <option value="sanitation">Sanitation</option>
                                        <option value="electricity">Electricity</option>
                                        <option value="water">Water Supply</option>
                                        <option value="security">Security</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select" required>
                                        <option value="">Select Priority</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="problem_description" class="form-control" rows="4" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Photos (Optional)</label>
                                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">You can upload multiple photos</small>
                                </div>
                                
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-flag me-2"></i>Submit Report
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Member Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Member Authentication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('public.member.login') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-4">Enter your Aadhar number and date of birth to view member details.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Member</label>
                        <select name="member_id" class="form-select" required>
                            <option value="">Choose a member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Aadhar Number</label>
                        <input type="text" name="aadhar_number" class="form-control" maxlength="12" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-key me-2"></i>Authenticate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast notifications will be handled by the layout -->
@endsection

@push('styles')
<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star-label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    transition: color 0.2s;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input .star-label:hover,
.rating-input .star-label:hover ~ .star-label {
    color: #fbbf24;
}
</style>
@endpush