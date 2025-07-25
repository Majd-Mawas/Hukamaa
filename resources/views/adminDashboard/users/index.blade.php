@extends('adminDashboard.layout.layout')

@php
    $title = 'Users Management';
    $subTitle = 'All Users';
    $script = '<script src="' . asset('assets/js/data-table.js') . '"></script>';
@endphp

@section('content')
    <div>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-12">
            <div class="col-span-12">
                <div class="card border-0 overflow-hidden">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Users</h2>
                            {{-- <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Add New User
                            </a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="selection-table"
                            class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Additional Info
                                    </th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Joined
                                    </th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($user->role === 'doctor' && $user->doctorProfile)
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="{{ $user->doctorProfile->getFirstMediaUrl('profile_picture') ?: asset('assets/images/user.png') }}"
                                                            alt="">
                                                    @elseif($user->role === 'patient' && $user->patientProfile)
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="{{ $user->patientProfile->getFirstMediaUrl('profile_picture') ?: asset('assets/images/user.png') }}"
                                                            alt="">
                                                    @else
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="{{ asset('assets/images/user.png') }}" alt="">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $user->email }}
                                                    </div>
                                                    @if ($user->timezone)
                                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                                            <i class="fas fa-clock mr-1"></i>{{ $user->timezone }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $user->role === 'admin'
                                                    ? 'bg-purple-100 text-purple-800'
                                                    : ($user->role === 'doctor'
                                                        ? 'bg-blue-100 text-blue-800'
                                                        : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($user->role === 'doctor' && $user->doctorProfile)
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    <div class="font-medium">
                                                        {{ $user->doctorProfile->specialization->specialization_name ?? 'Not specified' }}
                                                    </div>
                                                    <div class="text-gray-500 dark:text-gray-400">
                                                        {{ $user->doctorProfile->experience_years }} years exp.</div>
                                                    @if ($user->doctorProfile->license_number)
                                                        <div class="text-xs text-gray-400">License:
                                                            {{ $user->doctorProfile->license_number }}</div>
                                                    @endif
                                                </div>
                                            @elseif($user->role === 'patient' && $user->patientProfile)
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    @if ($user->patientProfile->date_of_birth)
                                                        <div class="text-gray-500 dark:text-gray-400">
                                                            Age:
                                                            {{ \Carbon\Carbon::parse($user->patientProfile->date_of_birth)->age }}
                                                            years
                                                        </div>
                                                    @endif
                                                    @if ($user->patientProfile->phone)
                                                        <div class="text-xs text-gray-400">
                                                            {{ $user->patientProfile->phone }}</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">No additional
                                                    info</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-1">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $user->status === 'active'
                                                        ? 'bg-green-100 text-green-800'
                                                        : ($user->status === 'inactive'
                                                            ? 'bg-gray-100 text-gray-800'
                                                            : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($user->status ?? 'active') }}
                                                </span>
                                                @if ($user->is_verified)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Verified
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Unverified
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->created_at->format('M d, Y') }}
                                            <div class="text-xs">{{ $user->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                            No users found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
