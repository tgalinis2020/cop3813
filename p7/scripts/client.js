/**
 * Frond-end component for baby names web application. Requires jQuery.
 * 
 * @author Thomas Galinis <tgalinis2020@fau.edu>
 */

// These conveniently log messages and disable functionality during testing.
// Setting the debug key to true will show messages in the browser's console.
// Setting dry run to true will prevent the app from communicating with the API.
const settings = { debug: false, dryRun: false }

$(function() {
    'use strict'

    const API_ROOT = '/~tgalinis2020/p7/api'
    const API_NAMES_ENDPOINT = `${API_ROOT}/names.php`
    const API_VOTES_ENDPOINT = `${API_ROOT}/votes.php`

    const { ajax } = window.jQuery
    const suggestions = $('#suggestions')
    const isMale = $('#baby_is-male')
    const isFemale = $('#baby_is-female')
    const nameInput = $('#baby_name')
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
        data: { name, gender, limit: 50 },
        success: function (res) {
            suggestions.empty() // clear previous suggestions

            // append new suggestions
            for (const baby of res.data) {
                const option = $('<span></span>')

                option.addClass('badge badge-secondary')
                option.html(baby.name)
                option.click(() => {
                    nameInput.val(baby.name)
                    suggestions.empty()
                    settings.debug && console.log(`Selecting ${baby.name}`)
                })

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

            // Add table headers
            for (const tbl of [boysTbl, girlsTbl]) {
                const head_tr = $('<tr></tr>')
                const rank_th = $('<th>Rank</th>')
                const name_th = $('<th>Name</td>')
                const votes_th = $('<th>Votes</th>')

                head_tr.append(rank_th);
                head_tr.append(name_th);
                head_tr.append(votes_th);

                tbl.append(head_tr);
            }

            // data should contain the names sorted by popularity
            for (const { name, gender, votes } of res.data) {
                const tr = $('<tr></tr>')
                const rank_td = $('<td></td>')
                const name_td = $('<td></td>')
                const votes_td = $('<td></td>')
                
                tr.append(rank_td)
                tr.append(name_td)
                tr.append(votes_td)

                name_td.html(name)
                votes_td.html(votes)

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

    const placeVote = (name, gender) => ajax({
        method: 'POST',
        url: API_VOTES_ENDPOINT,
        data: { name, gender },
        statusCode: {
            200: () => {
                nameInput.removeClass('is-invalid')
                updateLeaderboards()
            },
            406: () => {
                nameInput.addClass('is-invalid')
            }
        }
    })

    // Populate suggestions list with applicable baby names.
    // Debounce the input stream every three-quarters of a second to prevent
    // unecessary use of bandwidth.
    nameInput.keyup(debounced(
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
                        && searchName(value, isMale.is(':checked') ? 'M' : 'F')
                    
                    settings.debug && value !== '' && console.log(value)
            }
        },

        // Ignore debouncing when enter key is pressed.
        ({ key }) => key === 'Enter'
    ))

    // Update suggestions if radio buttons have been selected.
    for (const [el, gender] of [[isMale, 'M'], [isFemale, 'F']]) {
        el.change(function () {
            if (nameInput.val() === '' || !el.is(':checked')) return

            !settings.dryRun && searchName(nameInput.val(), gender)
            settings.debug && value !== '' && console.log(value)
        })
    }

    // Place a vote.
    vote.click(function () {
        // Don't do anything if value is empty.
        if (nameInput.val() === '') return
        suggestions.empty()
        !settings.dryRun && placeVote(nameInput.val(), isMale.is(':checked') ? 'M' : 'F')
        settings.debug && console.log(`Voting for ${name.val()}`)
    })

    // show popular baby names when page loads
    !settings.dryRun && updateLeaderboards()
})
