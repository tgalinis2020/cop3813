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
        // Reduce the array into the sum of its elements, then divide by
        // the length of the array.
        arithmetic: data => data.reduce((acc, cur) => acc + cur, 0) / data.length,
        
        // Same as above, but divide length of array by the sum of the
        // elements' reciprocals.
        harmonic: data => data.length / data.reduce((acc, curr) => acc + (1 / curr), 0),

        geometric: data => data.reduce((acc, curr) => acc * curr, 1) ** (1 / data.length),
    },

    // Note: data must be sorted beforehand to get a correct value
    median: function (data) {
        // Middle index of array, rounded down
        const mid = Math.floor(data.length / 2)

        // If the array has an odd number of elements, the median will be
        // contained directly in the middle of the sorted array. Otherwise,
        // it will be the mean of the two elements in the middle. Note that
        // the two elements are contained in mid - 1 and mid, but
        // Array.slice(x, y) returns the elements from index x up to but
        // not including index y.
        return data.length % 2
            ? data[mid]
            : Stats.mean.arithmetic(data.slice(mid-1, mid+1))
    },

    mode: function (data) {
        const counts = {} // map of all occurrences of numbers in the list
        let occurrences = 0 // number of occurrences of the mode
        let selected = null // the mode itself, assume there isn't one
        let uniqueOccurrences = 0

        // Transform data into set of numbers with their occurences
        data.forEach(n => {
            if (n in counts) {
                counts[n]++
            } else {
                counts[n] = 1
                uniqueOccurrences++
            }
        })

        // If there is no mode, don't even bother looking for it
        if (uniqueOccurrences < data.length) {
            for (const n in counts) {
                if (counts[n] > occurrences) {
                    selected = n
                    occurrences = counts[n]
                }
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

    // If there's no more than 2 samples in the set, there is no variance
    variance: function (data, mean) {
        return data.length > 1
            ? data.map(n => (n - mean) ** 2)
                .reduce((acc, curr) => acc + curr, 0) / (data.length - 1)
            : 0
    },
}
