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
            $morph = $licence->licensable;
            if($morph->country_id == 1) {
                $file_type = "libyan";
            } else {
                $file_type = "foreign";
            }
        } else {
            $file_type = "facilities";
        }



    @endphp
    <body class="A4">
        <section class="sheet zima bill {{$file_type}} ">
            <div id="id">
                <p>#{{$licence->id}}</p>
            </div>
            @if ($file_type == "facilities")
                <div class="name_facility">
                    {{$licence->licensable->name}}
                </div>
                @else 
                <div class="name">
                    {{$licence->licensable->name}}
                </div>
            @endif
            @if ($file_type == "libyan")
            <div class="branch">
                {{$licence->licensable->branch ? $licence->licensable->branch->name : "/"}}
            </div>
            @endif
            @if ($file_type == "facilities")
            <div class="facility_branch">
                {{$licence->licensable->branch ? $licence->licensable->branch->name : "/"}}
            </div>
            @endif
            @if ($file_type == "foreign")
            <div class="lincable_id_foreign">
                000{{$licence->licensable->id ? $licence->licensable->id : "/"}}
            </div>
            @endif
            @if ($file_type == "libyan")
            <div class="lincable_id">
                000{{$licence->licensable->id ? $licence->licensable->id : "/"}}
            </div>
            @endif
            @if ($file_type == "facilities")
            <div class="lincable_id_facilities">
                000{{$licence->licensable->id ? $licence->licensable->id : "/"}}
            </div>
            @endif
            @if ($file_type == "facilities")
                 <div class="commerical_number">
                    {{$licence->licensable->id ? $licence->licensable->commerical_number : "/"}}
                 </div>
            @endif

            @if ($file_type == "facilities")
                 <div class="doctor_name">
                    {{$licence->doctor ? $licence->doctor->name : "/"}}
                 </div>
            @endif

            @if ($file_type == "foreign")
                <div class="country_foregin">
                    {{$licence->licensable->country ? $licence->licensable->country->name : "/"}}
                </div>
                <div class="passport_foreign">
                    {{$licence->licensable->passport_number ? $licence->licensable->passport_number : "/"}}
                </div>
            @endif
            
            @if ($file_type == "foreign")
                <div class="specialty_foreign">
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
            @endif

            @if ($file_type == "facilities")
                <div class="doctor_id">
                    000{{$licence->doctor_id}}
                </div>
            @endif

            @if ($file_type == "libyan")
            <div class="specialty">
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
            @endif

            @if ($file_type == "foreign")
            <div class="facility">
                {{$licence->licensable->medicalFacilities->first()->name}}
            </div>
            @endif
            <div class="expired_at">
                {{$licence->expiry_date}}
            </div>
            @if ($file_type == "foreign")
            <div class="barcode_foregin">
                @php
                $qrCode = DNS2D::getBarcodePNG('https://example.com', 'QRCODE', 10, 10);
                @endphp
                <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 100px; height: 100px;" />
            </div>
            @endif 

            @if ($file_type == "libyan")
            <div class="barcode">
                @php
                $qrCode = DNS2D::getBarcodePNG('https://example.com', 'QRCODE', 10, 10);
                @endphp
                <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 100px; height: 100px;" />
            </div>
            @endif

            @if ($file_type == "facilities")
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