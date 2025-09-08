<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما يكون :other هو :value.',
    'active_url' => ':attribute ليس عنوان URL صالحًا.',
    'after' => 'يجب أن يكون :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'before' => 'يجب أن يكون :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون :attribute تاريخًا قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'يجب أن يكون :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute بين :min و :max حرفًا.',
        'array' => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max.',
    ],
    'boolean' => 'يجب أن يكون حقل :attribute إما صحيحًا أو خاطئًا.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => ':attribute ليس تاريخًا صالحًا.',
    'date_equals' => 'يجب أن يكون :attribute تاريخًا مساويًا لـ :date.',
    'date_format' => 'لا يتطابق :attribute مع التنسيق :format.',
    'declined' => 'يجب رفض :attribute.',
    'declined_if' => 'يجب رفض :attribute عندما يكون :other هو :value.',
    'different' => 'يجب أن يكون :attribute و :other مختلفين.',
    'digits' => 'يجب أن يحتوي :attribute على :digits أرقام.',
    'digits_between' => 'يجب أن يحتوي :attribute على عدد من الأرقام بين :min و :max.',
    'dimensions' => ':attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالح.',
    'email_invalid_or_nonexistent' => 'البريد الإلكتروني غير صالح أو غير موجود. يرجى التحقق من عنوان البريد الإلكتروني الخاص بك.',
    'registration_failed' => 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.',
    'ends_with' => 'يجب أن ينتهي :attribute بأحد القيم التالية: :values.',
    'enum' => ':attribute المحدد غير صالح.',
    'exists' => ':attribute المحدد غير صالح.',
    'file' => 'يجب أن يكون :attribute ملفًا.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن يكون :attribute أكبر من :value.',
        'file' => 'يجب أن يكون حجم :attribute أكبر من :value كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute أكبر من :value حرفًا.',
        'array' => 'يجب أن يحتوي :attribute على أكثر من :value عنصر.',
    ],
    'gte' => [
        'numeric' => 'يجب أن يكون :attribute أكبر من أو يساوي :value.',
        'file' => 'يجب أن يكون حجم :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute أكبر من أو يساوي :value حرفًا.',
        'array' => 'يجب أن يحتوي :attribute على :value عنصر أو أكثر.',
    ],
    'image' => 'يجب أن يكون :attribute صورة.',
    'in' => ':attribute المحدد غير صالح.',
    'in_array' => 'حقل :attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'ip' => 'يجب أن يكون :attribute عنوان IP صالحًا.',
    'ipv4' => 'يجب أن يكون :attribute عنوان IPv4 صالحًا.',
    'ipv6' => 'يجب أن يكون :attribute عنوان IPv6 صالحًا.',
    'json' => 'يجب أن يكون :attribute نص JSON صالح.',
    'lt' => [
        'numeric' => 'يجب أن يكون :attribute أقل من :value.',
        'file' => 'يجب أن يكون حجم :attribute أقل من :value كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute أقل من :value حرفًا.',
        'array' => 'يجب أن يحتوي :attribute على أقل من :value عنصر.',
    ],
    'lte' => [
        'numeric' => 'يجب أن يكون :attribute أقل من أو يساوي :value.',
        'file' => 'يجب أن يكون حجم :attribute أقل من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute أقل من أو يساوي :value حرفًا.',
        'array' => 'يجب أن لا يحتوي :attribute على أكثر من :value عنصر.',
    ],
    'mac_address' => 'يجب أن يكون :attribute عنوان MAC صالحًا.',
    'max' => [
        'numeric' => 'يجب أن لا يكون :attribute أكبر من :max.',
        'file' => 'يجب أن لا يكون حجم :attribute أكبر من :max كيلوبايت.',
        'string' => 'يجب أن لا يكون طول :attribute أكبر من :max حرفًا.',
        'array' => 'يجب أن لا يحتوي :attribute على أكثر من :max عنصر.',
    ],
    'mimes' => 'يجب أن يكون :attribute ملفًا من النوع: :values.',
    'mimetypes' => 'يجب أن يكون :attribute ملفًا من النوع: :values.',
    'min' => [
        'numeric' => 'يجب أن يكون :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute على الأقل :min حرفًا.',
        'array' => 'يجب أن يحتوي :attribute على الأقل على :min عنصر.',
    ],
    'multiple_of' => 'يجب أن يكون :attribute مضاعفًا لـ :value.',
    'not_in' => ':attribute المحدد غير صالح.',
    'not_regex' => 'تنسيق :attribute غير صالح.',
    'numeric' => 'يجب أن يكون :attribute رقمًا.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'يجب أن يكون حقل :attribute موجودًا.',
    'prohibited' => 'حقل :attribute محظور.',
    'prohibited_if' => 'حقل :attribute محظور عندما يكون :other هو :value.',
    'prohibited_unless' => 'حقل :attribute محظور ما لم يكن :other في :values.',
    'prohibits' => 'حقل :attribute يحظر وجود :other.',
    'regex' => 'تنسيق :attribute غير صالح.',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'يجب أن يحتوي حقل :attribute على مدخلات لـ: :values.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عندما يكون :values موجودًا.',
    'required_with_all' => 'حقل :attribute مطلوب عندما تكون :values موجودة.',
    'required_without' => 'حقل :attribute مطلوب عندما لا يكون :values موجودًا.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا تكون أي من :values موجودة.',
    'same' => 'يجب أن يتطابق :attribute و :other.',
    'size' => [
        'numeric' => 'يجب أن يكون :attribute :size.',
        'file' => 'يجب أن يكون حجم :attribute :size كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute :size حرفًا.',
        'array' => 'يجب أن يحتوي :attribute على :size عنصر.',
    ],
    'starts_with' => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values.',
    'string' => 'يجب أن يكون :attribute نصًا.',
    'timezone' => 'يجب أن يكون :attribute منطقة زمنية صالحة.',
    'unique' => 'قيمة :attribute مستخدمة بالفعل.',
    'uploaded' => 'فشل تحميل :attribute.',
    'url' => 'يجب أن يكون :attribute عنوان URL صالحًا.',
    'uuid' => 'يجب أن يكون :attribute معرف UUID صالحًا.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        // User Management
        'email' => [
            'required' => 'البريد الإلكتروني مطلوب.',
            'email' => 'يرجى إدخال عنوان بريد إلكتروني صالح.',
            'unique' => 'هذا البريد الإلكتروني مسجل بالفعل.',
        ],
        'password' => [
            'required' => 'كلمة المرور مطلوبة.',
            'min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ],
        'name' => [
            'required' => 'الاسم مطلوب.',
            'max' => 'لا يمكن أن يتجاوز الاسم 255 حرفًا.',
        ],
        'role' => [
            'required' => 'الدور مطلوب.',
            'in' => 'الدور المحدد غير صالح.',
        ],
        
        // Doctor Management
        'gender' => [
            'required' => 'الجنس مطلوب.',
            'in' => 'يجب أن يكون الجنس إما ذكر أو أنثى.',
        ],
        'birth_date' => [
            'required' => 'تاريخ الميلاد مطلوب.',
            'date' => 'يجب أن يكون تاريخ الميلاد تاريخًا صالحًا.',
            'before' => 'يجب أن يكون تاريخ الميلاد في الماضي.',
        ],
        'phone_number' => [
            'required' => 'رقم الهاتف مطلوب.',
            'max' => 'لا يمكن أن يتجاوز رقم الهاتف 20 حرفًا.',
        ],
        'address' => [
            'required' => 'العنوان مطلوب.',
            'max' => 'لا يمكن أن يتجاوز العنوان 255 حرفًا.',
        ],
        'profile_picture' => [
            'required' => 'صورة الملف الشخصي مطلوبة.',
            'image' => 'يجب أن تكون صورة الملف الشخصي صورة.',
            'max' => 'لا يمكن أن يتجاوز حجم صورة الملف الشخصي 5 ميجابايت.',
        ],
        'identity_document' => [
            'required' => 'وثيقة الهوية مطلوبة.',
            'file' => 'يجب أن تكون وثيقة الهوية ملفًا.',
            'mimes' => 'يجب أن تكون وثيقة الهوية ملف PDF أو JPG أو JPEG أو PNG.',
            'max' => 'لا يمكن أن يتجاوز حجم وثيقة الهوية 10 ميجابايت.',
        ],
        
        // Patient Management
        'medical_history' => [
            'string' => 'يجب أن يكون التاريخ الطبي نصًا.',
        ],
        'chronic_conditions' => [
            'required' => 'الحالات المزمنة مطلوبة.',
            'array' => 'يجب أن تكون الحالات المزمنة مصفوفة.',
        ],
        'chronic_conditions.*' => [
            'required' => 'كل حالة مزمنة مطلوبة.',
            'integer' => 'يجب أن تكون كل حالة مزمنة رقمًا صحيحًا.',
            'exists' => 'الحالة المزمنة المحددة غير صالحة.',
        ],
        
        // Appointment Management
        'patient_id' => [
            'required' => 'المريض مطلوب.',
            'exists' => 'المريض المحدد غير صالح.',
        ],
        'doctor_id' => [
            'required' => 'الطبيب مطلوب.',
            'exists' => 'الطبيب المحدد غير صالح.',
        ],
        'date' => [
            'required' => 'تاريخ الموعد مطلوب.',
            'date' => 'يجب أن يكون تاريخ الموعد تاريخًا صالحًا.',
            'after' => 'يجب أن يكون تاريخ الموعد في المستقبل.',
        ],
        'time_slot' => [
            'required' => 'الفترة الزمنية مطلوبة.',
            'string' => 'يجب أن تكون الفترة الزمنية نصًا.',
        ],
        'status' => [
            'string' => 'يجب أن تكون الحالة نصًا.',
            'in' => 'يجب أن تكون الحالة مجدولة أو مكتملة أو ملغاة.',
        ],
        'confirmed_by_doctor' => [
            'boolean' => 'يجب أن يكون تأكيد الطبيب صحيحًا أو خاطئًا.',
        ],
        'confirmed_by_patient' => [
            'boolean' => 'يجب أن يكون تأكيد المريض صحيحًا أو خاطئًا.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];