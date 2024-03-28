<?php
require_once "up.php";
?>

<form method="post" action="player.php" class="background-form">
    <div class="welcome">
        <h1> Tic Tac Toe!</h1>
        <h2>Write your names</h2>

        <div class="p-name">
            <label for="player-x"> Player 1 (X)</label>
            <input type="text" id="player-x" name="player-x" required />
        </div>

        <div class="p-name">
            <label for="player-o"> Player 2 (O)</label>
            <input type="text" id="player-o" name="player-o" required />
        </div>

        <button type="submit">Start</button>
    </div>
</form>


</div>
</body>
</html>

