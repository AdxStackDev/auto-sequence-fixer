<?php
require_once 'src/SequenceProcessor.php';
require_once 'src/InputValidator.php';
require_once 'src/export.php';


$inputSequence = $_POST['sequence'] ?? '';
$result = ['status' => 'idle', 'corrected' => [], 'dotNotation' => [], 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($inputSequence)) {
    $validator = new InputValidator();
    if ($validator->validate($inputSequence)) {
        try {
            $processor = new SequenceProcessor();
            $correctedSequence = $processor->correctSequence($inputSequence);
            $dotNotation = $processor->toDotNotation($correctedSequence);
            $result = [
                'status' => 'success',
                'corrected' => $correctedSequence,
                'dotNotation' => $dotNotation,
                'error' => ''
            ];
        } catch (Exception $e) {
            $result = ['status' => 'error', 'corrected' => [], 'dotNotation' => [], 'error' => $e->getMessage()];
        }
    } else {
        $result = ['status' => 'error', 'corrected' => [], 'dotNotation' => [], 'error' => 'Invalid input format'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sequence Correction & Visualization</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .error { color: red; }
        canvas { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sequence Correction & Visualization</h1>
        <form method="POST">
            <label for="sequence">Enter Sequence (e.g., 1.1,1.2,1.3.1,1.3.1.1,1.3.1.3,1.3.2,1.3.2.1,1.3.2.2,2.1,2.2,2.3.1,2.3.2,2.3.3):</label><br>
            <textarea id="sequence" name="sequence" rows="4" cols="50"><?php echo htmlspecialchars($inputSequence); ?></textarea><br>
            <button type="submit">Process Sequence</button>
        </form>

        <?php if ($result['status'] === 'error'): ?>
            <p class="error"><?php echo htmlspecialchars($result['error']); ?></p>
        <?php elseif ($result['status'] === 'success'): ?>
            <h2>Corrected Sequence</h2>
            <pre><?php echo htmlspecialchars(json_encode($result['dotNotation'], JSON_PRETTY_PRINT)); ?></pre>
                <form method="POST" action="src/export.php">
                    <?php foreach ($result['dotNotation'] as $sequence): ?>
                        <input type="hidden" name="sequences[]" value="<?php echo htmlspecialchars($sequence); ?>">
                    <?php endforeach; ?>
                    <button type="submit">📥 Export to CSV</button>
                </form>
            <canvas id="sequenceChart"></canvas>
        <?php endif; ?>
    </div>

    <?php if ($result['status'] === 'success'): ?>

    <script>
        const ctx = document.getElementById('sequenceChart').getContext('2d');
        const dotNotation = <?php echo json_encode($result['dotNotation']); ?>;

        // Extract labels and values for chart
        const labels = dotNotation;
        const values = dotNotation.map(item => {
            const parts = item.split('.');
            return parseFloat(parts[parts.length - 1]);
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Corrected Sequence',
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Value' } },
                    x: { title: { display: true, text: 'Sequence Position' } }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>