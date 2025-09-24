<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('doctor.index') }}" class="sidebar-logo text-center">
            <div class="mx-auto">
                حكماء
            </div>
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="sidebar-menu-group-title">Dashboard</li>
            <li>
                <a href="{{ route('doctor.index') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Appointments</li>
            <li>
                <a href="{{ route('doctor.appointments.index') }}">
                    <iconify-icon icon="solar:calendar-mark-linear" class="menu-icon"></iconify-icon>
                    <span>All Appointments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('doctor.appointments.new') }}">
                    <iconify-icon icon="solar:clock-circle-linear" class="menu-icon"></iconify-icon>
                    <span>Pending Appointments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('doctor.appointments.upcoming') }}">
                    <iconify-icon icon="solar:check-circle-linear" class="menu-icon"></iconify-icon>
                    <span>Upcoming Appointments</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Managment</li>
            <li>
                <a href="{{ route('doctor.patients.index') }}">
                    <iconify-icon icon="solar:user-rounded-linear" class="menu-icon"></iconify-icon>
                    <span>Patients</span>
                </a>
            </li>
            <li>
                <a href="{{ route('doctor.payments.index') }}">
                    <iconify-icon icon="solar:card-linear" class="menu-icon"></iconify-icon>
                    <span>Payments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('doctor.availabilities.index') }}">
                    <iconify-icon icon="solar:calendar-linear" class="menu-icon"></iconify-icon>
                    <span>Availabilities</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
