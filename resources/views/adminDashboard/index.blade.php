@extends('adminDashboard.layout.layout')
{{-- @dd($stats->monthlyPayments) --}}
@php
    $title = 'Dashboard';
    $subTitle = 'Hukamaa';
    $script = '<script>
        // ===================== Average Enrollment Rate Start ===============================
        function createChartTwo(chartId, color1, color2, paymentData) {
            var options = {
                series: [{
                    name: "series1",
                    data: paymentData
                }, ],
                chart: {
                    type: "area",
                    width: "100%",
                    height: 270,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                    colors: [color1, color2], // Use two colors for the lines
                    // lineCap: "round"
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                },
                markers: {
                    colors: [color1, color2], // Use two colors for the markers
                    strokeWidth: 3,
                    size: 0,
                    hover: {
                        size: 10
                    }
                },
                xaxis: {
                    labels: {
                        show: false,
                    },
                    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    tooltip: {
                        enabled: false
                    },
                    labels: {
                        formatter: function(value) {
                            return value;
                        },
                        style: {
                            fontSize: "14px"
                        },
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return "$" + val;
                        }
                    }
                },

                tooltip: {
                    x: {
                        format: "dd/MM/yy HH:mm"
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }

        const paymentData = ' . json_encode($stats->monthlyPayments ?? []) . ';
        createChartTwo("enrollmentChart", "#487FFF", "#FF9F29", paymentData);

        // ================================= Multiple Radial Bar Chart Start =============================

        const specialtySeries = ' . json_encode($stats->top_specialties['counts'] ?? []) . ';
        const specialtyLabels = ' . json_encode($stats->top_specialties['names'] ?? []) . ';

        var options = {
            series: specialtySeries,
            chart: {
                height: 300,
                type: "radialBar",
            },
            colors: ["#3D7FF9", "#FF9F29", "#16a34a"],
            stroke: {
                lineCap: "round",
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: "40%",
                    },
                    dataLabels: {
                        name: {
                            fontSize: "16px",
                        },
                        value: {
                            fontSize: "16px",
                        },
                        track: {
                            margin: 0,
                        }
                    }
                }
            },
            labels: specialtyLabels,
        };

        var chart = new ApexCharts(document.querySelector("#radialMultipleBar"), options);
        chart.render();
        // ================================= Multiple Radial Bar Chart End =============================
    </script>';
@endphp

@section('content')
    <div class="grid grid-cols-1 3xl:grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6">
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 2xl:col-span-4 2">
                    <div
                        class="card border-0 p-4 shadow-[0_0.25rem_1.875rem_rgba(46,45,116,0.05)] rounded-lg h-full bg-gradient-to-l from-cyan-600/10 to-bg-white">
                        <div class="card-body p-0">
                            <div class="flex flex-wrap items-center justify-between gap-1 mb-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-12 h-12 bg-cyan-600/25 text-cyan-600 dark:text-cyan-600 flex-shrink-0 flex justify-center items-center rounded-full h6 mb-0">
                                        <i class="ri-group-fill"></i>
                                    </span>
                                    <div>
                                        <h6 class="font-semibold mb-0.5">{{ $stats->doctors['total'] }}</h6>
                                        <span class="font-medium text-gray-600 text-sm">Doctors</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm mb-0 text-gray-600"><span
                                    class="text-cyan-600 dark:text-cyan-600">{{ $stats->doctors['new_this_week'] }}</span>
                                Doctors joined this week</p>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-4 2xl:col-span-4">
                    <div
                        class="card border-0 p-4 shadow-[0_0.25rem_1.875rem_rgba(46,45,116,0.05)] rounded-lg h-full bg-gradient-to-l from-primary-600/10 to-bg-white">
                        <div class="card-body p-0">
                            <div class="flex flex-wrap items-center justify-between gap-1 mb-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-12 h-12 bg-primary-600/25 text-primary-600 dark:text-primary-600 flex-shrink-0 flex justify-center items-center rounded-full h6 mb-0">
                                        <i class="ri-group-fill"></i>
                                    </span>
                                    <div>
                                        <h6 class="font-semibold mb-0.5">{{ $stats->patients['total'] }}</h6>
                                        <span class="font-medium text-gray-600 text-sm">Patients</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm mb-0 text-gray-600"><span
                                    class="text-primary-600 dark:text-primary-600">{{ $stats->patients['new_this_week'] }}</span>
                                New patients admitted</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 2xl:col-span-4">
                    <div
                        class="card border-0 p-4 shadow-[0_0.25rem_1.875rem_rgba(46,45,116,0.05)] rounded-lg h-full bg-gradient-to-l from-success-600/10 to-bg-white">
                        <div class="card-body p-0">
                            <div class="flex flex-wrap items-center justify-between gap-1 mb-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-12 h-12 bg-success-600/25 text-success-600 dark:text-success-600 flex-shrink-0 flex justify-center items-center rounded-full h6 mb-0">
                                        <i class="ri-calendar-check-fill"></i>
                                    </span>
                                    <div>
                                        <h6 class="font-semibold mb-0.5">{{ $stats->appointments['total'] }}</h6>
                                        <span class="font-medium text-gray-600 text-sm">Appointment</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm mb-0 text-gray-600"><span
                                    class="text-success-600 dark:text-success-600">{{ $stats->appointments['new_this_week'] }}</span>
                                new Appointment on reserve</p>
                        </div>
                    </div>
                </div>

                <!-- Earning Statistic -->
                <div class="col-span-12 2xl:col-span-6">
                    <div class="card border-0 h-full">
                        <div class="card-header">
                            <div class="flex items-center gap-2 justify-between">
                                <h6 class="mb-0 font-bold text-lg">Earning Statistic</h6>
                            </div>
                        </div>
                        <div class="card-body p-1.5">
                            <div id="enrollmentChart" class="apexcharts-tooltip-style-1 apexcharts-yaxis"></div>
                        </div>
                    </div>
                </div>
                <!-- Earning Statistic -->

                <!-- Patient Visited by Department -->
                <div class="col-span-12 lg:col-span-6">
                    <div class="card border-0 h-full">
                        <div class="card-header">
                            <div class="flex items-center gap-2 justify-between">
                                <h6 class="mb-0 font-bold text-lg">Patient Visited by Department</h6>
                            </div>
                        </div>
                        <div class="card-body p-1.5 flex items-center gap-4">
                            <div id="radialMultipleBar"></div>
                            <div class="flex flex-col gap-4">
                                @php
                                    $specialtyCount = count($stats->top_specialties['names'] ?? []);
                                    $loopCount = min(3, $specialtyCount);
                                @endphp
                                @foreach (range(0, $loopCount - 1) as $index)
                                    @php
                                        $colorClasses = [
                                            0 => 'text-primary-600 dark:text-primary-600',
                                            1 => 'text-warning-600 dark:text-warning-600',
                                            2 => 'text-success-600 dark:text-success-600',
                                        ];
                                    @endphp
                                    <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium">{{ $stats->top_specialties['names'][$index] }}
                                            </h4>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 text-right">
                                                <span class="text-lg font-bold {{ $colorClasses[$index] }}">
                                                    {{ $stats->top_specialties['percentages'][$index] }}%
                                                </span>
                                            </div>
                                            <div
                                                class="w-2 h-2 rounded-full {{ str_replace('text', 'bg', $colorClasses[$index]) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Patient Visited by Department -->

                <!-- Top performance Start -->
                <div class="col-span-12 2xl:col-span-6">
                    <div class="card border-0">
                        <div class="card-header border-bottom">
                            <div class="flex items-center gap-2 justify-between">
                                <h6 class="mb-0 font-bold text-lg">Doctors List</h6>
                                <a href="javascript:void(0)"
                                    class="flex-shrink-0 text-primary-600 dark:text-primary-600 hover-text-primary flex items-center gap-1">
                                    View All
                                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-col gap-6">
                                @foreach ($stats->doctors_list as $doctor)
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center">
                                            @php
                                                $pp =
                                                    $doctor?->getMedia('profile_picture')->first()?->getUrl() ??
                                                    asset('assets/images/home-eight/doctor-img5.png');
                                            @endphp
                                            <img src="{{ $pp }}" alt=""
                                                class="w-10 h-10 rounded-full flex-shrink-0 me-3 overflow-hidden">
                                            <div class="flex-grow-1">
                                                <h6 class="text-base mb-0">{{ $doctor->user->name }}</h6>
                                                <span
                                                    class="text-sm text-gray-600 font-medium">{{ $doctor?->specialization?->specialization_name }}</span>
                                            </div>
                                        </div>
                                        <span
                                            class="px-2.5 py-1 rounded-lg font-medium text-sm
                                            @if ($doctor->status == 'pending') bg-warning-focus text-warning-main dark:text-warning-main
                                            @elseif($doctor->status == 'approved')
                                                bg-success-focus text-success-main dark:text-success-main
                                            @elseif($doctor->status == 'rejected')
                                                bg-danger-focus text-danger-main dark:text-danger-main @endif
                                            ">{{ $doctor->status }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Top performance End -->

                <div class="col-span-12 2xl:col-span-6">
                    <div class="card border-0 h-full">
                        <div class="card-header border-bottom bg-base py-4 px-6 flex items-center justify-between">
                            <h6 class="text-lg font-semibold mb-0">Latest Appointments</h6>
                            <a href="javascript:void(0)"
                                class="flex-shrink-0 text-primary-600 dark:text-primary-600 hover-text-primary flex items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive scroll-sm">
                                <table class="table bordered-table mb-0 rounded-0 border-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-transparent rounded-0">ID</th>
                                            <th scope="col" class="bg-transparent rounded-0">Date & Time</th>
                                            <th scope="col" class="bg-transparent rounded-0">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
                                        @endphp
                                        @foreach ($stats->appointment as $appointment)
                                            <tr>
                                                <td>#{{ $appointment->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}
                                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="px-2.5 py-1 rounded-lg font-medium text-sm
                                                        @if ($appointment->status === AppointmentStatus::PENDING_PAYMENT) bg-warning-focus text-warning-main dark:text-warning-main
                                                        @elseif($appointment->status === AppointmentStatus::COMPLETED))
                                                            bg-success-focus text-success-main dark:text-success-main
                                                        @elseif($appointment->status === AppointmentStatus::CANCELLED)
                                                            bg-danger-focus text-danger-main dark:text-danger-main
                                                        @elseif($appointment->status === AppointmentStatus::SCHEDULED)
                                                            bg-info-focus text-info-main dark:text-info-main
                                                        @else
                                                            bg-gray-focus text-gray-main dark:text-gray-main @endif">
                                                        {{ $appointment->status->label() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Latest Performance End -->
            </div>
        </div>
    </div>
@endsection
