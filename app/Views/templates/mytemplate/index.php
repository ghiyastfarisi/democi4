<?php if (!isset($_COOKIE['auth']) && null!=session('auth')) { setcookie('auth', json_encode(session('auth')), time()+1*60*60, '/'); } ?>

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
        <?= $this->include(ViewPath('base/header')) ?>
    </div>

    <div class="column-container">
        <div class="columns is-gapless is-desktop">
            <div id="menu-container" class="menu-container px-3 has-background-white sidebar-hidden">
                <?= $this->include(ViewPath('base/sidebar')) ?>
            </div>
            <div class="page-container p-3 container is-fluid">
                <section class="section py-3 px-3">
                    <h1 class="title is-4"> <?= PrintArgs($args, '_PageSectionTitle') ?> </h1>
                    <h2 class="subtitle is-6">
                        <?= PrintArgs($args, '_PageSectionSubTitle') ?>
                    </h2>
                </section>
                <?= (isset($_Pages)) ? $this->include(ViewPath($_Pages)) : '' ?>
            </div>
        </div>
    </div>
    <?= (isset($_LoadJS)) ? $this->include(ViewPath('base/js')) : '' ?>
</body>
</html>