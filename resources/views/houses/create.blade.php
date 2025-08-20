@extends('layouts.app')

@section('title', 'Add New House - Boothcare')
@section('page-title', 'Add New House')

@push('styles')
<style>
    /* Simple Clean Design */
    .page-header {
        background: #667eea;
        color: white;
        padding: 20px;
        margin: -20px -20px 30px -20px;
        border-radius: 0 0 15px 15px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .page-header .subtitle {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .back-btn {
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }

    .back-btn:hover {
        color: rgba(255,255,255,0.8);
    }

    .form-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        font-size: 0.9rem;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        outline: none;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5a67d8;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .form-actions {
        text-align: right;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
        margin-top: 30px;
    }

    .form-actions .btn {
        margin-left: 10px;
    }

    .text-danger {
        color: #dc3545;
    }

    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .form-help {
        font-size: 0.8rem;
        color: #666;
        margin-top: 5px;
    }

    .location-section {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 15px;
        margin-top: 10px;
    }

    .btn-get-location {
        background: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        font-size: 0.85rem;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .btn-get-location:hover {
        background: #218838;
    }

    .location-status {
        font-size: 0.85rem;
        margin-top: 10px;
    }

    .location-status.success {
        color: #28a745;
    }

    .location-status.error {
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Simple Header -->
    <div class="page-header">
        <a href="{{ route('houses.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Houses
        </a>
        <h1>Add New House</h1>
        <div class="subtitle">Add a new house to the system</div>
    </div>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <h4><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h4>
            <ul style="margin: 10px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Simple Form -->
    <form method="POST" action="{{ route('houses.store', $booth ?? null) }}">
        @csrf
        
        <div class="form-card">
            <!-- Location Information -->
            <div class="form-section">
                <h3 class="section-title">Location Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Booth <span class="text-danger">*</span></label>
                        <select class="form-select" name="booth_id" id="booth_id" required>
                            <option value="">Choose a booth...</option>
                            @foreach($booths as $boothOption)
                                <option value="{{ $boothOption->id }}" 
                                        {{ (old('booth_id', $booth?->id) == $boothOption->id) ? 'selected' : '' }}>
                                    {{ $boothOption->booth_number }} - {{ $boothOption->booth_name }}
                                    ({{ $boothOption->location }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-help">Houses are organized under booths in the hierarchy</div>
                    </div>
                </div>
            </div>

            <!-- House Details -->
            <div class="form-section">
                <h3 class="section-title">House Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">House Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="house_number" value="{{ old('house_number') }}" 
                               placeholder="e.g., H-001, 123, A-45" required>
                        <div class="form-help">Unique within the selected booth</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Area/Locality</label>
                        <input type="text" class="form-control" name="area" value="{{ old('area') }}" 
                               placeholder="e.g., Central Area, Market Area">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="address" rows="3" required 
                                  placeholder="Enter complete address of the house">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">PIN Code</label>
                        <input type="text" class="form-control" name="pincode" value="{{ old('pincode') }}" 
                               placeholder="e.g., 2100" maxlength="10">
                        <div class="form-help">Optional postal code</div>
                    </div>
                </div>
            </div>

            <!-- Location Coordinates -->
            <div class="form-section">
                <h3 class="section-title">Location Coordinates (Optional)</h3>
                <div class="location-section">
                    <button type="button" class="btn-get-location" id="getCurrentLocation">
                        <i class="fas fa-map-marker-alt"></i> Get Current Location
                    </button>
                    <div class="location-status" id="locationStatus" style="display: none;"></div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" name="latitude" 
                                   value="{{ old('latitude') }}" placeholder="e.g., 23.8103">
                            <div class="form-help">Optional GPS coordinate</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" name="longitude" 
                                   value="{{ old('longitude') }}" placeholder="e.g., 90.4125">
                            <div class="form-help">Optional GPS coordinate</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="form-section">
                <h3 class="section-title">Status</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            Active House
                        </label>
                        <div class="form-help">Inactive houses won't appear in family creation</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('houses.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add House</button>
            </div>
        </div>
    </form>
</div>

<script>
// Simple geolocation functionality
document.getElementById('getCurrentLocation').addEventListener('click', function() {
    const button = this;
    const status = document.getElementById('locationStatus');
    const latInput = document.querySelector('input[name="latitude"]');
    const lngInput = document.querySelector('input[name="longitude"]');
    
    if (!navigator.geolocation) {
        showLocationStatus('Geolocation is not supported by this browser.', 'error');
        return;
    }
    
    button.disabled = true;
    showLocationStatus('Getting location...', 'info');
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude.toFixed(6);
            const lng = position.coords.longitude.toFixed(6);
            
            latInput.value = lat;
            lngInput.value = lng;
            
            showLocationStatus('Location found successfully!', 'success');
            button.disabled = false;
        },
        function(error) {
            let message = 'Unable to get location: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message += 'Location access denied.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message += 'Location information unavailable.';
                    break;
                case error.TIMEOUT:
                    message += 'Location request timed out.';
                    break;
                default:
                    message += 'An unknown error occurred.';
                    break;
            }
            showLocationStatus(message, 'error');
            button.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        }
    );
});

function showLocationStatus(message, type) {
    const status = document.getElementById('locationStatus');
    status.style.display = 'block';
    status.className = `location-status ${type}`;
    status.innerHTML = message;
    
    if (type === 'success') {
        setTimeout(() => {
            status.style.display = 'none';
        }, 3000);
    }
}
</script>
@endsection