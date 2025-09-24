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
                    </ul>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div id="tab-content">
                            <div id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab" class="">
                                <!-- Profile Image Upload -->
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
                                </div>
                            </div>
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
