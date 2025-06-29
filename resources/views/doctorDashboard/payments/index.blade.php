@extends('doctorDashboard.layout.layout')

@php
    $title = 'Payment Management';
    $subTitle = 'A list of all payments in the system';
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
                        <h6 class="card-title mb-0 text-lg">Payments</h6>
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
                                        Appointment</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Patient</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Doctor</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date</th>
                                    {{-- <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Invoice</th> --}}
                                    @if (isset($status) && $status === \Modules\PaymentManagement\App\Enums\PaymentStatus::PENDING->value)
                                        <th
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            #{{ $payment->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            @if ($payment->appointment)
                                                <a href="{{ route('admin.appointments.show', $payment->appointment) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    #{{ $payment->appointment->id }}
                                                </a>
                                            @else
                                                <span class="text-gray-500">No Appointment</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $payment->patient->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $payment->doctor->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            ${{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status === \Modules\PaymentManagement\App\Enums\PaymentStatus::APPROVED->value
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($payment->status === \Modules\PaymentManagement\App\Enums\PaymentStatus::REJECTED->value
                                                        ? 'bg-red-100 text-red-800'
                                                        : 'bg-yellow-100 text-yellow-400') }}">
                                                {{ $payment->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $payment->created_at->format('M d, Y') }}</td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            @if ($payment?->appointment?->getFirstMedia('payment_invoices'))
                                                <a href="{{ $payment?->appointment?->getFirstMedia('payment_invoices')->getUrl() }}"
                                                    target="_blank" class="text-blue-600 hover:text-blue-900">
                                                    View Invoice
                                                </a>
                                            @else
                                                <span class="text-gray-500">No Invoice</span>
                                            @endif
                                        </td> --}}
                                        @if (isset($status) && $status === \Modules\PaymentManagement\App\Enums\PaymentStatus::PENDING->value)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('admin.payments.approve', $payment) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-900 mr-4">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.payments.reject', $payment) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Reject</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                            No payments found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
