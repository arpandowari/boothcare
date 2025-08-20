<?php

return [
    'available_permissions' => [
        // Member Management
        'members.view' => 'View Members',
        'members.create' => 'Create Members',
        'members.edit' => 'Edit Members',
        'members.delete' => 'Delete Members',
        
        // House Management
        'houses.view' => 'View Houses',
        'houses.create' => 'Create Houses',
        'houses.edit' => 'Edit Houses',
        'houses.delete' => 'Delete Houses',
        
        // Booth Management
        'booths.view' => 'View Booths',
        'booths.create' => 'Create Booths',
        'booths.edit' => 'Edit Booths',
        'booths.delete' => 'Delete Booths',
        
        // Area Management
        'areas.view' => 'View Areas',
        'areas.create' => 'Create Areas',
        'areas.edit' => 'Edit Areas',
        'areas.delete' => 'Delete Areas',
        
        // Problem Management
        'problems.view' => 'View Problems',
        'problems.create' => 'Create Problems',
        'problems.edit' => 'Edit Problems',
        'problems.delete' => 'Delete Problems',
        'problems.assign' => 'Assign Problems',
        'problems.status_change' => 'Change Problem Status',
        
        // User Management
        'users.view' => 'View Users',
        'users.create' => 'Create Users',
        'users.edit' => 'Edit Users',
        'users.delete' => 'Delete Users',
        'users.permissions' => 'Manage User Permissions',
        
        // Reports
        'reports.view' => 'View Reports',
        'reports.export' => 'Export Reports',
        
        // Settings
        'settings.view' => 'View Settings',
        'settings.edit' => 'Edit Settings',
        
        // Update Requests
        'update_requests.view' => 'View Update Requests',
        'update_requests.approve' => 'Approve Update Requests',
        'update_requests.reject' => 'Reject Update Requests',
        
        // Notice Management
        'notices.view' => 'View Notices',
        'notices.create' => 'Create Notices',
        'notices.edit' => 'Edit Notices',
        'notices.delete' => 'Delete Notices',
        
        // Review Management
        'reviews.view' => 'View Reviews',
        'reviews.moderate' => 'Moderate Reviews',
        'reviews.delete' => 'Delete Reviews',
        
        // Public Reports Management
        'public_reports.view' => 'View Public Reports',
        'public_reports.moderate' => 'Moderate Public Reports',
        'public_reports.delete' => 'Delete Public Reports',
        
        // Booth Images Management
        'booth_images.view' => 'View Booth Images',
        'booth_images.upload' => 'Upload Booth Images',
        'booth_images.edit' => 'Edit Booth Images',
        'booth_images.delete' => 'Delete Booth Images',
    ],
    
    'permission_groups' => [
        'Member Management' => ['members.view', 'members.create', 'members.edit', 'members.delete'],
        'Location Management' => ['areas.view', 'areas.create', 'areas.edit', 'areas.delete', 'booths.view', 'booths.create', 'booths.edit', 'booths.delete', 'houses.view', 'houses.create', 'houses.edit', 'houses.delete'],
        'Problem Management' => ['problems.view', 'problems.create', 'problems.edit', 'problems.delete', 'problems.assign', 'problems.status_change'],
        'User Management' => ['users.view', 'users.create', 'users.edit', 'users.delete', 'users.permissions'],
        'System Management' => ['reports.view', 'reports.export', 'settings.view', 'settings.edit', 'update_requests.view', 'update_requests.approve', 'update_requests.reject'],
        'Content Management' => ['notices.view', 'notices.create', 'notices.edit', 'notices.delete', 'reviews.view', 'reviews.moderate', 'reviews.delete', 'public_reports.view', 'public_reports.moderate', 'public_reports.delete', 'booth_images.view', 'booth_images.upload', 'booth_images.edit', 'booth_images.delete'],
    ]
];