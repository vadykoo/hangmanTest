<?php
class VocabularyFilter
{
    private array $words;

    public function __construct(string $jsonFilePath)
    {
        // Load words from the JSON file
        $jsonContent = file_get_contents($jsonFilePath);
        if ($jsonContent === false) {
            throw new Exception("Error reading JSON file.");
        }
        $this->words = array_keys(json_decode($jsonContent, true) ?? []);
    }

    /**
     * Gets words filtered by their length.
     *
     * @param int $length The desired length of the words.
     * @return array The filtered array of words.
     */
    public function getWordsByLength(int $length): array
    {
        return array_filter($this->words, function ($word) use ($length) {
            return strlen($word) === $length;
        });
    }

    /**
     * Builds an array of letters sorted by their popularity from the provided words.
     *
     * @param array $words The array of words to analyze for letter popularity.
     * @return array An associative array of letters sorted by popularity.
     */
    public function getLetterPopularity(array $words): array
    {
        $letterCounts = [];

        // Count the occurrences of each letter in the provided words
        foreach ($words as $word) {
            $letters = str_split($word);
            foreach ($letters as $letter) {
                if (!isset($letterCounts[$letter])) {
                    $letterCounts[$letter] = 0;
                }
                $letterCounts[$letter]++;
            }
        }

        // Sort letters by their counts in descending order
        arsort($letterCounts);

        return $letterCounts;
    }

    /**
     * Checks if a word contains any of the failed letters.
     *
     * @param string $word The word to check.
     * @param array $failedLetters The array of failed letters.
     * @return bool True if the word contains failed letters, false otherwise.
     */
    public function containsFailedLetters(string $word, array $failedLetters): bool
    {
        foreach ($failedLetters as $letter) {
            if (stripos($word, $letter) !== false) {
                return true; // Word contains a failed letter
            }
        }
        return false; // No failed letters found
    }
}
