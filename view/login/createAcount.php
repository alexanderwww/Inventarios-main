<!DOCTYPE html>

<html lang="en">

<?php session_start(); 
session_destroy();

?>


<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Meta, title, CSS, favicons, etc. -->

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <!-- Bootstrap -->


    <link rel="stylesheet" href="../../requerimientos/vendors/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->

    <link href="../../requerimientos/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">


    <!-- jQuery -->

    <script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>

    <!-- Google Font "Didactic Century similar to Century Gothic -->

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap" rel="stylesheet">

    <!-- Custom Theme Style -->

    <link href="../../requerimientos/build/css/custom.min.css" rel="stylesheet">

    <!-- ini custom js -->

    <script src="../../jsGeneral/appInt.js"></script>


</head>

<body class="login">

    <!-- ------------------------------------------------------------------------------------------  -->
    <div class="login_wrapper">

        <div id="register" class="animate form">

            <section class="login_content">

                <form>

                    <h1>Crear Cuenta</h1>

                    <div>

                        <input onkeyup="sobreinput(event);" type="text" class="form-control trim getDataAcount" placeholder="nombre" id="Nombre" required="" />
                        <p style="text-align: left; display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre" class="form_text_adv"></p>

                    </div>

                    <div>

                        <input onkeyup="sobreinput(event);" type="email" class="form-control trim getDataAcount" placeholder="Email" id="Correo" required="" />
                        <p style="text-align: left; display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Correo" class="form_text_adv"></p>

                    </div>

                    <div>

                        <input onkeyup="sobreinput(event);" type="text" class="form-control getDataAcount upperTrim" placeholder="Rfc" id="Rfc" required="" />
                        <p style="text-align: left; display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Rfc" class="form_text_adv"></p>

                    </div>

                    <div>

                        <input onkeyup="sobreinput(event);" type="password" class="form-control trim getDataAcount" placeholder="Password" id="password" required="" />
                        <p style="text-align: left; display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_password" class="form_text_adv"></p>

                    </div>

                    <div>

                        <!-- <a class="btn btn-default submit" href="index.html">Submit</a> -->
                        <input type="button" class="btn btn-primary pull-left" id="btnRegistrar" value="Registrar">

                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">

                        <p class="change_link">Ya tienes una cuenta ?

                            <a href="index.php" class="to_register"> Log in </a>

                        </p>

                        <div class="clearfix"></div>

                        <br />

                        <div style="display: none;">

                            <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>

                            <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>

                        </div>

                    </div>

                </form>

            </section>

        </div>

    </div>

    <!-- ------------------------------------------------------------------------------------------  -->

</body>


<script src="js/login.js"></script>

</html>
