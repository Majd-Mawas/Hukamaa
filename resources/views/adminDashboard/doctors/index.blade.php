@extends('adminDashboard.layout.layout')

@php
    $title = 'Basic Table';
    $subTitle = 'Basic Table';
    $script = '<script src="' . asset('assets/js/data-table.js') . '"></script>';
@endphp
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- <div class="flex justify-between items-center mb-6">
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

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
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
                                Experience</th>
                            <th
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
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
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $doctor->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $doctor->specialization->specialization_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $doctor->experience_years }} years
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $doctor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($doctor->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.doctors.show', ['doctor' => $doctor->id]) }}"
                                        class="text-primary-600 hover:text-primary-900 mr-3">View</a>
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
@endsection
