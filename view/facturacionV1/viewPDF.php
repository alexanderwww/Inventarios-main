<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php if ($_GET['id']) { ?>


        <script>

            const getInit = async() => {

                let statusPDF = "<?php echo $_GET["statusPDF"]; ?>";

                console.log(statusPDF);

                const getDataPDF = async (id) => {

                    return (await fetch(rutaApi + '?Accion=factura&Select=createPDF&id='+id, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        }).then(respuesta => respuesta.json())
                        .then(respuesta => {
                            return respuesta;
                        })
                    )
                }

                const downloadPDF = (dataPDF) => {

                    let urlPDF = "../pdf/factura.php";

                    let form = document.createElement("form");

                    form.setAttribute("method", "post");

                    form.setAttribute("action", urlPDF);

                    form.appendChild(createInputForm('dataPDF', JSON.stringify(dataPDF)));
                    form.appendChild(createInputForm('statusPDF', JSON.stringify(statusPDF)));

                    document.body.appendChild(form);

                    form.submit();

                    document.body.removeChild(form);

                };


                const createInputForm = (name, value) => {

                    let inputCreateForm = document.createElement("input");

                    inputCreateForm.type = "hidden";

                    inputCreateForm.name = name;

                    inputCreateForm.value = value;

                    return inputCreateForm;
                }

                const rutaApi = '../../api/api.php';

                let urlPDF = "../pdf/factura.php";

                let dataPDF = await getDataPDF("<?php echo $_GET["id"]; ?>");

                downloadPDF(dataPDF['data'])

            }

            getInit()

            </script>

    <?php }; ?>

</body>

</html>