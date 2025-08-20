@extends('layouts.app')

@section('title', 'Create Notice - Admin')
@section('page-title', 'Create Notice')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create New Notice</h1>
            <p class="text-muted">Create a new community notice or announcement</p>
        </div>
        <a href="{{ route('admin.notices.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Notices
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.notices.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Notice Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('priority') is-invalid @enderror" 
                                       id="priority" name="priority" value="{{ old('priority', 10) }}" min="0" max="100" required>
                                <small class="form-text text-muted">Higher number = higher priority</small>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Notice Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Notice Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="urgent" {{ old('type') === 'urgent' ? 'selected' : '' }}>🚨 Urgent</option>
                                    <option value="important" {{ old('type') === 'important' ? 'selected' : '' }}>🔔 Important</option>
                                    <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>📢 General</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="display_location" class="form-label">Display Location <span class="text-danger">*</span></label>
                                <select class="form-select @error('display_location') is-invalid @enderror" id="display_location" name="display_location" required>
                                    <option value="">Select Location</option>
                                    <option value="marquee" {{ old('display_location') === 'marquee' ? 'selected' : '' }}>Marquee Only</option>
                                    <option value="card" {{ old('display_location') === 'card' ? 'selected' : '' }}>Notice Cards Only</option>
                                    <option value="both" {{ old('display_location') === 'both' ? 'selected' : '' }}>Both Locations</option>
                                </select>
                                @error('display_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="author" class="form-label">Author Name</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror" 
                                       id="author" name="author" value="{{ old('author', Auth::user()->name) }}">
                                <small class="form-text text-muted">Leave blank to use your name</small>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date (Optional)</label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date') }}">
                                <small class="form-text text-muted">When should this notice become active?</small>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date (Optional)</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                <small class="form-text text-muted">When should this notice expire?</small>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active Notice</strong>
                                    <br><small class="text-muted">Uncheck to save as draft</small>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Notice
                            </button>
                            <a href="{{ route('admin.notices.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Notice Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Notice Types:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge bg-danger me-2">🚨 Urgent</span>
                            Critical announcements requiring immediate attention
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-warning me-2">🔔 Important</span>
                            Significant updates and policy changes
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-primary me-2">📢 General</span>
                            Regular community announcements
                        </li>
                    </ul>
                    
                    <h6 class="fw-bold mt-4">Display Locations:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Marquee:</strong> Scrolling text at top of page
                        </li>
                        <li class="mb-2">
                            <strong>Cards:</strong> Notice cards in dedicated section
                        </li>
                        <li class="mb-2">
                            <strong>Both:</strong> Display in both locations
                        </li>
                    </ul>
                    
                    <h6 class="fw-bold mt-4">Priority System:</h6>
                    <p class="small text-muted">
                        Higher priority notices appear first. Use 0-100 scale where:
                        <br>• 80-100: Critical/Urgent
                        <br>• 50-79: Important
                        <br>• 0-49: General
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection