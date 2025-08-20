@extends('layouts.app')

@section('title', 'Family Member Update Request - Boothcare')
@section('page-title', 'Request Family Member Update')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Request Family Member Update
                    </h5>
                    <p class="text-muted mb-0 mt-2">Submit a request to update family member information</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.requests.store-family-member-update') }}">
                        @csrf
                        
                        <!-- Family Member Selection -->
                        <div class="mb-3">
                            <label for="family_member_id" class="form-label">Select Family Member</label>
                            <select name="family_member_id" id="family_member_id" class="form-select" required>
                                <option value="">Choose a family member...</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">
                                        {{ $member->name }} - 
                                        @if($member->house)
                                            House {{ $member->house->house_number }},
                                            @if($member->house->booth)
                                                Booth {{ $member->house->booth->booth_number }},
                                                @if($member->house->booth->area)
                                                    {{ $member->house->booth->area->area_name }}
                                                @endif
                                            @endif
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Field to Update -->
                        <div class="mb-3">
                            <label for="field_to_update" class="form-label">Field to Update</label>
                            <select name="field_to_update" id="field_to_update" class="form-select" required>
                                <option value="">Select field...</option>
                                <option value="name">Name</option>
                                <option value="phone">Phone Number</option>
                                <option value="age">Age</option>
                                <option value="relationship">Relationship</option>
                                <option value="occupation">Occupation</option>
                                <option value="education">Education</option>
                                <option value="marital_status">Marital Status</option>
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
    const memberSelect = document.getElementById('family_member_id');
    const fieldSelect = document.getElementById('field_to_update');
    const currentValueInput = document.getElementById('current_value');
    
    // Auto-populate current value when member and field are selected
    function updateCurrentValue() {
        const memberId = memberSelect.value;
        const field = fieldSelect.value;
        
        if (memberId && field) {
            // You could make an AJAX call here to get the current value
            // For now, we'll just clear the field so user can enter manually
            currentValueInput.value = '';
            currentValueInput.focus();
        }
    }
    
    memberSelect.addEventListener('change', updateCurrentValue);
    fieldSelect.addEventListener('change', updateCurrentValue);
});
</script>
@endsection