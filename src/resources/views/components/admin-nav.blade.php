<nav class="admin-nav navbar-expand-md navbar navbar-dark">
    <a class="navbar-brand" href="{{ route('admin.index') }}">Administrace</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navigation">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.aircrafts.index') }}">Letadla</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.airports.index') }}">Letiště</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">Nepotvrzené objednávky</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.uncompleted') }}">Nedokončené objednávky</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.completed') }}">Dokončené objednávky</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.routes.common') }}">Běžné trasy</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.routes.index') }}">Předdefinované trasy</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">Zpět na web</a></li>
        </ul>
    </div>
</nav>