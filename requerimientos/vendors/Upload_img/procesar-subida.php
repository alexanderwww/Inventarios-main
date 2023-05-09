<?php

if (isset($_FILES['fotoUsuario'])) {

  // Almacena el archivo en una variable
  $foto = $_FILES['fotoUsuario'];

  // Comprueba si se ha producido un error al subir el archivo
  if ($foto['error'] == 0) {

    // Genera un nombre único para el archivo
    $nombreFoto = time() . '.png';

    // Establece la ruta donde se guardará el archivo
    $rutaFoto = '../../imgGeneral/usuarios/' . $nombreFoto;

    // Mueve el archivo a la ubicación especificada
    if (move_uploaded_file($foto['tmp_name'], $rutaFoto)) {
      // El archivo se ha guardado correctamente
      echo json_encode(array(
        "success" => true,
        "nameFoto"=>$nombreFoto,
        "message" => "Foto subida correctamente"
      ), JSON_PRETTY_PRINT);
    } else {
      echo json_encode(array(
        "success" => false,
        "message" => "Error al subir foto",
      ), JSON_PRETTY_PRINT);
    }
  } else {
    echo json_encode(array(
      "success" => false,
      "message" => "Error al subir foto",
    ), JSON_PRETTY_PRINT);
  }
} else {
  echo json_encode(array(
    "success" => false,
    "message" => "Archivo no enviado",
  ), JSON_PRETTY_PRINT);
}
