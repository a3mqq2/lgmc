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
    <title> طباعة اذن مزاولة رقم : #{{$licence->id}} </title>


</head>

<div id="invoice">

    @php
        // if the licence for the doctor || check if the doctor is libyan or forign doctor
        // else if it is for clinic 

        $file_type = "";
        if($licence->licensable_type == "App\Models\Doctor") {
            if($licence->licensable->type == \App\Enums\DoctorType::Libyan->value)
            {
                $file_type = "libyan";
            }


            if($licence->licensable->type == \App\Enums\DoctorType::Foreign->value)
            {
                $file_type = "foreign";
            }


            if($licence->licensable->type == \App\Enums\DoctorType::Visitor->value)
            {
                $file_type = "visitor";
            }


            if($licence->licensable->type == \App\Enums\DoctorType::Palestinian->value)
            {
                $file_type = "foreign";
            }


            

        } else {
            $file_type = "facilities";
        }



    @endphp
    <body class="A4">
        <section class="sheet zima bill {{$file_type}} ">


            <div class="issued-licence-date text-muted">
                {{-- humman readable --}}
                <p>تاريخ الاصدار : {{$licence->created_at->format('Y-m-d')}}</p>
            </div>

            <div class="doctor-photo">
                <img src="{{ Storage::url($licence->licensable->files->first()?->file_path) }}" alt="صورة الطبيب">
            </div>

            <div class="libyan">


                <div class="branch-sett">
                    <h6 class="font-weight-bold"><strong>وبالإطلاع علـى سجل العضوية لنقابة أطباء {{ $licence->licensable->branch->name }}</strong></h6>
                </div>

                <div class="request-sett">
                  <h6 class="font-weight-bold text-danger" style="font-size:10px!important;"><strong>وبناء على الطلب المقدم إلينا من {{ $licence->MedicalFacility->name }}</strong></h6>
              </div>



                <div class="doctor-name">

                </div>

                <div id="id-visitor">
                    <p>#{{$licence->id}}</p>
                </div>
   
                <div class="permit">
                    <h6 class="font-weight-bold">
                        <strong>
                            يـــــؤذن للطبيب
                        </strong>
                    </h6>
                </div>
                <div class="name-box-visitor card  p-3">
                    <h3 class="font-weight-bold">
                        <strong>{{$licence->licensable->name}}</strong>
                    </h3>
                </div>


                <div class="his-national">
                  <h6 class="font-weight-bold">
                      <strong>
                        من الدولــــة
                      </strong>
                  </h6>
              </div>


              <div class="country-name-box card  p-3">
                  <h6 class="font-weight-bold">
                     <strong>{{$licence->licensable->country->nationality_name_ar}}</strong>
                  </h6>
            </div>
            

                <div class="that-registered">

                    <h6 class="font-weight-bold">
                        <strong>
                             رقم الجواز
                        </strong>
                    </h6>
                </div>

                <div class="branch-box card  p-3">
                    <h6 class="font-weight-bold">
                        <strong>{{$licence->licensable->passport_number}}</strong>
                    </h6>
                </div>


                <div class="under-number">

                    <h6 class="font-weight-bold">
                        <strong>
                            رقم العضوية
                        </strong>
                    </h6>
                </div>


                <div class="number-box card  p-3">
                    <h6 class="font-weight-bold">
                        <strong>{{$licence->licensable->id}}</strong>
                    </h6>
                </div>



                <div class="work-for">

                    <h6 class="font-weight-bold">
                        <strong>
                            بـــــمزاولة مـــهنة 
                        </strong>
                    </h6>
                </div>


                <div class="work-box card  p-3">
                    <h4 class="font-weight-bold">
                        <strong>{{$licence->licensable->doctor_rank->name}} \ {{$licence->licensable->specialization}}  </strong>
                    </h4>
                </div>





                <div class="in-place">

                    <h6 class="font-weight-bold">
                        <strong>
                            بـالمنشأة الطــبية  
                        </strong>
                    </h6>
                </div>



                <div class="medical-facility-box card">
                    <h4 class="font-weight-bold">
                          <strong>  {{$licence->MedicalFacility ? $licence->MedicalFacility->name : "-"}}  </strong>
                    </h4>

                </div>

                <div class="expired card">
                    <h5 class="text-center font-weight-bold">
                        <strong>ينتهي العمل بهذا الإذن ويعتبر ملغياََ بتاريخ {{ $licence->expiry_date }} </strong>
                    </h5>
                </div>



                <div class="signuture">
                    <h5><strong>نقابة أطباء {{$licence->licensable->branch->name}} </strong></h5>
                </div>

                <div class="valid text-danger text-right font-weight-bold">
                    تحقق من الاذن من هنا
                </div>

                <div class="qr-code card p-2">
                    <div class="code">
                        @php
                        $link = env('APP_URL') . "checker?licence=" . $licence->licensable->code;
                    $qrCode = DNS2D::getBarcodePNG($link, 'QRCODE', 5, 5);
                    @endphp
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 50px; height: 50px;" />
                    </div>
                </div>


            </div>
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