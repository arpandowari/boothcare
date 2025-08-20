@extends('layouts.app')

@section('title', 'Edit Member - Boothcare')
@section('page-title', 'Edit Member: ' . $member->name)

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

    .current-photo img {
        max-width: 100px;
        border-radius: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Simple Header -->
    <div class="page-header">
        <a href="{{ route('members.show', $member) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Member
        </a>
        <h1>Edit Member</h1>
        <div class="subtitle">Update information for {{ $member->name }}</div>
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
    <form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-card">
            <!-- Location Information -->
            <div class="form-section">
                <h3 class="section-title">Location Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Area <span class="text-danger">*</span></label>
                        <select class="form-select" name="area_id" required>
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $member->house->booth->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                    {{ $area->area_name }} ({{ $area->district }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Booth <span class="text-danger">*</span></label>
                        <select class="form-select" name="booth_id" required>
                            <option value="">Select Booth</option>
                            @foreach($booths as $booth)
                                <option value="{{ $booth->id }}" {{ old('booth_id', $member->house->booth_id ?? '') == $booth->id ? 'selected' : '' }}>
                                    Booth {{ $booth->booth_number }} - {{ $booth->booth_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">House <span class="text-danger">*</span></label>
                        <select class="form-select" name="house_id" required>
                            <option value="">Select House</option>
                            @foreach($houses as $house)
                                <option value="{{ $house->id }}" {{ old('house_id', $member->house_id) == $house->id ? 'selected' : '' }}>
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
                        <input type="text" class="form-control" name="name" value="{{ old('name', $member->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Relation to Head <span class="text-danger">*</span></label>
                        <select class="form-select" name="relation_to_head" required>
                            <option value="">Select Relation</option>
                            <option value="head" {{ old('relation_to_head', $member->relation_to_head) == 'head' ? 'selected' : '' }}>Head of Family</option>
                            <option value="spouse" {{ old('relation_to_head', $member->relation_to_head) == 'spouse' ? 'selected' : '' }}>Spouse</option>
                            <option value="son" {{ old('relation_to_head', $member->relation_to_head) == 'son' ? 'selected' : '' }}>Son</option>
                            <option value="daughter" {{ old('relation_to_head', $member->relation_to_head) == 'daughter' ? 'selected' : '' }}>Daughter</option>
                            <option value="father" {{ old('relation_to_head', $member->relation_to_head) == 'father' ? 'selected' : '' }}>Father</option>
                            <option value="mother" {{ old('relation_to_head', $member->relation_to_head) == 'mother' ? 'selected' : '' }}>Mother</option>
                            <option value="brother" {{ old('relation_to_head', $member->relation_to_head) == 'brother' ? 'selected' : '' }}>Brother</option>
                            <option value="sister" {{ old('relation_to_head', $member->relation_to_head) == 'sister' ? 'selected' : '' }}>Sister</option>
                            <option value="other" {{ old('relation_to_head', $member->relation_to_head) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $member->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" class="form-control" name="age" value="{{ old('age', $member->age) }}" min="0" max="120">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $member->phone) }}" placeholder="+91 XXXXX XXXXX">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $member->email) }}" placeholder="example@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Occupation</label>
                        <input type="text" class="form-control" name="occupation" value="{{ old('occupation', $member->occupation) }}" placeholder="e.g., Teacher, Farmer">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Education Level</label>
                        <input type="text" class="form-control" name="education" value="{{ old('education', $member->education) }}" placeholder="e.g., Graduate, HSC">
                    </div>
                </div>
            </div>

            <!-- Documents & Additional Info -->
            <div class="form-section">
                <h3 class="section-title">Documents & Additional Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Aadhaar Number</label>
                        <input type="text" class="form-control" name="aadhar_number" value="{{ old('aadhar_number', $member->aadhar_number) }}" placeholder="XXXX-XXXX-XXXX">
                    </div>

                    <div class="form-group">
                        <label class="form-label">PAN Number</label>
                        <input type="text" class="form-control" name="pan_number" value="{{ old('pan_number', $member->pan_number) }}" placeholder="ABCDE1234F">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Voter ID</label>
                        <input type="text" class="form-control" name="voter_id" value="{{ old('voter_id', $member->voter_id) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ration Card Number</label>
                        <input type="text" class="form-control" name="ration_card_number" value="{{ old('ration_card_number', $member->ration_card_number) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Marital Status</label>
                        <select class="form-select" name="marital_status">
                            <option value="">Select Status</option>
                            <option value="single" {{ old('marital_status', $member->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ old('marital_status', $member->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ old('marital_status', $member->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ old('marital_status', $member->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Monthly Income (₹)</label>
                        <input type="number" class="form-control" name="monthly_income" value="{{ old('monthly_income', $member->monthly_income) }}" min="0" step="0.01" placeholder="25000">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Profile Photo</label>
                        @if($member->profile_photo)
                            <div class="current-photo" style="margin-bottom: 10px;">
                                <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="Current Profile Photo">
                                <small style="display: block; color: #666;">Current Profile Photo</small>
                            </div>
                        @endif
                        <input type="file" class="form-control" name="profile_photo" accept="image/jpeg,image/png,image/jpg">
                        <small style="color: #666;">Upload a new profile photo (JPG, PNG - Max: 2MB)</small>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Member</button>
            </div>
        </div>
    </form>
</div>
@endsection