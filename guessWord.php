<?php
require_once('Classes/WordsGame.php');
require_once('Classes/VocabularyFilter.php');

// Path to the large vocabulary JSON file
$jsonFilePath = 'words_dictionary.json';

runGame($jsonFilePath);
// Function to run the game multiple times and gather statistics
function runGame($jsonFilePath, $totalGames = 100) {
    $wordGame = new WordGame($jsonFilePath);
    $vocabFilter = new VocabularyFilter($jsonFilePath);

    // Game statistics
    $successCount = 0;
    $failureCount = 0;

    for ($i = 0; $i < $totalGames; $i++) {
        $wordGame->selectRandomWord();
        $lengthOfWord = $wordGame->getLetterCount();
        // Instantiate the class with the path to JSON vocabulary file
        // Get words with the specified length for not keep all vocabulary
        $words = $vocabFilter->getWordsByLength($lengthOfWord);

        // Output the additional info for every word
        echo "------------Length of new word: $lengthOfWord---------------\n";
        $count = count($words);
        echo "Count of possible words in dictionary: $count\n";

        $failedAttempts = 0;
        $maxAttempts = 4;
        $guessedLetters = [];
        $failedLetters = [];
        // Game loop
        while ($failedAttempts < $maxAttempts) {
            $lettersPopularity = $vocabFilter->getLetterPopularity($words);
            print_r($lettersPopularity);
            $letter = suggestLetter($lettersPopularity, $guessedLetters, $failedLetters);
            echo "Entered letter: $letter\n";

            // Check if the letter is in the word and update the pattern
            if ($wordGame->checkLetterInWord($letter)) {
                $guessedLetters[] = $letter;
                $pattern = $wordGame->getRevealedPattern($letter);
                
                if($pattern === $wordGame->getSelectedWord()) {
                    $successCount++;
                    echo "Success, the word '$pattern' is guessed\n";
                    break;
                }

                $regexPattern = generateRegexFromPattern($pattern);
                //print($regexPattern);
                // Filter words by regex
                $words = array_filter($words, function($word) use ($regexPattern) {
                    return preg_match($regexPattern, $word);
                });
                echo "Status: $pattern\n";

            } else {
                $failedLetters[] = $letter;
                $failedAttempts++;
                //remove words that have letters that were used and unmatch
                $words = array_filter($words, function($word) use ($vocabFilter, $failedLetters) {
                    return !$vocabFilter->containsFailedLetters($word, $failedLetters);
                });
                echo "Incorrect guess. Attempts left: " . ($maxAttempts - $failedAttempts) . "\n";
            }

            // Count failures if max attempts reached
            if ($failedAttempts >= $maxAttempts) {
                $failureCount++;
            }
        }
    }

        // Output statistics
        echo "Total games played: $totalGames\n";
        echo "Successful guesses: $successCount\n";
        echo "Failed attempts: $failureCount\n";
}

/**
 * Generate a regex pattern from the given pattern string.
 *
 * @param string $pattern The pattern with known letters (e.g., '_es_').
 * @return string The generated regex pattern.
 */
function generateRegexFromPattern(string $pattern): string
{
    // Escape known letters and replace underscores with regex for any letter
    $escapedPattern = preg_quote($pattern, '/');
    $regex = str_replace('_', '[a-z]', $escapedPattern);
    
    // Add start and end anchors to match entire words
    return '/\b' . $regex . '\b/i';
}

function suggestLetter($lettersPopularity, $guessedLetters, $failedLetters) : string {
    $usedLetters = array_merge($guessedLetters, $failedLetters);
    // print_r($usedLetters);
    foreach($lettersPopularity as $letter => $count) {
        if (in_array($letter, $usedLetters)) {
            continue;
        }
        
        return $letter;
    }
}