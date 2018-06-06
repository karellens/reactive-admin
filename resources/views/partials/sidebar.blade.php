<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="d-flex mt-1 p-3 border-bottom">
        <div class="p2">
            <img src="http://via.placeholder.com/150x150" width="50" class="rounded float-left mr-3" alt="...">
        </div>
        <div class="align-self-center"><h4 class="mb-0">{{ auth()->user()->name }}</h4></div>
    </div>

    <div class="sidebar-sticky">
        {!! $ReactiveAdminSidebar->asUl(['class' => 'nav flex-column']) !!}

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Saved reports</span>
            <a class="d-flex align-items-center text-muted" href="#">
                <span></span>
            </a>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span></span>
                    Current month
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span></span>
                    Last quarter
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span></span>
                    Social engagement
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span></span>
                    Year-end sale
                </a>
            </li>
        </ul>
    </div>
</nav>