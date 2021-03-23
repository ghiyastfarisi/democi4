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
</head>
<body>
    <?= $this->include(ViewPath('base/header')) ?>

    <h1>Holla this is mytemplate index</h1>
    <?= isset($username) ? $username : '' ?>

    <?= (isset($_Pages)) ? $this->include(ViewPath($_Pages)) : '' ?>

    <?= $this->include(ViewPath('base/footer')) ?>

    <?= (isset($_LoadJS)) ? $this->include(ViewPath('base/js')) : '' ?>
</body>
</html>