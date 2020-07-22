/**
 * Frond-end component for baby names web application. Requires jQuery.
 * 
 * @author Thomas Galinis <tgalinis2020@fau.edu>
 */
$(function() {
    'use strict'

    // These conveniently log messages and disable functionality during testing.
    // Setting the debug key to true will show messages in the browser's console.
    // Setting dry run to true will prevent the app from communicating with the API.
    const settings = { debug: true, dryRun: true }

    const API_ROOT = '/~tgalinis2020/p7/api'
    const API_NAMES_ENDPOINT = `${API_ROOT}/names.php`
    const API_VOTES_ENDPOINT = `${API_ROOT}/votes.php`

    const { ajax } = window.jQuery
    const suggestions = $('#suggestions')
    const isMale = $('#baby_is-male')
    const name = $('#baby_name')
    const vote = $('#vote')
    const boyNames = $('#top-10-boy-names')
    const girlNames = $('#top-10-girl-names')

    // A decorator function that runs the callback only when a certain
    // amount of time has passed since the last time the function was invoked.
    //
    // Inspired by the following implementation by David Walsh:
    // https://davidwalsh.name/javascript-debounce-function
    function debounced(time, callback, override) {
        let active = false
        let timeout = null

        return function (...args) {
            // Run the following after the timeout is complete.
            //
            // A nice quirk of arrow functions is that they have no "this",
            // so one can reference the parent context without setting it to
            // another variable. Before arrow functions were introduced,
            // it was common to do "var self = this" to reference the parent
            // function's context.
            const handler = () => {
                callback.apply(this, args)
                active = false
            }

            active && window.clearTimeout(timeout)

            // If override condition is set, ignore debouncing behavior.
            if (typeof override === 'function' && override.apply(this, args)) {
                handler()
            } else {
                timeout = window.setTimeout(handler, time)
                active = true
            }
        }
    }

    // Look up the baby names database and retrieve names that are similar to
    // the user's input.
    const searchName = (name, gender) => ajax({
        method: 'GET',
        url: API_NAMES_ENDPOINT,
        data: { name, gender },
        success: function (res) {
            suggestions.empty() // clear previous suggestions

            // append new suggestions
            for (const baby of res.data) {
                const option = $('<option>')
                option.attr('value', baby.name)
                suggestions.append(option)
            }
        }
    })

    const updateLeaderboards = () => ajax({
        method: 'GET',
        url: API_NAMES_ENDPOINT,
        success: function (res) {
            const boysTbl = $('<table class="table"></table>')
            const girlsTbl = $('<table class="table"></table>')
            let boysCounter = 0
            let girlsCounter = 0

            // data should contain the names sorted by popularity
            for (const { name, gender } of res.data) {
                const tr = $('<tr></tr>')
                const rank_td = $('<td></td>')
                const name_td = $('<td></td>')
                
                tr.append(rank_td)
                tr.append(name_td)

                name_td.html(name)

                switch (gender) {
                    case 'M':
                        rank_td.html(++boysCounter)
                        boysTbl.append(tr)
                        break;

                    case 'F':
                        rank_td.html(++girlsCounter)
                        girlsTbl.append(tr)
                        break;
                }  
            }

            // remove the old tables and show new data
            boyNames.empty()
            girlNames.empty()
            boyNames.append(boysTbl)
            girlNames.append(girlsTbl)
        }
    })

    const placeVote = name => ajax({
        method: 'POST',
        url: API_VOTES_ENDPOINT,
        data: { name },
        success: () => updateLeaderboards()
    })

    // Populate suggestions list with applicable baby names.
    // Debounce the input stream every three-quarters of a second to prevent
    // unecessary use of bandwidth.
    name.keyup(debounced(
        750, // Debounce time (in milliseconds)

        // Run the following after the timeout.
        // Use object destructuring to get the key and target of the event.
        function ({ key, target }) {
            const { value } = target

            // Don't do anything if value is empty.
            if (value === '') return

            switch (key) {
                case 'Enter':
                    !settings.dryRun && placeVote()

                    settings.debug && console.log(`Voting for ${value}`)
                    break;

                default:
                    !settings.dryRun
                        && searchName(value, isMale.checked ? 'M' : 'F')
                    
                    settings.debug && value !== '' && console.log(value)
            }
        },

        // Ignore debouncing when enter key is pressed.
        ({ key }) => key === 'Enter'
    ))

    // Place a vote.
    vote.click(function () {
        // Don't do anything if value is empty.
        if (name.val() === '') return

        !settings.dryRun && placeVote(name.val())
        settings.debug && console.log(`Voting for ${name.val()}`)
    })

    // show popular baby names when page loads
    !settings.dryRun && updateLeaderboards()
})