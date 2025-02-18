<?php
session_start();
include 'auth/db.php'; // Updated path to include db.php

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Fetch history of expressions for the logged-in user
$sql = "SELECT expression, answer,Created FROM user_wise_history WHERE user_id = $user_id";
$result = $conn->query($sql);
$history = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Derivative Calculator - Dashboard</title>
    <script src="https://unpkg.com/mathjs@9.4.4/lib/browser/math.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=AM_HTMLorMML-full"></script>
    <script src="https://cdn.plot.ly/plotly-2.3.0.min.js"></script>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        #user-dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        #user-menu {
            display: none;
            position: absolute;
            right: 10px;
            top: 40px;
            background-color: rgba(35, 57, 93, 0.9);
            color: white;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        #user-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 5px;
        }

        #user-menu a:hover {
            background-color: #ff9800;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #1E3A8A;
            color: white;
            text-align: center;
        }

        .container {
            background: rgba(35, 57, 93, 0.9);
            padding: 20px;
            border-radius: 10px;
            min-width: 400px;
            max-width: fit-content;
            margin: auto;
            margin-top: 50px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }

        .input-box {
            display: flex;
            align-items: center;
            background: #2E4A7E;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }

        .input-box input {
            border: none;
            outline: none;
            background: none;
            color: white;
            flex: 1;
            padding: 5px;
            font-size: 16px;
        }

        .input-box i {
            color: white;
            margin-right: 10px;
        }

        .button {
            background: orange;
            border: none;
            padding: 10px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .button:hover {
            background: #ff9800;
        }

        .button i {
            margin-right: 8px;
        }

        #solution-container {
            display: none;
            background: #2E4A7E;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        th {
            padding: 7px 0px;
        }

        td {
            padding: 7px 0px;
        }
    </style>
</head>

<body>
    <!-- User icon for dropdown -->
    <div id="user-dropdown">
        <img src="user-icon.png" alt="User" width="60" onclick="toggleDropdown()">
        <div id="user-menu">
            <a href="auth/logout.php">Logout</a> <!-- Update logout link -->
        </div>
    </div>

    <div class="container">
        <h2>Derivative Calculator - Welcome, <?php echo $user_name; ?></h2>
        <div class="input-box">
            <i class="fas fa-calculator"></i>
            <input type="text" id="expression" placeholder="Enter Function (e.g. x^3 + 2x)">
        </div>
        <button class="button" onclick="userInput()">
            <i class="fas fa-rocket"></i> Calculate
        </button>

        <div id="solution-container">
            <h2>Derivative & Solution Steps</h2>
            <div id="derivative"></div>
            <div id="solution-steps"></div>
            <h2>Graph of Function and Its Derivative</h2>
            <div id="plot" style="margin-top: 15px;"></div>
        </div>

        <h2>Expression History</h2>
        <table id="history-table" border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Expression</th>
                    <th>Derivative</th>
                    <th>Date / Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($history as $entry) {
                    echo "<tr>
                            <td>{$entry['expression']}</td>
                            <td>{$entry['answer']}</td>
                            <td>{$entry['Created']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Hidden form to submit expression and derivative -->
        <form id="expression-form" action="auth/insert_expression.php" method="POST" style="display: none;">
            <input type="text" name="expression" id="hidden-expression" />
            <input type="text" name="derivative" id="hidden-derivative" />
            <input type="submit" value="Submit" />
        </form>
    </div>

    <script>
        // Toggle dropdown menu for logout
        function toggleDropdown() {
            var menu = document.getElementById("user-menu");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        }

        function updateHistory(expression, derivative,date) {
            var table = document.getElementById("history-table").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(0); // Insert at the top
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);

            cell1.textContent = expression;
            cell2.textContent = derivative;
            cell3.textContent = date;
        }

        function getCurrentDateTime() {
            let now = new Date();

            let year = now.getFullYear();
            let month = String(now.getMonth() + 1).padStart(2, '0');
            let day = String(now.getDate()).padStart(2, '0');

            let hours = String(now.getHours()).padStart(2, '0');
            let minutes = String(now.getMinutes()).padStart(2, '0');
            let seconds = String(now.getSeconds()).padStart(2, '0');

            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        console.log(getCurrentDateTime()); // Example output: 2025-02-19 00:36:06



        function userInput() {
            try {
                var input = document.getElementById("expression").value.trim();
                if (!input) {
                    alert("Please enter a valid expression!");
                    return;
                }

                var derivative = math.derivative(input, "x").toString();
                var steps = generateSteps(input);

                // Plot graph
                var expression = math.compile(input);
                var xValues = math.range(-10, 10, 0.5).toArray();
                var yValues = xValues.map(x => expression.evaluate({
                    x: x
                }));
                var yDerivative = xValues.map(x => math.compile(derivative).evaluate({
                    x: x
                }));

                var trace1 = {
                    x: xValues,
                    y: yValues,
                    mode: "lines",
                    name: "y"
                };
                var trace2 = {
                    x: xValues,
                    y: yDerivative,
                    mode: "lines",
                    name: "y'"
                };
                Plotly.newPlot("plot", [trace1, trace2], {
                    xaxis: {
                        title: "x"
                    },
                    yaxis: {
                        title: "y"
                    }
                });

                document.getElementById("derivative").innerHTML = `( y' = ${derivative} )`;
                document.getElementById("solution-steps").innerHTML = steps;

                MathJax.Hub.Typeset();
                document.getElementById("solution-container").style.display = "block";

                // Populate hidden form fields with the expression and derivative
                document.getElementById("hidden-expression").value = input;
                document.getElementById("hidden-derivative").value = derivative;

                // Submit the form to insert data into the database

                var formData = new FormData();
                formData.append("expression", input);
                formData.append("derivative", derivative);

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "auth/insert_expression.php", true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            updateHistory(response.expression, response.derivative, getCurrentDateTime());
                        } else {
                            alert("Error: " + response.message);
                        }
                    }
                };

                xhr.send(formData);

                // document.getElementById("expression-form").submit();
            } catch (error) {
                alert("Invalid expression! Please check your input.");
                console.error(error);
            }
        }

        function generateSteps(expression) {
            let steps = `<p><strong>Step 1:</strong> Start with the function: ( f(x) = ${expression} )</p>`;
            let derivative = math.derivative(expression, "x").toString();

            let tokens = expression.split(/([+\-*/() ])/).filter(t => t.trim());
            let processedSteps = [];

            for (let i = 0; i < tokens.length; i++) {
                if (tokens[i].includes("^")) {
                    let [base, exponent] = tokens[i].split("^");
                    exponent = parseInt(exponent);
                    let newExponent = exponent - 1;

                    if (!isNaN(exponent)) {
                        processedSteps.push(`Using power rule: ( d/dx (${tokens[i]}) = ${exponent} * ${base}^{${newExponent}} )`);
                    }
                }
            }

            if (processedSteps.length > 0) {
                steps += `<p><strong>Step 2:</strong> Apply differentiation rules:</p>`;
                processedSteps.slice(0, 3).forEach(step => {
                    steps += `<p>${step}</p>`;
                });
            }

            steps += `<p><strong>Final Step:</strong> The derivative is ( y' = ${derivative} )</p>`;
            return steps;
        }
    </script>
</body>

</html>