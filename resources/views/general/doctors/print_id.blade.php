<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Doctor ID</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .print-container {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            page-break-after: always;
        }
        .id-card {
            position: relative;
            width: 85.6mm; /* ID card width */
            height: 54mm;  /* ID card height */
            margin: 10mm 0;
            border: 1px solid #000;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            font-weight: bold;
        }
        .id-card img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        .details {
            position: absolute;
            width: 100%;
            color: black; /* Adjust based on design */
            font-size: 12px; /* Adjust as needed */
            font-weight: bold;
            text-align: center;
        }
        .name {
            top: 60%; /* Adjust based on image positioning */
        }
        .specialty {
            top: 70%;
        }
        .id-number {
            top: 80%;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Front Side -->
        <div class="id-card">
            <img src="{{ asset('/assets/images/doctor-id-face-1-01.svg') }}" alt="Front">
            <div class="details name">{{ $doctor->name }}</div>
            <div class="details specialty">{{ $doctor->specialty }}</div>
            <div class="details id-number">{{ $doctor->id_number }}</div>
        </div>

        <!-- Back Side -->
        <div class="id-card">
            <img src="{{ asset('/assets/images/doctor-id-face-2-02.svg') }}" alt="Back">
            <div class="details name">{{ $doctor->hospital_name }}</div>
            <div class="details specialty">{{ $doctor->issue_date }}</div>
        </div>
    </div>

    <button class="no-print" onclick="window.print()">Print ID Cards</button>
</body>
</html>
