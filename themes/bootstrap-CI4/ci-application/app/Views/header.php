<!doctype html>
<html>
<?php 
$currentUser = session()->get('currentUser');
?>
<head>
    <title>Admin / <?= $menu ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" type="image/png" href="<?= base_url() ?>/favicon.png"/>


    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/custom.css" />
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/main-back.css" />
    <!-- Date picker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" 
        integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        function base_url(){return "<?= base_url() ?>/";}
        function getLocale() { return "<?= $locale ?>"}
    </script>



    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    
    <!-- AJAX Form jquery plugin-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Date picker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" 
        integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Typeahead -->
    <script src="<?= base_url() ?>/assets/js/bootstrap-typeahead.js"></script>

    <!-- Trix Rich Text Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark navbar fixed-top">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <svg class="bi me-2" width="40" height="32">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                        <span class="fs-4">Admin</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <?php if( $currentUser->profile == 'ADMIN' ){ ?>
                            <li class="nav-item">
                            <a href="<?= base_url() ?>/Generated/User/Listusers" class="nav-link text-white <?php if($menu == "User"){?>active<?php } ?>">
                                Utilisateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() ?>/Generated/App/Listapps" class="nav-link text-white <?php if($menu == "App"){?>active<?php } ?>">
                                Applications
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>/Generated/Page/listpages" class="nav-link text-white <?php if($menu == "Pages"){?>active<?php } ?>">
                                Pages
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>/Generated/Element/Listelements/" class="nav-link text-white <?php if($menu == "Element"){?>active<?php } ?>">
                                Elements
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>/Generated/Translation/listtranslations" class="nav-link text-white <?php if($menu == "Translation"){?>active<?php } ?>">
                                Traductions
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>/Generated/Language/listlanguages" class="nav-link text-white <?php if($menu == "Language"){?>active<?php } ?>">
                                Langues
                            </a>
                        </li>
                        <?php }else{?>
                            <li class="nav-item">
                            <a href="<?= base_url() ?>/WebMaster/App/Listapps" class="nav-link text-white <?php if($menu == "App"){?>active<?php } ?>">
                                Applications
                            </a>
                        </li>
                        <?php } ?>
                        
                    </ul>
                    <hr>
                    <div class="dropup" style="top:-24px;">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            
                        <?php if($currentUser != null ){ ?>
                            <strong><?= $currentUser->name ?></strong>
                        <?php }else{ ?>
                            ADMIN
                        <?php } ?> 
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <!--li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li-->
                            <li><a class="dropdown-item" href="<?= base_url() ?>/welcome/logout">Log out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col py-3 offset-md-3 offset-xl-2">