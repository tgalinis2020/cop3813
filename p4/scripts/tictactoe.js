/**
 * Based on given code
 */
$(function () {
    'use strict'

    const squares = []
    const SIZE = 3
    const EMPTY = '&nbsp;'
    const winConditions = [7, 56, 448, 73, 146, 292, 273, 84]
    const score = { 'X': 0, 'O': 0 }
    const resetBtn = $('#game-reset')
    const statusEl = $('#game-status')
    const modal = {
        el: $('#game-modal'),
        header: $('#game-modal__header'),
        body: $('#game-modal__body'),
        footer: $('#game-modal__footer'),
        playAgainBtn: $('#game-modal__play-again'),
        maybeBtn: $('#game-modal__maybe'),
    }
    let moves
    let turn = 'X'
    let gameover

    function newGame() {
        gameover = false
        turn = 'X'
        score['X'] = 0
        score['O'] = 0
        moves = 0
        squares.forEach(square => square.html(EMPTY))
        statusEl.html(`${turn}'s turn`)
    }

    function win(score) {
        for (const condition of winConditions) {
            if ((condition & score) === condition) {
                return true
            }
        }
        return false
    }

    function set() {
        if ($(this).html() !== EMPTY || gameover) {
            return
        }

        $(this).html(`<span class="player-${turn.toLowerCase()}">${turn}</span>`)
        moves += 1
        score[turn] |= $(this)[0].indicator

        if (win(score[turn])) {
            [modal.header, statusEl].forEach(el => el.html(`${turn} wins!`))
            modal.el.modal('show')
            gameover = true
        } else if (moves === SIZE * SIZE) {
            [modal.header, statusEl].forEach(el => el.html('It\'s a tie!'))
            modal.el.modal('show')
            gameover = true
        } else {
            turn = turn === 'X' ? 'O' : 'X'
            statusEl.html(`${turn}'s turn`)
        }
    }

    function init() {
        let indicator = 1
        const board = $('<table class="game-board"></table>')

        for (let i = 0; i < SIZE; i += 1) {
            const row = $("<tr>")

            board.append(row)

            for (let j = 0; j < SIZE; j += 1) {
                const cell = $("<td></td>")

                cell[0].indicator = indicator
                cell.click(set)
                row.append(cell)
                squares.push(cell)
                indicator += indicator
            }
        }

        // Attach under tictactoe if present, otherwise to body.
        $(document.getElementById("tictactoe") || document.body).append(board)

        newGame()
    }

    modal.maybeBtn.click(function () {
        if (Math.random() <= 0.6) {
            newGame()
        }

        modal.el.modal('hide')
    })

    modal.playAgainBtn.click(function () {
        newGame()
        modal.el.modal('hide')
    })

    resetBtn.click(newGame)

    init()
})
