<?php

// Don't allow users to run this script directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: index.php'); // Redirect to main page
    die;
}

require __DIR__ . '/../common/sanitize.php';

$from_unit = null; // units of given value
$to_unit = null; // desired units for result
$value = null; // value given by user, initially empty
$result = null; // result to display, initially empty
$valueClasses = ['form-control']; // classes to apply to the input corresponding to the entered value
$feedback = null; // A hint to let the user know what went wrong

// Available conversion options, shown in button group
$measure_types = [
    'Mass &amp; Weight' => 'mass',
    'Volume' => 'volume',
    'Distance' => 'distance',
    'Temperature' => 'temperature',
];

// The selected measure type, default is mass
$measure_type = sanitize($_GET['type'] ?? 'mass');

// Undefined measure types default to mass
if (!in_array($measure_type, ['mass', 'volume', 'distance', 'temperature'])) {
    $measure_type = 'mass';
}

// Measure units to show in <select> elements
$measure_type_units = [
    'mass' => [
        'g' => 'Grams',
        'mg' => 'Milligrams',
        'kg' => 'Kilograms',
        'oz' => 'Ounces',
        'lb' => 'Pounds',
        't' => 'Tons',
    ],

    'volume' => [
        'L' => 'Liters',
        'mL' => 'Milliliters',
        'fl oz' => 'Fluid Ounces',
        'tbsp' => 'Tablespoons',
        'tsp' => 'Teaspoons',
        'c' => 'Cups',
        'pt' => 'Pints',
        'qt' => 'Quarts',
        'gal' => 'Gallons',
    ],

    'distance' => [
        'm' => 'Meters',
        'mm' => 'Millimeters',
        'cm' => 'Centimeters',
        'km' => 'Kilometers',
        'in' => 'Inches',
        'ft' => 'Feet',
        'yd' => 'Yards',
        'mi' => 'Miles',
    ],

    'temperature' => [
        'C' => 'Celsius',
        'F' => 'Fahrenheit',
        'K' => 'Kelvin',
    ]
];

// Since temperatures don't map 1-to-1, they are not present in this table
$measure_tables = [
    'mass' => [
        'g' => 1, // grams are the base unit for mass
        'mg' => 1000,
        'kg' => 0.001,
        'oz' => 0.035274,
        'lb' => 0.00220462,
        't' => 0.0000011023,
    ],

    'volume' => [
        'L' => 1, // liters are the base unit for volume
        'mL' => 1000,
        'fl oz' => 33.814,
        'tbsp' => 67.628,
        'tsp' => 202.884,
        'c' => 4.22675,
        'qt' => 1.05669,
        'pt' => 2.11338,
        'gal' => 0.264172,
    ],

    'distance' => [
        'm' => 1, // meters are the base unit for distance
        'mm' => 1000,
        'cm' => 100,
        'km' => 0.001,
        'in' => 39.3701,
        'ft' => 3.28084,
        'yd' => 1.09361,
        'mi' => 0.000621371,
    ],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_unit = $_POST['from_unit'];
    $to_unit = $_POST['to_unit'];
    $value = sanitize(trim($_POST['value']));

    // Some simple input validation
    if (empty($value)) {
        $valueClasses[] = 'is-invalid';
        $feedback = 'Input cannot be empty.';
    } elseif (!is_numeric($value)) {
        $valueClasses[] = 'is-invalid';
        $feedback = 'Please enter a numeric value.';
    } else {
        $result = (float) $value;

        // No need to do any conversion if the units are equal
        if ($from_unit !== $to_unit) {
            // Note: Celsius is the base unit of measurement for temperature
            if ($measure_type === 'temperature') {
                switch ($from_unit) {
                    case 'F':
                        $result = ($result-32) * 5/9;
                        break;
                    case 'K':
                        $result -= 273.15;
                        break;
                }

                switch ($to_unit) {
                    case 'F':
                        $result = ($result*9)/5 + 32;
                        break;
                    case 'K':
                        $result += 273.15;
                        break;
                }
            } else {
                $measures = $measure_tables[$measure_type];

                // Multiply the given value by the ratio of the desired and
                // given types to calculate the final result 
                $result *= $measures[$to_unit] / $measures[$from_unit];
            }
        }
    }
}
