/**
 * Initialize the web application
 *
 * @author Thomas Galinis <tgalinis2020@fau.edu>
 */

// Wrapping bootstrapping code in an immediately invoked
// anonymous function to avoid polluting the global namespace.
(function () {
    'use strict'

    // Save some keystrokes, going be typing this a lot...
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
    // numbers with decimal places. Excess whitespace is allowed.
    const listOfNumbers = /^\s*((\d*\.\d+)|(\d+\.\d*)|(\d+))\s*(\,\s*((\d*\.\d+)|(\d+\.\d*)|(\d+))\s*)*$/

    // Form validity status. Assume true until proven otherwise
    let isValid = true
    
    // Since the form is validated as the user types their input, the form will
    // be disabled until the input is valid.
    getById('stats-form').addEventListener('submit', function (event) {
        event.preventDefault()

        // If input isn't valid when submitting the form, show feedback message
        // and don't perform anything else.
        if (!listOfNumbers.test(input.value)) {
            input.classList.add('is-invalid')
            feedback.innerHTML = "Please enter a valid comma-separated list of numbers."
            return
        }

        // Split the input string by the commas and convert each
        // stringified number to a float
        const nums = input.value.split(',').map(n => parseFloat(n))
        const samples = analyzeSamples(nums) // see scripts/stats.js for details
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

        input.classList.remove('is-invalid')
        feedback.innerHTML = null 

        // Show all measures up to two decimal places.
        // Use array destructuring to pick out the element and value from
        // the parameter.
        outputs.forEach(([el, val]) => el.innerHTML = Math.round(val * 100) / 100)

        // Mode is an outlier: if there are no repeated numbers, the mode
        // is not applicable.
        mode.innerHTML = samples.mode || 'Not Applicable'
    })
})()
