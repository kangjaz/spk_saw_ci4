<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied !!!</title>
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/all.min.css'); ?>">
    <style>
        *,
        ::before,
        ::after {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0px;
            padding: 0px;
        }

        body {
            background-color: #efefef;
        }

        .icon {
            font-size: 5rem;
            margin-top: 10%;
            margin-bottom: 10px;
        }

        .icon i {
            background: #c12a00;
            padding: 30px 36px;
            color: #fff;
            border-radius: 100px;
        }

        .display-4 {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <div class="icon">
            <i class="fas fa-hand-paper"></i>
        </div>
        <h4 class="display-4 mb-2">ACCESS DENIED !!!</h4>
        <p class="fs-5">Anda dilarang untuk mengakses halaman ini.</p>
        <div class="mt-5">
            <button type="button" class="btn btn-lg btn-primary" onclick="window.history.back()">Kembali</button>
        </div>
    </div>
</body>

</html>