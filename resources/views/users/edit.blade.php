@extends('layouts.app')

@section('title', 'Edit User - Boothcare')
@section('page-title', 'Edit User: ' . $user->name)

@section('content')
<div class="container-fluid px-2">
    <!-- Modern Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit User: {{ $user->name }}
                </h1>
                <p class="dashboard-subtitle">Update user information and permissions</p>
            </div>
            <div class="col-md-4 text-end text-center-mobile">
                <a href="{{ route('users.index') }}" class="export-btn" style="background: #6c757d;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="chart-card">
                <div class="chart-title">
                    <span>User Information</span>
                </div>
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required onchange="togglePermissions()">
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="sub_admin" {{ old('role', $user->role) == 'sub_admin' ? 'selected' : '' }}>Sub Admin</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permissions Section (for Sub Admin) -->
                    <div id="permissionsSection" style="display: {{ old('role', $user->role) == 'sub_admin' ? 'block' : 'none' }};">
                        <h6><i class="fas fa-shield-alt me-2"></i>Permissions</h6>
                        <p class="text-muted mb-3">Select the permissions for this sub-admin user.</p>
                        
                        @if(isset($permissionGroups) && isset($availablePermissions))
                            @foreach($permissionGroups as $groupName => $groupPermissions)
                                <div class="permission-group mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="mb-0">{{ $groupName }}</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="toggleGroupPermissions('{{ $groupName }}')">
                                            <i class="fas fa-check-double me-1"></i>Select All
                                        </button>
                                    </div>
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            @if(isset($availablePermissions[$permission]))
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission }}" 
                                                               id="perm_{{ str_replace('.', '_', $permission) }}"
                                                               data-group="{{ $groupName }}"
                                                               {{ in_array($permission, old('permissions', $user->permissions ?? [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ str_replace('.', '_', $permission) }}">
                                                            {{ $availablePermissions[$permission] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Family Member Association -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="area_id" class="form-label">Area</label>
                                <select class="form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id">
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', $user->family_member->house->booth->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                            {{ $area->area_name }} ({{ $area->district }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="booth_id" class="form-label">Booth</label>
                                <select class="form-select @error('booth_id') is-invalid @enderror" id="booth_id" name="booth_id">
                                    <option value="">Select Booth</option>
                                    @foreach($booths as $booth)
                                        <option value="{{ $booth->id }}" {{ old('booth_id', $user->family_member->house->booth_id ?? '') == $booth->id ? 'selected' : '' }}>
                                            Booth {{ $booth->booth_number }} - {{ $booth->booth_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booth_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="house_id" class="form-label">House</label>
                                <select class="form-select @error('house_id') is-invalid @enderror" id="house_id" name="house_id">
                                    <option value="">Select House</option>
                                    @foreach($houses as $house)
                                        <option value="{{ $house->id }}" {{ old('house_id', $user->family_member->house_id ?? '') == $house->id ? 'selected' : '' }}>
                                            {{ $house->house_number }} - {{ Str::limit($house->address, 30) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('house_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="family_member_id" class="form-label">Associated Family Member</label>
                        <select class="form-select @error('family_member_id') is-invalid @enderror" id="family_member_id" name="family_member_id">
                            <option value="">Select Family Member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('family_member_id', $user->family_member_id) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->relation_to_head }})
                                </option>
                            @endforeach
                        </select>
                        @error('family_member_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Link this user account to a family member record</div>
                    </div>
                    
                    <!-- Password Change Section -->
                    <hr>
                    <h6 class="mb-3">Change Password (Leave blank to keep current password)</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Status -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Account
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="profile_completed" name="profile_completed" value="1" 
                                   {{ old('profile_completed', $user->profile_completed) ? 'checked' : '' }}>
                            <label class="form-check-label" for="profile_completed">
                                Profile Completed
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Dynamic dropdown loading
document.getElementById('area_id').addEventListener('change', function() {
    const areaId = this.value;
    const boothSelect = document.getElementById('booth_id');
    const houseSelect = document.getElementById('house_id');
    const memberSelect = document.getElementById('family_member_id');
    
    // Clear dependent dropdowns
    boothSelect.innerHTML = '<option value="">Select Booth</option>';
    houseSelect.innerHTML = '<option value="">Select House</option>';
    memberSelect.innerHTML = '<option value="">Select Family Member</option>';
    
    if (areaId) {
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

document.getElementById('booth_id').addEventListener('change', function() {
    const boothId = this.value;
    const houseSelect = document.getElementById('house_id');
    const memberSelect = document.getElementById('family_member_id');
    
    // Clear dependent dropdowns
    houseSelect.innerHTML = '<option value="">Select House</option>';
    memberSelect.innerHTML = '<option value="">Select Family Member</option>';
    
    if (boothId) {
        fetch(`/api/booths/${boothId}/houses`)
            .then(response => response.json())
            .then(houses => {
                houses.forEach(house => {
                    const option = document.createElement('option');
                    option.value = house.id;
                    option.textContent = `${house.house_number} - ${house.address.substring(0, 30)}...`;
                    houseSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching houses:', error));
    }
});

document.getElementById('house_id').addEventListener('change', function() {
    const houseId = this.value;
    const memberSelect = document.getElementById('family_member_id');
    
    // Clear member dropdown
    memberSelect.innerHTML = '<option value="">Select Family Member</option>';
    
    if (houseId) {
        fetch(`/api/houses/${houseId}/members`)
            .then(response => response.json())
            .then(members => {
                members.forEach(member => {
                    const option = document.createElement('option');
                    option.value = member.id;
                    option.textContent = `${member.name} (${member.relation_to_head})`;
                    memberSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching members:', error));
    }
});

// Permission management functions
function togglePermissions() {
    const roleSelect = document.getElementById('role');
    const permissionsSection = document.getElementById('permissionsSection');
    
    if (roleSelect.value === 'sub_admin') {
        permissionsSection.style.display = 'block';
    } else {
        permissionsSection.style.display = 'none';
        // Uncheck all permissions when hiding
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
}

function toggleGroupPermissions(groupName) {
    const groupCheckboxes = document.querySelectorAll(`input[data-group="${groupName}"]`);
    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
    
    groupCheckboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}
</script>
@endpush
@endsection