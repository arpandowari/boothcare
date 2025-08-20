@extends('layouts.app')

@section('title', 'Location Update Request - Boothcare')
@section('page-title', 'Request Location Update')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Request Location Update
                    </h5>
                    <p class="text-muted mb-0 mt-2">Submit a request to update area, booth, or house information</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.requests.store-location-update') }}">
                        @csrf
                        
                        <!-- Target Type -->
                        <div class="mb-3">
                            <label for="target_type" class="form-label">Location Type</label>
                            <select name="target_type" id="target_type" class="form-select" required>
                                <option value="">Select location type...</option>
                                <option value="area">Area</option>
                                <option value="booth">Booth</option>
                                <option value="house">House</option>
                            </select>
                        </div>
                        
                        <!-- Area Selection -->
                        <div class="mb-3" id="area_selection" style="display: none;">
                            <label for="area_id" class="form-label">Select Area</label>
                            <select name="area_id" id="area_id" class="form-select">
                                <option value="">Choose an area...</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Booth Selection -->
                        <div class="mb-3" id="booth_selection" style="display: none;">
                            <label for="booth_id" class="form-label">Select Booth</label>
                            <select name="booth_id" id="booth_id" class="form-select">
                                <option value="">Choose a booth...</option>
                                @foreach($booths as $booth)
                                    <option value="{{ $booth->id }}">
                                        Booth {{ $booth->booth_number }} - {{ $booth->area->area_name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- House Selection -->
                        <div class="mb-3" id="house_selection" style="display: none;">
                            <label for="house_id" class="form-label">Select House</label>
                            <select name="house_id" id="house_id" class="form-select">
                                <option value="">Choose a house...</option>
                                @foreach($houses as $house)
                                    <option value="{{ $house->id }}">
                                        House {{ $house->house_number }} - 
                                        @if($house->booth)
                                            Booth {{ $house->booth->booth_number }}, {{ $house->booth->area->area_name ?? 'N/A' }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Hidden field for target_id -->
                        <input type="hidden" name="target_id" id="target_id">
                        
                        <!-- Field to Update -->
                        <div class="mb-3">
                            <label for="field_to_update" class="form-label">Field to Update</label>
                            <select name="field_to_update" id="field_to_update" class="form-select" required>
                                <option value="">Select field...</option>
                                <!-- Area fields -->
                                <option value="area_name" class="area-field" style="display: none;">Area Name</option>
                                <option value="description" class="area-field" style="display: none;">Description</option>
                                <!-- Booth fields -->
                                <option value="booth_number" class="booth-field" style="display: none;">Booth Number</option>
                                <option value="booth_name" class="booth-field" style="display: none;">Booth Name</option>
                                <!-- House fields -->
                                <option value="house_number" class="house-field" style="display: none;">House Number</option>
                                <option value="owner_name" class="house-field" style="display: none;">Owner Name</option>
                                <option value="house_type" class="house-field" style="display: none;">House Type</option>
                            </select>
                        </div>
                        
                        <!-- Current Value -->
                        <div class="mb-3">
                            <label for="current_value" class="form-label">Current Value</label>
                            <input type="text" name="current_value" id="current_value" class="form-control" required 
                                   placeholder="Enter the current value">
                        </div>
                        
                        <!-- Requested Value -->
                        <div class="mb-3">
                            <label for="requested_value" class="form-label">Requested New Value</label>
                            <input type="text" name="requested_value" id="requested_value" class="form-control" required 
                                   placeholder="Enter the new value you want">
                        </div>
                        
                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Update</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" required 
                                      placeholder="Explain why this update is needed..."></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetTypeSelect = document.getElementById('target_type');
    const areaSelection = document.getElementById('area_selection');
    const boothSelection = document.getElementById('booth_selection');
    const houseSelection = document.getElementById('house_selection');
    const targetIdInput = document.getElementById('target_id');
    const fieldSelect = document.getElementById('field_to_update');
    
    // Show/hide selections based on target type
    targetTypeSelect.addEventListener('change', function() {
        const targetType = this.value;
        
        // Hide all selections
        areaSelection.style.display = 'none';
        boothSelection.style.display = 'none';
        houseSelection.style.display = 'none';
        
        // Hide all field options
        const fieldOptions = fieldSelect.querySelectorAll('option');
        fieldOptions.forEach(option => {
            if (option.value === '') return; // Keep the default option
            option.style.display = 'none';
        });
        
        // Show relevant selection and fields
        if (targetType === 'area') {
            areaSelection.style.display = 'block';
            fieldSelect.querySelectorAll('.area-field').forEach(option => {
                option.style.display = 'block';
            });
        } else if (targetType === 'booth') {
            boothSelection.style.display = 'block';
            fieldSelect.querySelectorAll('.booth-field').forEach(option => {
                option.style.display = 'block';
            });
        } else if (targetType === 'house') {
            houseSelection.style.display = 'block';
            fieldSelect.querySelectorAll('.house-field').forEach(option => {
                option.style.display = 'block';
            });
        }
        
        // Reset field selection
        fieldSelect.value = '';
        targetIdInput.value = '';
    });
    
    // Update target_id when selection changes
    document.getElementById('area_id').addEventListener('change', function() {
        targetIdInput.value = this.value;
    });
    
    document.getElementById('booth_id').addEventListener('change', function() {
        targetIdInput.value = this.value;
    });
    
    document.getElementById('house_id').addEventListener('change', function() {
        targetIdInput.value = this.value;
    });
});
</script>
@endsection