<?php

return [
    // General
    'doctors' => 'الأطباء',
    'doctor' => 'طبيب',
    'specialization' => 'التخصص',
    'experience' => 'الخبرة',
    'years' => 'سنوات',
    'services' => 'الخدمات',
    'availability' => 'التوفر',
    'appointments' => 'المواعيد',

    // Status
    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'قيد الانتظار',
        'approved' => 'تمت الموافقة',
        'rejected' => 'مرفوض',
        'suspended' => 'معلق'
    ],

    // Services
    'services' => [
        'remote_video_consultation' => 'استشارة فيديو عن بعد',
        'home_visit' => 'زيارة منزلية'
    ],

    // Messages
    'messages' => [
        'doctors_retrieved' => 'تم استرجاع الأطباء بنجاح',
        'doctor_created' => 'تم إنشاء ملف الطبيب بنجاح',
        'doctor_updated' => 'تم تحديث ملف الطبيب بنجاح',
        'doctor_deleted' => 'تم حذف ملف الطبيب بنجاح',
        'featured_doctors_retrieved' => 'تم استرجاع الأطباء المميزين بنجاح',
        'doctor_profile_created' => 'تم إنشاء ملف الطبيب بنجاح',
        'doctor_profile_retrieved' => 'تم استرجاع ملف الطبيب بنجاح',
        'doctor_profile_updated' => 'تم تحديث ملف الطبيب بنجاح',
        'doctor_profile_deleted' => 'تم حذف ملف الطبيب بنجاح',
        'availabilities_retrieved' => 'تم استرجاع المواعيد المتاحة بنجاح',
        'availability_created' => 'تم إنشاء الموعد المتاح بنجاح',
        'availability_updated' => 'تم تحديث الموعد المتاح بنجاح',
        'availability_deleted' => 'تم حذف الموعد المتاح بنجاح'
    ],

    // Validation
    'validation' => [
        'required' => 'هذا الحقل مطلوب',
        'invalid_gender' => 'الجنس المحدد غير صالح',
        'invalid_status' => 'الحالة المحددة غير صالحة',
        'invalid_specialization' => 'التخصص المحدد غير صالح',
        'invalid_experience' => 'يجب أن تكون الخبرة بين 0 و 50 سنة',
        'invalid_phone' => 'صيغة رقم الهاتف غير صالحة',
        'invalid_date' => 'صيغة التاريخ غير صالحة'
    ]
];
