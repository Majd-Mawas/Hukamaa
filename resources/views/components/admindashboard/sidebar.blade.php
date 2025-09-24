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
                <a href="{{ route('admin.index') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">User Management</li>
            <li>
                <a href="{{ route('admin.users.index') }}">
                    <iconify-icon icon="solar:users-group-rounded-linear" class="menu-icon"></iconify-icon>
                    <span>All Users</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Doctors</li>

            <li>
                <a href="{{ route('admin.doctors.index') }}">
                    <iconify-icon icon="healthicons:doctor" class="menu-icon"></iconify-icon>
                    <span>Doctors</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.doctors.doctorApprovals') }}">
                    <iconify-icon icon="solar:checklist-linear" class="menu-icon"></iconify-icon>
                    <span>Pending Doctors</span>
                </a>
            </li>


            <li class="sidebar-menu-group-title">Payments</li>
            <li>
                <a href="{{ route('admin.payments.index') }}">
                    <iconify-icon icon="solar:card-linear" class="menu-icon"></iconify-icon>
                    <span>Payments</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.payments.pending') }}">
                    <iconify-icon icon="solar:clock-circle-linear" class="menu-icon"></iconify-icon>
                    <span>Pending Payments</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Appointments</li>
            <li>
                <a href="{{ route('admin.appointments.index') }}">
                    <iconify-icon icon="solar:calendar-mark-linear" class="menu-icon"></iconify-icon>
                    <span>All Appointments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.appointments.pending') }}">
                    <iconify-icon icon="solar:clock-circle-linear" class="menu-icon"></iconify-icon>
                    <span>Pending Appointments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.appointments.completed') }}">
                    <iconify-icon icon="solar:check-circle-linear" class="menu-icon"></iconify-icon>
                    <span>Completed Appointments</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Managment</li>
            <li>
                <a href="{{ route('admin.patients.index') }}">
                    <iconify-icon icon="solar:user-rounded-linear" class="menu-icon"></iconify-icon>
                    <span>Patients</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.coverageAreas.index') }}">
                    <iconify-icon icon="solar:map-point-linear" class="menu-icon"></iconify-icon>
                    <span>Coverage Areas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.specializations.index') }}">
                    <iconify-icon icon="solar:stethoscope-linear" class="menu-icon"></iconify-icon>
                    <span>Specializations</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.allergies.index') }}">
                    <iconify-icon icon="mdi:allergy" class="menu-icon"></iconify-icon>
                    <span>Allergies</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.chronicConditions.index') }}">
                    <iconify-icon icon="healthicons:health-data-sync" class="menu-icon"></iconify-icon>
                    <span>Chronic Conditions</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
