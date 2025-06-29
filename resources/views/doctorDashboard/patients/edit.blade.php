@extends('doctorDashboard.layout.layout')

@php
    $title = 'Edit Patient';
    $subTitle = 'Edit Patient';
    $script = '<script>
        // ================== Image Upload Js Start ===========================
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                    $("#imagePreview").hide();
                    $("#imagePreview").fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });
        // ================== Image Upload Js End ===========================
    </script>';
@endphp

@section('content')
    <div class="card h-full p-0 rounded-xl border-0 overflow-hidden">
        <div class="card-body p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 justify-center">
                <div class="col-span-12 lg:col-span-10 xl:col-span-8 2xl:col-span-6 2xl:col-start-4">
                    <div class="card border border-neutral-200 dark:border-neutral-600">
                        <div class="card-body">
                            <form action="{{ route('doctor.patients.update', ['patient' => $patient]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-5">
                                    <label for="name"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Name <span class="text-danger-600">*</span></label>
                                    <input type="text" class="form-control rounded-lg" id="name"
                                        value="{{ $patient->user->name }}" disabled>
                                </div>
                                <div class="mb-5">
                                    <label for="email"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Email <span class="text-danger-600">*</span></label>
                                    <input type="text" class="form-control rounded-lg" id="email"
                                        value="{{ $patient->user->email }}" disabled>
                                </div>
                                <div class="mb-5">
                                    <label for="allergies"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Allergies <span class="text-danger-600">*</span>
                                    </label>
                                    <select
                                        class="form-control form-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        id="allergies" name="allergies[]" multiple>
                                        @foreach ($allergies as $allergy)
                                            <option value="{{ $allergy->id }}"
                                                {{ $patient->allergies->contains('id', $allergy->id) ? 'selected' : '' }}>
                                                {{ $allergy->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label for="chronicConditions"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Chronic Conditions <span class="text-danger-600">*</span>
                                    </label>
                                    <select
                                        class="form-control form-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        id="chronicConditions" name="chronicConditions[]" multiple>
                                        @foreach ($chronicConditions as $chronicCondition)
                                            <option value="{{ $chronicCondition->id }}"
                                                {{ $patient->chronicConditions->contains('id', $chronicCondition->id) ? 'selected' : '' }}>
                                                {{ $chronicCondition->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label for="desc"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Current
                                        Medications</label>
                                    <textarea name="current_medications" name="current_medications" class="form-control rounded-lg" id="desc"
                                        placeholder="Write description...">{{ is_array(json_decode($patient->current_medications)) ? '' : $patient->current_medications }}</textarea>
                                </div>

                                <div class="mb-5">
                                    <label for="desc"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Medical
                                        History</label>
                                    <textarea name="medical_history" name="medical_history" class="form-control rounded-lg" id="desc"
                                        placeholder="Write description...">{{ is_array(json_decode($patient->medical_history)) ? '' : $patient->medical_history }}</textarea>
                                </div>
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-base px-14 py-[11px] rounded-lg">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-base px-14 py-3 rounded-lg">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
