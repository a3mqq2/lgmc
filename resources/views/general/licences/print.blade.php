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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <title> طباعة اذن مزاولة رقم : #{{$licence->id}} </title>


</head>

<div id="invoice">

    @php
        // if the licence for the doctor || check if the doctor is libyan or forign doctor
        // else if it is for clinic 

        $file_type = "";
        if($licence->licensable_type == "App\Models\Doctor") {
            if($licence->licensable->type->value == \App\Enums\DoctorType::Libyan->value)
            {
                $file_type = "libyan";
            }


            if($licence->licensable->type->value == \App\Enums\DoctorType::Foreign->value)
            {
                $file_type = "foreign";
            }


            if($licence->licensable->type->value == \App\Enums\DoctorType::Visitor->value)
            {
                $file_type = "visitor";
            }


            if($licence->licensable->type->value == \App\Enums\DoctorType::Palestinian->value)
            {
                $file_type = "foreign";
            }


            

        } else {
            $file_type = "facilities";
        }



    @endphp
    <body class="A4">
        <section class="sheet zima bill {{$file_type}} ">
            @if ($file_type == "libyan")
            <div class="libyan">
                <div id="id">
                    <p>#{{$licence->id}}</p>
                </div>
                <div class="libyan-name">
                    {{$licence->licensable->name}}
                </div>
                <div class="libyan-branch">
                    {{$licence->branch->name}}
                </div>
                <div class="libyan-doctor-id">
                    {{$licence->licensable->id}}
                </div>
                <div class="libyan-specilists">
                    طبيب
                    @if ($licence->licensable->specialty1)
                        {{ $licence->licensable->specialty1->name }}
                        @endif

                        @if ($licence->licensable->specialty2)
                            -
                            {{ $licence->licensable->specialty2->name }}
                        @endif

                        @if ($licence->licensable->specialty3)
                        -
                        {{ $licence->licensable->specialty3->name }}
                    @endif
                </div>
                <div class="libyan-facility">
                    {{$licence->MedicalFacility?->name}}
                </div>
                <div class="expired_at">
                    {{$licence->expiry_date}}
                </div>
                <div class="barcode">
                    @php
                    $qrCode = DNS2D::getBarcodePNG('https://example.com', 'QRCODE', 10, 10);
                    @endphp
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 100px; height: 100px;" />
                </div>
            </div>
            @elseif($file_type == "foreign")
            <div class="foreign">
                <div id="id">
                    <p>#{{$licence->id}}</p>
                </div>
                <div class="foreign-name">
                    {{$licence->licensable->name}}
                </div>
                <div class="foreign-country">
                    {{$licence->licensable->country?->name}}
                </div>
                <div class="foreign-passport">
                    {{$licence->licensable->passport_number}}
                </div>
                <div class="foreign-doctor-id">
                    {{$licence->licensable->id}}
                </div>
                <div class="foreign-specilists">
                    طبيب
                    @if ($licence->licensable->specialty1)
                        {{ $licence->licensable->specialty1->name }}
                        @endif

                        @if ($licence->licensable->specialty2)
                            -
                            {{ $licence->licensable->specialty2->name }}
                        @endif

                        @if ($licence->licensable->specialty3)
                        -
                        {{ $licence->licensable->specialty3->name }}
                    @endif
                </div>
                <div class="foreign-facility">
                    {{$licence->MedicalFacility?->name}}
                </div>
                <div class="expired_at">
                    {{$licence->expiry_date}}
                </div>
                <div class="barcode">
                    @php
                    $qrCode = DNS2D::getBarcodePNG('https://example.com', 'QRCODE', 10, 10);
                    @endphp
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 100px; height: 100px;" />
                </div>
                @elseif($file_type == "visitor")
                <div class="visitor">
                    <div id="id">
                        <p>#{{$licence->id}}</p>
                    </div>
                    <div class="hoster-name">
                        {{$licence->MedicalFacility->name}}
                    </div>
                    <div class="hoster-id">
                        {{$licence->MedicalFacility->id}}
                    </div>
                    <div class="hoster-licence">
                        {{$licence->MedicalFacility->commerical_number}}
                    </div>
                    <div class="visitor-name">
                        {{$licence->licensable->name}}
                    </div>
                    <div class="visitor-country">
                        {{$licence->licensable->country->name}}
                    </div>
                    <div class="visitor-passport">
                        {{$licence->licensable->passport_number}}
                    </div>
                    <div class="visitor-doctor-id">
                        {{$licence->licensable->id}}
                    </div>
                    <div class="visitor-specilists">
                        طبيب
                        @if ($licence->licensable->specialty1)
                            {{ $licence->licensable->specialty1->name }}
                            @endif

                            @if ($licence->licensable->specialty2)
                                -
                                {{ $licence->licensable->specialty2->name }}
                            @endif

                            @if ($licence->licensable->specialty3)
                            -
                            {{ $licence->licensable->specialty3->name }}
                        @endif
                    </div>
                    <div class="expired_at">
                        {{$licence->expiry_date}}
                    </div>
                    <div class="barcode">
                        @php
                        $qrCode = DNS2D::getBarcodePNG('https://example.com', 'QRCODE', 10, 10);
                        @endphp
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 100px; height: 100px;" />
                    </div>
                @elseif($file_type == "facilities")
                <div id="id">
                    <p>#{{$licence->id}}</p>
                </div>
                <div class="name_facility">
                    {{$licence->licensable->name}}
                </div>
                <div class="facility_branch">
                    {{$licence->licensable->branch ? $licence->licensable->branch->name : "/"}}
                </div>
                <div class="lincable_id_facilities">
                    000{{$licence->licensable->id ? $licence->licensable->id : "/"}}
                </div>
                <div class="commerical_number">
                    {{$licence->licensable->id ? $licence->licensable->commerical_number : "/"}}
                 </div>
                 <div class="doctor_name">
                    {{$licence->licensable->manager ? $licence->licensable->manager->name : "/"}}
                 </div>
                 <div class="doctor_id">
                    000{{$licence->licensable->manager ? $licence->licensable->manager->id : ""}}
                </div>
                <div class="expired_at">
                    {{$licence->expiry_date}}
                </div>
                <div class="barcode_facilitiy">
                    @php
                    $qrCode = DNS2D::getBarcodePNG('https://example.com', 'QRCODE', 10, 10);
                    @endphp
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 100px; height: 100px;" />
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
        filename: 'quotation.pdf',
        image: {
            type: 'svg',
            quality: 1
        },
        html2canvas: {
            scale: 1
        },
        jsPDF: {
            unit: 'cm',
            format: 'A4',
            orientation: 'portrait'
        }
    };
    html2pdf().from(invoice).set(opt).then(function(pdf) {
        setTimeout(() => {
            location.href = '/admin/flights/{{$car->flight_id}}';
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