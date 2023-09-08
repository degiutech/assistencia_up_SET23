<!--  style="position: absolute; width: 100%; bottom: 0;" -->

<!-- <footer class="footer p-3 text-white cor-bg" style="position: absolute; bottom: 0; width: 100%;">  -->
<!-- <footer class="footer p-3 text-white cor-bg" style="position: relative; bottom: 0; width: 100%;">  -->
<footer class="footer p-3 text-white cor-bg" style="float: bottom; margin-top: 150px;">


    <div class="container">

        <h5>Vídeos Tutoriais</h5>

        <!-- Admin -->
        <?php if ($_SESSION['user']['acesso'] == 'Administração') { ?>
            <div class="row">

                <div class="col-md-4">
                    <div>
                        <a href="https://youtu.be/utIqNgbMNCw" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Assistência - do registro à finalização</a>
                    </div>
                    <div>
                        <a href="https://youtu.be/xjams-o_iz0" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Gerenciamento de Assistências</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <a href="https://youtu.be/LqXb2lJZ688" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Painel do Administrador</a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Supervisão -->
        <?php if ($_SESSION['user']['acesso'] == 'Supervisão') { ?>
            <div class="row">

                <div class="col-md-4">
                    <div>
                        <a href="https://youtu.be/utIqNgbMNCw" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Assistência - do registro à finalização</a>
                    </div>
                    <div>
                        <a href="https://youtu.be/xjams-o_iz0" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Gerenciamento de Assistências</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <a href="https://youtu.be/7aUWsnT79ac" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Painel do Supervisor</a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Coordenação -->
        <?php if ($_SESSION['user']['acesso'] == 'Coordenadoria') { ?>
            <div class="row">

                <div class="col-md-4">
                    <div>
                        <a href="https://youtu.be/utIqNgbMNCw" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Assistência - do registro à finalização</a>
                    </div>
                    <div>
                        <a href="https://youtu.be/xjams-o_iz0" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Gerenciamento de Assistências</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <!-- <a href="https://youtu.be/LqXb2lJZ688" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Painel do Administrador</a> -->
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Representante -->
        <?php if ($_SESSION['user']['acesso'] == 'Representante') { ?>
            <div class="row">

                <div class="col-md-4">
                    <div>
                        <a href="https://youtu.be/utIqNgbMNCw" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Assistência - do registro à finalização</a>
                    </div>
                    <div>
                        <a href="https://youtu.be/xjams-o_iz0" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Gerenciamento de Assistências</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <!-- <a href="https://youtu.be/LqXb2lJZ688" class="link-light" style="text-decoration: none;" target="_blank" rel="noopener noreferrer">Painel do Administrador</a> -->
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="col-12 mt-5">
            <small>
                Versão do sistema: <?= APP_VERSAO ?>
                <div class="border-top mt-3">
                    &COPY; 2023 - <?= date('Y') ?> degiutech@gmail.com
                </div>
            </small>
        </div>

    </div>
</footer>