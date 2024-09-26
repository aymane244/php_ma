<nav class="navbar navbar-expand-lg">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-itemes-center">
            <a class="navbar-brand text-color" href="/">
                <i class="fa-solid fa-circle-check check-color"></i>
                <strong><i><u>mysecurityplan</u></i></strong>
            </a>
            <div class="nav-item user-login mt-2 ms-3">
                <a class="nav-link text-color active fs-3" aria-current="page" href="/user/login"><i class="fa-regular fa-user"></i></a>
            </div>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            </ul>
            <ul class="navbar-nav">
                <?php if(is_user()) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= capital(user()->user_first_name)." ".capital(user()->user_last_name) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/user/manager/dashboard">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-transparent btn-no-padding">
                                        <span>Abmelden</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php elseif(is_worker()) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= capital(user()->user_first_name)." ".capital(user()->user_last_name) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/user/worker/dashboard">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout_worker" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-transparent btn-no-padding">
                                        <span>Abmelden</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php elseif(is_admin()) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= admin()->first_name ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/admin/dashboard">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout_admin" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-transparent btn-no-padding">
                                        <span>Abmelden</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item login-link">
                        <a class="nav-link text-color active" aria-current="page" href="/user/login">Anmelden</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-color active" aria-current="page" href="/user/signup">Registrieren</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>