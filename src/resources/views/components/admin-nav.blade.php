<nav class="admin-nav navbar-expand-md navbar navbar-dark">
    <a class="navbar-brand" href="{{ route('admin.index') }}">Administration</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navigation">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.aircrafts.index') }}">Aircrafts</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.airports.index') }}">Airports</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">Unconfirmed orders</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.uncompleted') }}">Uncompleted orders</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.completed') }}">Completed orders</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.routes.common') }}">All routes</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">Predefined routes</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">Back to website</a></li>
        </ul>
    </div>
</nav>