@extends('layouts.app')

@section('title', 'My Documents - Boothcare')
@section('page-title', 'Document Management')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profile</a></li>
        <li class="breadcrumb-item active">Documents</li>
    </ol>
</nav>





<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Upload Documents
                </h5>
            </div>
            <div class="card-body">
                @if(!$familyMember)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Please contact administrator to complete your profile setup first before uploading documents.
                    </div>
                @else
                    <form action="{{ route('profile.upload-documents') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Aadhar Card -->
                            <div class="col-md-6 mb-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-id-card me-2"></i>
                                            Aadhar Card
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($familyMember->aadhar_card)
                                            <div class="current-document mb-3">
                                                <p class="text-success mb-2">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Document uploaded
                                                </p>
                                                <a href="{{ Storage::url($familyMember->aadhar_card) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <input type="file" class="form-control @error('aadhar_card') is-invalid @enderror" 
                                                   id="aadhar_card" name="aadhar_card" accept="image/*,.pdf">
                                            @error('aadhar_card')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Voter ID -->
                            <div class="col-md-6 mb-4">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-vote-yea me-2"></i>
                                            Voter ID
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($familyMember->voter_id)
                                            <div class="current-document mb-3">
                                                <p class="text-success mb-2">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Document uploaded
                                                </p>
                                                <a href="{{ Storage::url($familyMember->voter_id) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <input type="file" class="form-control @error('voter_id') is-invalid @enderror" 
                                                   id="voter_id" name="voter_id" accept="image/*,.pdf">
                                            @error('voter_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PAN Card -->
                            <div class="col-md-6 mb-4">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">
                                            <i class="fas fa-credit-card me-2"></i>
                                            PAN Card
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($familyMember->pan_card)
                                            <div class="current-document mb-3">
                                                <p class="text-success mb-2">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Document uploaded
                                                </p>
                                                <a href="{{ Storage::url($familyMember->pan_card) }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <input type="file" class="form-control @error('pan_card') is-invalid @enderror" 
                                                   id="pan_card" name="pan_card" accept="image/*,.pdf">
                                            @error('pan_card')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ration Card -->
                            <div class="col-md-6 mb-4">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-utensils me-2"></i>
                                            Ration Card
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($familyMember->ration_card)
                                            <div class="current-document mb-3">
                                                <p class="text-success mb-2">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Document uploaded
                                                </p>
                                                <a href="{{ Storage::url($familyMember->ration_card) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <input type="file" class="form-control @error('ration_card') is-invalid @enderror" 
                                                   id="ration_card" name="ration_card" accept="image/*,.pdf">
                                            @error('ration_card')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Profile
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>
                                Upload Documents
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Document Guidelines
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">Accepted Formats:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>JPEG, PNG, JPG images</li>
                        <li><i class="fas fa-check text-success me-2"></i>PDF documents</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="text-primary">File Size:</h6>
                    <p class="text-muted mb-0">Maximum 2MB per file</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-primary">Important Notes:</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Ensure documents are clear and readable</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Upload original documents only</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Documents will be verified by admin</li>
                    </ul>
                </div>

                @if($familyMember)
                <div class="mt-4">
                    <h6 class="text-primary">Upload Status:</h6>
                    <div class="progress mb-2" style="height: 20px;">
                        @php
                            $uploadedCount = 0;
                            if($familyMember->aadhar_card) $uploadedCount++;
                            if($familyMember->voter_id) $uploadedCount++;
                            if($familyMember->pan_card) $uploadedCount++;
                            if($familyMember->ration_card) $uploadedCount++;
                            $percentage = ($uploadedCount / 4) * 100;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
                            {{ $uploadedCount }}/4 Documents
                        </div>
                    </div>
                    <small class="text-muted">{{ $uploadedCount }} out of 4 documents uploaded</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection