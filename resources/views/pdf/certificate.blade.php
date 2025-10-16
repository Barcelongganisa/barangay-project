80% of storage used … If you run out of space, you can't save to Drive, back up Google Photos, or use Gmail. Get 30 GB of storage for ₱49 ₱10/month for 3 months.
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barangay Clearance</title>
    <style>
        @page {
            size: 8.5in 11.7in; /* width x height */
            margin: 0.5in;    /* adjust to your needs */
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11.5pt;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .certificate-container {
            max-width: 8in;
            margin: 0 auto;
            padding: 25px 30px;
            border: 3px double #a3d9a5;
            position: relative;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }
        .header .republic {
            font-size: 12pt;
            font-weight: bold;
        }
        .header .city {
            font-size: 11pt;
            margin: 3px 0;
        }
        .header .barangay {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #e67e22;
        }
        .header .office {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 5px;
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 3px;
        }

        /* Seal images */
        .seal-left, .seal-right {
            width: 80px;
            height: 80px;
            position: absolute;
            top: 0;
        }
        .seal-left {
            left: 0;
        }
        .seal-right {
            right: 0;
        }
        .seal-right img {
          width: 88px;
          height: auto;
          object-fit: contain;
      }
        .seal-left img{
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Title */
        .certificate-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 25px 0;
            color: #2c6e49;
        }

        /* Body */
        .certificate-body {
            text-align: justify;
        }
        .certificate-body p {
            margin: 18px 0;
            text-indent: 40px;
        }
        .certificate-body p:first-child {
            text-indent: 0;
        }
        .recipient-info {
            border: 1px solid #f5b97d;
            background-color: #fffaf3;
            padding: 12px;
            margin: 18px 0;
            border-radius: 4px;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 60px;
        }
        .signature-block {
            text-align: center;
            float: right;
            width: 240px;
        }
        .signature-line {
            width: 190px;
            margin: 0 auto;
            border-top: 1.5px solid #2c6e49;
            margin-bottom: 5px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 80px;
            font-size: 9pt;
            color: #444;
            border-top: 1px solid #aaa;
            padding-top: 8px;
        }

        /* Watermark Image */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: 0;
        }
        .watermark img {
            width: 500px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="certificate-container">

        <!-- WATERMARK -->
        <div class="watermark">
            <img src="{{ public_path('images/seal.png') }}" alt="Barangay Watermark">
        </div>

        <!-- HEADER -->
        <div class="header">
            <div class="seal-left">
                <img src="{{ public_path('images/seal.png') }}" alt="Left Seal">
            </div>
            <div class="seal-right">
                <img src="{{ public_path('images/barangay_seal.png') }}" alt="Right Seal">
            </div>

            <div class="republic">Republic of the Philippines</div>
            <div class="city">City of Caloocan</div>
            <div class="barangay">{{ $resident->barangay_name ?? 'Our Barangay' }}</div>
            <div class="office">OFFICE OF THE PUNONG BARANGAY</div>
        </div>

        <!-- TITLE -->
        <div class="certificate-title">Barangay Clearance</div>

        <!-- BODY -->
        <div class="certificate-body">
            <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

            <p>
                This is to certify that
                <strong>{{ $resident->first_name ?? 'Resident' }} {{ $resident->last_name ?? 'Name' }}</strong>,
                of legal age, a bona fide resident of Barangay
                {{ $resident->barangay_name ?? 'Our Barangay' }},
                has requested for <strong>{{ $request->request_type }}</strong> from this office.
            </p>

            <div class="recipient-info">
                <p><strong>Request Details:</strong></p>
                <p>Request ID: #{{ $request->request_id }}</p>
                <p>Purpose: {{ $request->remarks }}</p>
                <p>Date Issued: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
            </div>

            <p>
                This clearance is issued upon request of the interested party for whatever legal purpose it may serve.
            </p>

            <p>
                Issued this {{ \Carbon\Carbon::now()->format('jS') }} day of {{ \Carbon\Carbon::now()->format('F, Y') }}, at Barangay Hall, {{ $resident->barangay_name ?? 'Our Barangay' }},
                City of Caloocan.
            </p>
        </div>

        <!-- SIGNATURE -->
        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line"></div>
                <p><strong>HON. BARANGAY CAPTAIN</strong></p>
                <p>Punong Barangay</p>
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