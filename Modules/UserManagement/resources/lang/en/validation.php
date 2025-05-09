<?php

return [
    'attributes' => [
        'name' => 'name',
        'email' => 'email',
        'password' => 'password',
        'role' => 'role',
        'gender' => 'gender',
        'birth_date' => 'birth date',
        'medical_history' => 'medical history',
        'chronic_conditions' => 'chronic conditions',
        'allergies' => 'allergies',
        'current_medications' => 'current medications',
        'files' => 'files',
        'appointment_id' => 'appointment',
        'started_at' => 'start time',
        'ended_at' => 'end time',
        'call_duration' => 'call duration',
        'status' => 'status',
        'user_id' => 'user',
        'gender' => 'gender',
        'profile_picture' => 'profile picture',
        'specialization_id' => 'specialization',
        'title' => 'title',
        'experience_years' => 'years of experience',
        'experience_description' => 'experience description',
        'certificates' => 'certificates',
        'patient_id' => 'patient',
        'doctor_id' => 'doctor',
        'date' => 'date',
        'time_slot' => 'time slot',
        'confirmed_by_doctor' => 'doctor confirmation',
        'confirmed_by_patient' => 'patient confirmation',
        'weekday' => 'weekday',
        'start_time' => 'start time',
        'end_time' => 'end time',
    ],

    'custom' => [
        'name' => [
            'required' => 'The name field is required.',
            'string' => 'The name must be a string.',
            'max' => 'The name cannot exceed 255 characters.',
        ],
        'email' => [
            'required' => 'The email field is required.',
            'string' => 'The email must be a string.',
            'email' => 'Please provide a valid email address.',
            'max' => 'The email cannot exceed 255 characters.',
            'unique' => 'This email is already registered.',
        ],
        'password' => [
            'required' => 'The password field is required.',
            'string' => 'The password must be a string.',
            'min' => 'The password must be at least 8 characters.',
            'confirmed' => 'The password confirmation does not match.',
        ],
        'role' => [
            'required' => 'The role field is required.',
            'string' => 'The role must be a string.',
            'in' => 'The role must be either doctor or patient.',
        ],
        'gender' => [
            'required' => 'The gender field is required.',
            'string' => 'The gender must be a string.',
            'in' => 'The gender must be either male or female',
        ],
        'birth_date' => [
            'required' => 'The birth date field is required.',
            'date' => 'The birth date must be a valid date.',
            'before' => 'The birth date must be before today.',
        ],
        'medical_history' => [
            'string' => 'The medical history must be a string.',
        ],
        'chronic_conditions' => [
            'string' => 'The chronic conditions must be a string.',
        ],
        'allergies' => [
            'string' => 'The allergies must be a string.',
        ],
        'current_medications' => [
            'string' => 'The current medications must be a string.',
        ],
        'files.*' => [
            'file' => 'The file must be a valid file.',
            'mimes' => 'The file must be a file of type: pdf, jpg, jpeg, png.',
            'max' => 'The file may not be greater than 10MB.',
        ],
        'appointment_id' => [
            'required' => 'The appointment field is required.',
            'exists' => 'The selected appointment is invalid.',
        ],
        'started_at' => [
            'date' => 'The start time must be a valid date.',
        ],
        'ended_at' => [
            'date' => 'The end time must be a valid date.',
            'after' => 'The end time must be after the start time.',
        ],
        'call_duration' => [
            'integer' => 'The call duration must be an integer.',
            'min' => 'The call duration must be at least 0.',
        ],
        'status' => [
            'string' => 'The status must be a string.',
            'in' => 'The status must be one of: pending, in progress, completed, failed.',
        ],
        'user_id' => [
            'required' => 'The user field is required.',
            'exists' => 'The selected user is invalid.',
        ],
        'gender' => [
            'required' => 'The gender field is required.',
            'string' => 'The gender must be a string.',
            'in' => 'The gender must be either male or female.',
        ],
        'profile_picture' => [
            'string' => 'The profile picture must be a string.',
            'max' => 'The profile picture cannot exceed 255 characters.',
        ],
        'specialization_id' => [
            'required' => 'The specialization field is required.',
            'exists' => 'The selected specialization is invalid.',
        ],
        'title' => [
            'required' => 'The title field is required.',
            'string' => 'The title must be a string.',
            'max' => 'The title cannot exceed 255 characters.',
        ],
        'experience_years' => [
            'required' => 'The years of experience field is required.',
            'integer' => 'The years of experience must be an integer.',
            'min' => 'The years of experience must be at least 0.',
            'max' => 'The years of experience cannot exceed 50.',
        ],
        'experience_description' => [
            'required' => 'The experience description field is required.',
            'string' => 'The experience description must be a string.',
        ],
        'certificates' => [
            'required' => 'The certificates field is required.',
            'array' => 'The certificates must be an array.',
        ],
        'certificates.*' => [
            'required' => 'The certificate field is required.',
            'string' => 'The certificate must be a string.',
        ],
        'patient_id' => [
            'required' => 'The patient field is required.',
            'exists' => 'The selected patient is invalid.',
        ],
        'doctor_id' => [
            'required' => 'The doctor field is required.',
            'exists' => 'The selected doctor is invalid.',
        ],
        'date' => [
            'required' => 'The date field is required.',
            'date' => 'The date must be a valid date.',
            'after' => 'The date must be after today.',
        ],
        'time_slot' => [
            'required' => 'The time slot field is required.',
            'string' => 'The time slot must be a string.',
        ],
        'confirmed_by_doctor' => [
            'boolean' => 'The doctor confirmation must be true or false.',
        ],
        'confirmed_by_patient' => [
            'boolean' => 'The patient confirmation must be true or false.',
        ],
        'weekday' => [
            'required' => 'The weekday field is required.',
            'string' => 'The weekday must be a string.',
            'in' => 'The weekday must be one of: monday, tuesday, wednesday, thursday, friday, saturday, sunday.',
        ],
        'start_time' => [
            'required' => 'The start time field is required.',
            'date_format' => 'The start time must be in the format HH:mm.',
        ],
        'end_time' => [
            'required' => 'The end time field is required.',
            'date_format' => 'The end time must be in the format HH:mm.',
            'after' => 'The end time must be after the start time.',
        ],
    ],
];
