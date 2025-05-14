<?php
class InputValidator {
    /**
     * Validates input sequence format
     * @param string $input
     * @return bool
     */
    public function validate(string $input): bool {
        if (empty($input)) {
            return false;
        }

        // Basic format validation: numbers, commas, and periods
        if (!preg_match('/^[0-9,. ]+$/', $input)) {
            return false;
        }

        // Validate number format
        $numbers = array_map('trim', explode(',', $input));
        foreach ($numbers as $number) {
            if (!preg_match('/^([0-9]+(\.[0-9]+)*)$/', $number)) {
                return false;
            }
        }

        return true;
    }
}
?>