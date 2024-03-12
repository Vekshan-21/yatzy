// yatzy_engine.js

// Example function to calculate the score for a specific type (e.g., ones, twos)
function calculateTurnScore(game, scoreType) {
    // Assuming game is an instance of YatzyGame and has a property 'diceValues'
    const diceValues = game.diceValues;

    // Implement logic for calculating the score based on the scoreType
    let score = 0;

    switch (scoreType) {
        case "ones":
            score = diceValues.filter(value => value === 1).reduce((sum, value) => sum + value, 0);
            break;
        // Implement other score types as needed

        default:
            console.log("Invalid score type.");
            break;
    }

    return score;
}

// Example function to update the global score of the game, including the bonus calculation
function updateTotalScore(game) {
    // Assuming game is an instance of YatzyGame and has properties like 'diceValues', 'totalScore', etc.
    const diceValues = game.diceValues;
    const currentTurn = game.currentTurn;

    // Implement logic for calculating the score for the current turn and updating the total score
    const turnScore = calculateTurnScore(game, "ones"); // Example using the "ones" score type

    // Update the total score
    game.totalScore += turnScore;

    // Check for bonus eligibility (assuming Yatzy bonus rules)
    if (game.totalScore >= 63) {
        game.bonus = 35;
        console.log("Bonus applied!");
    }

    return game.totalScore;
}

// Node.js export statement (if you are using Node.js modules)
// module.exports = { calculateTurnScore, updateTotalScore };
