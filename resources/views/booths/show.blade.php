@extends('layouts.app')

@section('title', 'Booth Details - Boothcare')
@section('page-title', 'Booth: ' . $booth->booth_number)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('booths.index') }}">Booths</a></li>
        <li class="breadcrumb-item active">{{ $booth->booth_number }}</li>
    </ol>
</nav>

<!-- Booth Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-building fa-3x text-primary me-3"></i>
                    <div>
                        <h3 class="mb-1">{{ $booth->booth_name }}</h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $booth->location }}, {{ $booth->constituency }}
                        </p>
                        <span class="badge bg-{{ $booth->is_active ? 'success' : 'secondary' }}">
                            {{ $booth->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                @if($booth->description)
                <p class="text-muted">{{ $booth->description }}</p>
                @endif
                
                <div class="d-flex gap-2">
                    <a href="{{ route('booths.edit', $booth) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Booth
                    </a>
                    <a href="{{ route('houses.create') }}?booth_id={{ $booth->id }}" class="btn btn-info">
                        <i class="fas fa-plus me-1"></i> Add House
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h5><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
                <div class="row">
                    <div class="col-6">
                        <h3 class="text-info">{{ $booth->houses->count() }}</h3>
                        <small class="text-muted">Houses</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success">{{ $booth->houses->sum(function($house) { return $house->members->where('is_family_head', true)->count(); }) }}</h3>
                        <small class="text-muted">Families</small>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <h3 class="text-warning">{{ $booth->houses->sum(function($house) { return $house->members->count(); }) }}</h3>
                        <small class="text-muted">Members</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-danger">{{ $booth->houses->sum(function($house) { return $house->members->sum(function($member) { return $member->problems ? $member->problems->count() : 0; }); }) }}</h3>
                        <small class="text-muted">Problems</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hierarchical Structure -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-sitemap me-2"></i>
            Hierarchical Structure
        </h5>
    </div>
    <div class="card-body">
        @if($booth->houses->count() > 0)
            <div class="hierarchy-container">
                @foreach($booth->houses as $house)
                <div class="house-section mb-4 p-3 border rounded">
                    <!-- House Level -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-home text-info fa-lg me-2"></i>
                            <div>
                                <h6 class="mb-0 text-info">{{ $house->house_number }}</h6>
                                <small class="text-muted">{{ $house->address }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success">{{ $house->members->where('is_family_head', true)->count() }} families</span>
                            <span class="badge bg-warning">{{ $house->members->count() }} members</span>
                            <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-plus me-1"></i> Add Member
                            </a>
                        </div>
                    </div>
                    
                    <!-- Members Level -->
                    @if($house->members->count() > 0)
                        <div class="members-container ms-4">
                            @php
                                $familyHeads = $house->members->where('is_family_head', true);
                                $otherMembers = $house->members->where('is_family_head', false);
                            @endphp
                            
                            @foreach($familyHeads as $head)
                            <div class="family-section mb-3 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-crown text-warning me-2"></i>
                                        <div>
                                            <strong class="text-success">{{ $head->name }} (Head)</strong>
                                            @if($head->phone)
                                                <br><small class="text-muted">{{ $head->phone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('members.show', $head) }}" class="btn btn-sm btn-outline-info" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($head->problems && $head->problems->count() > 0)
                                            <span class="badge bg-danger">{{ $head->problems->count() }} problems</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($otherMembers->count() > 0)
                                <div class="other-members ms-3">
                                    <h6 class="text-muted mb-2">Other Members:</h6>
                                    @foreach($otherMembers as $member)
                                    <div class="member-item d-flex justify-content-between align-items-center mb-1 p-2 bg-white rounded">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user text-primary me-2" style="font-size: 0.9em;"></i>
                                            <div>
                                                <span class="text-primary">{{ $member->name }}</span>
                                                <small class="text-muted ms-2">({{ $member->relation_to_head }})</small>
                                                @if($member->problems && $member->problems->count() > 0)
                                                    <span class="badge bg-danger ms-2">{{ $member->problems->count() }} problems</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('problems.create') }}?member_id={{ $member->id }}" class="btn btn-sm btn-outline-danger" title="Report Problem">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="ms-4 text-center py-3">
                            <small class="text-muted">No members added yet</small>
                            <br>
                            <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="btn btn-sm btn-success mt-2">
                                <i class="fas fa-plus me-1"></i> Add First Member
                            </a>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-home fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No houses in this booth yet</h5>
                <p class="text-muted">Start building the hierarchy by adding the first house.</p>
                <a href="{{ route('houses.create') }}?booth_id={{ $booth->id }}" class="btn btn-info">
                    <i class="fas fa-plus me-2"></i>
                    Add First House
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6><i class="fas fa-tools me-2"></i>Quick Actions for {{ $booth->booth_number }}</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('houses.create') }}?booth_id={{ $booth->id }}" class="btn btn-info">
                        <i class="fas fa-home me-1"></i> Add House
                    </a>
                    <a href="{{ route('members.create') }}?booth_id={{ $booth->id }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-1"></i> Add Member
                    </a>
                    <a href="{{ route('members.index') }}?booth_id={{ $booth->id }}" class="btn btn-warning">
                        <i class="fas fa-users me-1"></i> View All Members
                    </a>
                    <a href="{{ route('problems.index') }}?booth_id={{ $booth->id }}" class="btn btn-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i> View All Problems
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hierarchy-container {
    font-size: 0.95em;
}

.house-section {
    border-left: 4px solid #17a2b8 !important;
}

.family-section {
    border-left: 3px solid #28a745 !important;
}

.member-item {
    border-left: 2px solid #ffc107 !important;
}

.hierarchy-container .badge {
    font-size: 0.7em;
}
</style>
@endpush