@extends('layouts.app')

@section('title', 'Edit House - Boothcare')
@section('page-title', 'Edit House: ' . $house->house_number)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('houses.index') }}">Houses</a></li>
        <li class="breadcrumb-item"><a href="{{ route('houses.show', $house) }}">{{ $house->house_number }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit House Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('houses.update', $house) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="area_id" class="form-label">Area <span class="text-danger">*</span></label>
                                <select class="form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', $house->booth->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                            {{ $area->area_name }} ({{ $area->district }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="booth_id" class="form-label">Booth <span class="text-danger">*</span></label>
                                <select class="form-select @error('booth_id') is-invalid @enderror" id="booth_id" name="booth_id" required>
                                    <option value="">Select Booth</option>
                                    @foreach($booths as $booth)
                                        <option value="{{ $booth->id }}" {{ old('booth_id', $house->booth_id) == $booth->id ? 'selected' : '' }}>
                                            Booth {{ $booth->booth_number }} - {{ $booth->booth_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booth_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="house_number" class="form-label">House Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('house_number') is-invalid @enderror" 
                                       id="house_number" name="house_number" value="{{ old('house_number', $house->house_number) }}" required>
                                @error('house_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="house_type" class="form-label">House Type</label>
                                <select class="form-select @error('house_type') is-invalid @enderror" id="house_type" name="house_type">
                                    <option value="">Select Type</option>
                                    <option value="residential" {{ old('house_type', $house->house_type) == 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('house_type', $house->house_type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="mixed" {{ old('house_type', $house->house_type) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                                @error('house_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address', $house->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="owner_name" class="form-label">Owner Name</label>
                                <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                       id="owner_name" name="owner_name" value="{{ old('owner_name', $house->owner_name) }}">
                                @error('owner_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                       id="contact_number" name="contact_number" value="{{ old('contact_number', $house->contact_number) }}">
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes', $house->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $house->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active House
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('houses.show', $house) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update House
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('area_id').addEventListener('change', function() {
    const areaId = this.value;
    const boothSelect = document.getElementById('booth_id');
    
    // Clear booth options
    boothSelect.innerHTML = '<option value="">Select Booth</option>';
    
    if (areaId) {
        // Fetch booths for selected area
        fetch(`/api/areas/${areaId}/booths`)
            .then(response => response.json())
            .then(booths => {
                booths.forEach(booth => {
                    const option = document.createElement('option');
                    option.value = booth.id;
                    option.textContent = `Booth ${booth.booth_number} - ${booth.booth_name}`;
                    boothSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching booths:', error));
    }
});
</script>
@endpush
@endsection