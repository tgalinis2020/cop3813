<?php

// Don't allow users to run this script directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: index.php'); // Redirect to main page
    die;
}

require __DIR__ . '/../common/sanitize.php';

function to_celcius($f) {
    return ($f-32) * 5/9;
}

function to_farenheit($c) {
    return ($c*9)/5 + 32;
}

// Helper utility to determine whether or not a measure type is selected
function is_selected($type) {
    if (isset($_GET['type'])) {
        return $type === $_GET['type'];
    } else {
        return $type === 'mass';
    }
}

$from_unit = null;
$to_unit = null;
$value = null; // value given by user, initially empty
$result = null; // result to display, initially empty

// Available conversion options
$measure_types = [
    'Mass' => 'mass',
    'Volume' => 'volume',
    'Distance' => 'distance',
    'Temperature' => 'temperature'
];

// Base types for conversions
// All values given by user are converted to one of these types
$base_units = [
    'mass' => 'g',
    'volume' => 'L',
    'distance' => 'm',
    'temperature' => 'C',
];

// The selected unit type, default is mass
$measure_type = sanitize($_GET['type'] ?? 'mass');

// Undefined unit types default to mass
if (!isset($base_units[$measure_type])) {
    $measure_type = 'mass';
}

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
        'fl oz' => 'Fluid Ounce',
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

// Note: since temperatures don't map 1-to-1, they are not present
//       in this table.
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
    $value = (float) sanitize($_POST['value']);
    $result = $value; // initially assume from_unit === to_unit

    // No need to do any conversion if the units are equal
    if ($from_unit !== $to_unit) {
        // Note: Celsius is the base unit of measurement for temperature
        if ($measure_type === 'temperature') {
            switch ($from_unit) {
                case 'F':
                    $result = ($value-32) * 5/9;
                    break;
                case 'K':
                    $result = $value + 273.15;
                    break;
            }

            switch ($to_unit) {
                case 'F':
                    $result = ($result*9)/5 + 32;
                    break;
                case 'K':
                    $result = $result - 273.15;
                    break;
            }
        } else {
            $measures = $measure_tables[$measure_type];

            // Multiply the given value by the ratio of the given and desired
            // types to calculate the result 
            $result *= $measures[$to_unit] / $measures[$from_unit];
        }
    }
}
