<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UpdateRequest;
use App\Models\User;
use App\Models\FamilyMember;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateRequestController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Show the form for creating a new update request
     */
    public function create()
    {
        $user = Auth::user();
        $familyMember = $user->familyMember;

        return view('user.update-requests.create', compact('user', 'familyMember'));
    }

    /**
     * Store a newly created update request
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Debug: Log the incoming request data
        Log::info('Update request form data received', [
            'user_id' => $user->id,
            'request_type' => $request->request_type,
            'requested_data' => $request->requested_data,
            'reason' => $request->reason,
            'has_files' => $request->hasFile('profile_photo') || $request->hasFile('aadhar_photo') || $request->hasFile('pan_photo')
        ]);

        $request->validate([
            'request_type' => 'required|in:profile,family_member,documents',
            'reason' => 'required|string|max:1000',
            'requested_data' => 'required|array',
            // File validation
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'pan_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'voter_id_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ration_card_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Handle file uploads first
        $uploadedFiles = $this->handleFileUploads($request);

        // Get current data based on request type
        $currentData = $this->getCurrentData($user, $request->request_type);

        // Clean and prepare requested data
        $requestedData = $request->requested_data;
        
        // Don't filter out empty values - we want to see what user is trying to change
        // Only remove completely null values, but keep empty strings to show user wants to clear field
        $requestedData = array_filter($requestedData, function($value) {
            return $value !== null;
        });

        // Add uploaded files to requested data
        if (!empty($uploadedFiles)) {
            $requestedData['uploaded_files'] = $uploadedFiles;
        }

        // Debug: Log the processed data
        Log::info('Processed update request data', [
            'current_data' => $currentData,
            'requested_data' => $requestedData,
            'uploaded_files_count' => count($uploadedFiles),
            'target_type' => $this->getTargetType($request->request_type),
            'target_id' => $this->getTargetId($user, $request->request_type)
        ]);

        // Create the update request
        $updateRequest = UpdateRequest::create([
            'user_id' => $user->id,
            'request_type' => $request->request_type,
            'target_type' => $this->getTargetType($request->request_type),
            'target_id' => $this->getTargetId($user, $request->request_type),
            'current_data' => $currentData,
            'requested_data' => $requestedData,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        // Send email notifications
        try {
            $this->emailService->sendUpdateRequestNotification($updateRequest, 'created');
        } catch (\Exception $e) {
            Log::error('Failed to send update request notification email: ' . $e->getMessage());
        }

        Log::info('Update request created successfully', [
            'request_id' => $updateRequest->id,
            'user_id' => $user->id,
            'request_type' => $request->request_type,
            'data_count' => count($requestedData),
            'files_uploaded' => count($uploadedFiles)
        ]);

        $message = 'Update request submitted successfully! An administrator will review your request and notify you via email.';
        if (!empty($uploadedFiles)) {
            $fileCount = count($uploadedFiles);
            $message .= " {$fileCount} file(s) were uploaded with your request.";
        }

        return redirect()->route('dashboard')->with('success', $message);
    }

    /**
     * Get current data based on request type
     */
    private function getCurrentData(User $user, string $requestType): array
    {
        switch ($requestType) {
            case 'profile':
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'date_of_birth' => $user->date_of_birth?->format('Y-m-d'),
                    'gender' => $user->gender,
                ];

            case 'family_member':
                if ($user->familyMember) {
                    return [
                        'name' => $user->familyMember->name,
                        'email' => $user->familyMember->email,
                        'phone' => $user->familyMember->phone,
                        'date_of_birth' => $user->familyMember->date_of_birth?->format('Y-m-d'),
                        'gender' => $user->familyMember->gender,
                        'relation_to_head' => $user->familyMember->relation_to_head,
                        'education' => $user->familyMember->education,
                        'occupation' => $user->familyMember->occupation,
                        'marital_status' => $user->familyMember->marital_status,
                        'monthly_income' => $user->familyMember->monthly_income,
                        'medical_conditions' => $user->familyMember->medical_conditions,
                    ];
                }
                return [];

            case 'documents':
                return [
                    'aadhar_number' => $user->aadhar_number,
                    'pan_number' => $user->pan_number ?? ($user->familyMember->pan_number ?? null),
                    'voter_id' => $user->voter_id ?? ($user->familyMember->voter_id ?? null),
                ];

            default:
                return [];
        }
    }

    /**
     * Get target type based on request type
     */
    private function getTargetType(string $requestType): ?string
    {
        switch ($requestType) {
            case 'profile':
                return 'App\\Models\\User';
            case 'family_member':
                return 'App\\Models\\FamilyMember';
            case 'documents':
                return 'App\\Models\\User';
            default:
                return null;
        }
    }

    /**
     * Get target ID based on request type
     */
    private function getTargetId(User $user, string $requestType): ?int
    {
        switch ($requestType) {
            case 'profile':
            case 'documents':
                return $user->id;
            case 'family_member':
                // If user doesn't have a family member record, create one
                if (!$user->familyMember) {
                    Log::info('Creating family member record for user', ['user_id' => $user->id]);
                    $familyMember = FamilyMember::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'date_of_birth' => $user->date_of_birth,
                        'gender' => $user->gender,
                        'relation_to_head' => 'head', // Default to head of family
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    return $familyMember->id;
                }
                return $user->familyMember->id;
            default:
                return null;
        }
    }

    /**
     * Handle file uploads for the update request
     */
    private function handleFileUploads(Request $request): array
    {
        $uploadedFiles = [];
        
        // Define file upload fields
        $fileFields = [
            'profile_photo' => 'profile_photos',
            'family_profile_photo' => 'family_photos',
            'aadhar_photo' => 'documents/aadhar',
            'pan_photo' => 'documents/pan',
            'voter_id_photo' => 'documents/voter_id',
            'ration_card_photo' => 'documents/ration_card'
        ];

        // Handle single file uploads
        foreach ($fileFields as $fieldName => $directory) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                
                if ($file->isValid()) {
                    // Generate unique filename
                    $filename = time() . '_' . $fieldName . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Store file
                    $path = $file->storeAs($directory, $filename, 'public');
                    
                    $uploadedFiles[$fieldName] = [
                        'original_name' => $file->getClientOriginalName(),
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_at' => now()->toISOString()
                    ];

                    Log::info('File uploaded', [
                        'field' => $fieldName,
                        'filename' => $filename,
                        'size' => $file->getSize()
                    ]);
                }
            }
        }

        // Handle multiple file uploads (additional documents)
        if ($request->hasFile('additional_documents')) {
            $additionalDocs = [];
            
            foreach ($request->file('additional_documents') as $index => $file) {
                if ($file->isValid()) {
                    // Generate unique filename
                    $filename = time() . '_additional_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Store file
                    $path = $file->storeAs('documents/additional', $filename, 'public');
                    
                    $additionalDocs[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_at' => now()->toISOString()
                    ];

                    Log::info('Additional document uploaded', [
                        'index' => $index,
                        'filename' => $filename,
                        'size' => $file->getSize()
                    ]);
                }
            }
            
            if (!empty($additionalDocs)) {
                $uploadedFiles['additional_documents'] = $additionalDocs;
            }
        }

        return $uploadedFiles;
    }
}