<div id="sidebar" class="col-md-2 d-md-block sidebar py-3">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item"> <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#usersSubmenu"> <i class="bi bi-person"></i> Users </a>
                <div id="usersSubmenu" class="collapse">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"> <a class="nav-link" href="{{route('admin.users')}}"><i class="bi bi-people"></i> All Users</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{route('admin.dashboard.cancelled-subscriptions-logs')}}"><i class="bi bi-person-x-fill"></i> Cancelled Logs</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{route('admin.dashboard.deleted-accounts-logs')}}"><i class="bi bi-person-dash-fill"></i> Deleted Logs</a> </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item"> <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#anotherMenuSubmenu"> <i class="bi bi-grid"></i> Another Menu </a>
                <div id="anotherMenuSubmenu" class="collapse">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"> <a class="nav-link" href="#">Option 1</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#">Option 2</a> </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item"> <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#systemSubmenu"> <i class="bi bi-wrench"></i> System </a>
                <div id="systemSubmenu" class="collapse">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"> <a class="nav-link" href="/admin/logs"><i class="bi bi-list"></i> Logs</a> </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
