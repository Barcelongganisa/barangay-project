<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barangay Clearance</title>
    <style>
        @page {
            margin: 0.7in;
            size: letter;
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11.5pt;
            line-height: 1.4;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .certificate-container {
            max-width: 8in;
            margin: 0 auto;
        }

        /* Header Section */
        .header {
            text-align: center;
            position: relative;
            margin-bottom: 5px;
        }
        .seals-container {
            position: relative;
            height: 80px;
        }
        .seal-left, .seal-right {
            position: absolute;
            top: 0;
            width: 70px;
            height: 70px;
        }
        .seal-left { left: 0; }
        .seal-right { right: 0; }

        .seal-placeholder {
            width: 70px;
            height: 70px;
            border: 1px solid #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
        }

        .header-text {
            text-align: center;
        }
        .header-text h1 {
            font-size: 13pt;
            margin: 0;
        }
        .header-text h2 {
            font-size: 11pt;
            margin: 2px 0;
            font-weight: normal;
        }
        .header-text h3 {
            font-size: 11pt;
            margin: 2px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Title */
        .certificate-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 12px 0;
        }

        /* Body */
        .certificate-body {
            text-align: justify;
            margin-top: 10px;
        }
        .certificate-body p {
            margin: 8px 0;
        }
        .text-bold {
            font-weight: bold;
        }
        .recipient-info {
            border: 1px solid #aaa;
            background-color: #f8f8f8;
            padding: 8px;
            margin: 10px 0;
            border-radius: 3px;
            font-size: 11pt;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 40px;
        }
        .signature-block {
            text-align: center;
            float: right;
            width: 220px;
        }
        .signature-line {
            width: 180px;
            margin: 0 auto;
            border-top: 1px solid #000;
            margin-bottom: 3px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 60px;
            font-size: 9pt;
            color: #444;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">

        <!-- HEADER -->
        <div class="header">
            <div class="seals-container">
                <div class="seal-left">
                    <div class="seal-placeholder">BARANGAY<br>SEAL</div>
                </div>
                <div class="seal-right">
                    <div class="seal-placeholder">CITY<br>SEAL</div>
                </div>
            </div>

            <div class="header-text">
                <h1>Republic of the Philippines</h1>
                <h2>City of Caloocan</h2>
                <h3>Barangay {{ $resident->barangay_name ?? 'Our Barangay' }}</h3>
            </div>
        </div>

        <!-- TITLE -->
        <div class="certificate-title">Barangay Clearance</div>

        <!-- BODY -->
        <div class="certificate-body">
            <p class="text-bold">TO WHOM IT MAY CONCERN:</p>

            <p>This is to certify that <strong>{{ $resident->first_name ?? 'Resident' }} {{ $resident->last_name ?? 'Name' }}</strong>,
            of legal age, a bona fide resident of Barangay {{ $resident->barangay_name ?? 'Our Barangay' }}, 
            has requested for <strong>{{ $request->request_type }}</strong> from this office.</p>

            <div class="recipient-info">
                <p><strong>Request Details:</strong></p>
                <p>Request ID: #{{ $request->request_id }}</p>
                <p>Purpose: {{ $request->remarks }}</p>
                <p>Date Issued: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
            </div>

            <p>This clearance is issued upon request of the interested party for whatever legal purpose it may serve.</p>

            <p>Issued this {{ \Carbon\Carbon::now()->format('jS') }} day of {{ \Carbon\Carbon::now()->format('F, Y') }}, 
            at Barangay Hall, {{ $resident->barangay_name ?? 'Our Barangay' }}, City of Caloocan.</p>
        </div>

        <!-- SIGNATURE -->
        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line"></div>
                <p><strong>HON. BARANGAY CAPTAIN</strong></p>
                <p>Barangay Captain</p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>This document is system-generated and requires no physical signature. Valid only with official barangay seal.</p>
            <p>Reference No: BRGY-{{ date('Y') }}-{{ str_pad($request->request_id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>

    </div>
</body>
</html>
