<?php

class WordGame
{
    private array $words;
    public string $selectedWord;
    private int $letterCount;
    private array $revealedLetters = [];

    /**
     * WordGame constructor.
     * Loads words from a dictionary JSON file and selects a random word.
     *
     * @param string $jsonFilePath Path to the JSON file containing the dictionary.
     * @throws Exception if the JSON file cannot be read or decoded.
     */
    public function __construct(string $jsonFilePath)
    {
        // Load and decode JSON data
        $jsonContent = file_get_contents($jsonFilePath);
        if ($jsonContent === false) {
            throw new Exception("Error reading JSON file.");
        }

        $this->words = array_keys(json_decode($jsonContent, true) ?? []);
        if (empty($this->words)) {
            throw new Exception("No words found in dictionary.");
        }
    }

    /**
     * Selects a random word from the dictionary and calculates its letter count.
     */
    public function selectRandomWord(): void
    {
        $this->selectedWord = $this->words[array_rand($this->words)];
        $this->letterCount = strlen($this->selectedWord);
        $this->revealedLetters = [];
    }

    /**
     * Checks if a letter exists in the selected word.
     *
     * @param string $letter The letter to check.
     * @return bool True if the letter is in the word, false otherwise.
     */
    public function checkLetterInWord(string $letter): bool
    {
        return stripos($this->selectedWord, $letter) !== false;
    }

    /**
     * Returns the selected word's letter count.
     *
     * @return int The number of letters in the selected word.
     */
    public function getLetterCount(): int
    {
        return $this->letterCount;
    }

    /**
     * Returns the selected word.
     *
     * @return string The selected word.
     */
    public function getSelectedWord(): string
    {
        return $this->selectedWord;
    }

    /**
     * Updates the revealed pattern based on guessed letters.
     *
     * @param string $letter The guessed letter.
     * @return string The current pattern with matched letters revealed.
     */
    public function getRevealedPattern(string $letter): string
    {
        // Add the guessed letter to the list of revealed letters if it's in the word
        if ($this->checkLetterInWord($letter)) {
            $this->revealedLetters[] = strtolower($letter);
        }

        // Build the pattern with matched letters and underscores for hidden ones
        $pattern = '';
        foreach (str_split($this->selectedWord) as $char) {
            if (in_array(strtolower($char), $this->revealedLetters)) {
                $pattern .= $char; // reveal the matched letter
            } else {
                $pattern .= '_';   // mask the letter
            }
        }

        return $pattern;
    }
}
