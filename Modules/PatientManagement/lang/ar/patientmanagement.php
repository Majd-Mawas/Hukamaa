<?php

return [
    'status' => [
        'pending' => 'قيد الانتظار',
        'accepted' => 'مقبول',
        'rejected' => 'مرفوض',
    ],
    'messages' => [
        'form_created' => 'تم إنشاء النموذج بنجاح',
        'form_updated' => 'تم تحديث النموذج بنجاح',
        'form_deleted' => 'تم حذف النموذج بنجاح',
        'form_accepted' => 'تم قبول النموذج بنجاح',
        'form_rejected' => 'تم رفض النموذج بنجاح',
        'form_not_found' => 'النموذج غير موجود',
        'invalid_status' => 'حالة النموذج غير صالحة',
        'already_accepted' => 'تم قبول النموذج بالفعل',
        'already_rejected' => 'تم رفض النموذج بالفعل',
        'cannot_accept' => 'لا يمكن قبول هذا النموذج',
        'cannot_reject' => 'لا يمكن رفض هذا النموذج',
        'required_fields' => 'يرجى ملء جميع الحقول المطلوبة',
        'invalid_data' => 'بيانات النموذج غير صالحة',
        'basic_info_updated' => 'تم تحديث المعلومات الطبية الأساسية بنجاح',
        'extra_info_updated' => 'تم تحديث المعلومات الصحية التكميلية بنجاح',
        'profile_updated' => 'تم تحديث الملف الشخصي بنجاح',
    ],
    'validation' => [
        'required' => 'حقل :field مطلوب',
        'invalid_date' => 'صيغة التاريخ غير صالحة',
        'birthdate_before_today' => 'يجب أن يكون تاريخ الميلاد قبل اليوم',
        'invalid_gender' => 'الجنس المحدد غير صالح',
        'allergy_max_length' => 'لا يمكن أن يتجاوز وصف الحساسية 1000 حرف',
        'invalid_file_type' => 'نوع الملف غير صالح. الأنواع المسموح بها: PDF، JPG، JPEG، PNG',
        'file_too_large' => 'لا يمكن أن يتجاوز حجم الملف 10 ميجابايت',
        'fields' => [
            'name' => 'الاسم',
            'birthdate' => 'تاريخ الميلاد',
            'gender' => 'الجنس',
            'allergy' => 'الحساسية',
            'patient_files' => 'ملفات المريض',
        ],
    ],
];
