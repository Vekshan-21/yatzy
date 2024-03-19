<?php
session_start();

// Initialize the game if not already done
if (!isset($_SESSION['tic_tac_toe'])) {
    $_SESSION['tic_tac_toe'] = [
        'board' => ['', '', '', '', '', '', '', '', ''], // Empty board
        'player' => 'X', // Player X starts the game
        'winner' => null, // Winner of the game
        'leaderboard' => [] // Leaderboard array
    ];
}

// Update game state based on user's move
if (isset($_POST['move'])) {
    $move = $_POST['move'];
    $board = $_SESSION['tic_tac_toe']['board'];

    // Check if the move is valid
    if ($board[$move] == '' && !$_SESSION['tic_tac_toe']['winner']) {
        $board[$move] = $_SESSION['tic_tac_toe']['player'];
        $_SESSION['tic_tac_toe']['board'] = $board;

        // Check for a winner
        if (checkWinner($_SESSION['tic_tac_toe']['player'], $board)) {
            $_SESSION['tic_tac_toe']['winner'] = $_SESSION['tic_tac_toe']['player'];
            // Update leaderboard if someone wins
            updateLeaderboard($_SESSION['tic_tac_toe']['player']);
        } else {
            // Switch player
            $_SESSION['tic_tac_toe']['player'] = ($_SESSION['tic_tac_toe']['player'] == 'X') ? 'O' : 'X';
        }
    }
}

// Check if the game is over
if (checkGameOver($_SESSION['tic_tac_toe']['board']) && !$_SESSION['tic_tac_toe']['winner']) {
    $_SESSION['tic_tac_toe']['winner'] = 'draw';
}

// Function to check for a winner
function checkWinner($player, $board) {
    // Check rows, columns, and diagonals
    $winningCombos = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
        [0, 4, 8], [2, 4, 6] // Diagonals
    ];

    foreach ($winningCombos as $combo) {
        if ($board[$combo[0]] == $player && $board[$combo[1]] == $player && $board[$combo[2]] == $player) {
            return true;
        }
    }
    return false;
}

// Function to check if the game is over
function checkGameOver($board) {
    return !in_array('', $board); // If no empty squares left, game over
}

// Function to update leaderboard
function updateLeaderboard($winner) {
    $leaderboard =& $_SESSION['tic_tac_toe']['leaderboard'];
    $leaderboard[] = $winner;

    // Sort leaderboard based on wins
    $leaderboard = array_count_values($leaderboard);
    arsort($leaderboard);

    // Keep only top 10 scores
    $leaderboard = array_slice($leaderboard, 0, 10, true);
}

// Output JSON API
if (isset($_GET['api'])) {
    header('Content-Type: application/json');

    // Prepare JSON response
    $response = [
        'board' => $_SESSION['tic_tac_toe']['board'],
        'player' => $_SESSION['tic_tac_toe']['player'],
        'winner' => $_SESSION['tic_tac_toe']['winner'],
        'leaderboard' => $_SESSION['tic_tac_toe']['leaderboard']
    ];

    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tic Tac Toe</title>
<style>
    .board {
        display: grid;
        grid-template-columns: repeat(3, 100px);
        grid-gap: 5px;
    }
    .cell {
        width: 100px;
        height: 100px;
        border: 1px solid #000;
        font-size: 3em;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .leaderboard {
        margin-top: 20px;
    }
</style>
</head>
<body>
<h1>Tic Tac Toe</h1>
<div class="board">
    <?php foreach ($_SESSION['tic_tac_toe']['board'] as $key => $value): ?>
        <div class="cell" data-index="<?php echo $key; ?>"><?php echo $value; ?></div>
    <?php endforeach; ?>
</div>
<p>Current Player: <?php echo $_SESSION['tic_tac_toe']['player']; ?></p>
<p>Winner: <?php echo ($_SESSION['tic_tac_toe']['winner']) ? $_SESSION['tic_tac_toe']['winner'] : 'None'; ?></p>
<div class="leaderboard">
    <h2>Leaderboard</h2>
    <ol>
        <?php foreach ($_SESSION['tic_tac_toe']['leaderboard'] as $player => $wins): ?>
            <li><?php echo $player; ?>: <?php echo $wins; ?> wins</li>
        <?php endforeach; ?>
    </ol>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cells = document.querySelectorAll('.cell');

    cells.forEach(cell => {
        cell.addEventListener('click', function() {
            makeMove(cell.dataset.index);
        });
    });

    function makeMove(index) {
        fetch('xo.php?api', {
            method: 'POST',
            body: 'move=' + index,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.json())
        .then(data => {
            updateBoard(data.board);
            updateStatus(data);
            updateLeaderboard(data.leaderboard);
        })
        .catch(error => console.error('Error:', error));
    }

    function updateBoard(board) {
        cells.forEach((cell, index) => {
            cell.textContent = board[index];
        });
    }

    function updateStatus(data) {
        document.querySelector('p:nth-of-type(2)').textContent = 'Current Player: ' + data.player;
        document.querySelector('p:nth-of-type(3)').textContent = 'Winner: ' + ((data.winner) ? data.winner : 'None');
    }

    function updateLeaderboard(leaderboard) {
        const leaderboardElement = document.querySelector('.leaderboard ol');
        leaderboardElement.innerHTML = '';

        for (const [player, wins] of Object.entries(leaderboard)) {
            const listItem = document.createElement('li');
            listItem.textContent = player + ': ' + wins + ' wins';
            leaderboardElement.appendChild(listItem);
        }
    }
});
</script>
</body>
</html>
