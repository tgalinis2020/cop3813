<?php require __DIR__ . '/index.controller.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Converter | Thomas Galinis</title>
</head>
<body>
    <main>
        <div class="container my-5">
            <h1 class="mb-4">PHP Unit Converter</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../">Portal</a></li>
                <li class="breadcrumb-item active">PHP Unit Converter</li>
            </ul>
        </div>

        <?php if (!empty($result)): ?>
            <div class="alert alert-info" role="alert"><?=
                sprintf('%.2f %s = %.2f %s', $value, $from_unit, $result, $to_unit)
            ?></div>
        <?php endif ?>

        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
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
                <input class="form-control col-sm-7" type="text">
                <select class="form-control col-sm-3" name="from_unit">
                    <?php foreach ($measure_type_units[$unit_type] as $unit => $label): ?>
                        <option value="<?= $unit ?>"><?= $label ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Convert To</label>
                <select class="form-control col-sm-3 offset-sm-7" name="from_unit">
                    <?php foreach ($measure_type_units[$unit_type] as $unit => $label): ?>
                        <option value="<?= $unit ?>"><?= $label ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </form>
    </main>

    <footer>
        <div class="container">
            <p class="text-center text-muted">&copy; 2020 Thomas Galinis</p>
        </div>
    </footer>
</body>
</html>