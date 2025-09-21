@extends('adminDashboard.layout.layout')

@php
    $title = 'Basic Table';
    $subTitle = 'Basic Table';
    $script = '<script src="' . asset('assets/js/data-table.js') . '"></script>';
@endphp

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
@section('content')
    <div>
        {{-- <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Doctors Management</h1>
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Add New Doctor
            </a>
        </div> --}}

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="card border-0 overflow-hidden">
                    <div class="card-header">
                        <h6 class="card-title mb-0 text-lg">Doctors</h6>
                    </div>
                    <div class="card-body">
                        <table id="selection-table"
                            class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Doctor</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Specialization</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Phone Number</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Expertise Focus</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($doctors as $doctor)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 h-10 w-10 ">
                                                    <img class="h-10 w-10 rounded-full "
                                                        src="{{ $doctor->getFirstMediaUrl('profile_picture') ?: asset('assets/images/user.png') }}"
                                                        alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $doctor->user->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $doctor->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $doctor?->specialization?->specialization_name ?? 'Not specified' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $doctor->phone_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $doctor->expertise_focus }}
                                            </div>
                                        </td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $doctor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($doctor->status) }}
                                            </span>
                                        </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.doctors.show', ['doctor' => $doctor->id]) }}"
                                                class="text-primary-600 hover:text-primary-900 mr-3">View</a>
                                            <button type="button" data-modal-target="editFeesModal{{ $doctor->id }}" data-modal-toggle="editFeesModal{{ $doctor->id }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">Edit Fees</button>
                                            {{-- <a href="{{ route('admin.doctors.edit', ['doctor'=>$doctor->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a> --}}
                                            <form action="{{ route('admin.doctors.destroy', ['doctor' => $doctor->id]) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                            No doctors found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $doctors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Fees Modals -->
    @foreach($doctors as $doctor)
        <div id="editFeesModal{{ $doctor->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Edit Doctor Fees
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="editFeesModal{{ $doctor->id }}">
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
                    <form action="{{ route('admin.doctors.update-fees', $doctor->id) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-12 gap-4 p-4 md:p-5 space-y-4">
                            <div class="col-span-12">
                                <label class="form-label">Consultation Fee</label>
                                <input type="number" class="form-control" required
                                    step="0.5" placeholder="150" value="{{ $doctor->consultation_fee }}"
                                    name="consultation_fee">
                            </div>
                            <div class="col-span-12">
                                <label class="form-label">Commission Percent - 100%</label>
                                <input type="number" class="form-control" required
                                    step="0.5" placeholder="10" min="0" value="{{ $doctor->commission_percent }}"
                                    max="100" name="commission_percent">
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
