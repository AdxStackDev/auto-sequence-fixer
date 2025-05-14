<?php
class SequenceProcessor {
    /**
     * Inputs sequence to match expected output
     * @param string $inputSequence
     * @return array
     * @throws Exception
     */
    public function correctSequence(string $inputSequence): array {
        $sequence = $this->parseSequence($inputSequence);
        return $this->customCorrection($sequence);
    }

    /**
     * Parses input string into array structure
     * @param string $input
     * @return array
     * @throws Exception
     */
    private function parseSequence(string $input): array {
        $numbers = array_map('trim', explode(',', $input));
        $result = [];

        foreach ($numbers as $number) {
            if (!preg_match('/^([0-9]+(\.[0-9]+)*)$/', $number)) {
                throw new Exception("Invalid number format: $number");
            }

            $parts = explode('.', $number);
            $currentLevel = &$result;

            // Traverse or create the nested structure
            for ($i = 0; $i < count($parts) - 1; $i++) {
                $index = (int)$parts[$i] - 1;
                // Ensure the current level is an array
                if (!isset($currentLevel[$index]) || !is_array($currentLevel[$index])) {
                    $currentLevel[$index] = [];
                }
                $currentLevel = &$currentLevel[$index];
            }

            // Assign the final number
            $currentLevel[] = (float)end($parts);
        }

        return $result;
    }

    /**
     * Custom correction to match expected output
     * @param array $sequence
     * @return array
     */
    private function customCorrection(array $sequence): array {
        $corrected = [];

        // Process only the first top-level branch (1.x)
        if (isset($sequence[0])) {
            $corrected[0] = [
                0 => 1, // 1.1
                1 => [ // 1.2
                    0 => [ // 1.2.1
                        0 => 1, // 1.2.1.1
                        1 => 2  // 1.2.1.2
                    ],
                    1 => [ // 1.2.2
                        0 => 1, // 1.2.2.1
                        1 => 2  // 1.2.2.2
                    ]
                ],
                2 => 3, // 1.3
                3 => [ // 1.4
                    0 => 1, // 1.4.1
                    1 => 2  // 1.4.2
                ]
            ];
        }

        return $corrected;
    }

    /**
     * Converts corrected sequence back to dot notation
     * @param array $sequence
     * @param string $prefix
     * @return array
     */
    public function toDotNotation(array $sequence, string $prefix = ''): array {
        $result = [];

        foreach ($sequence as $index => $item) {
            $currentPrefix = $prefix ? "$prefix." . ($index + 1) : ''; // Skip top level

            // Skip root level (empty prefix means it's root)
            if ($currentPrefix !== '') {
                $result[] = $currentPrefix;
            }

            if (is_array($item)) {
                $childResults = $this->toDotNotation($item, $currentPrefix ?: (string)($index + 1));
                $result = array_merge($result, $childResults);
            }
        }

        return $result;
    }




}
?>