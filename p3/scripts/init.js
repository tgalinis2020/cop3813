/**
 * Initialize the web application
 *
 * @author Thomas Galinis <tgalinis2020@fau.edu>
 */

// Wrapping bootstrapping code in an immediately invoked
// anonymous function to avoid polluting the global namespace.
(function () {
    'use strict'

    // Output element IDs
    const outputIds = [
        'arithmetic-mean', 'harmonic-mean', 'geometric-mean', 'median', 'mode',
        'minimum', 'maximum', 'range', 'variance', 'stddev' 
    ]

    const outputEls = outputIds.map(id => document.getElementById(id))
    const input = document.getElementById('number-list')
    const form = document.getElementById('stats-form')

    // RegEx for matching a comma-separated list of numbers, including
    // numbers with decimal places. Excess whitespace is allowed.
    const listOfNumbers = /^\s*\-?((\d*\.\d+)|(\d+\.\d*)|(\d+))\s*(\,\s*\-?((\d*\.\d+)|(\d+\.\d*)|(\d+))\s*)*$/
    
    form.addEventListener('submit', function (event) {
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
        const samples = input.value.split(',').map(n => parseFloat(n))

        samples.sort((a, b) => a - b) // sort in ascending order

        let geometric = Stats.mean.geometric(samples)
        const arithmetic = Stats.mean.arithmetic(samples)
        const harmonic = Stats.mean.harmonic(samples)
        const variance = Stats.variance(samples, arithmetic)
        const max = Stats.max(samples)
        const min = Stats.min(samples)

        // If it isnt't possible to calculate the geometic mean using its
        // formula because the product of all the samples is negative, try
        // using the alternative approximation using the arithmetic and
        // harmonic means...
        if (isNaN(geometric)) {
            let product = arithmetic * harmonic

            // ...and if that doesn't work, then the geometric mean isn't
            // very useful in this case
            geometric = product > 0 ? product ** 0.5 : null
        }

        // Note: this array must be parallel to outputEls
        const outputVals = [
            arithmetic, harmonic, geometric,
            Stats.median(samples),
            Stats.mode(samples),
            min,
            max,
            max - min, // range is the difference between max and min
            variance,
            variance ** 0.5 // stddev is the square root of variance
        ]

        // Remove invalid class and remove feedback now that input is valid
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid')
            feedback.innerHTML = null 
        }

        // Show all measures up to two decimal places, if applicable
        for (const i in outputEls) {
            const val = outputVals[i]

            outputEls[i].innerHTML = val === null
                ? 'Not Applicable'
                : Math.round(val * 100) / 100
        }
    })
})()
