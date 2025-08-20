@extends('layouts.app')

@section('title', 'Edit Booth - Boothcare')
@section('page-title', 'Edit Booth: ' . $booth->booth_name)

@push('styles')
<style>
    /* Professional Edit Booth Styles */
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .edit-header {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        color: white;
        padding: 30px 20px;
        margin: -16px -16px 24px -16px;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 4px 20px rgba(111, 66, 193, 0.3);
        text-align: center;
    }

    .edit-header h1 {
        margin: 0 0 8px 0;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .edit-header .subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
        margin: 0;
    }

    .breadcrumb-nav {
        background: white;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-item a {
        color: #6f42c1;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #5a32a3;
        transform: translateX(2px);
    }

    .breadcrumb-item.active {
        color: #6b7280;
        font-weight: 600;
    }

    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .form-card-header {
        background: #f8f9fa;
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .form-card-header i {
        color: #6f42c1;
        font-size: 1.3rem;
    }

    .form-card-body {
        padding: 24px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.95rem;
        display: block;
    }

    .form-label.required::after {
        content: ' *';
        color: #dc3545;
    }

    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
        outline: none;
        background: white;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 6px;
        font-weight: 500;
    }

    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 6px;
        line-height: 1.4;
    }

    /* Custom Checkbox */
    .form-check {
        background: #f8f9fa;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        transition: all 0.3s ease;
        margin-bottom: 16px;
    }

    .form-check:hover {
        border-color: #6f42c1;
        background: #f0f4ff;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        margin-top: 0;
        margin-right: 12px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
    }

    .form-check-input:checked {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }

    .form-check-label {
        font-weight: 600;
        color: #374151;
        font-size: 1rem;
        cursor: pointer;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #f1f5f9;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        color: white;
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
        color: white;
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #e9ecef;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        color: #495057;
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .edit-header {
            padding: 24px 16px;
            margin: -16px -16px 20px -16px;
        }

        .edit-header h1 {
            font-size: 1.5rem;
        }

        .form-card-body {
            padding: 20px;
        }

        .action-buttons {
            flex-direction: column-reverse;
        }

        .btn {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('booths.index') }}">Booths</a></li>
            <li class="breadcrumb-item"><a href="{{ route('booths.show', $booth) }}">{{ $booth->booth_name }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <!-- Edit Header -->
    <div class="edit-header">
        <h1><i class="fas fa-edit me-2"></i>Edit Booth</h1>
        <p class="subtitle">Update booth information and settings</p>
    </div>

    <div class="form-container">
        <!-- Booth Information Form -->
        <div class="form-card">
            <div class="form-card-header">
                <i class="fas fa-building"></i>
                <h3>Booth Information</h3>
            </div>
            <div class="form-card-body">
                <form action="{{ route('booths.update', $booth) }}" method="POST" id="editBoothForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Area Selection -->
                    <div class="form-group">
                        <label for="area_id" class="form-label required">Select Area</label>
                        <select class="form-select @error('area_id') is-invalid @enderror" 
                                id="area_id" 
                                name="area_id" 
                                required>
                            <option value="">Choose an area...</option>
                            @foreach(\App\Models\Area::where('is_active', true)->get() as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $booth->area_id) == $area->id ? 'selected' : '' }}>
                                    {{ $area->area_name }} - {{ $area->district }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Select the area where this booth is located</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="booth_number" class="form-label required">Booth Number</label>
                                <input type="text" 
                                       class="form-control @error('booth_number') is-invalid @enderror" 
                                       id="booth_number" 
                                       name="booth_number" 
                                       value="{{ old('booth_number', $booth->booth_number) }}" 
                                       placeholder="e.g., 001, 002"
                                       required>
                                @error('booth_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Unique booth number for identification</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="booth_name" class="form-label required">Booth Name</label>
                                <input type="text" 
                                       class="form-control @error('booth_name') is-invalid @enderror" 
                                       id="booth_name" 
                                       name="booth_name" 
                                       value="{{ old('booth_name', $booth->booth_name) }}" 
                                       placeholder="e.g., Central Booth 1"
                                       required>
                                @error('booth_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Official name of the booth</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location', $booth->location) }}" 
                                       placeholder="e.g., Main Street, Central Area">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Physical location of the booth</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="constituency" class="form-label">Constituency</label>
                                <input type="text" 
                                       class="form-control @error('constituency') is-invalid @enderror" 
                                       id="constituency" 
                                       name="constituency" 
                                       value="{{ old('constituency', $booth->constituency) }}" 
                                       placeholder="e.g., Central-1, North-2">
                                @error('constituency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Electoral constituency code</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Optional description about this booth">{{ old('description', $booth->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional description about this booth</div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $booth->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Booth
                            </label>
                            <div class="form-text">Check this to make the booth active and available for use</div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('booths.show', $booth) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Booth
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('editBoothForm');
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    function validateField(field) {
        const value = field.value.trim();
        const isValid = value.length > 0;
        
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        let isFormValid = true;
        
        inputs.forEach(input => {
            validateField(input);
            if (input.classList.contains('is-invalid')) {
                isFormValid = false;
            }
        });
        
        if (!isFormValid) {
            e.preventDefault();
            
            // Show error message
            const firstInvalidField = form.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
@endsection