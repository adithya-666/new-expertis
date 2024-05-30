<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                     <a class="sidebar-link sidebar-link" href="{{ url('absensi') }}"
                        aria-expanded="false"><i class="bi bi-calendar2-check"></i><span
                            class="hide-menu">Presence</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ec') }}"
                        aria-expanded="false"><i class="bi bi-clipboard-fill"></i><span
                            class="hide-menu">Expenses Claim
                        </span></a>
                </li>
                <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('bt') }}"
                        aria-expanded="false"><i class="bi bi-briefcase"></i><span
                            class="hide-menu">Business Trip
                        </span></a>
                </li>
                @can('access', ['Manager'])   
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Page Manager</span></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow mb-1" href="javascript:void(0)"
                        aria-expanded="false"><i class="bi bi-person-lines-fill"></i><span
                            class="hide-menu">Manager </span></a>
                    <ul aria-expanded="false" class="collapse  first-level base-level-line">
                        <li class="sidebar-item"><a href="{{ url('absensi/presence-manager') }}" class="sidebar-link"><span
                                    class="hide-menu">Presence
                                </span></a>
                        </li>
                        <li class="sidebar-item"><a href="{{ url('ec/expenses-claims-manager') }}" class="sidebar-link"><span
                                    class="hide-menu"> Expenses Claims
                                </span></a>
                        </li>
                        <li class="sidebar-item"><a href="form-checkbox-radio.html" class="sidebar-link"><span
                                    class="hide-menu">Business Trip
                                </span></a>
                        </li>
                    </ul>
                </li>
                @endcan
                @can('department', ['Human Resource', 'Manager'])   
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Page Human Resource</span></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow mb-1" href="javascript:void(0)"
                        aria-expanded="false"><i class="bi bi-person-badge"></i><span
                            class="hide-menu">HRD </span></a>
                    <ul aria-expanded="false" class="collapse  first-level base-level-line">
                        <li class="sidebar-item"><a href="{{ url('absensi/presence-hrd') }}" class="sidebar-link"><span
                                    class="hide-menu">Presence
                                </span></a>
                        </li>
                        <li class="sidebar-item"><a href="{{ url('ec/expenses-claims-hrd') }}" class="sidebar-link"><span
                                    class="hide-menu"> Expenses Claims
                                </span></a>
                        </li>
                        <li class="sidebar-item"><a href="form-checkbox-radio.html" class="sidebar-link"><span
                                    class="hide-menu">Business Trip
                                </span></a>
                        </li>
                    </ul>
                </li>
                @endcan
                @can('department', ['Finance Departement', 'Manager'])   
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Page Finance</span></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow mb-1" href="javascript:void(0)"
                        aria-expanded="false"><i class="bi bi-person-badge"></i><span
                            class="hide-menu">HRD </span></a>
                    <ul aria-expanded="false" class="collapse  first-level base-level-line">
                        <li class="sidebar-item"><a href="{{ url('absensi/presence-hrd') }}" class="sidebar-link"><span
                                    class="hide-menu">Presence
                                </span></a>
                        </li>
                        <li class="sidebar-item"><a href="{{ url('ec/expenses-claims-hrd') }}" class="sidebar-link"><span
                                    class="hide-menu"> Expenses Claims
                                </span></a>
                        </li>
                        <li class="sidebar-item"><a href="form-checkbox-radio.html" class="sidebar-link"><span
                                    class="hide-menu">Business Trip
                                </span></a>
                        </li>
                    </ul>
                </li>
                @endcan
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>