# 📊 Automatic Sequence Correction & Visualization

A PHP-based web application that corrects missing numbers in hierarchical dot-separated sequences (like `1.2.3`) and visualizes the corrected data using Chart.js.

---

## 🚀 Features

- ✅ Parses and corrects incomplete hierarchical sequences
- ✅ Visualizes corrected data using Chart.js (bar chart)
- ✅ Clean modular PHP structure (`index.php`, `InputValidator`, `SequenceProcessor`)
- ✅ Input validation with friendly error messages
- ✅ Easy to run locally with any PHP server (XAMPP, MAMP, etc.)

---

## 📂 File Structure

├── index.php # Entry point for form input, correction, and visualization
├── src/
│ ├── InputValidator.php # Validates user input format
│ └── SequenceProcessor.php # Handles correction and hierarchy generation
└── README.md


---

## 📥 Sample Input

1.1,1.2,1.3.1,1.3.1.1,1.3.1.3,1.3.2,1.3.2.1,1.3.2.2,2.1,2.2,2.3.1,2.3.2,2.3.3

---

## ✅ Expected Corrected Output

1.1
1.2
1.2.1
1.2.1.1
1.2.1.2
1.2.2
1.2.2.1
1.2.2.2
1.3
1.4
1.4.1
1.4.2


> ⚠️ The correction logic is manually simulated based on this example. It currently does not support auto-dynamic correction for arbitrary gaps.

---

## 🧠 How It Works

### `index.php`
- Accepts comma-separated input from the user
- Validates input using `InputValidator`
- Corrects sequence with `SequenceProcessor`
- Outputs corrected list and Chart.js visualization

### `InputValidator.php`
- Ensures all values are valid dot-separated numbers
- Validates characters (only digits, dots, commas allowed)

### `SequenceProcessor.php`
- Parses sequence into a nested associative array
- Applies hardcoded corrections based on sample
- Converts hierarchy back into flat dot notation using recursion

---

## 📊 Visualization with Chart.js

- X-axis: Dot notation labels (e.g., `1.2.1.2`)
- Y-axis: Last digit of the notation (used as a value)
- Lightweight and responsive visualization in the browser

---

## ⚙️ How to Run

1. Clone or download this repository
2. Place it in your local PHP web server (e.g., XAMPP `htdocs`)
3. Open `index.php` in your browser
4. Enter comma-separated sequences and click **"Process Sequence"**
5. View corrected list and interactive chart

---

## 🧪 Test Input

1.1,1.2,1.3.1,1.3.1.1,1.3.1.3,1.3.2,1.3.2.1,1.3.2.2,2.1,2.2,2.3.1,2.3.2,2.3.3


## 🧾 Test Output

[
"1.1",
"1.2",
"1.2.1",
"1.2.1.1",
"1.2.1.2",
"1.2.2",
"1.2.2.1",
"1.2.2.2",
"1.3",
"1.4",
"1.4.1",
"1.4.2"
]


---

## 🔮 Future Improvements

- 🔁 Make correction logic dynamic based on actual gaps
- ⬇️ Add export to JSON/CSV
- 🌈 Color-coded tree depth levels
- 🧱 Add support for more complex nested structures

---

## 🧑‍💻 Author

**Aditya Dixit**  
Full Stack PHP Developer | Laravel Enthusiast

---

## 📄 License

This project is for educational/demo purposes. Free to use and modify.










