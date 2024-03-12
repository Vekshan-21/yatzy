// yatzy_game.js
class YatzyGame {
    constructor() {
        // Initialize game state
        this.currentTurn = 0; // Represents the current turn (0, 1, 2, or 3)
        this.diceValues = Array(5).fill(0); // Represents the values on each of the 5 dice
        this.diceState = Array(5).fill(true); // Represents the state of each die (keep or reroll)
    }

    // Example method to simulate advancing to the next turn
    nextTurn() {
        if (this.currentTurn < 3) {
            this.currentTurn++;
            // Reset dice state for the new turn
            this.diceState = Array(5).fill(true);
        } else {
            console.log("Game over. Cannot proceed to the next turn.");
        }
    }

    // Example method to roll the dice for the current turn
    rollDice() {
        if (this.currentTurn < 3) {
            for (let i = 0; i < this.diceValues.length; i++) {
                if (this.diceState[i]) {
                    this.diceValues[i] = rollDice(); // Assuming a rollDice function is available
                }
            }
        } else {
            console.log("Cannot roll the dice. The game is over.");
        }
    }

    // Your other game state-related methods can go here
}

// Node.js export statement (if you are using Node.js modules)
// module.exports = { YatzyGame };
