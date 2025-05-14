<?php
class SequenceProcessor {
    /**
     * Entry point: accepts string input and returns corrected sequence
     */
    public function correctSequence(string $inputSequence): array {
        $sequence = $this->parseSequence($inputSequence);
        $corrected = $this->fillMissing($sequence);
        return $corrected;
    }

    /**
     * Parses the dot-separated string into a nested array
     */
    private function parseSequence(string $input): array {
        $numbers = array_map('trim', explode(',', $input));
        $result = [];

        foreach ($numbers as $number) {
            if (!preg_match('/^([0-9]+(\.[0-9]+)*)$/', $number)) {
                throw new Exception("Invalid number format: $number");
            }

            $parts = explode('.', $number);
            $current = &$result;

            foreach ($parts as $part) {
                $index = (int)$part;
                if (!isset($current[$index])) {
                    $current[$index] = [];
                }
                $current = &$current[$index];
            }
        }

        return $result;
    }

    /**
     * Fills missing sibling keys sequentially where gaps are found
     */
    private function fillMissing(array $tree): array {
        $filled = [];

        if (!empty($tree)) {
            $keys = array_keys($tree);
            $min = min($keys);
            $max = max($keys);

            for ($i = $min; $i <= $max; $i++) {
                if (isset($tree[$i])) {
                    $filled[$i] = $this->fillMissing($tree[$i]);
                } else {
                    $filled[$i] = []; // Fill missing node
                }
            }
        }

        return $filled;
    }

    /**
     * Converts the nested array structure back to dot notation
     */
    public function toDotNotation(array $sequence, string $prefix = ''): array {
        $result = [];

        foreach ($sequence as $index => $subtree) {
            $current = $prefix === '' ? "$index" : "$prefix.$index";
            $result[] = $current;

            if (is_array($subtree) && !empty($subtree)) {
                $child = $this->toDotNotation($subtree, $current);
                $result = array_merge($result, $child);
            }
        }

        return $result;
    }
}
?>
