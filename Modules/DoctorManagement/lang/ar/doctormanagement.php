<?php

return [
    // General
    'doctors' => 'الأطباء',
    'doctor' => 'طبيب',
    'specialization' => 'التخصص',
    'experience' => 'الخبرة',
    'years' => 'سنوات',
    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'قيد الانتظار',
    ],
    'services' => 'الخدمات',
    'availability' => 'التوفر',
    'appointments' => 'المواعيد',

    // Status
    'status.approved' => 'تمت الموافقة',
    'status.rejected' => 'مرفوض',
    'status.suspended' => 'معلق',

    // Services
    'services.remote_video_consultation' => 'استشارة فيديو عن بعد',
    'services.home_visit' => 'زيارة منزلية',

    // Messages
    'messages.doctors_retrieved' => 'تم استرجاع الأطباء بنجاح',
    'messages.doctor_profile_created' => 'تم إنشاء ملف الطبيب بنجاح',
    'messages.doctor_profile_retrieved' => 'تم استرجاع ملف الطبيب بنجاح',
    'messages.doctor_profile_updated' => 'تم تحديث ملف الطبيب بنجاح',
    'messages.doctor_profile_deleted' => 'تم حذف ملف الطبيب بنجاح',
    'messages.featured_doctors_retrieved' => 'تم استرجاع الأطباء المميزين بنجاح',
    'messages.availabilities_retrieved' => 'تم استرجاع المواعيد المتاحة بنجاح',
    'messages.availability_created' => 'تم إنشاء الموعد المتاح بنجاح',
    'messages.availability_updated' => 'تم تحديث الموعد المتاح بنجاح',
    'messages.availability_deleted' => 'تم حذف الموعد المتاح بنجاح',

    // Validation
    'validation.required' => 'هذا الحقل مطلوب',
    'validation.invalid_gender' => 'الجنس المحدد غير صالح',
    'validation.invalid_status' => 'الحالة المحددة غير صالحة',
    'validation.invalid_specialization' => 'التخصص المحدد غير صالح',
    'validation.invalid_experience' => 'يجب أن تكون الخبرة بين 0 و 50 سنة',
    'validation.invalid_phone' => 'صيغة رقم الهاتف غير صالحة',
    'validation.invalid_date' => 'صيغة التاريخ غير صالحة',

    // Weekdays
    'weekdays.monday' => 'الاثنين',
    'weekdays.tuesday' => 'الثلاثاء',
    'weekdays.wednesday' => 'الأربعاء',
    'weekdays.thursday' => 'الخميس',
    'weekdays.friday' => 'الجمعة',
    'weekdays.saturday' => 'السبت',
    'weekdays.sunday' => 'الأحد',

    // Gender
    'gender.male' => 'ذكر',
    'gender.female' => 'أنثى',

    // Titles
    'titles.dr' => 'د.',
    'titles.prof' => 'أ.د.',
    'titles.assoc_prof' => 'أ.د. مشارك',
    'titles.asst_prof' => 'أ.د. مساعد',

    // User Roles
    'roles.admin' => 'مدير النظام',
    'roles.doctor' => 'طبيب',
    'roles.patient' => 'مريض',
    'roles.staff' => 'موظف',
];
