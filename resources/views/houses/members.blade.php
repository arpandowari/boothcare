@extends('layouts.app')

@section('title', 'House Members - Boothcare')
@section('page-title', 'House Members')

@push('styles')
<style>
/* Modern House Members Design */
.house-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.house-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(50px, -50px);
}

.house-header-content {
    position: relative;
    z-index: 2;
}

.house-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.house-subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.house-stats {
    display: flex;
    gap: 2rem;
    margin-top: 1rem;
}

.house-stat {
    text-align: center;
}

.house-stat-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.house-stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Member Cards */
.member-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: none;
    overflow: hidden;
    height: 100%;
}

.member-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.member-card.family-head {
    border: 2px solid #ffc107;
    position: relative;
}

.member-card.family-head::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ffc107, #ffb300);
}

.member-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    position: relative;
}

.member-avatar.has-photo {
    padding: 0;
    overflow: hidden;
}

.member-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.family-head-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ffc107;
    color: #212529;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.4);
}

.member-name {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1a1a1a;
}

.member-relation {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    font-weight: 500;
}

.member-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
}

.member-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
}

.member-detail i {
    width: 16px;
    text-align: center;
    color: #495057;
}

.member-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-member {
    flex: 1;
    padding: 0.5rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-view {
    background: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.btn-view:hover {
    background: #1976d2;
    color: white;
}

.btn-edit {
    background: #fff3e0;
    color: #f57c00;
    border: 1px solid #ffcc02;
}

.btn-edit:hover {
    background: #f57c00;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: #6c757d;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .house-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .house-title {
        font-size: 1.5rem;
    }
    
    .house-stats {
        justify-content: center;
        gap: 1rem;
    }
    
    .member-details {
        grid-template-columns: 1fr;
    }
    
    .member-actions {
        flex-direction: column;
    }
}

/* Avatar Colors */
.avatar-blue { background: linear-gradient(135deg, #667eea, #764ba2); }
.avatar-green { background: linear-gradient(135deg, #11998e, #38ef7d); }
.avatar-orange { background: linear-gradient(135deg, #f093fb, #f5576c); }
.avatar-purple { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.avatar-red { background: linear-gradient(135deg, #fa709a, #fee140); }
.avatar-teal { background: linear-gradient(135deg, #43e97b, #38f9d7); }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- House Header -->
    <div class="house-header">
        <div class="house-header-content">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="house-title">
                        <i class="fas fa-home me-2"></i>
                        House {{ $house->house_number ?? 'N/A' }}
                    </h1>
                    <p class="house-subtitle mb-0">
                        {{ $house->address ?? 'Address not available' }}
                    </p>
                    <div class="house-stats">
                        <div class="house-stat">
                            <div class="house-stat-number">{{ $members->count() }}</div>
                            <div class="house-stat-label">Total Members</div>
                        </div>
                        <div class="house-stat">
                            <div class="house-stat-number">{{ $members->where('is_family_head', true)->count() }}</div>
                            <div class="house-stat-label">Family Heads</div>
                        </div>
                        <div class="house-stat">
                            <div class="house-stat-number">{{ $members->where('gender', 'male')->count() }}</div>
                            <div class="house-stat-label">Male</div>
                        </div>
                        <div class="house-stat">
                            <div class="house-stat-number">{{ $members->where('gender', 'female')->count() }}</div>
                            <div class="house-stat-label">Female</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Members Grid -->
    @if($members->count() > 0)
        <div class="row">
            @foreach($members as $index => $member)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="member-card {{ $member->is_family_head ? 'family-head' : '' }}">
                        <div class="card-body text-center p-4">
                            <!-- Avatar -->
                            <div class="member-avatar {{ $member->profile_photo ? 'has-photo' : '' }} avatar-{{ ['blue', 'green', 'orange', 'purple', 'red', 'teal'][$index % 6] }}">
                                @if($member->profile_photo)
                                    <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}">
                                @else
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                @endif
                                
                                @if($member->is_family_head)
                                    <div class="family-head-badge">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Member Info -->
                            <h5 class="member-name">{{ $member->name }}</h5>
                            <p class="member-relation">{{ $member->relation_to_head ?? $member->relationship_to_head ?? 'Member' }}</p>

                            <!-- Details Grid -->
                            <div class="member-details">
                                @if($member->age)
                                    <div class="member-detail">
                                        <i class="fas fa-birthday-cake"></i>
                                        <span>{{ $member->age }} years</span>
                                    </div>
                                @endif
                                
                                @if($member->gender)
                                    <div class="member-detail">
                                        <i class="fas fa-{{ $member->gender === 'male' ? 'mars' : 'venus' }}"></i>
                                        <span>{{ ucfirst($member->gender) }}</span>
                                    </div>
                                @endif
                                
                                @if($member->phone)
                                    <div class="member-detail">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $member->phone }}</span>
                                    </div>
                                @endif
                                
                                @if($member->occupation)
                                    <div class="member-detail">
                                        <i class="fas fa-briefcase"></i>
                                        <span>{{ Str::limit($member->occupation, 15) }}</span>
                                    </div>
                                @endif
                                
                                @if($member->education)
                                    <div class="member-detail">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>{{ Str::limit($member->education, 15) }}</span>
                                    </div>
                                @endif
                                
                                @if($member->marital_status)
                                    <div class="member-detail">
                                        <i class="fas fa-heart"></i>
                                        <span>{{ ucfirst($member->marital_status) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="member-actions">
                                <a href="{{ route('members.show', $member) }}" class="btn btn-member btn-view">
                                    <i class="fas fa-eye me-1"></i>
                                    View Details
                                </a>
                                @if(auth()->user()->isAdminOrSubAdmin() || auth()->user()->id === $member->user_id)
                                    <a href="{{ route('members.edit', $member) }}" class="btn btn-member btn-edit">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="empty-title">No Members Found</h3>
            <p class="empty-text">This house doesn't have any registered members yet.</p>
            @if(auth()->user()->isAdminOrSubAdmin())
                <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add First Member
                </a>
            @endif
        </div>
    @endif
</div>
@endsection 