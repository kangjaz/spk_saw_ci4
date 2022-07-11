<?php
$uri = service('uri');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/sweetalert.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('datatable/css/dataTables.bootstrap5.min.css'); ?>">
</head>

<body>
    <header>
        <div class="header-content">
            <img src="<?= base_url('img/logo.png'); ?>" alt="logo" class="img-header">
            <span class="text-header">SPK metode SAW</span>
        </div>
    </header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="ms-auto">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <hr class="dropdown-divider">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'dashboard') ? 'active' : ''; ?>" href="<?= base_url('/dashboard'); ?>">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'alternative') ? 'active' : ''; ?>" href="<?= base_url('/alternative'); ?>">
                            <i class="fas fa-users"></i> Alternative
                        </a>
                    </li>
                    <?php if (session()->get('role') == 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'kriteria') ? 'active' : ''; ?>" href="<?= base_url('/kriteria'); ?>">
                                <i class="fas fa-list"></i> Kriteria
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'sub-kriteria') ? 'active' : ''; ?>" href="<?= base_url('/sub-kriteria'); ?>">
                                <i class="fas fa-list-alt"></i> Sub Kriteria
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'bobot') ? 'active' : ''; ?>" href="<?= base_url('/bobot'); ?>">
                                <i class="fas fa-balance-scale"></i> Bobot
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'penilaian') ? 'active' : ''; ?>" href="<?= base_url('/penilaian'); ?>">
                            <i class="fas fa-balance-scale-right"></i> Penilaian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'hasil') ? 'active' : ''; ?>" href="<?= base_url('/hasil'); ?>">
                            <i class="fas fa-clipboard-check"></i> Hasil
                        </a>
                    </li>
                    <?php if (session()->get('role') == 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (strtolower($uri->getSegment(1)) == 'user') ? 'active' : ''; ?>" href="<?= base_url('/user'); ?>">
                                <i class="fas fa-users"></i> Manajemen User
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?= session()->get('user'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('profil'); ?>">Profil</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('change-password'); ?>">Ganti Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><button type="button" class="dropdown-item" href="#" onclick="signOut()">Log Out</button></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content mb-2">
        <?= $this->renderSection('content'); ?>
    </div>

    <script src="<?= base_url('js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('js/popper.js'); ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('js/sweetalert.min.js'); ?>"></script>

    <script>
        function signOut() {
            swal({
                title: 'Sign Out?',
                text: 'Apakah anda yakin ingin keluar dari aplikasi?',
                type: 'warning',
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary',
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Ya, Keluar!',
                closeOnConfirm: false
            }, function() {
                location.href = '<?= base_url('logout'); ?>';
            });
        }
        $('.alert-message').delay(3000).slideUp('slow');
    </script>

    <?php
    $page = strtolower($uri->getSegment(1));
    $arr_page = ['alternative', 'kriteria', 'sub-kriteria', 'user'];

    if (in_array($page, $arr_page)) :
        switch ($page) {
            case 'alternative':
                $UrlDatatable = base_url('alternative/ajax-list');
                $orderLess = 3;
                $urlDelete = base_url('alternative/delete');
                break;
            case 'kriteria':
                $UrlDatatable = base_url('kriteria/ajax-list');
                $orderLess = 4;
                $urlDelete = base_url('kriteria/delete');
                break;
            case 'sub-kriteria':
                $UrlDatatable = base_url('sub-kriteria/ajax-list');
                $orderLess = 4;
                $urlDelete = base_url('sub-kriteria/delete');
                break;
            case 'user':
                $UrlDatatable = base_url('user/ajax-list');
                $orderLess = 4;
                $urlDelete = base_url('user/delete');
                break;
        }
    ?>
        <script src="<?= base_url('datatable/js/jquery.dataTables.min.js'); ?>"></script>
        <script src="<?= base_url('datatable/js/dataTables.bootstrap5.min.js'); ?>"></script>

        <script>
            var table = $('#table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?= $UrlDatatable; ?>",
                    "type": "POST",
                    "data": {
                        csrf_token: $('input[name=<?= csrf_token(); ?>').val()
                    },
                    "data": function(data) {
                        data.csrf_token = $('input[name=<?= csrf_token(); ?>]').val();
                    },
                    "dataSrc": function(response) {
                        $('input[name=<?= csrf_token(); ?>]').val(response.csrf_token);
                        return response.data;
                    }
                },
                "columnDefs": [{
                    "targets": [0, <?= $orderLess; ?>],
                    "orderable": false,
                }],
            });

            function sweetDelete(id) {
                if (id == null || id == '') {
                    return false;
                }

                swal({
                    title: 'Hapus Data ini?',
                    text: 'Data yang terhapus tidak dapat dipulihkan',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonClass: 'btn-secondary',
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'Ya, Hapus!',
                    closeOnConfirm: false
                }, function() {
                    var csrfName = '<?= csrf_token(); ?>';
                    var csrfHash = $('input[name=csrf_token]').val();

                    $.ajax({
                        url: "<?= $urlDelete; ?>",
                        method: "POST",
                        data: {
                            id: id,
                            [csrfName]: csrfHash
                        },
                        success: function(obj) {

                            var a = $.parseJSON(obj);
                            //update token
                            $('input[name=<?= csrf_token(); ?>]').val(a.token);

                            if (a.status == 'success') {
                                swal({
                                    title: "Success!",
                                    text: "Data berhasil dihapus",
                                    type: "success",
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 2000
                                }, function() {
                                    swal.close();
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: "Data gagal dihapus",
                                    type: "error",
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 2000
                                }, function() {
                                    swal.close();
                                });
                            }
                            table.ajax.reload();
                        }
                    });
                });
            }
        </script>
    <?php endif; ?>

    <?php if (strtolower($uri->getSegment(1)) == 'alternative') : ?>
        <script>
            function getData(id) {
                if (id == null || id == '') {
                    return false;
                }

                var csrfName = '<?= csrf_token(); ?>';
                var csrfHash = $('input[name=csrf_token]').val();

                $.ajax({
                    url: '<?= base_url('alternative/get'); ?>',
                    method: "POST",
                    data: {
                        id: id,
                        [csrfName]: csrfHash
                    },
                    success: function(obj) {
                        var a = $.parseJSON(obj);

                        if (a.status == 'success') {
                            $('#form-header').html(a.header);
                            $('#form-alternative').attr('action', a.url_action);
                            $('#kode').val(a.kode);
                            $('#nama').val(a.nama);
                        } else {
                            return false;
                        }

                        $('input[name=<?= csrf_token(); ?>]').val(a.token);
                    }
                })
            }
        </script>
    <?php endif; ?>

    <?php if (strtolower($uri->getSegment(1)) == 'kriteria') : ?>
        <script>
            function getData(id) {
                if (id == null || id == '') {
                    return false;
                }

                var csrfName = '<?= csrf_token(); ?>';
                var csrfHash = $('input[name=csrf_token]').val();

                $.ajax({
                    url: '<?= base_url('kriteria/get'); ?>',
                    method: "POST",
                    data: {
                        id: id,
                        [csrfName]: csrfHash
                    },
                    success: function(obj) {
                        var a = $.parseJSON(obj);

                        if (a.status == 'success') {
                            $('#form-header').html(a.header);
                            $('#form-kriteria').attr('action', a.url_action);
                            $('#kode').val(a.kode);
                            $('#judul').val(a.judul);
                            $('#sifat').val(a.sifat);
                        } else {
                            return false;
                        }

                        $('input[name=<?= csrf_token(); ?>]').val(a.token);
                    }
                })
            }
        </script>
    <?php endif; ?>

    <?php if (strtolower($uri->getSegment(1)) == 'sub-kriteria') : ?>
        <script>
            function getData(id) {
                if (id == null || id == '') {
                    return false;
                }

                var csrfName = '<?= csrf_token(); ?>';
                var csrfHash = $('input[name=csrf_token]').val();

                $.ajax({
                    url: '<?= base_url('sub-kriteria/get'); ?>',
                    method: "POST",
                    data: {
                        id: id,
                        [csrfName]: csrfHash
                    },
                    success: function(obj) {
                        var a = $.parseJSON(obj);

                        if (a.status == 'success') {
                            $('#form-header').html(a.header);
                            $('#form-sub-kriteria').attr('action', a.url_action);
                            $('#kriteria').val(a.kriteria);
                            $('#nilai').val(a.nilai);
                            $('#keterangan').val(a.keterangan);
                        } else {
                            return false;
                        }

                        $('input[name=<?= csrf_token(); ?>]').val(a.token);
                    }
                })
            }
        </script>
    <?php endif; ?>

    <?php if (strtolower($uri->getSegment(1)) == 'penilaian') : ?>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('penilaian'));

            function detailPenilaian(id) {

                if (id == null || id == '') {
                    return false;
                }

                var csrfName = '<?= csrf_token(); ?>';
                var csrfHash = $('input[name=csrf_token]').val();

                $.ajax({
                    url: '<?= base_url('penilaian/get-detail'); ?>',
                    method: "POST",
                    data: {
                        id: id,
                        [csrfName]: csrfHash
                    },
                    success: function(obj) {
                        var a = $.parseJSON(obj);

                        if (a.status == 'success') {
                            $('#penilaianModalLabel').html(a.header);
                            $('#penilaianModalBody').html(a.body);
                            myModal.show();
                        } else {
                            return false;
                        }

                        $('input[name=<?= csrf_token(); ?>]').val(a.token);
                    }
                })
            }

            function getData(id) {
                if (id == null || id == '') {
                    return false;
                }

                var csrfName = '<?= csrf_token(); ?>';
                var csrfHash = $('input[name=csrf_token]').val();

                $.ajax({
                    url: '<?= base_url('penilaian/get'); ?>',
                    method: "POST",
                    data: {
                        id: id,
                        [csrfName]: csrfHash
                    },
                    success: function(obj) {
                        var a = $.parseJSON(obj);

                        if (a.status == 'success') {
                            $('#form-header').html(a.header);
                            $('#form-penilaian').attr('action', a.url_action);
                            $('#body-form-penilaian').html(a.body);
                        } else {
                            return false;
                        }

                        $('input[name=<?= csrf_token(); ?>]').val(a.token);
                    }
                })
            }

            function sweetDelete(id) {
                if (id == null || id == '') {
                    return false;
                }

                swal({
                    title: 'Hapus Data ini?',
                    text: 'Data yang terhapus tidak dapat dipulihkan',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonClass: 'btn-secondary',
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'Ya, Hapus!',
                    closeOnConfirm: false
                }, function() {
                    var csrfName = '<?= csrf_token(); ?>';
                    var csrfHash = $('input[name=csrf_token]').val();

                    $.ajax({
                        url: "<?= base_url('penilaian/delete'); ?>",
                        method: "POST",
                        data: {
                            id: id,
                            [csrfName]: csrfHash
                        },
                        success: function(obj) {

                            var a = $.parseJSON(obj);

                            if (a.status == 'success') {
                                swal({
                                    title: "Success!",
                                    text: "Data berhasil dihapus",
                                    type: "success",
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 2000
                                }, function() {
                                    window.location.reload();
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: "Data gagal dihapus",
                                    type: "error",
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 2000
                                }, function() {
                                    swal.close();
                                });
                            }
                        }
                    });
                });
            }
        </script>
        </script>
    <?php endif; ?>
</body>

</html>