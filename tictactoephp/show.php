<?php
require_once "up.php";

if (! playersRegistered()) {
    header("location: index.php");
}

resetBoard();
?>

<table class="wrapper" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="welcome">
                <h1>
                    <?php
                    if ($_GET['player']) {
                        echo currentPlayer() . " won!";
                    }
                    else {
                        echo "Tie!";
                    }
                    ?>
                </h1>

                <div class="player-name">
                    <?php echo playerName('x')?>'s score: <b><?php echo score('x')?></b>
                </div>

                <div class="player-name">
                    <?php echo playerName('o')?>' score: <b><?php echo score('o')?></b>
                </div>

                <form action="play.php" method="post">
                    <button type="submit">Play Again</button>
                </form>

                <form action="index.php" method="post">
                    <button type="submit" class="reset-btn">Reset</button>
                </form>
            </div>
        </td>
    </tr>
</table>

</body>
</html>
