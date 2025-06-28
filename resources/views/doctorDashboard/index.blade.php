@extends('doctorDashboard.layout.layout')
@php
    $title = 'My Dashboard';
    $subTitle = 'Doctor Portal';
    $script = '<script>
        function createEarningsChart(chartId, color1, color2, paymentData) {
            var options = {
                series: [{
                    name: "Monthly Earnings",
                    data: paymentData
                }],
                chart: {
                    type: "area",
                    height: 270,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                    colors: [color1]
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
                xaxis: {
                    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    labels: {
                        style: {
                            fontSize: "12px"
                        }
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
                    y: {
                        formatter: function(val) {
                            return "$" + val;
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }

        const earningsData = ' . json_encode($stats->earnings['monthly_chart'] ?? []) . ';
        createEarningsChart("earningsChart", "#3D7FF9", "#FF9F29", earningsData);
    </script>';
@endphp

@section('content')
    <div class="grid grid-cols-1 3xl:grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6">
                <!-- Total Patients Card -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-4">
                    <div
                        class="card border-0 p-4 shadow-lg rounded-lg h-full bg-gradient-to-l from-primary-600/10 to-bg-white">
                        <div class="card-body p-0">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-12 h-12 bg-primary-600/25 text-primary-600 flex-shrink-0 flex justify-center items-center rounded-full">
                                    <i class="ri-group-fill"></i>
                                </span>
                                <div>
                                    <h6 class="font-semibold mb-0.5">{{ $stats->patients['total'] }}</h6>
                                    <span class="text-gray-600 text-sm">Total Patients</span>
                                </div>
                            </div>
                            <p class="text-sm mt-2 text-gray-600">
                                <span class="text-primary-600">+{{ $stats->patients['new_this_week'] }}</span> new this week
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Appointments Card -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-4">
                    <div
                        class="card border-0 p-4 shadow-lg rounded-lg h-full bg-gradient-to-l from-success-600/10 to-bg-white">
                        <div class="card-body p-0">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-12 h-12 bg-success-600/25 text-success-600 flex-shrink-0 flex justify-center items-center rounded-full">
                                    <i class="ri-calendar-check-fill"></i>
                                </span>
                                <div>
                                    <h6 class="font-semibold mb-0.5">{{ $stats->appointments['total'] }}</h6>
                                    <span class="text-gray-600 text-sm">Total Appointments</span>
                                </div>
                            </div>
                            <p class="text-sm mt-2 text-gray-600">
                                <span class="text-success-600">+{{ $stats->appointments['new_this_week'] }}</span> new this
                                week
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Earnings Card -->
                <div class="col-span-12 sm:col-span-6 xl:col-span-4">
                    <div
                        class="card border-0 p-4 shadow-lg rounded-lg h-full bg-gradient-to-l from-warning-600/10 to-bg-white">
                        <div class="card-body p-0">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-12 h-12 bg-warning-600/25 text-warning-600 flex-shrink-0 flex justify-center items-center rounded-full">
                                    <i class="ri-money-dollar-circle-fill"></i>
                                </span>
                                <div>
                                    <h6 class="font-semibold mb-0.5">${{ number_format($stats->earnings['total'], 2) }}</h6>
                                    <span class="text-gray-600 text-sm">Total Earnings</span>
                                </div>
                            </div>
                            <p class="text-sm mt-2 text-gray-600">
                                <span class="text-warning-600">${{ number_format($stats->earnings['monthly'], 2) }}</span>
                                this month
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Earnings Chart -->
                <div class="col-span-12 xl:col-span-8">
                    <div class="card border-0 h-full">
                        <div class="card-header">
                            <h6 class="mb-0 font-bold text-lg">Earnings Overview</h6>
                        </div>
                        <div class="card-body">
                            <div id="earningsChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div class="col-span-12 xl:col-span-4">
                    <div class="card border-0 h-full">
                        <div class="card-header">
                            <h6 class="mb-0 font-bold text-lg">Upcoming Appointments</h6>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-col gap-4">
                                @forelse($stats->appointments['upcoming'] as $appointment)
                                    <div class="flex items-center justify-between p-3  rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <h6 class="text-sm font-semibold">{{ $appointment->patient->name }}
                                                </h6>
                                                <p class="text-xs text-gray-600">
                                                    {{ $appointment->date->format('d M Y, h:i A') }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="p-1.5 text-xs font-medium rounded-full
                                        {{ match ($appointment->status) {
                                            'confirmed' => 'bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100',
                                            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-700 dark:text-yellow-100',
                                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100',
                                            'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-700 dark:text-blue-100',
                                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-100',
                                        } }}">
                                            {{ ucfirst($appointment->status->label()) }}
                                        </span>

                                    </div>
                                @empty
                                    <p class="text-center text-gray-500">No upcoming appointments</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
