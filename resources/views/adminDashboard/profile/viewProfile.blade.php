@extends('adminDashboard.layout.layout')
@php
    $title = 'Admin Profile';
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
        {{-- <div class="col-span-12 lg:col-span-4">
            <div
                class="user-grid-card relative border border-neutral-200 dark:border-neutral-600 rounded-2xl overflow-hidden bg-white dark:bg-neutral-700 h-full shadow-sm">
                <div class="profile-header relative h-32">
                    <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="Profile Background"
                        class="w-full h-full object-cover">
                </div>
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
        </div> --}}

        <!-- Profile Edit Form -->
        <div class="col-span-12 lg:col-span-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-6">
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

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div id="tab-content">
                            <div id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab" class="">
                                <!-- Profile Image Upload -->
                                {{-- <h6 class="text-base text-neutral-600 dark:text-neutral-200 mb-4">Profile Image</h6>
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
                                </div> --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                                        <input type="text" id="name" name="name"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                    <div>
                                        <label for="account_number" class="block text-sm font-medium mb-2">Account
                                            Number</label>
                                        <input type="text" id="account_number" name="account_number"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('account_number', getSetting('account_number')) }}" required>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium mb-2">Email</label>
                                        <input type="text" id="email" name="email"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                                        <input type="password" id="password" name="password"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 ">
                                    </div>
                                    <div>
                                        <label for="account_holder_name" class="block text-sm font-medium mb-2">Account
                                            Holder Name</label>
                                        <input type="text" id="account_holder_name" name="account_holder_name"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('account_holder_name', getSetting('account_holder_name')) }}"
                                            required>
                                    </div>
                                    <div>
                                        <label for="bank_name" class="block text-sm font-medium mb-2">Bank Name</label>
                                        <input type="text" id="bank_name" name="bank_name"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 "
                                            value="{{ old('bank_name', getSetting('bank_name')) }}" required>
                                    </div>

                                    {{-- <div>
                                        <label for="phone" class="block text-sm font-medium mb-2">Phone Number</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            value="{{ old('phone', auth()->user()->doctorProfile->phone_number) }}">
                                    </div> --}}
                                    {{-- <div>
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
                                    </div> --}}
                                    {{-- <div>
                                        <label for="phone" class="block text-sm font-medium mb-2">Experience
                                            Years</label>
                                        <input type="text" id="experience_years" name="experience_years"
                                            class="form-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            value="{{ old('phone', auth()->user()->doctorProfile->experience_years) }}">
                                    </div> --}}
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
@endsection
