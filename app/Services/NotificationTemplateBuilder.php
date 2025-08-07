<?php

// app/Services/NotificationTemplateBuilder.php

namespace App\Services;

class NotificationTemplateBuilder
{
    public static function approvedDoctorAccount($user): array
    {
        return [
            'title' => 'تمت الموافقة على حسابك في حكماء',
            'message' => implode("\n", [
                'تهانينا دكتور/دكتورة،',
                'تمت الموافقة على طلب انضمامك إلى منصة حكماء.',
                'يمكنك الآن تسجيل الدخول والبدء بتقديم خدماتك الطبية.',
                'مع تحيات فريق حكماء.',
            ]),
            'data' => ['doctor_id' => $user->id],
        ];
    }

    public static function rejectedDoctorAccount($user): array
    {
        return [
            'title' => 'نأسف، لم يتم قبول طلبك حالياً',
            'message' => implode("\n", [
                'نعتذر منك،',
                'لم يتم قبول طلب انضمامك إلى منصة حكماء حالياً.',
                'إذا كان لديك أي استفسار يرجى التواصل مع الدعم الفني.',
            ]),
            'data' => ['doctor_id' => $user->id],
        ];
    }

    public static function confirmedAppointment($appointment): array
    {
        return [
            'title' => 'تم تأكيد موعدك مع الطبيب.',
            'message' => implode("\n", [
                'تم تأكيد موعدك بنجاح.',
                'شكراً لاستخدامك منصة حكماء.',
                'سوف يصلك تذكير بالموعد.',
            ]),
            'data' => ['appointment_id' => $appointment->id],
        ];
    }

    public static function newPatientCase($appointment): array
    {
        return [
            'title' => 'لديك حالة جديدة من مريض.',
            'message' => implode("\n", [
                'لديك حالة جديدة من أحد المرضى بحاجة إلى المراجعة.',
                'يرجى الدخول إلى المنصة للاطلاع على تفاصيل الحالة واتخاذ الإجراءات اللازمة.',
            ]),
            'data' => ['appointment_id' => $appointment->id],
        ];
    }

    public static function paymentNeedsApproval($appointment): array
    {
        return [
            'title' => 'تأكيد دفع موعد جديد',
            'message' => "قام مريض {$appointment->patient->name} بحجز موعد جديد ويحتاج إلى التحقق من الدفع. يرجى مراجعة الحجز في لوحة الإدارة.",
            'data' => ['appointment_id' => $appointment->id],
        ];
    }

    public static function paymentApprovedForDoctor($appointment): array
    {
        return [
            'title' => 'تم تأكيد دفع الموعد',
            'message' => implode("\n", [
                'تم تأكيد دفع الموعد من قبل المريض.',
                'يمكنك الآن متابعة الحالة والتواصل مع المريض.',
                'شكراً لاستخدامك منصة حكماء.',
            ]),
            'data' => [
                'appointment_id' => $appointment->id,
                'event' => 'payment_approved'
            ],
        ];
    }

    public static function appointmentDecision($appointment, string $decision): array
    {
        return $decision === 'accept'
            ? [
                'title' => 'تم قبول حالتك من الطبيب - الرجاء اكمال عملية الدفع.',
                'message' => implode("\n", [
                    'تم قبول حالتك الطبية من قبل الطبيب.',
                    'الرجاء اكمال عملية الدفع.',
                    'سيتم التواصل معك قريباً لمتابعة الاستشارة.',
                ]),
                'data' => ['appointment_id' => $appointment->id],
            ]
            : [
                'title' => 'نعتذر، لم يتم قبول حالتك.',
                'message' => implode("\n", [
                    'نعتذر، لم يتم قبول حالتك الطبية من قبل الطبيب حالياً.',
                    'يمكنك التواصل مع الدعم الفني.',
                ]),
                'data' => ['appointment_id' => $appointment->id],
            ];
    }

    public static function newDoctorSignup($doctor): array
    {
        return [
            'title' => 'طبيب جديد سجل في النظام',
            'message' => "مرحباً، تم تسجيل طبيب جديد ({$doctor->name}) على المنصة ويحتاج إلى مراجعة طلبه.",
            'data' => ['doctor_id' => $doctor->id],
        ];
    }

    public static function appointmentReportAdded($appointment): array
    {
        return [
            'title' => 'تم إضافة تقرير الجلسة',
            'message' => implode("\n", [
                'تم إضافة تقرير الجلسة الخاص بك من قبل الطبيب.',
                'يمكنك الآن الاطلاع على التشخيص والوصفة الطبية والإرشادات.',
                'شكراً لاستخدامك منصة حكماء.',
            ]),
            'data' => [
                'appointment_id' => $appointment->id,
                'event' => 'appointment_report_added'
            ],
        ];
    }

    public static function appointmentReportUpdated($appointment): array
    {
        return [
            'title' => 'تم تحديث تقرير الجلسة',
            'message' => implode("\n", [
                'تم تحديث تقرير الجلسة الخاص بك من قبل الطبيب.',
                'يمكنك الآن الاطلاع على التشخيص والوصفة الطبية والإرشادات المحدثة.',
                'شكراً لاستخدامك منصة حكماء.',
            ]),
            'data' => [
                'appointment_id' => $appointment->id,
                'event' => 'appointment_report_updated'
            ],
        ];
    }
}
