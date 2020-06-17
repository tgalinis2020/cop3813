/**
 * Initialize the web application
 *
 * @author Thomas Galinis <tgalinis2020@fau.edu>
 */

// Wrapping bootstrapping code in an immediately invoked
// anonymous function to avoid polluting the global namespace.
(function () {
    'use strict'

    // Save some keystrokes, going be typing this a lot
    const getById = id => document.getElementById(id)

    // Output elements
    const arithmeticMean = getById('arithmeticMean')
    const harmonicMean = getById('harmonicMean')
    const geometricMean = getById('geometricMean')
    const median = getById('median')
    const mode = getById('mode')
    const variance = getById('variance')
    const stddev = getById('stddev')
    const min = getById('minimum')
    const max = getById('maximum')
    const range = getById('range')
    const feedback = getById('feedback')

    // Input elements
    const input = getById('numberList')
    const submit = getById('calculate')

    // RegEx for matching a comma-separated list of numbers, including
    // numbers with decimal places. White space is allowed.
    const listOfNumbers = /^\s*(\d*\.?\d*)\s*(\,\s*(\d*\.?\d*)\s*)*$/

    // Form validity status. Intially invalid because input textbox is empty.
    let isValid = false

    // Validate input as the user types it
    input.addEventListener('keyup', function (event) {
        if (input.value === '') {
            input.classList.remove('is-invalid')
            submit.disabled = true
            isValid = false

        } else if (listOfNumbers.test(input.value)) {
            if (!isValid) {
                input.classList.remove('is-invalid')
                submit.disabled = false
                feedback.innerHTML = null 
                isValid = true
            }

        } else {
            if (isValid) {
                input.classList.add('is-invalid')
                submit.disabled = true
                feedback.innerHTML = "Please enter a valid comma-separated list of numbers."
                isValid = false
            }
        }
    })
    
    // Since the form is validated as the user types their input, the form will
    // be disabled until the input is valid.
    getById('stats-form').addEventListener('submit', function (event) {
        event.preventDefault()

        // Split the input string by the commas and convert each
        // stringified number to a float
        const nums = input.value.split(',').map(n => parseFloat(n))
        const samples = analyzeSamples(nums)
        const outputs = [
            [arithmeticMean, samples.mean.arithmetic],
            [harmonicMean, samples.mean.harmonic],
            [geometricMean, samples.mean.geometric],
            [median, samples.median],
            [mode, samples.mode],
            [variance, samples.variance],
            [stddev, samples.stddev],
            [min, samples.min],
            [max, samples.max],
            [range, samples.range],
        ]

        // Show all measures up to two decimal places
        outputs.forEach(([el, val]) => el.innerHTML = Math.round(val * 100) / 100)
    })
})()
