<?php
session_start();
require("../config/config.php");

//error_reporting(0);

// Comprobar sesión usuario
function sessionLocal($session, $c)
{
    $check = false;

    if (!empty($session)) {
        $resultado = mysqli_query($c, "SELECT * FROM duenos WHERE correo = '$session'");

        if (mysqli_num_rows($resultado) > 0) $check = true;
    }

    return $check;
}
// Fin comprobar usuario sesión

$session = empty($_SESSION["usuario"]) ? "" : $_SESSION["usuario"];

$session = sessionLocal($session, $conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Puestos</title>

    <?php include "../includes/styles.php"; ?>
</head>

<body>
    <div class="container-fluid ">
        <div class="row flex-nowrap">
            <?php include "../includes/header.php"; ?>
            <div class="col py-3">
                <h2>Puestos</h2>
                <?php if (obtenerCampo("rol") >= 2) { ?>
                    <div class="d-block p-10 mb-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearPuesto" title="Crear puesto">
                            <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" class="d-inline-block align-middle">
                                <image href="../icons/plus-circle-1441-svgrepo-com.svg" height="20" width="20" />
                            </svg>
                        </button>
                    </div>
                <?php } ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"># id</th>
                            <th scope="col">Número</th>
                            <th scope="col">Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $resultado = mysqli_query($conn, "SELECT * FROM puestos");
                        if (mysqli_num_rows($resultado) > 0) {
                            while ($puesto = mysqli_fetch_assoc($resultado)) { ?>
                                <tr>
                                    <th scope="row"><?php echo $puesto["id"]; ?></th>
                                    <td><?php echo $puesto["numero"]; ?></td>
                                    <td>
                                        <?php
                                        if ($puesto["disponibilidad"]) { ?>
                                            <button type="button" class="btn btn-primary">Disponible</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="popover" data-bs-title="<?php echo $puesto["placa"]; ?> <?php echo $puesto["marca"]; ?>" data-bs-content="Modelo: <?php echo $puesto["modelo"]; ?>">Ocupado</button>
                                        <?php } ?>
                                    </td>
                                    <?php if (obtenerCampo("rol") >= 2) { ?>
                                        <td>
                                            <button type="button" data-modal="<?php echo $puesto["numero"]; ?>:<?php echo $puesto["id"]; ?>" class="btn btn-success" id="editar" data-bs-toggle="modal" data-bs-target="#editarPuesto" title="Editar">
                                                Editar
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-confirm-delete="true" data-delete-card="puesto" data-href-delete="http://<?php echo $host; ?>/ParqueaderoVL/delete/delete_puesto.php?id=<?php echo $puesto["id"]; ?>" title="Eliminar">
                                                Eliminar
                                            </button>
                                        </td>
                                    <?php } ?>
                                </tr>
                        <?php }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include "../includes/js.php"; ?>
    <?php include "../modals/crear_puesto.php"; ?>
    <?php include "../modals/editar_puesto.php"; ?>

    <script>
        $(document).ready(() => {
            $("button#editar").click((e) => {
                const data = e.target.getAttribute("data-modal").split(":");
                $("#numero").val(data[0]);
                $("#id").val(data[1]);
            });
        })
    </script>

</body>

</html>