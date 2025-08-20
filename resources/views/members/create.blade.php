@extends('layouts.app')

@section('title', 'Add New Member - Boothcare')
@section('page-title', 'Add New Family Member')

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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Simple Header -->
    <div class="page-header">
        <a href="{{ route('members.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Members
        </a>
        <h1>Add New Member</h1>
        <div class="subtitle">Add a new family member to the system</div>
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
    <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-card">
            <!-- Location Information -->
            <div class="form-section">
                <h3 class="section-title">Location Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Area <span class="text-danger">*</span></label>
                        <select class="form-select" name="area_id" id="area_id" required>
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->area_name }} ({{ $area->district }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Booth <span class="text-danger">*</span></label>
                        <select class="form-select" name="booth_id" id="booth_id" required>
                            <option value="">Select Booth</option>
                            @foreach($booths as $booth)
                                <option value="{{ $booth->id }}" {{ old('booth_id') == $booth->id ? 'selected' : '' }}>
                                    Booth {{ $booth->booth_number }} - {{ $booth->booth_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">House <span class="text-danger">*</span></label>
                        <select class="form-select" name="house_id" id="house_id" required>
                            <option value="">Select House</option>
                            @foreach($houses as $house)
                                <option value="{{ $house->id }}" {{ old('house_id') == $house->id ? 'selected' : '' }}>
                                    {{ $house->house_number }} - {{ Str::limit($house->address, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="section-title">Personal Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required placeholder="Enter full name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Relation to Head <span class="text-danger">*</span></label>
                        <select class="form-select" name="relation_to_head" required>
                            <option value="">Select Relation</option>
                            <option value="head" {{ old('relation_to_head') == 'head' ? 'selected' : '' }}>Head of Family</option>
                            <option value="spouse" {{ old('relation_to_head') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                            <option value="son" {{ old('relation_to_head') == 'son' ? 'selected' : '' }}>Son</option>
                            <option value="daughter" {{ old('relation_to_head') == 'daughter' ? 'selected' : '' }}>Daughter</option>
                            <option value="father" {{ old('relation_to_head') == 'father' ? 'selected' : '' }}>Father</option>
                            <option value="mother" {{ old('relation_to_head') == 'mother' ? 'selected' : '' }}>Mother</option>
                            <option value="brother" {{ old('relation_to_head') == 'brother' ? 'selected' : '' }}>Brother</option>
                            <option value="sister" {{ old('relation_to_head') == 'sister' ? 'selected' : '' }}>Sister</option>
                            <option value="other" {{ old('relation_to_head') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        <div class="form-help">Age will be calculated automatically</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" class="form-control" name="age" value="{{ old('age') }}" min="0" max="120" placeholder="Enter age">
                        <div class="form-help">Leave blank if date of birth is provided</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="+91 XXXXX XXXXX">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="example@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Occupation</label>
                        <input type="text" class="form-control" name="occupation" value="{{ old('occupation') }}" placeholder="e.g., Teacher, Farmer, Student">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Education Level</label>
                        <input type="text" class="form-control" name="education" value="{{ old('education') }}" placeholder="e.g., Graduate, HSC, Primary">
                    </div>
                </div>
            </div>

            <!-- Documents & Additional Info -->
            <div class="form-section">
                <h3 class="section-title">Documents & Additional Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Aadhaar Number</label>
                        <input type="text" class="form-control" name="aadhar_number" value="{{ old('aadhar_number') }}" placeholder="XXXX-XXXX-XXXX">
                        <div class="form-help">12-digit Aadhaar number</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">PAN Number</label>
                        <input type="text" class="form-control" name="pan_number" value="{{ old('pan_number') }}" placeholder="ABCDE1234F" style="text-transform: uppercase;">
                        <div class="form-help">10-character PAN number</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Voter ID</label>
                        <input type="text" class="form-control" name="voter_id" value="{{ old('voter_id') }}" placeholder="Enter Voter ID">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ration Card Number</label>
                        <input type="text" class="form-control" name="ration_card_number" value="{{ old('ration_card_number') }}" placeholder="Enter ration card number">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Marital Status</label>
                        <select class="form-select" name="marital_status">
                            <option value="">Select Status</option>
                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Monthly Income (₹)</label>
                        <input type="number" class="form-control" name="monthly_income" value="{{ old('monthly_income') }}" min="0" step="0.01" placeholder="25000">
                        <div class="form-help">Optional - for statistical purposes</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" name="profile_photo" accept="image/jpeg,image/png,image/jpg">
                        <div class="form-help">Upload profile photo (JPG, PNG - Max: 2MB)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any additional information about this member">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Member</button>
            </div>
        </div>
    </form>
</div>

<script>
// Simple form enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate age from date of birth
    const dobInput = document.querySelector('input[name="date_of_birth"]');
    const ageInput = document.querySelector('input[name="age"]');
    
    if (dobInput && ageInput) {
        dobInput.addEventListener('change', function() {
            if (this.value) {
                const today = new Date();
                const birthDate = new Date(this.value);
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                ageInput.value = age >= 0 ? age : '';
            }
        });
    }

    // Format PAN number to uppercase
    const panInput = document.querySelector('input[name="pan_number"]');
    if (panInput) {
        panInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Format Aadhaar number with dashes
    const aadhaarInput = document.querySelector('input[name="aadhar_number"]');
    if (aadhaarInput) {
        aadhaarInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,4}/g).join('-');
                if (value.length > 14) value = value.substr(0, 14);
            }
            this.value = value;
        });
    }
});
</script>
@endsection