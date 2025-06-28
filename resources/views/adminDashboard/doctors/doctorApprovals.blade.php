@extends('adminDashboard.layout.layout')

@php
    $title = 'Doctor Approval';
    $subTitle = 'Doctor Approval';
    $script = '<script src="' . asset('assets/js/data-table.js') . '"></script>';
@endphp
@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($pendingDoctors->isEmpty())
            <div class="text-center py-4">
                <h5 class="text-muted mb-2">No Pending Approvals</h5>
                <p class="text-muted font-size-14">There are no doctors waiting for approval at this time.
                </p>
            </div>
        @else
            <div class="grid grid-cols-12">
                <div class="col-span-12">
                    <div class="card border-0 overflow-hidden">
                        <div class="card-header">
                            <h6 class="card-title mb-0 text-lg">Doctors</h6>
                        </div>
                        <div class="card-body">
                            <table id="selection-table"
                                class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate">
                                <thead class="table-light">
                                    <tr class="font-size-13">
                                        <th>Doctor Name</th>
                                        <th>Specialization</th>
                                        <th>Experience</th>
                                        <th>Documents</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="font-size-14">
                                    @foreach ($pendingDoctors as $doctor)
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="h-20 w-16">
                                                        @if ($doctor->getFirstMediaUrl('profile_picture'))
                                                            <img src="{{ $doctor->getFirstMediaUrl('profile_picture') }}"
                                                                class="rounded-full h-full w-full object-cover me-2"
                                                                alt="Profile Picture"
                                                                style="max-width: 6rem; min-height: 6rem">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="mb-1">{{ $doctor->user->name }}</div>
                                                        <p class="mb-0 text-muted font-size-13">
                                                            {{ $doctor->user->email }}</p>
                                                        <p class="mb-0 text-muted font-size-13">
                                                            {{ $doctor->phone_number }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $doctor?->specialization?->specialization_name ?? 'Not specified' }}
                                            </td>
                                            <td>{{ $doctor->experience_years }} years</td>
                                            <td>
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i
                                                            class="mdi mdi-file-document-outline text-muted font-size-18"></i>
                                                        <div class="flex-grow-1">
                                                            @if ($doctor->getFirstMediaUrl('identity_document'))
                                                                <a href="{{ $doctor->getFirstMediaUrl('identity_document') }}"
                                                                    class="d-flex align-items-center text-reset text-decoration-none"
                                                                    target="_blank">
                                                                    <span class="text-body font-size-13">Identity
                                                                        Document</span>
                                                                    <i class="mdi mdi-open-in-new ms-1 font-size-13"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted font-size-13">No ID
                                                                    Document</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="mdi mdi-license text-muted font-size-18"></i>
                                                        <div class="flex-grow-1">
                                                            @if ($doctor->getFirstMediaUrl('practice_license'))
                                                                <a href="{{ $doctor->getFirstMediaUrl('practice_license') }}"
                                                                    class="d-flex align-items-center text-reset text-decoration-none"
                                                                    target="_blank">
                                                                    <span class="text-body font-size-13">Practice
                                                                        License</span>
                                                                    <i class="mdi mdi-open-in-new ms-1 font-size-13"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted font-size-13">No
                                                                    License</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="mdi mdi-certificate-outline text-muted font-size-18"></i>
                                                        <div class="flex-grow-1">
                                                            @if ($doctor->getMedia('medical_certificates')->count() > 0)
                                                                <div data-modal-target="certificatesModal{{ $doctor->id }}"
                                                                    data-modal-toggle="certificatesModal{{ $doctor->id }}"
                                                                    class="cursor-pointer">
                                                                    Certificates
                                                                </div>
                                                            @else
                                                                <span class="text-muted font-size-13">No
                                                                    Certificates</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ $doctor->status }}</td>

                                            <td>
                                                <div class="flex gap-4 items-center justify-center">
                                                    <a href="{{ route('admin.doctors.show', ['doctor' => $doctor->id]) }}"
                                                        class="text-primary-600 hover:text-primary-900 mr-3">
                                                        <iconify-icon icon="mdi:eye"></iconify-icon>
                                                    </a>
                                                    <button data-modal-target="default-modal"
                                                        data-modal-toggle="default-modal" type="button">
                                                        <iconify-icon icon="mdi:check"></iconify-icon>
                                                    </button>
                                                    {{-- <div>
                                                        <form action="{{ route('admin.doctors.approve', $doctor->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" title="Approve">
                                                                <iconify-icon icon="mdi:check"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    </div> --}}
                                                    @if ($doctor->status == 'pending')
                                                        <div>
                                                            <form action="{{ route('admin.doctors.reject', $doctor->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" data-bs-toggle="tooltip"
                                                                    title="Reject">
                                                                    <iconify-icon icon="mdi:close"></iconify-icon>

                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Certificates Modal -->
                                        <div id="certificatesModal{{ $doctor->id }}" tabindex="-1" aria-hidden="true"
                                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                                <!-- Modal content -->
                                                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                                    <!-- Modal header -->
                                                    <div
                                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                            Medical Certificates
                                                        </h3>
                                                        <button type="button"
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                            data-modal-hide="certificatesModal{{ $doctor->id }}">
                                                            <svg class="w-3 h-3" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 14 14">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                            </svg>
                                                            <span class="sr-only">Close modal</span>
                                                        </button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="p-4 md:p-5 space-y-4">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            @foreach ($doctor->getMedia('medical_certificates') as $certificate)
                                                                <div
                                                                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-600">
                                                                    <h6
                                                                        class="text-lg font-medium text-gray-900 dark:text-white mb-3">
                                                                        Certificate {{ $loop->iteration }}
                                                                    </h6>
                                                                    <a href="{{ $certificate->getUrl() }}" target="_blank"
                                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                        View Certificate
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="default-modal" tabindex="-1" aria-hidden="true"
                                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                                <!-- Modal content -->
                                                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                                    <!-- Modal header -->
                                                    <div
                                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                            Accept Doctor
                                                        </h3>
                                                        <button type="button"
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                            data-modal-hide="default-modal">
                                                            <svg class="w-3 h-3" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 14 14">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                            </svg>
                                                            <span class="sr-only">Close modal</span>
                                                        </button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form action="{{ route('admin.doctors.approve', $doctor->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <div class="grid grid-cols-12 gap-4 p-4 md:p-5 space-y-4">
                                                            <div class="col-span-12">
                                                                <label class="form-label">Consultation Fee</label>
                                                                <input type="number" class="form-control" required
                                                                    step="0.5" placeholder="150"
                                                                    name="consultation_fee">
                                                            </div>
                                                            <div class="col-span-12">
                                                                <label class="form-label">Commission Percent - 100%</label>
                                                                <input type="number" class="form-control" required
                                                                    step="0.5" placeholder="10"
                                                                    name="commission_percent">
                                                            </div>
                                                        </div>

                                                        {{-- <button type="submit" data-bs-toggle="tooltip" title="Approve">
                                                            <iconify-icon icon="mdi:check"></iconify-icon>
                                                        </button> --}}

                                                        <!-- Modal footer -->
                                                        <div
                                                            class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                            <button type="submit"
                                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Approve</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- </div>
                </div>
            </div> --}}
    </div>

@endsection

@push('styles')
    <style>
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .font-size-18 {
            font-size: 18px !important;
        }

        .font-size-14 {
            font-size: 14px !important;
        }

        .font-size-13 {
            font-size: 13px !important;
        }

        .avatar-xs {
            width: 32px;
            height: 32px;
        }

        .btn-sm {
            font-size: 12px;
            padding: 0.25rem 0.5rem;
        }

        .card .card-body {
            padding: 1rem;
        }

        .font-size-24 {
            font-size: 24px !important;
        }

        .modal .card:hover {
            transform: translateY(-3px);
            transition: 0.2s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize any necessary JavaScript functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endpush
