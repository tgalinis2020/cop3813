/**
 * Statistical analysis utilities
 *
 * @author Thomas Galinis <tgalinis2020@fau.edu>
 */

'use strict'

/**
 * A library of functions to perform basic statistical analysis on data.
 */
const Stats = {
    mean: {
        arithmetic: function (data) {
            // Reduce the array into the sum of its elements, then divide by
            // the length of the array
            return data.reduce((acc, cur) => acc + cur, 0) / data.length
        },

        harmonic: function (data) {
            return data.length / data.reduce((acc, curr) => 1 / curr + acc, 0)
        },
    },

    // Note: data must be sorted beforehand to get a correct value
    median: function (data) {
        // Middle index of array, rounded down
        const mid = Math.floor(data.length / 2)

        if (data.length % 2) {
            // Array has an odd number of elements. Median will be contained directly
            // in the middle of the sorted array
            return data[mid]
        } else {
            // Array has an even number of elements. Median will be the mean of
            // the two elements in the middle of the sorted array
            return Stats.mean.arithmetic(data.slice(mid-1, mid+1))
        }

    },

    mode: function (data) {
        const counts = {} // map of all occurrences of numbers in the list
        let occurrences = 0 // number of occurrences of the mode
        let selected = null // the mode itself

        // Transform data into set of numbers with their occurences
        data.forEach(n => {
            if (n in counts) {
                counts[n]++
            } else {
                counts[n] = 1
            }
        })

        for (const n in counts) {
            if (counts[n] > occurrences) {
                selected = n
                occurrences = counts[n]
            }
        }

        return selected
    },

    max: function (data) {
        const samples = [...data] // make a deep copy of the array
        const last = samples.pop()

        // Reduce the array into its largest value
        // Initialize the accumilator with the last element of the array
        return samples.reduce((acc, curr) => curr > acc ? curr : acc, last)
    },

    min: function (data) {
        const samples = [...data] // make a deep copy of the array
        const last = samples.pop()

        // Reduce the array into its smallest value
        // Initialize the accumilator with the last element of the array
        return samples.reduce((acc, curr) => curr < acc ? curr : acc, last)
    },
}

// Convenience utility for analyzing a list of numbers
function analyzeSamples(samples) {
    let variance
    let stddev

    samples.sort((a, b) => a - b) // sort in ascending order

    const arithmetic = Stats.mean.arithmetic(samples)

    const harmonic = Stats.mean.harmonic(samples)

    const geometric = (arithmetic * harmonic) ** 0.5

    const max = Stats.max(samples)

    const min = Stats.min(samples)

    if (samples.length > 1) {
        variance = samples.map(n => (n - arithmetic) ** 2)
            .reduce((acc, curr) => acc + curr, 0) / (samples.length - 1)

        stddev = variance ** 0.5
    } else {
        variance = stddev = 0
    }

    return {
        mean: { arithmetic, harmonic, geometric },
        median: Stats.median(samples),
        mode: Stats.mode(samples),
        variance,
        stddev,
        max,
        min,
        range: max - min,
    }
}
