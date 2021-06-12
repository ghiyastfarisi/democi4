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
        <div class="container ">
            <div class="columns is-gapless is-centered is-vcentered">
                <div class="column is-half is-offset-one-quarter">
                    <div id="vuec"></div>
                </div>
            </div>
        </div>
    </div>

    <?= (isset($_LoadJS)) ? $this->include(ViewPath('base/js')) : '' ?>
</body>
</html>