<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= (!isset($_PageTitle)) ? 'undefined $_PageTitle' : $_PageTitle ?>
    </title>
    <?= (isset($_LoadCSS)) ? $this->include(ViewPath('base/css')) : '' ?>
    <script>
        const BASE_URL = window.location.origin
        const BASE_API_URL = BASE_URL + '/api/'
    </script>
</head>
<body>
    <div class="header-container has-background-white">
        <?= $this->include(ViewPath('base/login_header')) ?>
    </div>

    <div class="column-container">
        <section class="hero is-link" style="margin-top:100px;">
            <div class="hero-body has-text-centered">
                <p class="title">
                    Verifikasi Berhasil
                </p>
                <p class="subtitle mt-4">
                    hore, email kamu sudah diverifikasi dan aktif
                </p>
                <p>
                    <a href="<?=base_url('web/login')?>" class="button is-large is-primary is-light is-rounded">Kembali ke Beranda</a>
                </p>
                <span id="countdown" class="mb-2"></span>
            </div>
        </section>
    </div>

    <script>
        var timeleft = 3;
        var downloadTimer = setInterval(function(){
        if(timeleft <= 0){
            clearInterval(downloadTimer);
            window.location.replace('<?=base_url('web/login')?>')
        } else {
            document.getElementById("countdown").innerHTML = timeleft + " seconds remaining";
        }
        timeleft -= 1;
        }, 1000);
    </script>

    <?= (isset($_LoadJS)) ? $this->include(ViewPath('base/js')) : '' ?>
</body>
</html>