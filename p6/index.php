<?php require __DIR__ . '/index.controller.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Converter</title>
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap-4.5.0-dist/css/bootstrap.min.css">
    <script type="text/javascript" src="../vendor/jquery-3.5.1-dist/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="../vendor/bootstrap-4.5.0-dist/js/bootstrap.min.js"></script>
</head>
<body>
    <main>
        <div class="container my-5">
            <h1 class="mb-4">PHP Unit Converter</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../">Portal</a></li>
                <li class="breadcrumb-item active">Unit Converter</li>
            </ul>

            <?php if (!empty($result)): ?>
                <div class="alert alert-info alert-dismissable fade show" role="alert">
                    <?= sprintf('%.2f %s = %.2f %s', $value, $from_unit, $result, $to_unit) ?>
                    
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>

            <form action="" method="POST">
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Type</label>
                    <div class="col-sm-10">
                        <div class="btn-group" role="group" aria-label="Measurement types">
                            <?php foreach ($measure_types as $label => $value): ?>
                                <a href="?type=<?= $value ?>"
                                    class="btn btn-<?= $measure_type === $value ? 'primary' : 'secondary' ?>"
                                ><?= $label ?></a>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2" for="value">Value</label>
                    <div class="col-sm-10">
                        <input class="<?= implode(' ', $valueClasses) ?>"
                            type="text"
                            name="value"
                            id="value"
                            value="<?= $_POST['value'] ?? '' ?>">
                        <div class="invalid-feedback"><?= $feedback ?></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2" for="to_unit">Convert</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-sm-3">
                                <select class="form-control" name="from_unit" id="from_unit">
                                    <?php foreach ($measure_type_units[$measure_type] as $unit => $label): ?>
                                        <option value="<?= $unit ?>"
                                            <?= $from_unit === $unit ? 'selected' : '' ?>
                                        ><?= $label ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="offset-sm-1 col-sm-1">To</div>

                            <div class="offset-sm-1 col-sm-3">
                                <select class="form-control" name="to_unit" id="to_unit">
                                    <?php foreach ($measure_type_units[$measure_type] as $unit => $label): ?>
                                        <option value="<?= $unit ?>"
                                            <?= $to_unit === $unit ? 'selected' : '' ?>
                                        ><?= $label ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <input class="btn btn-primary" type="submit" value="Convert">
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