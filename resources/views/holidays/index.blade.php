<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday Calendar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Holidays in {{ $country }} - {{ $year }}</h1>

    <table>
        <thead>
            <tr>
                <th>Holiday Name</th>
                <th>Date</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($savedHolidays as $holiday)
                <tr>
                    <td>{{ $holiday->name }}</td>
                    <td>{{ $holiday->date }}</td>
                    <td>{{ $holiday->type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
