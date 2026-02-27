<?php
// Esta plantilla recibe $data_pdf con keys: base, porcentajes, detalles, firmas, fotosNovedad
$base = isset($data_pdf['base']) ? $data_pdf['base'] : [];
$porcentajes = isset($data_pdf['porcentajes']) ? $data_pdf['porcentajes'] : [];
$detalles = isset($data_pdf['detalles']) ? $data_pdf['detalles'] : [];
$fotosNovedad = isset($data_pdf['fotosNovedad']) ? $data_pdf['fotosNovedad'] : [];

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inspección #<?= htmlspecialchars($base['id_inspeccion'] ?? '') ?></title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size:12px; }
        .header { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #f2f2f2; }
        .small { font-size: 11px; }
        .firma-container {
            margin-top: 20px;
            text-align: left;
        }

        .img-firma {
            width: 150px;
            height: auto;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            display: block;
        }

        .firma-label {
            font-size: 10px;
            margin-top: 4px;
        }
        .firma-container {
            text-align: center;
        }

        .img-firma {
            margin: 0 auto;
        }


    </style>
</head>
<body>
    <div class="header">
        <h2>Inspección Pre-Operacional</h2>
        <div>Inspección ID: <?= htmlspecialchars($base['id_inspeccion'] ?? '') ?></div>
        <div>Fecha: <?= htmlspecialchars($base['fecha_registro'] ?? '') ?></div>
    </div>

    <h4>Datos generales</h4>
    <table>
        <tr>
            <th>Vehículo ID</th>
            <th>Conductor ID</th>
            <th>Kilometraje</th>
            <th>Tipo Vehículo</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($base['vehiculo_id'] ?? '') ?></td>
            <td><?= htmlspecialchars($base['conductor_id'] ?? '') ?></td>
            <td><?= htmlspecialchars($base['kilometraje'] ?? '') ?></td>
            <td><?= htmlspecialchars($base['tipo_vehiculo'] ?? '') ?></td>
        </tr>
    </table>

    <h4>Porcentajes por sistema</h4>
    <table>
        <tr>
            <th>Sistema</th>
            <th>Puntaje obtenido</th>
        </tr>
        <?php foreach ($porcentajes as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre_sistema']) ?></td>
                <td class="small"><?= htmlspecialchars($p['puntaje_obtenido']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h4>Detalles de items</h4>
    <table>
        <tr>
            <th>Sistema</th>
            <th>Item</th>
            <th>Estado</th>
        </tr>
        <?php foreach ($detalles as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['sistema_pertenece']) ?></td>
                <td><?= htmlspecialchars($d['item_nombre']) ?></td>
                <td><?= htmlspecialchars($d['estado_valor']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <!-- Agregar la informacion de las novedades -->

    <h4>Novedades</h4>
    <table>
        <tr>
            <th>Observaciones registradas</th>
        </tr>
        <tr>
            <td><?= nl2br(htmlspecialchars($base['novedades'] ?? 'No se registraron novedades')) ?></td>
        </tr>
    </table>

    <!-- Permite agregar fotos de como pruebas  -->
    <h4>Fotos de novedades</h4>
    <?php if (!empty($fotosNovedad)): ?>
        <table>
            <tr>
                <th>Sistema</th>
                <th>Foto</th>
            </tr>
            <?php foreach ($fotosNovedad as $foto): ?>
                <?= 
                    // Hacer explode para tomar la informacion del item del sistema
                    $sistema = explode('_', $foto['img_path'])[2] ?? 'desconocido';
                    $nombreFoto = explode('.', $sistema)[0] ?? 'foto';

                    $ruta_local = $_SERVER['DOCUMENT_ROOT'] . '/demos/proyect_preoperacional/' . htmlspecialchars($foto['img_path']);
                    $ruta_servidor = $_SERVER['DOCUMENT_ROOT'] . '/proyect_preoperacional/' . htmlspecialchars($foto['img_path']);

                 ?>    
                ?>
                <tr>
                    <td><?= htmlspecialchars($nombreFoto) ?></td>
                    <!-- Ruta que permite mostrar la informacion del servidor -->
                    <td><img src="<?= $ruta_servidor ?>" alt="Foto de novedad" style="width: 150px; height: auto;"></td>
                  </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No se registraron fotos de novedades.</p>
    <?php endif; ?>
    


    <!-- Anexar la firma del documento -->
    <?php
        // ambiar fimar para servidor local o produccion
        $firmaPath_servidor = $_SERVER['DOCUMENT_ROOT'] . '/proyect_preoperacional/' . $base['firma_path'];
        $firmaPath_local = $_SERVER['DOCUMENT_ROOT'] . '/demos/proyect_preoperacional/' . $base['firma_path'];
    ?>
    <div class="firma-container">
        <img src="<?= $firmaPath_servidor ?>" class="img-firma" alt="Firma Digital">
        <p class="firma-label">Firma del conductor</p>
    </div>
  
    <!-- <div class="small">PDF generado automáticamente.</div> -->
</body>
</html>
