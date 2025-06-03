<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Doctor ID</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            background-color: #f5f5f5;
        }
        
        .print-container {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            page-break-after: always;
            background-color: white;
        }
        
        .id-card {
            position: relative;
            width: 85.6mm; /* ID card width */
            height: 54mm;  /* ID card height */
            margin: 10mm 0;
            border-radius: 4mm;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            background-color: white;
        }
        
        .id-card img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            object-fit: cover;
        }
        
        /* Base details styling */
        .details {
            position: absolute;
            width: 90%;
            left: 5%;
            color: #2c3e50;
            font-weight: 600;
            text-align: center;
            z-index: 10;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }
        
        /* Front card specific styling */
        .front-card .name {
            top: 55%;
            font-size: 14px;
            font-weight: 700;
            color: #1a237e;
            line-height: 1.2;
        }
        
        .front-card .specialty {
            top: 68%;
            font-size: 11px;
            font-weight: 500;
            color: #3949ab;
            letter-spacing: 0.3px;
        }
        
        .front-card .id-number {
            top: 78%;
            font-size: 10px;
            font-weight: 600;
            color: #5c6bc0;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }
        
        /* Back card specific styling */
        .back-card .hospital-name {
            top: 45%;
            font-size: 13px;
            font-weight: 700;
            color: #1a237e;
            line-height: 1.3;
            max-width: 80%;
            left: 10%;
        }
        
        .back-card .issue-date {
            top: 70%;
            font-size: 10px;
            font-weight: 500;
            color: #3949ab;
        }
        
        /* Professional enhancements */
        .details::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, 
                transparent 0%, 
                rgba(255, 255, 255, 0.2) 50%, 
                transparent 100%);
            z-index: -1;
        }
        
        /* Print button styling */
        .print-button {
            margin: 20px;
            padding: 12px 30px;
            background-color: #1a237e;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .print-button:hover {
            background-color: #283593;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Label styling for better context */
        .label {
            font-size: 8px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.7;
            margin-bottom: 2px;
        }
        
        /* Ensure text doesn't overflow */
        .details {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* For longer names, allow wrapping */
        .name {
            white-space: normal !important;
            line-height: 1.2;
        }
        
        @media print {
            body {
                background-color: white;
            }
            
            .no-print {
                display: none !important;
            }
            
            .id-card {
                box-shadow: none;
                border: 0.5px solid #ddd;
            }
            
            .print-container {
                margin: 0;
                padding: 0;
            }
        }
        
        /* Additional professional touches */
        .id-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                transparent 50%, 
                rgba(0, 0, 0, 0.05) 100%);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Front Side -->
        <div class="id-card front-card">
            <img src="{{ asset('/assets/images/doctor-id-face-1-01.svg') }}" alt="Front">
            <div class="details name">{{ $doctor->name }}</div>
            <div class="details specialty">{{ $doctor->specialty }}</div>
            <div class="details id-number">ID: {{ $doctor->id_number }}</div>
        </div>

        <!-- Back Side -->
        <div class="id-card back-card">
            <img src="{{ asset('/assets/images/doctor-id-face-2-02.svg') }}" alt="Back">
            <div class="details hospital-name">{{ $doctor->hospital_name }}</div>
            <div class="details issue-date">Issued: {{ $doctor->issue_date }}</div>
        </div>
    </div>

    <button class="no-print print-button" onclick="window.print()">Print ID Cards</button>

    <script>
        // Optional: Auto-adjust font size for long names
        document.addEventListener('DOMContentLoaded', function() {
            const nameElement = document.querySelector('.name');
            if (nameElement && nameElement.scrollWidth > nameElement.clientWidth) {
                nameElement.style.fontSize = '12px';
            }
        });
    </script>
</body>
</html>