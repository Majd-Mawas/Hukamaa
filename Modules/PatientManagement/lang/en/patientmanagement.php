<?php

return [
    'status' => [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
    ],
    'messages' => [
        'form_created' => 'Form created successfully',
        'form_updated' => 'Form updated successfully',
        'form_deleted' => 'Form deleted successfully',
        'form_accepted' => 'Form accepted successfully',
        'form_rejected' => 'Form rejected successfully',
        'form_not_found' => 'Form not found',
        'invalid_status' => 'Invalid form status',
        'already_accepted' => 'Form is already accepted',
        'already_rejected' => 'Form is already rejected',
        'cannot_accept' => 'Cannot accept this form',
        'cannot_reject' => 'Cannot reject this form',
        'required_fields' => 'Please fill in all required fields',
        'invalid_data' => 'Invalid form data',
        'basic_info_updated' => 'Basic medical information updated successfully',
        'extra_info_updated' => 'Supplementary health information updated successfully',
        'profile_updated' => 'Profile updated successfully',
    ],
    'validation' => [
        'required' => 'The :field field is required',
        'invalid_date' => 'Invalid date format',
        'birthdate_before_today' => 'Birthdate must be before today',
        'invalid_gender' => 'Invalid gender selected',
        'allergy_max_length' => 'Allergy description cannot exceed 1000 characters',
        'invalid_file_type' => 'Invalid file type. Allowed types: PDF, JPG, JPEG, PNG',
        'file_too_large' => 'File size cannot exceed 10MB',
        'fields' => [
            'name' => 'name',
            'birthdate' => 'birthdate',
            'gender' => 'gender',
            'allergy' => 'allergy',
            'patient_files' => 'patient files',
        ],
    ],
];
