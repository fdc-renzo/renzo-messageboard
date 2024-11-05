<!-- app/View/Layouts/guest.ctp -->

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">

    <title><?php echo h($title); ?></title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo Router::url('/assets/bootstrap/css/bs.min.css', true); ?>" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Message Board Guest CSS -->
    <link href="<?php echo ($this->request->params['action'] === 'login' || $this->request->params['action'] === 'register') ? Router::url('/assets/guest/css/auth.css', true) : ''; ?>" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="<?php echo Router::url('/assets/user/js/jquery.min.js', true); ?>"></script>
    <script src="<?php echo Router::url('/assets/bootstrap/js/bs.bundle.min.js', true); ?>"></script>

</head>

<body class="<?php echo ($this->request->params['action'] === 'login'  || $this->request->params['action'] === 'register') ? 'text-center' : ''; ?>">

    <main class="<?php echo ($this->request->params['action'] === 'login' || $this->request->params['action'] === 'register') ? 'form-auth' : ''; ?>">
        <div class="container py-4">
            <?php echo $this->element('Guest/header'); ?>

            <div class=" container">
                <?php echo $this->fetch('content'); ?>
            </div>

            <?php echo $this->element('Guest/footer'); ?>


        </div>
    </main>

</body>

</html>