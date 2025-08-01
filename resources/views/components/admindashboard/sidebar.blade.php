<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('doctor.index') }}" class="sidebar-logo text-center">
            {{-- <img src="{{ asset('assets/comingSoon/img/hukamaa-logo.png') }}" alt="site logo" class="light-logo mx-auto">
            <img src="{{ asset('assets/comingSoon/img/hukamaa-logo.png') }}" alt="site logo" class="dark-logo mx-auto">
            <img src="{{ asset('assets/comingSoon/img/hukamaa-logo.png') }}" alt="site logo" class="logo-icon mx-auto"> --}}
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

            {{-- <li class="sidebar-menu-group-title">Patients</li> --}}

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
            {{-- <li class="sidebar-menu-group-title">Specializations</li> --}}


            {{-- <li class="sidebar-menu-group-title">Coverage Areas</li> --}}

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
            {{-- <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> AI</a>
                    </li>
                    <li>
                        <a href="{{ route('index2') }}"><i
                                class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> CRM</a>
                    </li>
                    <li>
                        <a href="{{ route('index3') }}"><i class="ri-circle-fill circle-icon text-info-600 w-auto"></i>
                            eCommerce</a>
                    </li>
                    <li>
                        <a href="{{ route('index4') }}"><i
                                class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Cryptocurrency</a>
                    </li>
                    <li>
                        <a href="{{ route('index5') }}"><i
                                class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Investment</a>
                    </li>
                    <li>
                        <a href="{{ route('index6') }}"><i
                                class="ri-circle-fill circle-icon text-purple-600 w-auto"></i> LMS / Learning System</a>
                    </li>
                    <li>
                        <a href="{{ route('index7') }}"><i class="ri-circle-fill circle-icon text-info-600 w-auto"></i>
                            NFT & Gaming</a>
                    </li>
                    <li>
                        <a href="{{ route('index8') }}"><i
                                class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Medical</a>
                    </li>
                    <li>
                        <a href="{{ route('index9') }}"><i
                                class="ri-circle-fill circle-icon text-purple-600 w-auto"></i> Analytics</a>
                    </li>
                </ul>
            </li> --}}
            @if (env('APP_DASHBOARD'))
                <li class="sidebar-menu-group-title">Application</li>
                <li>
                    <a href="{{ route('email') }}">
                        <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                        <span>Email</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('chatMessage') }}">
                        <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                        <span>Chat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('calendarMain') }}">
                        <iconify-icon icon="solar:calendar-outline" class="menu-icon"></iconify-icon>
                        <span>Calendar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kanban') }}">
                        <iconify-icon icon="material-symbols:map-outline" class="menu-icon"></iconify-icon>
                        <span>Kanban</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                        <span>Invoice</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('invoiceList') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                        </li>
                        <li>
                            <a href="{{ route('invoicePreview') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Preview</a>
                        </li>
                        <li>
                            <a href="{{ route('invoiceAdd') }}"><i
                                    class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Add new</a>
                        </li>
                        <li>
                            <a href="{{ route('invoiceEdit') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Edit</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:ai-brain-03" class="menu-icon"></iconify-icon>
                        <span>Ai Application</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('textGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Text Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('codeGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Code Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('imageGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Image Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('voiceGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Voice Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('videoGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Video Generator</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:bitcoin-circle" class="menu-icon"></iconify-icon>
                        <span>Crypto Currency</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('wallet') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Wallet</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-group-title">UI Elements</li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                        <span>Components</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('typography') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Typography</a>
                        </li>
                        <li>
                            <a href="{{ route('colors') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Colors</a>
                        </li>
                        <li>
                            <a href="{{ route('button') }}"><i
                                    class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Button</a>
                        </li>
                        <li>
                            <a href="{{ route('dropdown') }}"><i
                                    class="ri-circle-fill circle-icon text-purple-600  dark:text-purple-400 w-auto"></i>
                                Dropdown</a>
                        </li>
                        <li>
                            <a href="{{ route('alert') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Alerts</a>
                        </li>
                        <li>
                            <a href="{{ route('card') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Card</a>
                        </li>
                        <li>
                            <a href="{{ route('carousel') }}"><i
                                    class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Carousel</a>
                        </li>
                        <li>
                            <a href="{{ route('avatar') }}"><i
                                    class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Avatars</a>
                        </li>
                        <li>
                            <a href="{{ route('progress') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Progress bar</a>
                        </li>
                        <li>
                            <a href="{{ route('tabs') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Tab & Accordion</a>
                        </li>
                        <li>
                            <a href="{{ route('pagination') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Pagination</a>
                        </li>
                        <li>
                            <a href="{{ route('badges') }}"><i
                                    class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Badges</a>
                        </li>
                        <li>
                            <a href="{{ route('tooltip') }}"><i
                                    class="ri-circle-fill circle-icon dark:text-purple-400 w-auto"></i> Tooltip &
                                Popover</a>
                        </li>
                        <li>
                            <a href="{{ route('videos') }}"><i
                                    class="ri-circle-fill circle-icon text-cyan-600 w-auto"></i> Videos</a>
                        </li>
                        <li>
                            <a href="{{ route('starRating') }}"><i
                                    class="ri-circle-fill circle-icon text-[#7f27ff] w-auto"></i> Star Ratings</a>
                        </li>
                        <li>
                            <a href="{{ route('tags') }}"><i
                                    class="ri-circle-fill circle-icon text-[#8252e9] w-auto"></i> Tags</a>
                        </li>
                        <li>
                            <a href="{{ route('list') }}"><i
                                    class="ri-circle-fill circle-icon text-[#e30a0a] w-auto"></i> List</a>
                        </li>
                        <li>
                            <a href="{{ route('calendar') }}"><i
                                    class="ri-circle-fill circle-icon text-yellow-400 w-auto"></i> Calendar</a>
                        </li>
                        <li>
                            <a href="{{ route('radio') }}"><i
                                    class="ri-circle-fill circle-icon text-orange-500 w-auto"></i> Radio</a>
                        </li>
                        <li>
                            <a href="{{ route('switch') }}"><i
                                    class="ri-circle-fill circle-icon text-pink-600 w-auto"></i> Switch</a>
                        </li>
                        <li>
                            <a href="{{ route('imageUpload') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Upload</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                        <span>Forms</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('form') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Input Forms</a>
                        </li>
                        <li>
                            <a href="{{ route('formLayout') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Input Layout</a>
                        </li>
                        <li>
                            <a href="{{ route('formValidation') }}"><i
                                    class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Form Validation</a>
                        </li>
                        <li>
                            <a href="{{ route('wizard') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Form Wizard</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="mingcute:storage-line" class="menu-icon"></iconify-icon>
                        <span>Table</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('tableBasic') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Basic Table</a>
                        </li>
                        <li>
                            <a href="{{ route('tableData') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Data Table</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:pie-chart-outline" class="menu-icon"></iconify-icon>
                        <span>Chart</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('lineChart') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Line Chart</a>
                        </li>
                        <li>
                            <a href="{{ route('columnChart') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Column Chart</a>
                        </li>
                        <li>
                            <a href="{{ route('pieChart') }}"><i
                                    class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Pie Chart</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('widgets') }}">
                        <iconify-icon icon="fe:vector" class="menu-icon"></iconify-icon>
                        <span>Widgets</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Users</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('usersList') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List</a>
                        </li>
                        <li>
                            <a href="{{ route('usersGrid') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Users Grid</a>
                        </li>
                        <li>
                            <a href="{{ route('addUser') }}"><i
                                    class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Add User</a>
                        </li>
                        <li>
                            <a href="{{ route('viewProfile') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> View Profile</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-group-title">Application</li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="simple-line-icons:vector" class="menu-icon"></iconify-icon>
                        <span>Authentication</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('signin') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Sign In</a>
                        </li>
                        <li>
                            <a href="{{ route('signup') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Sign Up</a>
                        </li>
                        <li>
                            <a href="{{ route('forgotPassword') }}"><i
                                    class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Forgot Password</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('gallery') }}">
                        <iconify-icon icon="solar:gallery-wide-linear" class="menu-icon"></iconify-icon>
                        <span>Gallery</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pricing') }}">
                        <iconify-icon icon="hugeicons:money-send-square" class="menu-icon"></iconify-icon>
                        <span>Pricing</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('faq') }}">
                        <iconify-icon icon="mage:message-question-mark-round" class="menu-icon"></iconify-icon>
                        <span>FAQs.</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pageError') }}">
                        <iconify-icon icon="streamline:straight-face" class="menu-icon"></iconify-icon>
                        <span>404</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('termsCondition') }}">
                        <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                        <span>Terms & Conditions</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                        <span>Settings</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('company') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Company</a>
                        </li>
                        <li>
                            <a href="{{ route('notification') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Notification</a>
                        </li>
                        <li>
                            <a href="{{ route('notificationAlert') }}"><i
                                    class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Notification Alert</a>
                        </li>
                        <li>
                            <a href="{{ route('theme') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Theme</a>
                        </li>
                        <li>
                            <a href="{{ route('currencies') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Currencies</a>
                        </li>
                        <li>
                            <a href="{{ route('language') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Languages</a>
                        </li>
                        <li>
                            <a href="{{ route('paymentGateway') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Payment Gateway</a>
                        </li>

                    </ul>
                </li>
            @endif

        </ul>
    </div>
</aside>
