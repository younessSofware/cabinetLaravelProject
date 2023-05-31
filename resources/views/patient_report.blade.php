<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>
<h1>Patient Report</h1>
<h2>Patient Information</h2>
<p><strong>Name:</strong> {{ $patient->FullName }}</p>
<p><strong>Email:</strong> {{ $patient->Adress }}</p>
<p><strong>Phone:</strong> {{ $patient->PhoneNumber }}</p>

<h2>Appointments</h2>
<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Start</th>
        <th>End</th>
        <th>Reason</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($patient->appointments as $appointment)
        <tr>
            <td>{{ $appointment->date }}</td>
            <td>{{ $appointment->start_time }}</td>
            <td>{{ $appointment->end_time }}</td>
            <td>{{ $appointment->reason }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
