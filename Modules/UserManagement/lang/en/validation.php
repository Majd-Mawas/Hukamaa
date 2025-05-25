<?php

return [
    'attributes' => [
        'email' => 'email address',
        'password' => 'password',
        'token' => 'reset token',
        'code' => 'verification code',
    ],
    'custom' => [
        'email' => [
            'required' => 'The email address is required.',
            'email' => 'Please enter a valid email address.',
            'exists' => 'We could not find this email address in our records.',
        ],
        'password' => [
            'required' => 'The password is required.',
            'min' => 'The password must be at least :min characters.',
            'confirmed' => 'The password confirmation does not match.',
        ],
        'token' => [
            'required' => 'The reset token is required.',
            'string' => 'The reset token must be a valid string.',
        ],
        'code' => [
            'required' => 'The verification code is required.',
            'digits' => 'The verification code must be :digits digits.',
        ],
    ],
];
