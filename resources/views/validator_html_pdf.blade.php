<!DOCTYPE html>
<html>
<head>
    <title>Name Validator Report</title>
    <style>
        table { width:100%; border-collapse: collapse; }
        td, th { border:1px solid black; padding:6px; }
        pre { white-space: pre-line; }

        /* Print button styling */
        #printButton {
            margin-bottom: 15px;
            padding: 8px 16px;
            background-color: #2c698d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #printButton:hover {
            background-color: #1e4d6f;
        }

        /* Hide print button when printing */
        @media print {
            #printButton {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h2>Name Validator Report</h2>

    <!-- Print Button -->
    <button id="printButton" onclick="window.print();">Print / Save as PDF</button>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Violations</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td><pre>{{ $row['violation'] }}</pre></td>
                <td>{{ $row['result'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
