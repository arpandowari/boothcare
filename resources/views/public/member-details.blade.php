@extends('layouts.public')

@section('title', $member->name . ' - Member Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($member->profile_photo_url)
                        <img src="{{ $member->profile_photo_url }}" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                            <i class="bi bi-person text-white" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                    
                    <h3 class="mb-2">{{ $member->name }}</h3>
                    <p class="text-muted mb-3">{{ $member->relation_to_head ?? 'Family Member' }}</p>
                    
                    @if($member->is_family_head)
                        <span class="badge bg-success mb-3">Family Head</span>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('public.booth.show', $member->house->booth) }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Booth
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i>Home
                        </a>
                        <a href="{{ route('public.member.logout') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Full Name:</strong>
                            <p class="mb-0">{{ $member->name }}</p>
                        </div>
                        
                        @if($member->date_of_birth)
                            <div class="col-md-6 mb-3">
                                <strong>Date of Birth:</strong>
                                <p class="mb-0">{{ $member->date_of_birth->format('d M, Y') }} ({{ $member->age }} years)</p>
                            </div>
                        @endif
                        
                        <div class="col-md-6 mb-3">
                            <strong>Gender:</strong>
                            <p class="mb-0">{{ ucfirst($member->gender ?? 'Not specified') }}</p>
                        </div>
                        
                        @if($member->phone)
                            <div class="col-md-6 mb-3">
                                <strong>Phone:</strong>
                                <p class="mb-0">{{ $member->phone }}</p>
                            </div>
                        @endif
                        
                        @if($member->email)
                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong>
                                <p class="mb-0">{{ $member->email }}</p>
                            </div>
                        @endif
                        
                        @if($member->education)
                            <div class="col-md-6 mb-3">
                                <strong>Education:</strong>
                                <p class="mb-0">{{ $member->education }}</p>
                            </div>
                        @endif
                        
                        @if($member->occupation)
                            <div class="col-md-6 mb-3">
                                <strong>Occupation:</strong>
                                <p class="mb-0">{{ $member->occupation }}</p>
                            </div>
                        @endif
                        
                        @if($member->marital_status)
                            <div class="col-md-6 mb-3">
                                <strong>Marital Status:</strong>
                                <p class="mb-0">{{ ucfirst($member->marital_status) }}</p>
                            </div>
                        @endif
                        
                        @if($member->monthly_income)
                            <div class="col-md-6 mb-3">
                                <strong>Monthly Income:</strong>
                                <p class="mb-0">₹{{ number_format($member->monthly_income) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-house me-2"></i>Address Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Area:</strong>
                            <p class="mb-0">{{ $member->house->booth->area->area_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Booth:</strong>
                            <p class="mb-0">{{ $member->house->booth->booth_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>House Number:</strong>
                            <p class="mb-0">{{ $member->house->house_number ?? 'N/A' }}</p>
                        </div>
                        
                        @if($member->house->address)
                            <div class="col-12 mb-3">
                                <strong>Full Address:</strong>
                                <p class="mb-0">{{ $member->house->address }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($member->medical_conditions)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Medical Information</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $member->medical_conditions }}</p>
                    </div>
                </div>
            @endif

            @if($member->problems->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Reported Issues</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 text-center">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary mb-1">{{ $member->total_problems }}</h4>
                                    <small class="text-muted">Total Issues</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="border rounded p-3">
                                    <h4 class="text-warning mb-1">{{ $member->active_problems }}</h4>
                                    <small class="text-muted">Active Issues</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="border rounded p-3">
                                    <h4 class="text-success mb-1">{{ $member->resolved_problems }}</h4>
                                    <small class="text-muted">Resolved Issues</small>
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3">Recent Issues:</h6>
                        @foreach($member->problems->take(5) as $problem)
                            <div class="border-start border-3 border-{{ $problem->status === 'resolved' ? 'success' : ($problem->status === 'in_progress' ? 'warning' : 'danger') }} ps-3 mb-3">
                                <h6 class="mb-1">{{ $problem->title }}</h6>
                                <p class="text-muted mb-1">{{ Str::limit($problem->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-{{ $problem->status === 'resolved' ? 'success' : ($problem->status === 'in_progress' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($problem->status) }}
                                    </span>
                                    <small class="text-muted">{{ $problem->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($member->notes)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-sticky me-2"></i>Additional Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $member->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection