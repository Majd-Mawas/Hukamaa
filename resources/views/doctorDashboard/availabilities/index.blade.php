@extends('doctorDashboard.layout.layout')

@php
    $title = 'Availabilities Management';
    $subTitle = 'A list of all Availabilities in the system';
    $script = '<script src="' . asset('assets/js/data-table.js') . '"></script>
    <script src="' . asset('assets/js/availability.js') . '"></script>';
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
                        <h6 class="card-title mb-0 text-lg">Payments</h6>
                    </div>
                    <div class="card-body">
                        <table id="selection-table"
                            class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-neutral-800 dark:text-white">Day</th>
                                    <th scope="col" class="text-neutral-800 dark:text-white">Time Slots</th>
                                    <th scope="col" class="text-neutral-800 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\Modules\DoctorManagement\App\Enums\Weekday::cases() as $weekday)
                                    @php
                                        $dayAvailabilities = $availabilities->where('weekday', $weekday->value);
                                    @endphp
                                    <tr data-weekday="{{ $weekday->value }}">
                                        <td>{{ $weekday->label() }}</td>
                                        <td class="time-slots">
                                            @if ($dayAvailabilities->count() > 0)
                                                <div class="flex flex-col gap-2">
                                                    @foreach ($dayAvailabilities as $availability)
                                                        <div class="time-slot-item flex items-center gap-2"
                                                            data-id="{{ $availability->id }}">
                                                            <span class="start-time">{{ $availability->start_time }}</span>
                                                            -
                                                            <span class="end-time">{{ $availability->end_time }}</span>
                                                            <button
                                                                class="edit-availability text-primary-600 hover:text-primary-900 ml-2"
                                                                data-modal-target="availability-modal"
                                                                data-modal-toggle="availability-modal"
                                                                data-id="{{ $availability->id }}">
                                                                <iconify-icon icon="heroicons:pencil-square"
                                                                    class="icon"></iconify-icon>
                                                            </button>
                                                            <button
                                                                class="delete-availability text-red-600 hover:text-red-900"
                                                                data-id="{{ $availability->id }}">
                                                                <iconify-icon icon="heroicons:trash"
                                                                    class="icon"></iconify-icon>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-neutral-500">No time slots available</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button data-modal-target="availability-modal"
                                                data-modal-toggle="availability-modal"
                                                class="add-availability text-primary-600 hover:text-primary-900 flex items-center gap-1"
                                                data-weekday="{{ $weekday->value }}">
                                                <iconify-icon icon="heroicons:plus-circle" class="icon"></iconify-icon>
                                                Add Time Slot
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="availability-modal" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">

                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white dark:bg-neutral-800 rounded-lg shadow">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium mb-4" id="modal-title">Add Time Slot</h3>
                                    <form id="availabilityForm">
                                        @csrf
                                        <input type="hidden" id="availabilityId" name="id">
                                        <input type="hidden" id="weekday" name="weekday">

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium mb-1">Start Time</label>
                                            <input type="time" id="startTime" name="start_time"
                                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium mb-1">End Time</label>
                                            <input type="time" id="endTime" name="end_time"
                                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <button data-modal-hide="availability-modal" type="button"
                                                class="px-4 py-2 border rounded">
                                                Cancel
                                            </button>
                                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded">
                                                Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $payments->links() }}
                    </div> --}}
                </div>
            </div>
        </div>

        <div id="availability-modal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">

            <div class="relative w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white dark:bg-neutral-800 rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Edit Availability</h3>
                        <form id="availabilityForm">
                            @csrf
                            <input type="hidden" id="availabilityId" name="id">

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Start Time</label>
                                <input type="time" id="startTime" name="start_time"
                                    class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">End Time</label>
                                <input type="time" id="endTime" name="end_time"
                                    class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button data-modal-hide="availability-modal" type="button"
                                    class="px-4 py-2 border rounded">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
