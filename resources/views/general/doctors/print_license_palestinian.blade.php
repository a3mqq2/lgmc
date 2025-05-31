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
    <title> طباعة اذن مزاولة رقم : #{{$doctor->licence->code}} </title>


</head>

<div id="invoice">

    @php
        // if the licence for the doctor || check if the doctor is libyan or forign doctor
        // else if it is for clinic 

        $file_type = "";
        $file_type = "foreign";



    @endphp
    <body class="A4">
        <section class="sheet zima bill {{$file_type}} ">


            <div class="issued-licence-date text-muted" style="direction: rtl;
            font-size: 12px;
            margin: 10px;">
                <p class="m-0">تاريخ الطباعة : {{$doctor->created_at->format('Y-m-d')}}</p>
                <p class="m-0">     الأشاري  ............................................... </p>
            </div>

            <div class="doctor-photo">
                <img src="{{ Storage::url($doctor->files->first()?->file_path) }}" alt="صورة الطبيب">
            </div>


            <div class="card-title">
                    إذن مزاولة مهنة الطب (لغير الليبيين)
            </div>



            <p class="numbero">رقم الاذن   : #{{$doctor->licence->code}}</p>

            <p class="sub-title">في شأن مزاولة مهنة الطب بمؤسسة او مركز او عيادة طبية</p>

            <div class="libyan">


                <div class="branch-sett" style="position: absolute;
                right: 50px;
                top: 540px;">
                </div>

          



                <div class="doctor-name">

                </div>



                <div class="card-permissions">
                    <p class="main-head m-0" style="font-size: 11px;margin-bottom: 10px !important;margin-bottom:0!important;">بعد الاطلاع على</p>
                    <ol class="list-unstyled" dir="rtl" style="font-size: 10px!important;margin-bottom:0!important;">
                        <li><span>&#8592;</span> الاعلان الدستوري الصادر عن المجلس الوطني الانتقالي بتاريخ 03/08/2011.</li>
                        <li><span>&#8592;</span> وعلى قرار تشكيل المجلس الوطني المؤقت وتحديد اختصاصاته ونظامه الأساسي.</li>
                        <li><span>&#8592;</span> وعلى قرار المجلس الوطني الانتقالي رقم 184 لسنة 2011 بشأن اعتماد حكومة الانتقالية.</li>
                        <li><span>&#8592;</span> وعلى قرار مجلس الوزراء رقم 38 لسنة 2012 بشأن اعتماد الهيكل التنظيمي واختصاصات وزارة الصحة وتنظيم جهازها الإداري.</li>
                        <li><span>&#8592;</span> وعلى القانون رقم 23 لسنة 1428 بشأن نقابة الأطباء ولائحته التنفيذية.</li>
                        <li><span>&#8592;</span> وعلى القانون رقم 106 لسنة 1973 ولائحته.</li>
                        <li><span>&#8592;</span> وعلى القانون رقم 9 لسنة 1985 ولائحته.</li>
                        <li><span>&#8592;</span> وعلى اللوائح والقوانين المنظمة لعمل نقابة أطباء البشريين.</li>
                        <li><span>&#8592;</span> وبالإطلاع علـى سجل العضوية للنقابة العامة للأطباء   </li>
                    </ol>
                    <p class="main-head m-0" style="font-size: 13px;" dir="rtl"> وعليـــه  ...   </p>
                </div>
                
   
                <div class="">
                    <div class="permit">
                        <h6 class="font-weight-bold">
                            <strong>
                                يـــــؤذن للطبيب
                            </strong>
                        </h6>
                    </div>
                    <div class="name-box-visitor card  p-3" style=" text-align: center;
                    width: 246px !important;
                    position: absolute;
                    top: 512px;
                    right: 158px;
                    background: linear-gradient(45deg, #ffcaca, #cc20281c);
                    border: 2px dashed #6a6a6a;
                    padding: 5px !important;
                    font-size: 2px;">
                        <h3 class="font-weight-bold" style="font-size: 14px !important;
                        margin: 0;">
                            <strong>{{$doctor->name}}</strong>
                        </h3>
                    </div>
                </div>


                <div class="his-national" style="font-size: 25px;
                font-weight: bold !important;
                text-align: center;
                position: absolute;
                right: 430px;
                bottom: 570px;">
                  <h6 class="font-weight-bold">
                      <strong>
                        من الدولــــة
                      </strong>
                  </h6>
              </div>


              <div class="country-name-box card  p-3" style="text-align: center;
              width: 140px;
              position: absolute;
              top: 509px;
              right: 547px;
              background: linear-gradient(45deg, #ffcaca, #cc20281c);
              border: 2px dashed #6a6a6a;
              padding: 5px !important;
              font-size: 2px;">
                  <h6 class="font-weight-bold">
                     <strong>{{$doctor->country?->country_name_ar}}</strong>
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
                        <strong>{{$doctor->passport_number}}</strong>
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
                        <strong>{{$doctor->code}}</strong>
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
                        <strong>{{$doctor->doctor_rank->name}}
                            @if ($doctor->specialty1)
                                -   {{$doctor->specialization}}  
                            @endif
                        </strong>
                    </h4>
                </div>





                @if (($doctor->MedicalFacility && ! $doctor->institution) || $doctor->MedicalFacility)
                <div class="in-place">
                 <h6 class="font-weight-bold">
                     <strong>
                         بـالمنشأة الطــبية  
                     </strong>
                 </h6>
             </div>
 
             <div class="medical-facility-box card">
                 <h4 class="font-weight-bold">
                         <strong>  {{$doctor->MedicalFacility ? $doctor->MedicalFacility->name : "-"}}  </strong>
                 </h4>
 
             </div>
                @endif



                @if ($doctor->institution && ! $doctor->MedicalFacility)
                <div class="in-place">
                 <h6 class="font-weight-bold">
                     <strong>
                          بــالجهــة  
                     </strong>
                 </h6>
             </div>
 
             <div class="medical-facility-box card">
                 <h4 class="font-weight-bold">
                         <strong>  {{$doctor->institution}}  </strong>
                 </h4>
 
             </div>
                @endif







                <div class="expired card">
                    <h5 class="text-center font-weight-bold">
                        <strong>ينتهي العمل بهذا الإذن ويعتبر ملغياََ بتاريخ {{ date('Y-m-d', strtotime($doctor->licence->expiry_date)) }} </strong>
                    </h5>
                </div>



                <div class="signuture">
                </div>

                <div class="valid text-danger text-right font-weight-bold">
                    تحقق من الاذن من هنا
                </div>

                <div class="qr-code card p-2">
                    <div class="code">
                        @php
                        $link = env('APP_URL') . "checker?licence=" . $doctor->code;
                    $qrCode = DNS2D::getBarcodePNG($link, 'QRCODE', 5, 5);
                    @endphp
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 50px; height: 50px;" />
                    </div>
                </div>


            </div>


            <div class="important_notes" style="    position: absolute;
            bottom: 60px;
            direction: rtl;
            right: 26px;
            font-size: 10px !important;
            text-align: right;">
                <p class="m-0 font-weight-bold"><strong class="arrow" >←</strong> يجب ان يراعى التجديد حسب التاريخ المذكور اعلاه</p>
                <p class="m-0 font-weight-bold"><strong class="arrow">←</strong> لا يسمح للطبيب بالعمل الا في الجهة المذكورة فقط  </p>
                <p class="m-0 font-weight-bold"><strong class="arrow">←</strong> يلغى هذا الاذن في حالة مخالفة القوانين واللوائح المنظمة للمهنة </p>
            </div>


            @isset($signature)
                <div class="signature" style="position: absolute;
                bottom: 162px;
                left: 120px;">
                    <h6 class="text-center">{{$signature->name}}</h6>
                    <h6 class="text-center">{{$signature->job_title_en}}</h6>
                </div>
            @endisset

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