<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai&family=Cairo:wght@300&display=swap" rel="stylesheet">
    <link href="{{asset('/assets/css/print_bill.css')}}" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <title> طباعة اوراق خارجية طلب #{{$documentPreparation->id}} </title>
    
    <style>
        @media print {
            body {
                margin: 0;
            }
            .sheet {
                margin: 0;
            }
        }
        
        .sheet {
            background: white;
            padding: 20mm;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .logo {
            width: 120px;
            height: 120px;
        }
        
        .council-name {
            flex-grow: 1;
            text-align: center;
        }
        
        .council-name h1 {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }
        
        .council-name h2 {
            font-size: 20px;
            color: #7f8c8d;
            margin: 0;
            font-weight: normal;
        }
        
        .info-line {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 14px;
        }
        
        .to-whom {
         text-align: center;
         font-size: 22px;
         font-weight: bold;
         letter-spacing: 2px;
         margin-top: 220px;
        }
        
        .content {
            line-height: 2;
            font-size: 16px;
            text-align: justify;
            margin: 0 60px;
        }
        
        .validity-box {
            border: 2px dashed #3498db;
            padding: 15px;
            margin: 0 60px;
            text-align: center;
            font-size: 14px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 16px;
            margin-left: 60px;
        }
        
        .signature {
            text-align: center;
        }
        
        .signature img {
            width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .stamp {
            width: 150px;
            height: 150px;
        }
        
        .footer {
            position: absolute;
            bottom: 20mm;
            left: 20mm;
            right: 20mm;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
        }
        
        .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

    <style>
      section.sheet {
         background-image: url('/assets/images/blank-document.png') !important;
      }

    </style>
</head>

<div id="invoice">
    <body class="A4">
        <section class="sheet zima bill">
            @if($documentPreparation->document_type == 'specialist')
                
                <!-- Date and Reference -->
                <div class="info-line">
                    <div>Date : {{ now()->format('d / m / Y') }}</div>
                    <div>Ref.No.: ............................ </div>
                </div>
                
                <!-- TO WHOM IT MAY CONCERN -->
                <div class="to-whom">
                    TO WHOM IT MAY CONCERN
                </div>
                
                <!-- Content -->
                <div class="content">

                    <p>
                        We, the Libyan General Medical Council (LGMC), certifies that<br>
                        <strong>Dr. {{$documentPreparation->doctorMail->doctor->name}}</strong>, 
                        of {{$documentPreparation->doctorMail->doctor->country->nationality_name_en}} nationality with passport number: 
                        <strong>{{$documentPreparation->doctorMail->doctor->passport_number}}</strong>, 
                        is registered as a {{$documentPreparation->doctorMail->doctor->doctor_rank->name_en}}

                        @if ($documentPreparation->doctorMail->doctor->specialty1)
                        in {{$documentPreparation->doctorMail->doctor->specialty1->name_en}}
                        @endif

                        under LGMC number <strong>({{$documentPreparation->doctorMail->doctor->code}})</strong>, 
                        {{$documentPreparation->doctorMail->doctor->branch->name_en}}, since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                    </p>
                    
                    <p style="margin-top: 30px;">
                        If you require further assistance, please don't hesitate to contact us.
                    </p>
                </div>
                
                <!-- Validity Box -->
                <div class="validity-box">
                    <em>Validity of this document is for <strong>90 days</strong>.</em><br>
                    <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
                </div>
                
                <!-- Signature Section -->
                <div class="signature-section">
                    <div class="signature">
                        <p style="margin: 0;">Yours sincerely</p>
                        @if(isset($documentPreparation->preparedBy->signature))
                            <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
                        @else
                            <div style="height: 20px;"></div>
                        @endif

                        @if ($signature)
                           <p style="margin: 0;"><strong>{{$signature->name_en}}</strong></p>
                           <p style="margin: 0; text-decoration: underline;">{{$signature->job_title_en}}</p>
                        @endif

                        
                    </div>
                </div>
                @elseif($documentPreparation->document_type == 'license')
                <div class="info-line">
                    <div>Date : {{ now()->format('d / m / Y') }}</div>
                    <div>Ref.No.: .................................... </div>
                </div>
                
                <!-- Title -->
                <div style="text-align: center; margin-top: 200px;">
                    <h2 style="font-size: 24px; font-weight: bold; text-decoration: underline;">
                        Certificate of Registration and License
                    </h2>
                </div>
                
                <!-- TO WHOM IT MAY CONCERN -->
                <div class="to-whom" style="margin-top: 30px;">
                    To whom it may concern
                </div>
                
                <!-- Content -->
                <div class="content">
                    <p style="margin-top: 30px;">
                        We hereby certify that <strong>Dr. {{$documentPreparation->doctorMail->doctor->name}}</strong>, 
                        {{$documentPreparation->doctorMail->doctor->country->nationality_name_en}} 
                        nationality, passport No:<strong>{{$documentPreparation->doctorMail->doctor->passport_number}}</strong>.
                    </p>
                    
                    <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            He holds full registration in the Libyan General Medical Council, No:<strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>, and he is currently active and licensed for medical practice in 
                            Libya.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            She holds full registration in the Libyan General Medical Council, No:<strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>, and she is currently active and licensed for medical practice in 
                            Libya.
                        @else
                            The aforementioned doctor holds full registration in the Libyan General Medical Council, No:<strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>, and is currently active and licensed for medical practice in 
                            Libya.
                        @endif
                    </p>
                    
                    <p style="margin-top: 30px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            This certificate is issued upon his request to be legally used.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            This certificate is issued upon her request to be legally used.
                        @else
                            This certificate is issued upon request to be legally used.
                        @endif
                    </p>
                </div>
                
                <!-- Validity Box -->
                <div class="validity-box" style="margin-top: 40px;">
                    <em>Validity of this document is for <strong>90 days</strong>.</em><br>
                    <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
                </div>
                
                <!-- Signature Section -->
                <div class="signature-section">
                    <div class="signature">
                        <p style="margin: 0;">Yours sincerely</p>
                        @if(isset($documentPreparation->preparedBy->signature))
                            <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
                        @else
                            <div style="height: 20px;"></div>
                        @endif
                        @if ($signature)
                           <p style="margin: 0;"><strong>{{$signature->name_en}}</strong></p>
                           <p style="margin: 0; text-decoration: underline;">{{$signature->job_title_en}}</p>
                        @endif
                    </div>
                    
                    <!-- Stamp -->
                    <div style="position: absolute; right: 100px; bottom: 150px;">
                        @if(isset($documentPreparation->stamp))
                            <img src="{{ asset($documentPreparation->stamp) }}" alt="LGMC Stamp" class="stamp">
                        @endif
                    </div>
                </div>

                @elseif($documentPreparation->document_type == 'good_standing')
                <!-- Date and Reference -->
                <div class="info-line">
                    <div>Date : {{ now()->format('d / m / Y') }}</div>
                    <div>Ref.No.: .................................................. </div>
                </div>
                
                <!-- Title -->
                <div style="text-align: center; margin-top: 170px;">
                    <h2 style="font-size: 24px; font-weight: bold;">
                        CERTIFICATE OF GOOD STANDING
                    </h2>
                </div>
                
                <!-- TO WHOM IT MAY CONCERN -->
                <div style="text-align: center; margin-top: 30px; margin-bottom: 40px;">
                    <p style="font-size: 16px;">To whom it may concern</p>
                </div>
                
                <!-- Content -->
                <div class="content">
                    <p style="margin-top: 30px;">
                        This is to certify that <strong>Dr. {{strtoupper($documentPreparation->doctorMail->doctor->name)}}</strong>, Libyan
                        national, graduated from {{$documentPreparation->doctorMail->doctor->handGraduation->en_name}} , Tripoli - LIBYA, 
                        in <strong>{{$documentPreparation->doctorMail->doctor->graduation_date}}</strong>, and completed one year of internship in our teaching hospitals on 
                        <strong>{{$documentPreparation->doctorMail->doctor->internership_complete}}</strong>.
                    </p>
                    
                    <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            He holds full registration in the Libyan General Medical Council, No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong> - {{$documentPreparation->doctorMail->doctor->branch->name_en}} 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            She holds full registration in the Libyan General Medical Council, No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong> - {{$documentPreparation->doctorMail->doctor->branch->name_en}} 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @else
                            The doctor holds full registration in the Libyan General Medical Council, No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong> - {{$documentPreparation->doctorMail->doctor->branch->name_en}} 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @endif
                    </p>
                    
                    <!-- Work History as Paragraph (if work details are provided) -->
                    
                    @if ($documentPreparation->service->work_mention == "with")
                    @if(isset($documentPreparation->preparation_data['work_as']) && 
                    is_array($documentPreparation->preparation_data['work_as']) && 
                    count($documentPreparation->preparation_data['work_as']) > 0)
                    
                    <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            He has worked as 
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            She has worked as 
                        @else
                            The doctor has worked as 
                        @endif
                        
                        @foreach($documentPreparation->preparation_data['work_as'] as $index => $workAs)
                            {{$workAs}} in {{$documentPreparation->preparation_data['work_specialty'][$index] ?? 'N/A'}} {{$documentPreparation->preparation_data['work_department'][$index] ?? 'department'}} at {{$documentPreparation->preparation_data['work_hospital'][$index] ?? 'N/A'}} from 
                            @if(isset($documentPreparation->preparation_data['work_from'][$index]))
                                {{\Carbon\Carbon::parse($documentPreparation->preparation_data['work_from'][$index])->format('d/m/Y')}}
                            @else
                                N/A
                            @endif
                            up to 
                            @if(isset($documentPreparation->preparation_data['work_to'][$index]))
                                {{\Carbon\Carbon::parse($documentPreparation->preparation_data['work_to'][$index])->format('d/m/Y')}}
                            @else
                                N/A
                            @endif
                            @if(!$loop->last)
                                , then 
                                @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                                    he has worked as 
                                @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                                    she has worked as 
                                @else
                                    has worked as 
                                @endif
                            @else
                                .
                            @endif
                        @endforeach
                    </p>
                @endif
                    @endif
                    
                    <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            He is in good standing, good moral character and there is no legal or disciplinary 
                            proceeding in action or contemplated against him.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            She is in good standing, good moral character and there is no legal or disciplinary 
                            proceeding in action or contemplated against her.
                        @else
                            The doctor is in good standing, good moral character and there is no legal or disciplinary 
                            proceeding in action or contemplated against them.
                        @endif
                    </p>
                    
                    @if(isset($documentPreparation->preparation_data['work_place']) && !empty($documentPreparation->preparation_data['work_place']))
                        <p style="margin-top: 20px;">
                            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                                This certificate is issued upon his request to be legally used for employment at <strong>{{$documentPreparation->preparation_data['work_place']}}</strong>.
                            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                                This certificate is issued upon her request to be legally used for employment at <strong>{{$documentPreparation->preparation_data['work_place']}}</strong>.
                            @else
                                This certificate is issued upon request to be legally used for employment at <strong>{{$documentPreparation->preparation_data['work_place']}}</strong>.
                            @endif
                        </p>
                    @else
                        <p style="margin-top: 20px;">
                            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                                This certificate is issued upon his request to be legally used.
                            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                                This certificate is issued upon her request to be legally used.
                            @else
                                This certificate is issued upon request to be legally used.
                            @endif
                        </p>
                    @endif
                </div>
                
                <!-- Validity Box -->
                <div class="validity-box" style="margin-top: 40px;">
                    <em>Validity of this document is for <strong>90 days</strong>.</em><br>
                    <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
                </div>
                
                <!-- Signature Section -->
                <div class="signature-section">
                    <div class="signature">
                        <p style="margin: 0;">Yours sincerely</p>
                        @if(isset($documentPreparation->preparedBy->signature))
                            <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
                        @else
                            <div style="height: 20px;"></div>
                        @endif
                        @if ($signature)
                            <p style="margin: 0;"><strong>{{$signature->name_en}}</strong></p>
                            <p style="margin: 0;">{{$signature->job_title_en}}</p>
                        @endif
                    </div>
                    
                    <!-- Stamp -->
                    <div style="position: absolute; right: 100px; bottom: 150px;">
                        @if(isset($documentPreparation->stamp))
                            <img src="{{ asset($documentPreparation->stamp) }}" alt="LGMC Stamp" class="stamp">
                        @endif
                    </div>
                </div>
                @elseif($documentPreparation->document_type == 'certificate')
          
                <!-- Date and Reference -->
                <div class="info-line">
                    <div>Date : {{ now()->format('d / m / Y') }}</div>
                    <div>Ref.No.: .................................................. </div>
                </div>
                
                <!-- Title -->
                <div style="text-align: center;margin-top: 170px;">
                    <h2 style="font-size: 24px; font-weight: bold;">
                        CERTIFICATE
                    </h2>
                </div>
                
                <!-- TO WHOM IT MAY CONCERN -->
                <div style="text-align: center; margin-top: 30px; margin-bottom: 40px;">
                    <p style="font-size: 16px;">To whom it may concern</p>
                </div>
                
                <!-- Content -->
                <div class="content">
                    <p style="margin-top: 30px;">
                        This is to certify that <strong>DR. {{strtoupper($documentPreparation->doctorMail->doctor->name)}}</strong>, was one 
                        of the working staff in {{$documentPreparation->preparation_data['department']}}.
                    </p>
                    
                    <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            He holds full registration in the Libyan General Medical Council No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            She holds full registration in the Libyan General Medical Council No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @else
                            The doctor holds full registration in the Libyan General Medical Council No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                            since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @endif
                    </p>
                    
                    <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            We have no objection for the above named doctor to practice medicine in the Republic 
                            of {{\App\Models\Country::find($documentPreparation->preparation_data['country_id'])->country_name_en}} as far as his job in Libya is not maintained any more.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            We have no objection for the above named doctor to practice medicine in the Republic 
                            of {{\App\Models\Country::find($documentPreparation->preparation_data['country_id'])->country_name_en}} as far as her job in Libya is not maintained any more.
                        @else
                            We have no objection for the above named doctor to practice medicine in the Republic 
                            of {{\App\Models\Country::find($documentPreparation->preparation_data['country_id'])->country_name_en}} as far as their job in Libya is not maintained any more.
                        @endif
                    </p>
                </div>
                
                <!-- Validity Box -->
                <div class="validity-box" style="margin-top: 40px;">
                    <em>Validity of this document is for <strong>90 days</strong>.</em><br>
                    <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
                </div>
                
                <!-- Signature Section -->
                <div class="signature-section">
                    <div class="signature">
                        <p style="margin: 0;">Yours sincerely</p>
                        @if(isset($documentPreparation->preparedBy->signature))
                            <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
                        @else
                            <div style="height: 20px;"></div>
                        @endif
                        @if ($signature)
                           <p style="margin: 0;"><strong>{{$signature->name_en}}</strong></p>
                           <p style="margin: 0; text-decoration: underline;">{{$signature->job_title_en}}</p>
                        @endif
                    </div>
                    
                    <!-- Stamp -->
                    <div style="position: absolute; right: 100px; bottom: 150px;">
                        @if(isset($documentPreparation->stamp))
                            <img src="{{ asset($documentPreparation->stamp) }}" alt="LGMC Stamp" class="stamp">
                        @endif
                    </div>
                </div>
                @elseif($documentPreparation->document_type == 'license')
   
    
    <!-- Date and Reference -->
    <div class="info-line">
        <div>Date : {{ now()->format('d / m / Y') }}</div>
        <div>Ref.No.: {{$documentPreparation->id ?? '.......................'}} </div>
    </div>
    
    <!-- Title -->
    <div style="text-align: center; margin: 40px 0;">
        <h2 style="font-size: 24px; font-weight: bold; text-decoration: underline;">
            Certificate of Registration and License
        </h2>
    </div>
    
    <!-- TO WHOM IT MAY CONCERN -->
    <div class="to-whom" style="margin-top: 30px;">
        To whom it may concern
    </div>
    
    <!-- Content -->
    <div class="content">
        <p style="margin-top: 30px;">
            We hereby certify that <strong>Dr. {{$documentPreparation->doctorMail->doctor->name}}</strong>, 
            {{$documentPreparation->doctorMail->doctor->country->nationality_name_en}} 
            nationality, passport No:<strong>{{$documentPreparation->doctorMail->doctor->passport_number}}</strong>.
        </p>
        
        <p style="margin-top: 20px;">
            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                He holds full registration in the Libyan General Medical Council, No:<strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>, and he is currently active and licensed for medical practice in 
                Libya.
            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                She holds full registration in the Libyan General Medical Council, No:<strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>, and she is currently active and licensed for medical practice in 
                Libya.
            @else
                The aforementioned doctor holds full registration in the Libyan General Medical Council, No:<strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>, and is currently active and licensed for medical practice in 
                Libya.
            @endif
        </p>
        
        <p style="margin-top: 30px;">
            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                This certificate is issued upon his request to be legally used.
            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                This certificate is issued upon her request to be legally used.
            @else
                This certificate is issued upon request to be legally used.
            @endif
        </p>
    </div>
    
    <!-- Validity Box -->
    <div class="validity-box" style="margin-top: 40px;">
        <em>Validity of this document is for <strong>90 days</strong>.</em><br>
        <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature">
            <p style="margin: 0;">Yours sincerely</p>
            @if(isset($documentPreparation->preparedBy->signature))
                <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
            @else
                <div style="height: 20px;"></div>
            @endif
            <p style="margin: 0;"><strong>Dr. MOHAMED ALI ALGHOJ</strong></p>
            <p style="margin: 0;">Secretary General</p>
        </div>
        
        <!-- Stamp -->
        <div style="position: absolute; right: 100px; bottom: 150px;">
            @if(isset($documentPreparation->stamp))
                <img src="{{ asset($documentPreparation->stamp) }}" alt="LGMC Stamp" class="stamp">
            @endif
        </div>
    </div>

    @elseif($documentPreparation->document_type == 'internship_second_year')
   
    <!-- Date and Reference -->
    <div class="info-line" style="margin-top: 20px;">
        <div>Date : {{ now()->format('d / m / Y') }}</div>
        <div>Ref.No.: {{ '.......................'}} </div>
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-top: 150px;">
        <h2 style="font-size: 20px; font-weight: bold; margin: 0;">
            POST GRADUATE TRAINING
        </h2>
        <p style="font-size: 14px; margin: 5px 0;">2<sup>nd</sup> Year</p>
    </div>

    <!-- TO WHOM IT MAY CONCERN -->
    <div style="text-align: center; margin: 30px 0;">
        <p style="font-size: 16px; margin: 0;">To whom it may concern</p>
    </div>

    <!-- Content -->
    <div class="content" style="margin: 0 40px;">
        <p style="margin-top: 20px; line-height: 1.8;font-size:13px;">
            I would like to certify that <strong>Dr. {{strtoupper($documentPreparation->doctorMail->doctor->name)}}</strong>, {{$documentPreparation->doctorMail->doctor->country->nationality_name_en}} 
            nationality, passport number: <strong>{{$documentPreparation->doctorMail->doctor->passport_number}}</strong>, and holds full registration in the Libyan 
            General Medical Council, No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong> – {{$documentPreparation->doctorMail->doctor->branch->name_en}}, since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
        </p>

        @php
            $nameParts = explode(' ', $documentPreparation->doctorMail->doctor->name);
            $lastName = strtoupper(end($nameParts));
        @endphp
        
        <p style="margin-top: 20px; line-height: 1.8;font-size:13px;">
            <strong>Dr. {{$lastName}}</strong>, has completed 
            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                his internship on <strong>{{$documentPreparation->doctorMail->doctor->internership_complete}}</strong>. 
                @if(isset($documentPreparation->preparation_data['gap_start']) && isset($documentPreparation->preparation_data['gap_end']) && 
                    !empty($documentPreparation->preparation_data['gap_start']) && !empty($documentPreparation->preparation_data['gap_end']))
                    His file shows that the period between 
                    <strong>{{ \Carbon\Carbon::parse($documentPreparation->preparation_data['gap_start'])->format('F Y') }} – {{ \Carbon\Carbon::parse($documentPreparation->preparation_data['gap_end'])->format('F Y') }}</strong>
                    is not classified as a gap period due to the post graduate clinical medical training scheme he received as shown in the table below:
                @else
                    He has completed his post graduate clinical medical training as shown in the table below:
                @endif
            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                her internship on <strong>{{$documentPreparation->doctorMail->doctor->internership_complete}}</strong>. 
                @if(isset($documentPreparation->preparation_data['gap_start']) && isset($documentPreparation->preparation_data['gap_end']) && 
                    !empty($documentPreparation->preparation_data['gap_start']) && !empty($documentPreparation->preparation_data['gap_end']))
                    Her file shows that the period between 
                    <strong>{{ \Carbon\Carbon::parse($documentPreparation->preparation_data['gap_start'])->format('F Y') }} – {{ \Carbon\Carbon::parse($documentPreparation->preparation_data['gap_end'])->format('F Y') }}</strong>
                    is not classified as a gap period due to the post graduate clinical medical training scheme she received as shown in the table below:
                @else
                    She has completed her post graduate clinical medical training as shown in the table below:
                @endif
            @else
                their internship on <strong>{{$documentPreparation->doctorMail->doctor->internership_complete}}</strong>. 
                @if(isset($documentPreparation->preparation_data['gap_start']) && isset($documentPreparation->preparation_data['gap_end']) && 
                    !empty($documentPreparation->preparation_data['gap_start']) && !empty($documentPreparation->preparation_data['gap_end']))
                    Their file shows that the period between 
                    <strong>{{ \Carbon\Carbon::parse($documentPreparation->preparation_data['gap_start'])->format('F Y') }} – {{ \Carbon\Carbon::parse($documentPreparation->preparation_data['gap_end'])->format('F Y') }}</strong>
                    is not classified as a gap period due to the post graduate clinical medical training scheme they received as shown in the table below:
                @else
                    They have completed their post graduate clinical medical training as shown in the table below:
                @endif
            @endif
        </p>
        <!-- Training Table -->
        <!-- Training Table -->
<div style="">
    <table style="width: 100%; border-collapse: collapse; border: 2px solid #2c3e50; font-size: 12px;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">Hospital / Medical Center</th>
                <th style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">Starting date</th>
                <th style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">Ending date</th>
                <th style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">Specialty / Course type</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($documentPreparation->preparation_data['hospital']) && 
                is_array($documentPreparation->preparation_data['hospital']) && 
                count($documentPreparation->preparation_data['hospital']) > 0)
                
                @foreach($documentPreparation->preparation_data['hospital'] as $index => $hospital)
                    <tr @if($index % 2 == 0) style="background-color: #ffeee6;" @endif>
                        <td style="border: 1px solid #2c3e50; padding: 8px;">
                            {{ $hospital ?? 'N/A' }}
                        </td>
                        <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">
                            @if(isset($documentPreparation->preparation_data['start_date'][$index]))
                                {{ \Carbon\Carbon::parse($documentPreparation->preparation_data['start_date'][$index])->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">
                            @if(isset($documentPreparation->preparation_data['end_date'][$index]))
                                {{ \Carbon\Carbon::parse($documentPreparation->preparation_data['end_date'][$index])->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">
                            @if(isset($documentPreparation->preparation_data['specialty'][$index]))
                                {{ $documentPreparation->preparation_data['specialty'][$index] }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <!-- Default training records when no data is provided -->
                <tr style="background-color: #ffeee6;">
                    <td style="border: 1px solid #2c3e50; padding: 8px;">Alhabsa polyclinic Tripoli/LIBYA</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">01/08/2019</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">01/12/2019</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">Family and community medicine</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #2c3e50; padding: 8px;">Alkathra Teaching Hospital Tripoli/LIBYA</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">02/12/2019</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">02/06/2020</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">Emergency medicine</td>
                </tr>
                <tr style="background-color: #ffeee6;">
                    <td style="border: 1px solid #2c3e50; padding: 8px;">El-razzi Teaching Hospital, Tripoli/LIBYA</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">03/06/2020</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">03/08/2020</td>
                    <td style="border: 1px solid #2c3e50; padding: 8px; text-align: center;">Psychiatry</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
        <p style="margin-top: 5px;
        line-height: 1.8;
        font-size: 13px;">
            If you require further assistance, please don't hesitate to contact us
        </p>
    </div>

    <!-- Validity Box -->
    <div class="validity-box" style="margin: 30px 40px;">
        <em>Validity of this document is for <strong>90 days</strong>.</em><br>
        <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature">
            <p style="margin: 0;">Yours sincerely</p>
            @if(isset($documentPreparation->preparedBy->signature))
                <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
            @else
                <div style="height: 20px;"></div>
            @endif
            @if ($signature)
               <p style="margin: 0;"><strong>{{$signature->name_en}}</strong></p>
               <p style="margin: 0;">{{$signature->job_title_en}}</p>
            @endif
        </div>
        
        <!-- Stamp -->
        <div style="position: absolute; right: 100px; bottom: 150px;">
            @if(isset($documentPreparation->stamp))
                <img src="{{ asset($documentPreparation->stamp) }}" alt="LGMC Stamp" class="stamp">
            @endif
        </div>
    </div>

    @elseif($documentPreparation->document_type == 'verification_work')
    <!-- Date and Reference -->
    <div class="info-line">
        <div>Date : {{ now()->format('d / m / Y') }}</div>
        <div>Ref.No.: .................................................. </div>
    </div>
    
    <!-- Title -->
    <div style="text-align: center; margin-top: 170px;">
        <h2 style="font-size: 24px; font-weight: bold;">
            VERIFICATION LETTER
        </h2>
    </div>
    
    <!-- TO WHOM IT MAY CONCERN -->
    <div style="text-align: center; margin-top: 30px; margin-bottom: 40px;">
        <p style="font-size: 16px;">To whom it may concern</p>
    </div>
    
    <!-- Content -->
    <div class="content">
        <p style="margin-top: 30px;">
            I would like to certify that <strong>Dr. {{strtoupper($documentPreparation->doctorMail->doctor->name)}}</strong>, Libyan
            nationality, passport number:<strong>{{$documentPreparation->doctorMail->doctor->passport_number}}</strong>, and holds full registration in the Libyan 
            General Medical Council, No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong> – {{$documentPreparation->doctorMail->doctor->branch->name_en}}, since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
        </p>
        
       
        <!-- The problematic section starts around line 620-650 -->
        @if(isset($documentPreparation->preparation_data['work_specialty']) && is_array($documentPreparation->preparation_data['work_specialty']))
        @php
            $nameParts = explode(' ', $documentPreparation->doctorMail->doctor->name);
            $lastName = strtoupper(end($nameParts));
        @endphp
        <p style="margin-top: 20px;">
            <strong>Dr. {{$lastName}}</strong> has completed 
            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                his internship on <strong>{{$documentPreparation->doctorMail->doctor->internship_completion_date ?? '15/03/2017'}}</strong>. Then he has worked as a trainee doctor
            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                her internship on <strong>{{$documentPreparation->doctorMail->doctor->internship_completion_date ?? '15/03/2017'}}</strong>. Then she has worked as a trainee doctor
            @else
                their internship on <strong>{{$documentPreparation->doctorMail->doctor->internship_completion_date ?? '15/03/2017'}}</strong>. Then they have worked as a trainee doctor
            @endif
            in 
            @foreach($documentPreparation->preparation_data['work_specialty'] as $index => $specialty)
                @if(isset($documentPreparation->preparation_data['work_hospital'][$index]) && 
                isset($documentPreparation->preparation_data['work_from'][$index]) && 
                isset($documentPreparation->preparation_data['work_to'][$index]))
                    {{$specialty}} department at {{$documentPreparation->preparation_data['work_hospital'][$index]}}, Tripoli – Libya from 
                    {{\Carbon\Carbon::parse($documentPreparation->preparation_data['work_from'][$index])->format('d/m/Y')}} up to 
                    {{\Carbon\Carbon::parse($documentPreparation->preparation_data['work_to'][$index])->format('d/m/Y')}}
                    @if(!$loop->last)
                        . Then 
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                            he has
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                            she has
                        @else 
                            they have
                        @endif
                        worked as a trainee doctor in 
                    @else
                        .
                    @endif
                @endif
            @endforeach
        </p>
        @else
        @php
            $nameParts = explode(' ', $documentPreparation->doctorMail->doctor->name);
            $lastName = strtoupper(end($nameParts));
        @endphp
        <p style="margin-top: 20px;">
            <strong>Dr. {{$lastName}}</strong> has completed 
            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                his internship on <strong>{{$documentPreparation->doctorMail->doctor->internship_completion_date ?? '15/03/2017'}}</strong>.
            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                her internship on <strong>{{$documentPreparation->doctorMail->doctor->internship_completion_date ?? '15/03/2017'}}</strong>.
            @else
                their internship on <strong>{{$documentPreparation->doctorMail->doctor->internship_completion_date ?? '15/03/2017'}}</strong>.
            @endif
        </p>
        @endif
        
        <p style="margin-top: 20px;">
            @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                He is in good standing, good moral character and there is no legal or disciplinary 
                proceeding in action or contemplated against him.
            @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                She is in good standing, good moral character and there is no legal or disciplinary 
                proceeding in action or contemplated against her.
            @else
                The doctor is in good standing, good moral character and there is no legal or disciplinary 
                proceeding in action or contemplated against them.
            @endif
        </p>
        
        <p style="margin-top: 20px;">
            If you require further assistance, please don't hesitate to contact us.
        </p>
    </div>
    
    <!-- Validity Box -->
    <div class="validity-box" style="margin-top: 40px;">
        <em>Validity of this document is for <strong>90 days</strong>.</em><br>
        <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature">
            <p style="margin: 0;">Yours sincerely</p>
            @if(isset($documentPreparation->preparedBy->signature))
                <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
            @else
                <div style="height: 20px;"></div>
            @endif
            @if ($signature)
               <p style="margin: 0;"><strong>{{$signature->name_en}}</strong></p>
               <p style="margin: 0;">{{$signature->job_title_en}}</p>
            @endif
        </div>
        
        <!-- Stamp -->
        <div style="position: absolute; right: 100px; bottom: 150px;">
            @if(isset($documentPreparation->stamp))
                <img src="{{ asset($documentPreparation->stamp) }}" alt="LGMC Stamp" class="stamp">
            @endif
        </div>
    </div>
            @elseif($documentPreparation->document_type == 'certificate')
              
               
               <!-- Date and Reference -->
               <div class="info-line">
                  <div>Date : {{ now()->format('d / m / Y') }}</div>
                  <div>Ref.No.: {{$documentPreparation->id ?? '.......................'}} </div>
               </div>
               
               <!-- Title -->
               <div style="text-align: center; margin: 40px 0;">
                  <h2 style="font-size: 24px; font-weight: bold;">
                        CERTIFICATE
                  </h2>
               </div>
               
               <!-- TO WHOM IT MAY CONCERN -->
               <div style="text-align: center; margin-top: 30px; margin-bottom: 40px;">
                  <p style="font-size: 16px;">To whom it may concern</p>
               </div>
               
               <!-- Content -->
               <div class="content">
                  <p style="margin-top: 30px;">
                        This is to certify that <strong>DR. {{strtoupper($documentPreparation->doctorMail->doctor->name)}}</strong>, was one 
                        of the working staff in general practitioner department.
                  </p>
                  
                  <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                           He holds full registration in the Libyan General Medical Council No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                           since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                           She holds full registration in the Libyan General Medical Council No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                           since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @else
                           The doctor holds full registration in the Libyan General Medical Council No: <strong>{{$documentPreparation->doctorMail->doctor->code}}</strong>—{{$documentPreparation->doctorMail->doctor->branch->name_en}}, 
                           since <strong>{{$documentPreparation->doctorMail->doctor->registered_at}}</strong>.
                        @endif
                  </p>
                  
                  <p style="margin-top: 20px;">
                        @if($documentPreparation->doctorMail->doctor->gender->value == 'male')
                           We have no objection for the above named doctor to practice medicine in the Republic 
                           of Germany as far as his job in Libya is not maintained any more.
                        @elseif($documentPreparation->doctorMail->doctor->gender->value == 'female')
                           We have no objection for the above named doctor to practice medicine in the Republic 
                           of Germany as far as her job in Libya is not maintained any more.
                        @else
                           We have no objection for the above named doctor to practice medicine in the Republic 
                           of Germany as far as their job in Libya is not maintained any more.
                        @endif
                  </p>
               </div>
               
               <!-- Validity Box -->
               <div class="validity-box" style="margin-top: 40px;">
                  <em>Validity of this document is for <strong>90 days</strong>.</em><br>
                  <small>The paper is considered <strong>NULL AND VOID</strong> in the event of alteration or change of the contents.</small>
               </div>
               
               <!-- Signature Section -->
               <div class="signature-section">
                  <div class="signature">
                        <p style="margin: 0;">Yours sincerely</p>
                        @if(isset($documentPreparation->preparedBy->signature))
                           <img src="{{ asset($documentPreparation->preparedBy->signature) }}" alt="Signature">
                        @else
                           <div style="height: 20px;"></div>
                        @endif
                        <p style="margin: 0;"><strong>Dr. MOHAMED ALI ALGHOJ</strong></p>
                        <p style="margin: 0;">Secretary General</p>
                  </div>
                  
                  <!-- Stamp -->
                  <div style="position: absolute; right: 100px; bottom: 150px;">
                        @if(isset($documentPreparation->stamp))
                           <img src="{{ asset($documentPreparation->stamp) }}" alt="LGMC Stamp" class="stamp">
                        @endif
                  </div>
               </div>
               </div>
                @else 
                <div class="text-center p-5">
                    <h3>{{ $documentTypeName }}</h3>
                    <p>محتوى الوثيقة غير متوفر حالياً</p>
                </div>
            @endif
        </section>
    </body>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.4/jspdf.plugin.autotable.min.js"></script>

@if(request('download'))
<script>
    const invoice = this.document.getElementById("invoice");
    var opt = {
        margin: 0,
        pagesplit: true,
        filename: 'specialist_certificate_{{$documentPreparation->id}}.pdf',
        image: {
            type: 'jpeg',
            quality: 0.98
        },
        html2canvas: {
            scale: 2,
            useCORS: true
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        }
    };
    html2pdf().from(invoice).set(opt).then(function(pdf) {
        setTimeout(() => {
            window.close();
        }, 1000);
    }).save();
</script>
@else
<script>
    setTimeout(() => {
        window.print()
    }, 400);
</script>
@endif

</html>