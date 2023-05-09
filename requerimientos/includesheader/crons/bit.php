<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require '../../vendorComposer/vendor/autoload.php';
require_once "../../../view/login/controlador/loginCtrl.php";
$dbObj = new loginCtrl();

////////////// CONFIGURACION //////////////////////////
$nombre='Ivan Pelaez';
$correo='angelct013@gmail.com';
// $correo='ivanplaez@gmail.com';
// $correo='apaulcb@gmail.com';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
try{
    $mail = new PHPMailer(true);

    //Server settings
    $mail->isSMTP();                                          //Send using SMTP

    $mail->isHTML(true);

    $mail->CharSet = 'UTF-8';

    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through

    $mail->SMTPAuth   = true;

    $mail->Port = 587;

    // $mail->SMTPSecure = 'ssl';                                //Enable SMTP authentication
    $mail->SMTPSecure = 'tls';                                //Enable SMTP authentication

    $mail->Username   = 'legnaarmstrong@gmail.com';        //SMTP username

    $mail->Password   = 'fdckcnglrtzszanh';                          //SMTP password

    $mail->CharSet = 'UTF-8';

    $mail->Mailer = 'smtp';

    
    $mail->From = "legnaarmstrong@gmail.com";
    $mail->FromName = "Sistema 5inco";
    //Recipients
    // $mail->setFrom('legnaarmstrong@gmail.com', 'Prueba');


    $mail->addAddress($correo, $nombre);
    // Info Bitacora
        $flag=0;
        $date =date('Y-m-d');
        $date2=date('d-m-Y');

        $fecha  = strtotime("-1 day", strtotime($date));
        $fechaFormato =date("Y-m-d", $fecha);
        $sql='SELECT bt.Id, bt.TimeStamp, bt.User, bt.Comentarios, btc.Operacion, mol.Modulo AS Modulo FROM bitacora bt 
        INNER JOIN bitacoracodigo btc ON bt.IdOperacion = btc.Id
        JOIN modulos mol ON mol.Id = bt.Modulo
        WHERE bt.TimeStamp BETWEEN "'.$fechaFormato.'" AND "'.$date.'" ORDER BY bt.Id DESC';
        // print_r($sql);
        $respuesta=$dbObj->GetBitacora($sql);
        if(empty($respuesta)){
            echo "No hubo actividades de la bitacora del dia anterior";
            return ;

            // $lastDate = "Select Id, max(TimeStamp) AS date from bitacora";
            // $respuestaDate=$dbObj->GetBitacora($lastDate);
            // $fechaFormato =date("Y-m-d", strtotime($respuestaDate[0]['date']));
            // $fecha  = strtotime("+1 day", strtotime($date));
            // $fechaDespues =date("Y-m-d", $fecha);
            // $sql='SELECT bt.Id, bt.TimeStamp, bt.User, bt.Comentarios, btc.Operacion, mol.Modulo AS Modulo FROM bitacora bt 
            // INNER JOIN bitacoracodigo btc ON bt.IdOperacion = btc.Id
            // JOIN modulos mol ON mol.Id = bt.Modulo
            // WHERE bt.TimeStamp BETWEEN "'.$fechaFormato.'" AND "'.$fechaDespues.'" ORDER BY bt.Id DESC';
            // // print_r($sql);
            // $respuesta=$dbObj->GetBitacora($sql);

        }
        foreach ($respuesta as $key => $value) {
            $value['TimeStamp']=date('d/m/Y',strtotime($value['TimeStamp']));
            if($value['Modulo']=='Ordenes de Trabajo'){
                $LL0[$key] ='<tr style= "background-color: rgb(0, 220, 0, 0.2); text-align:center;">
                <td>'.$value['TimeStamp'].'</td>
                <td>'.$value['User'].'</td>
                <td>'.$value['Operacion'].'</td>
                <td>'.$value['Modulo'].'</td>
                <td>'.$value['Comentarios'].'</td>
                </tr>';
            }
            elseif($value['Modulo']=='Ajustes'){
                $flag=1;
                $LL0[$key] ='<tr style= "background-color: rgb(237, 28, 36, 0.2); text-align:center;">
                <td>'.$value['TimeStamp'].'</td>
                <td>'.$value['User'].'</td>
                <td>'.$value['Operacion'].'</td>
                <td>'.$value['Modulo'].'</td>
                <td>'.$value['Comentarios'].'</td>
                </tr>';
            }else{
                $LL0[$key] ='<tr style= "background-color: rgb(222, 222, 222, 0.2); text-align:center;">
                <td>'.$value['TimeStamp'].'</td>
                <td>'.$value['User'].'</td>
                <td>'.$value['Operacion'].'</td>
                <td>'.$value['Modulo'].'</td>
                <td>'.$value['Comentarios'].'</td>
                </tr>';
            }
            if($value['Modulo']=='Compras' ||$value['Modulo']=='Ordenes de Compras'||$value['Modulo']=='Ordenes de Venta'){
                $flag=1;
            }
        
        }
       if($flag==0){
        echo "No hubo actividades en los modulos de ventas, ajustes o compras del dia anterior";
            return ;
       }
    //Content
   
    $mail->isHTML(true);

    $mail->Subject = 'Bitacora Sistema 5inco del día '.$date2;

    $mail->Body = bodyHtml($LL0);

    $mail->send();

    echo "Bitacora Enviada\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}

    function bodyHtml($LL0)
    {
        return '
            <body>
                
            <style>
                .root{
                    text-align: center;
                    width: 100%;
                }

                .border{
                    width: 100%;
                    height: 5px;
                    background-color: rgb(39, 39, 39);
                }
                th {
                    font-size: 16px;
                    font-weight: lighter;
                    font-family: "didactic";
                    width:2.20in;
                    padding: 4px;
                    border-spacing: 0;
                    border-collapse: collapse;
                    background-color: #d7572b;;
                    color: #fff;
                }
                
                td {
                    font-size: 16px;
                    background: transparent;
                    font-family: "didactic";
                    padding: 7px;
                    color: #000;
                    text-align: center;
                }

            </style>


            <div class="root">

                <div class="border"></div>
                <div style="width: 300px; margin:auto; padding: 10px;">
                    <img src="./Data/logoTextCt.svg" style="width: 300px; margin:auto; text-align: center;" alt="">
                </div>
                <section>
                
                    <div class="containerCabezera">
                
                        <div style="width: 100%; ">
                        <h2>  <strong>Bitacora del: </strong>   ' . date('d-m-Y') . ' </h2>
                        </div> 
                    
                    </div>
                
                </section>
                
                <div style="width: 100%; height: 50px; "  ></div>
                
                
                <section>
                    <div class="containerAgradecimiento">
                
                        <div class="boxAgredecimiento" >
                
                           
                
                            <div>
                                <strong>FECHA: &nbsp;</strong> 
                                <span>
                                ' . date('d-m-Y'). '
                                </span>
                            </div>
                
                            <div class="boxTabla">
                            <table cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Operacion</th>
                                    <th>Modulo</th>
                                    <th>Comentario</th>
                                </tr>
                            </thead>
                                <tbody>
        
                                '.implode($LL0).'
        
                                </tbody>
                
                            </table>
                
                        </div>
                
                
                        </div>
                
                    </div>
                </section>
                
                <div style="width: 100%; height: 50px; "  ></div>
                
                
                
                <div class="border"></div>

            </div>


            </body>';
    }
 
?>