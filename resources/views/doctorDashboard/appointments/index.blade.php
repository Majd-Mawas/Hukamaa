@extends('doctorDashboard.layout.layout')

@php
    $title = 'Appointment Management';
    $subTitle = 'A list of all appointments in the system';
    $script = '<script src="' . asset('assets/js/data-table.js') . '"></script>';
@endphp

@section('content')
    <div>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="card border-0 overflow-hidden">
                    <div class="card-header">
                        <h6 class="card-title mb-0 text-lg">Appointments</h6>
                    </div>
                    <div class="card-body">
                        <table id="selection-table"
                            class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Patient</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Time</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            #{{ $appointment->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $appointment->patient->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $appointment->date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($appointment?->time_range['start_time'] ?? null)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($appointment?->time_range['end_time'] ?? null)->format('h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $appointment->status === \Modules\AppointmentManagement\App\Enums\AppointmentStatus::COMPLETED
                                            ? 'bg-green-100 text-green-800'
                                            : ($appointment->status === \Modules\AppointmentManagement\App\Enums\AppointmentStatus::CANCELLED
                                                ? 'bg-red-100 text-red-800'
                                                : ($appointment->status === \Modules\AppointmentManagement\App\Enums\AppointmentStatus::SCHEDULED
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : 'bg-yellow-100 text-yellow-800')) }}">
                                                {{ $appointment->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-4">View</a>
                                            @if (isset($status) && $status === \Modules\AppointmentManagement\App\Enums\AppointmentStatus::PENDING->value)
                                                <a href="{{ route('doctor.appointments.accept', $appointment) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-4">Accept</a>
                                                <a href="{{ route('doctor.appointments.reject', $appointment) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-4">Reject</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                            No appointments found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $appointments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
