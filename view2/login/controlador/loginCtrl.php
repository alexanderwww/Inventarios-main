<?php
   if(!isset($_SESSION)) 
   { 
       session_start(); 
   } 

include_once '../../../requerimientos/classes/Model.php';



class loginCtrl extends model{
    function dataLogin($usuario,$pass){
            if(!empty($usuario) && !empty($pass)){
                $passCrifrada = md5($pass);
                $query = "SELECT * FROM user_accounts WHERE User = '$usuario' AND Password='$passCrifrada'";
                $user = $this->getRow($query);
                $query2 = "SELECT * FROM tipos_cambio WHERE fecha in (Select max(fecha) from tipos_cambio) AND tipo_cambio IS NOT NULL LIMIT 1";
                $TC = $this->getRow($query2);
                
                if(!empty($TC)){
                    $TC =round( $TC['tipo_cambio'], 2);;
                }else{
                    $TC = 1;
                }
                if (!empty($user)) {
                $query2 = "SELECT r.*
                FROM  roles r 
                WHERE id = ".$user['Rol'];
                $rol = $this->getRow($query2);
                
                
                        if($user['Active'] == 1){
                            
                            $foto='../../requerimientos/imgGeneral/usuarios/'.$user['ImgUser'];

                            if($user['ImgUser']=='foto.png'){
                                $foto='../../requerimientos/imgGeneral/'.$user['ImgUser'];
                            }

                            $_SESSION['Validado'] = true;
                            $_SESSION['IdUser'] = $user['Id'];
                            $_SESSION['Nombre'] = $user['Name'];
                            $_SESSION['Email'] = $user['Email'];
                            $_SESSION['ImgUser'] = $foto;
                            $_SESSION['Rol'] = $rol;
                            $_SESSION['Moneda'] = 'Pesos';
                            $_SESSION['TipoCambio'] = $TC;//Variable de se TC DataBase
                            $_SESSION['TC']= $TC;
                            $_SESSION['listaAccesso'] = $this->pagesAccess($rol); 
                              return  array("success"=>true,"message"=>"log in");
                        }else{
                            return  array("success"=>false,"message"=>"Acceso denegado");
                        }
                    }else{
                        return array("success"=>false,"message"=>"Error usuario/contraseña");
                    }
            }else{
                return array("success"=>false,"message"=>"Campo usuario y contraseña requeridos");
            }
        } 
    public function GetAllDataTC()
    {
       $sql = "SELECT * FROM tipos_cambio WHERE fecha = CURDATE() AND tipo_cambio IS NOT NULL LIMIT 1";
       $respuesta = $this->getAllTable($sql);
       return $respuesta;
    }
    public function UpdateTableTC($tipoCambio)
    {
        // "`config` SET `TipoCambio`=".$tipoCambio." WHERE 1"
       $sql = "UPDATE `config` SET `TipoCambio`=".$tipoCambio." WHERE 1";
       $respuesta = $this->updateTable($sql);
       return $respuesta;
    }
    public function SetTableTC($sql)
    {
        // "`config` SET `TipoCambio`=".$tipoCambio." WHERE 1"
    //    $sql = "UPDATE `config` SET `TipoCambio`=".$tipoCambio." WHERE 1";
       $respuesta = $this->setTable($sql);
       return $respuesta;
    }
    public function GetBitacora($sql)
    {
       $respuesta = $this->getAllTable($sql);
       $data=[];
       while($bitBD =$respuesta->fetch_assoc()){
           $bitBD['Id']= (int)$bitBD['Id'];
           $data[]= $bitBD;
       }
       return $data;
    }
    public function pagesAccess($rol)
    {
        $acceso = [];
        foreach($rol as $key => $value){
            if($value){
                switch($key){
                    case 'proveedorR':
                        $acceso[] = 'proveedores';
                    break;
                    case 'clienteR':
                        $acceso[] = 'clientes';
                    break;
                    case 'usuarioR':
                        $acceso[] = 'usuarios';
                    break;
                    case 'rolR':
                        $acceso[] = 'roles';
                    break;
                    case 'prductoR':
                        $acceso[] = 'productos';
                    break;
                    case 'formulasR':
                        $acceso[] = 'odt';
                    break;
                    case 'odtR':
                        $acceso[] = 'odt';
                    break;
                    case 'notasVR':
                        $acceso[] = 'odv';
                    break;
                    case 'comprasR':
                        $acceso[] = 'compras';
                    break;
                    case 'ajustesR':
                        $acceso[] = 'ajustes';
                    break;
                    case 'bitacoraR':
                        $acceso[] = 'bitacora';
                    break;
                }
            }

        }
        $acceso[] ='inventario';
        return $acceso;
    //     // "`config` SET `TipoCambio`=".$tipoCambio." WHERE 1"
    //     //    $sql = "UPDATE `config` SET `TipoCambio`=".$tipoCambio." WHERE 1";
    //    $respuesta = $this->getAllTable($sql);
    //    $data=[];
    //    while($bitBD =$respuesta->fetch_assoc()){
    //        $bitBD['Id']= (int)$bitBD['Id'];
    //        $data[]= $bitBD;
    //    }
    //    return $data;
    }
}
?>
