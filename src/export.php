<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sequences'])) {
    $sequences = $_POST['sequences'];

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="corrected_sequences.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Corrected Sequence']);

    foreach ($sequences as $seq) {
        fputcsv($output, [$seq]);
    }

    fclose($output);
    exit;
}
