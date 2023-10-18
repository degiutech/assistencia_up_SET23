<div class="d-flex justify-content-center col-12" style="position: absolute; opacity: 0.5">
    <div style="font-size: 50px; color: #2F4F4F">

        <div class="d-none d-md-none d-lg-block">Deputado Estadual Fabinho</div>
        <div class="d-flex justify-content-center">
            <h4 class="d-none d-md-none d-lg-block">Gestão de Assistência ao Cidadão</h4>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center col-12 d-block d-sm-none" style="opacity: 0.5">
    <h4>Deputado Estadual Fabinho</h4>
</div>

<div class="d-flex justify-content-center col-12 d-block d-sm-none" style="opacity: 0.5">
    <h6>Gestão de Assistência ao Cidadão</h6>
</div>

<div class="d-flex align-items-center justify-content-center" style="height: 100vh; width: 100vw;">

    <div class="card col-md-3">

        <div class="card-body">
            <h2>SISTEMA SUSPENSO</h2>
            Efetue o pagamento da(s) parcela(s) vencida(s) para reestabelecer o Sistema

            <div id="wallet_container">
            </div>
            <script>
                const mp = new MercadoPago('TEST-439cec97-9e41-4fe0-99b0-c38a58e6ce45', {
                    locale: 'pt-BR'
                });

                mp.bricks().create("wallet", "wallet_container", {
                    initialization: {
                        preferenceId: "<PREFERENCE_ID>",
                    },
                });
            </script>


        </div>

    </div>
</div>