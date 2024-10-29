# Word Guessing Game

This project is a PHP-based word guessing game that uses a dictionary of words to simulate guessing letters based on their frequency in the English language. The goal of the game is to guess a hidden word within a set number of attempts.

## Overview
The program:
1. Loads words from a JSON dictionary.
2. Filters words based on length and initializes a pattern to represent the hidden word.
3. Guesses letters based on letter frequency in the filtered dictionary subset.
4. Updates the pattern with correctly guessed letters and reduces attempts for incorrect guesses.
5. Repeats for a specified number of rounds, providing a statistical report of wins and losses.

## Requirements
- **PHP**: Make sure PHP is installed on your machine.
- **JSON Dictionary**: A JSON file (`words_dictionary.json`) containing the dictionary words in the same directory as the script.

## Installation
1. Clone or download this repository to your local machine.
2. Place `words_dictionary.json` in the same directory as the PHP script.

## Usage
Run the program by executing the following command in your terminal:
```bash
php guessWord.php

Total games played: 100
Successful guesses: 74
Failed attempts: 26
```
```bash
description of work with debug

------------Length of new word: 8---------------
Count of possible words in dictionary: 51626
Array of popularity of all possible letters in all words with Length 8
(
    [e] => 47123
    [a] => 36080
    [i] => 34173
    ...
)
take first most popular from previous array
Entered letter: e
Status: e_____e_
Array of popularity but filtered with words that match e_____e_
(
    [e] => 1088
    [s] => 309
    [n] => 273
    ...
)
take the most popular unsused letter s
Entered letter: s
Status: e___s_e_

Entered letter: n
Status: en__s_e_

Entered letter: t
Status: en__ste_
Array

Entered letter: d
Status: en__sted

Entered letter: c
Incorrect guess. Attempts left: 3
Array filtered array - words without incorect letter
(
    [e] => 2
    [n] => 1
    [l] => 1
    [i] => 1
    [s] => 1
    [t] => 1
    [d] => 1
)
Entered letter: l
Status: enl_sted

Entered letter: i
Success, the word 'enlisted' is guessed
```