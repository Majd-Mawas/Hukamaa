<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development/)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Email verification with code system.

Profile completion onboarding (for both users).

Doctor discovery with filters by department and specialization.

Pre-consultation form before payment.

Manual payment proof upload and admin approval.

Doctor-patient chat before confirmation.

Appointment scheduling after both doctor and patient agree.

Real-time or scheduled video consultation.

Dashboard views for doctors, patients, and admins.

Notifications (can be future improvement).

1. UserManagement
   Handles authentication, roles, and email verification.

Models:

User

EmailVerification

Features:

Registration/login

Role-based separation

Email verification flow

Basic user dashboard redirection

2. PatientManagement
   Handles patient-specific onboarding, profile, and interactions.

Models:

PatientProfile

PreConsultationForm

Appointment

ChatMessage

Features:

Medical history form

View/search for doctors

Send pre-consultation form

Chat with doctor

Appointment selection

Join video session

3. DoctorManagement
   Handles doctor-specific onboarding, profile, and schedules.

Models:

DoctorProfile

Certificate

Availability

Features:

Doctor onboarding

Certificate upload

Schedule setup

Accept/reject patient requests

Join video consultation

View earnings

4. SpecializationManagement
   Handles departments and specializations.

Models:

Department (optional)

Specialization

Features:

Search doctors by field

Filter by specialization

Used in doctor onboarding and patient search

5. PaymentManagement
   Handles the full payment workflow.

Models:

Payment

Features:

Patient uploads proof

Admin verifies payments

Link payment to appointments

Status tracking

6. AppointmentManagement
   Handles appointment logic between patients and doctors.

Models:

Appointment

VideoCall

Features:

Scheduling with calendar UI

Join video session

Track duration and status

7. AdminPanel
   Centralized module for administrative controls.

Features:

Approve/reject doctors

Approve/reject payments

Set session fees and percentages

View metrics and reports

This module can use models from other modules (via service providers, facades, or events), but should not own them directly unless managing system-wide settings.

1. User
   Handles both Doctors and Patients.

Fields:

id, name, email, password, role (doctor|patient|admin), is_verified, status, created_at 2. EmailVerification
Tracks verification codes.

Fields:

id, user_id, code, expires_at, is_used, created_at 3. PatientProfile
Additional data after patient onboarding.

Fields:

user_id, age, gender, medical_history, chronic_conditions, allergies, current_medications 4. DoctorProfile
Doctor-specific info.

Fields:

user_id, age, gender, profile_picture, specialization_id, title, experience_years, experience_description, certificates, status (pending|approved|rejected) 5. Specialization
Medical specializations for filtering/search.

Fields:

id, department_name, specialization_name 6. PreConsultationForm
Sent by patients before scheduling.

Fields:

id, patient_id, doctor_id, symptoms, condition_description, status (pending|accepted|rejected), response_at 7. Payment
Manual payment submission and approval flow.

Fields:

id, patient_id, doctor_id, amount, payment_receipt_url, status (pending|approved|rejected), approved_by, approved_at 8. Appointment
Consultation schedule linking patient and doctor.

Fields:

id, patient_id, doctor_id, date, time_slot, status (scheduled|completed|cancelled), confirmed_by_doctor, confirmed_by_patient 9. Availability
Doctor's working schedule.

Fields:

doctor_id, weekday, start_time, end_time 10. VideoCall
Track actual consultation session.

Fields:

appointment_id, started_at, ended_at, call_duration, status 11. ChatMessage
Simple doctor-patient messaging pre-consultation.

Fields:

sender_id, receiver_id, message, sent_at 12. Admin
For doctor approvals, payment validation, and general control.

Fields:

id, name, email, password, role (super_admin, finance_admin, etc.)
