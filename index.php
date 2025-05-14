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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 text-center">Sequence Correction & Visualization</h1>
        
        <form method="POST" class="space-y-4">
            <label for="sequence" class="block font-medium text-gray-700">Enter Sequence:</label>
            <textarea id="sequence" name="sequence" rows="4" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="e.g., 1.1,1.2,1.3.1,..."><?php echo htmlspecialchars($inputSequence); ?></textarea>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">Process Sequence</button>
        </form>

        <?php if ($result['status'] === 'error'): ?>
            <div class="mt-4 text-red-600 font-semibold">
                <?php echo htmlspecialchars($result['error']); ?>
            </div>
        <?php elseif ($result['status'] === 'success'): ?>
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-green-700 mb-2">Corrected Sequence</h2>
                <pre class="bg-gray-100 p-4 rounded border overflow-x-auto text-sm"><?php echo htmlspecialchars(json_encode($result['dotNotation'], JSON_PRETTY_PRINT)); ?></pre>

                <form method="POST" action="src/export.php" class="mt-4">
                    <?php foreach ($result['dotNotation'] as $sequence): ?>
                        <input type="hidden" name="sequences[]" value="<?php echo htmlspecialchars($sequence); ?>">
                    <?php endforeach; ?>
                    <button type="submit" class="mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">📥 Export to CSV</button>
                </form>

                <div class="mt-6">
                    <canvas id="sequenceChart" class="w-full h-64"></canvas>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($result['status'] === 'success'): ?>
    <script>
        const ctx = document.getElementById('sequenceChart').getContext('2d');
        const dotNotation = <?php echo json_encode($result['dotNotation']); ?>;

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
                responsive: true,
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
