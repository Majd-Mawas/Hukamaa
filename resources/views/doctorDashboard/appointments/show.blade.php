@extends('doctorDashboard.layout.layout')

@php
    $title = 'Appointment Details';
    $subTitle = 'View and manage appointment information';
@endphp

@section('content')
    <div class="container mx-auto px-4 py-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">Appointment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Appointment ID</p>
                        <p class="font-medium dark:text-white">#{{ $appointment->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Status</p>
                        <span
                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $appointment->status === \Modules\AppointmentManagement\App\Enums\AppointmentStatus::COMPLETED
                                ? 'bg-green-100 text-green-800'
                                : ($appointment->status === \Modules\AppointmentManagement\App\Enums\AppointmentStatus::CANCELLED
                                    ? 'bg-red-100 text-red-800'
                                    : ($appointment->status === \Modules\AppointmentManagement\App\Enums\AppointmentStatus::SCHEDULED
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ $appointment->status->label() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Date</p>
                        <p class="font-medium dark:text-white">{{ $appointment->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Time</p>
                        <p class="font-medium dark:text-white">
                            {{ \Carbon\Carbon::parse($appointment?->time_range['start_time'] ?? null)->format('h:i A') }} -
                            {{ \Carbon\Carbon::parse($appointment?->time_range['end_time'] ?? null)->format('h:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Created At</p>
                        <p class="font-medium dark:text-white">{{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Updated At</p>
                        <p class="font-medium dark:text-white">{{ $appointment->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Patient Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">Patient Information</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Name</p>
                            <p class="font-medium dark:text-white">{{ $appointment->patient->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Email</p>
                            <p class="font-medium dark:text-white">{{ $appointment->patient->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Phone</p>
                            <p class="font-medium dark:text-white">
                                {{ $appointment->patient->patientProfile->phone_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Birthdate</p>
                            <p class="font-medium dark:text-white">
                                {{ $appointment->patient->patientProfile->birth_date ? $appointment->patient->patientProfile->birth_date->format('M d, Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctor Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">Doctor Information</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Name</p>
                            <p class="font-medium dark:text-white">{{ $appointment->doctor->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Email</p>
                            <p class="font-medium dark:text-white">{{ $appointment->doctor->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Phone</p>
                            <p class="font-medium dark:text-white">
                                {{ $appointment->doctor->doctorProfile->phone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Condition Description -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">Condition Description</h2>
                <p class="dark:text-white">
                    {{ $appointment->condition_description ?? 'No condition description provided.' }}</p>
            </div>
        </div>

        <!-- Appointment Report (if available) -->
        @if ($appointment->appointmentReport)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">Appointment Report</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Diagnosis</p>
                            <p class="font-medium dark:text-white">{{ $appointment->appointmentReport->diagnosis }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Prescription</p>
                            <p class="font-medium dark:text-white">{{ $appointment->appointmentReport->prescription }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Additional Notes</p>
                            <p class="font-medium dark:text-white">
                                {{ $appointment->appointmentReport->additional_notes ?? 'No additional notes.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Appointment Files -->
        @if ($appointment->getMedia('appointment_files')->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">Appointment Files</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($appointment->getMedia('appointment_files') as $file)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="flex items-center space-x-3 gap-2">
                                    <div class="flex-shrink-0">
                                        @if (str_contains($file->mime_type, 'image'))
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $file->human_readable_size }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $file->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ $file->getUrl() }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Payment Invoice -->
        @if ($appointment->payment_invoice)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">Payment Invoice</h2>
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 gap-2">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $appointment->payment_invoice->human_readable_size }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $appointment->payment_invoice->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ $appointment->payment_invoice->getUrl() }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Download Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Update Status Form -->
        {{-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">Update Appointment Status</h2>
                <form action="{{ route('admin.appointments.update-status', $appointment) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach (\Modules\AppointmentManagement\App\Enums\AppointmentStatus::cases() as $status)
                                <option value="{{ $status->value }}"
                                    {{ $appointment->status === $status ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}
    </div>
@endsection
