const cells = document.querySelectorAll(".cell");
const statusText = document.querySelector("#statusText");
const restartBtn = document.querySelector("#restartBtn");
const resetScoreBtn = document.getElementById("resetScoreBtn");

let scoreX = 0;
let scoreO = 0;
const winConditions = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6]
];
let options = ["", "", "", "", "", "", "", "", ""];
let currentPlayer = "X";
let running = false;

initializeGame();

function initializeGame(){
    cells.forEach(cell => cell.addEventListener("click", cellClicked));
    restartBtn.addEventListener("click", restartGame);
    statusText.textContent = `${currentPlayer}'s turn`;
    running = true;
}

function cellClicked(){
    const cellIndex = this.getAttribute("cellIndex");

    if(options[cellIndex] != "" || !running){
        return;
    }

    updateCell(this, cellIndex);
    checkWinner();
}

function updateCell(cell, index){
    options[index] = currentPlayer;
    cell.textContent = currentPlayer;
}

function changePlayer(){
    currentPlayer = (currentPlayer == "X") ? "O" : "X";
    statusText.textContent = `${currentPlayer}'s turn`;
}

function checkWinner(){
    let roundWon = false;

    for(let i = 0; i < winConditions.length; i++){
        const condition = winConditions[i];
        const cellA = options[condition[0]];
        const cellB = options[condition[1]];
        const cellC = options[condition[2]];

        if(cellA == "" || cellB == "" || cellC == ""){
            continue;
        }
        if(cellA == cellB && cellB == cellC){
            roundWon = true;
            break;
        }
    }

    if(roundWon){
        gameOverScreen(currentPlayer);
        updateScore(currentPlayer);
    }
    else if(!options.includes("")){
        gameOverScreen(null);
    }
    else{
        changePlayer();
    }
}

function updateScore(winner) {
    if (winner === "X") {
        scoreX++;
        document.getElementById("player-x-score").textContent = `Player X: ${scoreX}`;
    } else if (winner === "O") {
        scoreO++;
        document.getElementById("player-o-score").textContent = `Player O: ${scoreO}`;
    }
}

function gameOverScreen(winnerText) {
    let text = "Draw!";
    let backgroundClass = "default-background";

    if (winnerText === "X") {
        text = "Winner is X!";
        backgroundClass = "x-winner-background";
    } else if (winnerText === "O") {
        text = "Winner is O!";
        backgroundClass = "o-winner-background";
    }

    const gameOverArea = document.getElementById("gameOverArea");
    const gameOverText = document.getElementById("gameOverText");
    const gameOverSound = new Audio("game_over.wav");

    if (gameOverArea && gameOverText) {
        gameOverArea.className = `visible ${backgroundClass}`;
        gameOverText.innerText = text;

        
        gameOverSound.play();
    }
}

resetScoreBtn.addEventListener("click", resetScore);
function resetScore() {
    scoreX = 0;
    scoreO = 0;
    document.getElementById("player-x-score").textContent = `Player X: ${scoreX}`;
    document.getElementById("player-o-score").textContent = `Player O: ${scoreO}`;
}

function restartGame(){
    currentPlayer = "X";
    options = ["", "", "", "", "", "", "", "", ""];
    statusText.textContent = `${currentPlayer}'s turn`;
    cells.forEach(cell => cell.textContent = "");
    running = true;
    gameOverArea.className = "hidden";
    document.body.style.backgroundImage = "url('xoback.gif')";
}

document.getElementById("gameOverRestartBtn").addEventListener("click", restartGame);
