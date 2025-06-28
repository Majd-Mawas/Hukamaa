@extends('doctorDashboard.layout.layout')
@php
    $title = 'Doctor Profile';
    $subTitle = 'Manage Your Profile';
    $script = '<script>
        // Image Upload Handler
        const imageUploadHandler = {
            init() {
                $("#imageUpload").change(this.handleImageChange);
            },
            handleImageChange(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    $("#imagePreview")
                        .css("background-image", `url(${e.target.result})`)
                        .css("display", "block")
                        .hide()
                        .fadeIn(650);
                };
                reader.readAsDataURL(file);
            }
        };

        // Password Toggle Handler
        const passwordToggleHandler = {
            init() {
                $(".toggle-password").on("click", this.togglePasswordVisibility);
            },
            togglePasswordVisibility() {
                const $this = $(this);
                const input = $($this.data("toggle"));

                $this.toggleClass("ri-eye-off-line");
                input.attr("type", input.attr("type") === "password" ? "text" : "password");
            }
        };

        // Initialize Handlers
        $(document).ready(() => {
            imageUploadHandler.init();
            passwordToggleHandler.init();
        });
    </script>
    <script src="' . asset('assets/js/data-table.js') . '"></script>
    <script src="' . asset('assets/js/availability.js') . '"></script>';
@endphp

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Profile Info Card -->
        <div class="col-span-12 lg:col-span-4">
            <div
                class="user-grid-card relative border border-neutral-200 dark:border-neutral-600 rounded-2xl overflow-hidden bg-white dark:bg-neutral-700 h-full shadow-sm">
                {{-- <div class="profile-header relative h-32">
                    <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="Profile Background"
                        class="w-full h-full object-cover">
                </div> --}}
                <div class="profile-content px-6 pb-6 -mt-[60px] mt-2">
                    <div class="text-center border-b border-neutral-200 dark:border-neutral-600 pb-4">
                        <div class="relative inline-block">
                            <img src="{{ auth()?->user()?->doctorProfile?->getFirstMediaUrl('profile_picture') ?: asset('assets/images/user-grid/user-grid-img1.png') }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-[120px] h-[120px] rounded-full object-cover border-4 border-white dark:border-neutral-800 shadow-sm">
                        </div>
                        <h4 class="text-xl font-semibold mt-3 mb-1">{{ auth()->user()->name }}</h4>
                        <p class="text-neutral-600 dark:text-neutral-400">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="mt-6">
                        <h5 class="text-lg font-semibold mb-4">Professional Info</h5>
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Specialty</span>
                                <span
                                    class="font-medium">{{ auth()->user()->doctorProfile->specialization->specialization_name ?? 'Not Set' }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Experience</span>
                                <span class="font-medium">{{ auth()->user()->doctorProfile->experience_years ?? '0' }}
                                    Years</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Patients</span>
                                <span class="font-medium">{{ $doctor->patients_count ?? '0' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Edit Form -->
        <div class="col-span-12 lg:col-span-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-6">
                    {{-- <nav class="tab-style-gradient flex flex-wrap gap-2 mb-6" role="tablist">
                        <button class="py-2 px-4 border-b-2 border-primary-600 font-medium text-primary-600" role="tab"
                            aria-selected="true" aria-controls="edit-profile">
                            Edit Profile
                        </button>
                        <button
                            class="py-2 px-4 border-b-2 border-transparent hover:text-primary-600 hover:border-primary-600"
                            role="tab" aria-controls="security">
                            Security
                        </button>
                        <button
                            class="py-2 px-4 border-b-2 border-transparent hover:text-primary-600 hover:border-primary-600"
                            role="tab" aria-controls="notifications">
                            Notifications
                        </button>
                    </nav> --}}

                    <ul class="tab-style-gradient flex flex-wrap text-sm font-medium text-center mb-5" id="tablist"
                        data-tabs-toggle="#tab-content" role="tablist">

                        <li role="presentation">
                            <button
                                class="py-2.5 px-4 border-t-2 font-semibold text-base inline-flex items-center gap-3 text-primary-600"
                                id="edit-profile-tab" data-tabs-target="#edit-profile" type="button" role="tab"
                                aria-controls="edit-profile" aria-selected="true">
                                Edit Profile
                            </button>
                        </li>
                        {{-- <li role="presentation">
                            <button
                                class="py-2.5 px-4 border-t-2 font-semibold text-base inline-flex items-center gap-3 text-neutral-600 hover:text-primary-600"
                                id="security-tab" data-tabs-target="#security" type="button" role="tab"
                                aria-controls="security" aria-selected="false">
                                Security
                            </button>
                        </li>
                        <li role="presentation">
                            <button
                                class="py-2.5 px-4 border-t-2 font-semibold text-base inline-flex items-center gap-3 text-neutral-600 hover:text-primary-600"
                                id="notifications-tab" data-tabs-target="#notifications" type="button" role="tab"
                                aria-controls="notifications" aria-selected="false">
                                Notifications
                            </button>
                        </li> --}}
                    </ul>

                    <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div id="tab-content">
                            <div id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab" class="">
                                <!-- Profile Image Upload -->
                                {{-- <div class="mb-3">
                                    <div class="flex items-center gap-4" style="min-height: 6rem;">
                                        <div class="relative">
                                            <input type="file" id="imageUpload" name="profile_picture" class="hidden"
                                                accept="image/*">
                                            <label for="imageUpload"
                                                class="cursor-pointer inline-flex items-center justify-center w-32 h-32 border-2 border-dashed border-neutral-300 rounded-lg hover:border-primary-500 transition-colors">
                                                <div class="text-center">
                                                    <i class="ri-camera-line text-2xl mb-1"></i>
                                                    <p class="text-sm">Upload Photo</p>
                                                </div>
                                            </label>
                                        </div>
                                        <div id="imagePreview" class="rounded-full bg-cover bg-center hidden"
                                            style="width: 6rem; height: 6rem;"></div>
                                    </div>
                                </div> --}}

                                <h6 class="text-base text-neutral-600 dark:text-neutral-200 mb-4">Profile Image</h6>
                                <!-- Upload Image Start -->
                                <div class="mb-6 mt-4">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit absolute bottom-0 end-0 me-6 mt-4 z-[1] cursor-pointer">
                                            <input type='file' id="imageUpload" name="profile_picture"
                                                accept=".png, .jpg, .jpeg" hidden>
                                            <label for="imageUpload"
                                                class="w-8 h-8 flex justify-center items-center bg-primary-100 dark:bg-primary-600/25 text-primary-600 dark:text-primary-400 border border-primary-600 hover:bg-primary-100 text-lg rounded-full">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- <div>
                                        <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                                        <input type="email" id="email" name="email" disabled
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            value="{{ old('email', auth()->user()->email) }}" required>
                                    </div> --}}
                                    <div>
                                        <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                                        <input type="text" id="name" name="name"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium mb-2">Phone Number</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            value="{{ old('phone', auth()->user()->doctorProfile->phone_number) }}">
                                    </div>
                                    <div>
                                        <label for="specialization" class="block text-sm font-medium mb-2">Specialty</label>
                                        <select id="specialization" name="specialization"
                                            class="form-select w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            required>
                                            <option value="">Select Specialization</option>
                                            @foreach ($specialties as $specialization)
                                                <option value="{{ $specialization->id }}"
                                                    {{ old('specialization', auth()->user()->doctorProfile->specialization_id) == $specialization->id ? 'selected' : '' }}>
                                                    {{ $specialization->specialization_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium mb-2">Experience
                                            Years</label>
                                        <input type="text" id="experience_years" name="experience_years"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            value="{{ old('phone', auth()->user()->doctorProfile->experience_years) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- <div id="security" role="tabpanel" aria-labelledby="security-tab" class="hidden">
                            </div>

                            <div id="notifications" role="tabpanel" aria-labelledby="notifications-tab" class="hidden">
                                <!-- Professional Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="experience" class="block text-sm font-medium mb-2">Years of
                                            Experience</label>
                                        <input type="number" id="experience" name="experience"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('experience', auth()->user()->doctorProfile->experience_years) }}"
                                            min="0" required>
                                    </div>
                                    <div>
                                        <label for="consultation_fee" class="block text-sm font-medium mb-2">Consultation
                                            Fee</label>
                                        <input type="number" id="consultation_fee" name="consultation_fee"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('consultation_fee', auth()->user()->doctorProfile->consultation_fee) }}"
                                            min="0" required>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 mt-3">
                            <button type="reset"
                                class="px-6 py-2 border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="grid grid-cols-12 mt-4">
            <div class="col-span-12">
                <div class="card border-0 overflow-hidden">

                    <div class="card-header">
                        <h6 class="card-title mb-0 text-lg">Availabilities</h6>
                    </div>
                    <div class="card-body">
                        <table id="selection-table"
                            class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-neutral-800 dark:text-white">Day</th>
                                    <th scope="col" class="text-neutral-800 dark:text-white">Start Time</th>
                                    <th scope="col" class="text-neutral-800 dark:text-white">End Time</th>
                                    <th scope="col" class="text-neutral-800 dark:text-white">Status</th>
                                    <th scope="col" class="text-neutral-800 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\Modules\DoctorManagement\App\Enums\Weekday::cases() as $weekday)
                                    @php
                                        $availability = $availabilities->firstWhere('weekday', $weekday->value);
                                    @endphp
                                    <tr>
                                        <td>{{ $weekday->label() }}</td>
                                        <td class="start-time">{{ $availability->start_time ?? '09:00' }}</td>
                                        <td class="end-time">{{ $availability->end_time ?? '17:00' }}</td>
                                        {{-- <td>{{ isset($availability) ? ($availability->is_available ? 'Available' : 'Unavailable') : 'Unavailable' }} --}}
                                        <td class="available">{{ isset($availability) ? 'Available' : 'Unavailable' }}
                                        </td>
                                        <td>
                                            @if ($availability)
                                                <button data-modal-target="availability-modal"
                                                    data-modal-toggle="availability-modal"
                                                    class="edit-availability text-primary-600 hover:text-primary-900"
                                                    data-id="{{ $availability->id }}">
                                                    Edit
                                                </button>
                                                {{-- <button class="edit-availability" data-id="{{ $availability->id }}">Edit</button> --}}
                                            @else
                                                <button data-modal-target="availability-modal"
                                                    data-modal-toggle="availability-modal"
                                                    class="edit-availability text-primary-600 hover:text-primary-900"
                                                    data-id="{{ $weekday->value }}">
                                                    Add
                                                </button>
                                                {{-- <button class="add-availability" data-weekday="{{ $weekday->value }}">Add</button> --}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


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
