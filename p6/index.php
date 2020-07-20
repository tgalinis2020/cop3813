<?php require __DIR__ . '/index.controller.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Converter | Thomas Galinis</title>
    <link rel="stylesheet" type="text/css"
            href="../vendor/bootstrap-4.5.0-dist/css/bootstrap.min.css">
</head>
<body>
    <main>
        <div class="container my-5">
            <h1 class="mb-4">PHP Unit Converter</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../">Portal</a></li>
                <li class="breadcrumb-item active">PHP Unit Converter</li>
            </ul>

            <?php if (!empty($result)): ?>
                <div class="alert alert-info" role="alert"><?=
                    sprintf('%.2f %s = %.2f %s', $value, $from_unit, $result, $to_unit)
                ?></div>
            <?php endif ?>

            <form action="" method="POST">
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Type</label>
                    <div class="btn-group" role="group" aria-label="Measurement types">
                        <?php foreach ($measure_types as $label => $value): ?>
                            <a  href="?type=<?= $value ?>"
                                class="btn btn-primary"
                                <?= is_selected($value) ? 'disabled' : '' ?>
                            ><?= $label ?></a>
                        <?php endforeach ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Value</label>
                    <input class="form-control col-sm-6" type="text">
                    <select class="form-control col-sm-3 offset-sm-1" name="from_unit">
                        <?php foreach ($measure_type_units[$measure_type] as $unit => $label): ?>
                            <option value="<?= $unit ?>"><?= $label ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Convert To</label>
                    <select class="form-control col-sm-3" name="from_unit">
                        <?php foreach ($measure_type_units[$measure_type] as $unit => $label): ?>
                            <option value="<?= $unit ?>"><?= $label ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group row">
                    <div class="offset-sm-2">
                        <input class="btn-primary" type="submit" value="Convert">
                    </div>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p class="text-center text-muted">&copy; 2020 Thomas Galinis</p>
        </div>
    </footer>
</body>
</html>