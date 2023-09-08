<?php $dg_user = $_SESSION['user'] ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL ?>/degiutech/admin0">DegiuTech</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= URL ?>/degiutech/admin0">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Faqs
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= URL ?>/degiutech/faqs">Não respondidas</a></li>
                        <li><a class="dropdown-item" href="#">Respondidas</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= URL ?>/dgassistencia/index">Assistência ao Cidadão</a>
                </li>
            </ul>
            <!-- <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form> -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle bg-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Olá, <?= $dg_user['nome'] ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= URL ?>/dguser/meus_dados">Meus Dados</a></li>
                    <li><a class="dropdown-item" href="<?= URL ?>/dguser/trocar_senha">Trocar Senha</a></li>
                    <li><a class="dropdown-item" href="<?= URL ?>/dguser/logof">Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>