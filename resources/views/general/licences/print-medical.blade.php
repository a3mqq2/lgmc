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
    <title> طباعة اذن مزاولة رقم : #{{$license->id}} </title>


</head>

<div id="invoice">

    @php
        // if the licence for the doctor || check if the doctor is libyan or forign doctor
        // else if it is for clinic 

        $file_type = "";
        if($license->licensable_type == "App\Models\Doctor") {
            if($license->licensable->type == \App\Enums\DoctorType::Libyan->value)
            {
                $file_type = "libyan";
            }


            if($license->licensable->type == \App\Enums\DoctorType::Foreign->value)
            {
                $file_type = "foreign";
            }


            if($license->licensable->type == \App\Enums\DoctorType::Visitor->value)
            {
                $file_type = "visitor";
            }


            if($license->licensable->type == \App\Enums\DoctorType::Palestinian->value)
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
                <p>تاريخ الاصدار : {{$license->created_at->format('Y-m-d')}}</p>
            </div>




         <div class="qr">
            <div class="valid text-danger text-right font-weight-bold" style="position: absolute;
            top: 130px;
            left: 50px;
            width: 90px;
            font-weight: bold;
            font-size: 13px;bottom:auto;">
               تحقق من الاذن من هنا
           </div>

           <div class="qr-code card p-2" style="width: 100px;
           position: absolute;
           left: 156px;
           top: 120px;bottom:auto;">
               <div class="code">
                   @php
                   $link = env('APP_URL') . "checker?licence=" . $license->id;
               $qrCode = DNS2D::getBarcodePNG($link, 'QRCODE', 5, 5);
               @endphp
               <img src="data:image/png;base64,{{ $qrCode }}" alt="qrcode" style="width: 80px; height: 80px;" />
               </div>
           </div>
           
         </div>
            <div class="libyan">


                <div class="branch-sett">
                    <h6 class="font-weight-bold"><strong>وبالإطلاع علـى سجل العضوية لنقابة أطباء {{ $license->licensable->branch->name }}</strong></h6>
                </div>

          



                <div class="doctor-name">

                </div>

                <div id="id-foreign" style="left: 41px !important;width: 300px;
                position: absolute;
                top: 250px;
                left: -60px;">
                    <p style="    text-align: center;
                    font-size: 25px;
                    color: #e30613;
                    font-weight: bold;
                    font-family: sans-serif;">#{{$license->code}}</p>
                </div>
   
            
                <div class="permit" style="bottom:525px!important;">
                  <h6 class="font-weight-bold">
                      <strong>
                          يـــــؤذن 
                      </strong>
                  </h6>
              </div>


              <div class="name-box-visitor card  p-3" style="width: 556px!important;">
               <h3 class="font-weight-bold">
                   <strong>{{$license->licensable->name}}</strong>
               </h3>
           </div>




           <div class="his-national" style="font-size: 25px;
           font-weight: bold !important;
           text-align: center;
           position: absolute;
           right: 43px;
           bottom: 470px;">
            <h6 class="font-weight-bold">
                <strong>
                  ذات المسؤولية المحدودة والمسجلة بنقابة أطباء {{ $license->licensable->branch->name }} تحت رقم
                </strong>
            </h6>
            </div>


            <div class="country-name-box card  p-3" style="    text-align: center;
            width: 120px;
            position: absolute;
            top: 614px;
            right: 567px;
            background: linear-gradient(45deg, #ffcaca, #cc20281c);
            border: 2px dashed #6a6a6a;
            padding: 5px !important;
            font-size: 2px;">
               <h6 class="font-weight-bold">
                  <strong>{{$license->licensable->code}}</strong>
               </h6>
         </div>



         <div class="his-national" style="    font-size: 25px;
         font-weight: bold !important;
         text-align: center;
         position: absolute;
         right: 43px;
         bottom: 430px;">
          <h6 class="font-weight-bold">
              <strong>
               ووفقا لسجلها التجاري رقم 
              </strong>
          </h6>
          </div>



          <div class="branch-box card  p-3" style="text-align: center;
          width: 210px;
          position: absolute;
          top: 651px;
          right: 258px;
          background: linear-gradient(45deg, #ffcaca, #cc20281c);
          border: 2px dashed #6a6a6a;
          padding: 6px !important;
          font-size: 47px;">
            <h6 class="font-weight-bold">
                <strong>{{$license->licensable->commerical_number ?? '-'}}</strong>
            </h6>
        </div>


        <div class="his-national" style="    font-size: 25px;
        font-weight: bold !important;
        text-align: center;
        position: absolute;
        right: 493px;
        bottom: 429px;">
         <h6 class="font-weight-bold">
             <strong>
               بمزاولــــــة   نشاط {{ $license->licensable->type->name  }}
             </strong>
         </h6>
         </div>



         <div class="his-national" style="    font-size: 25px;
         font-weight: bold !important;
         text-align: center;
         position: absolute;
         right: 43px;
         bottom: 380px;">
          <h6 class="font-weight-bold">
              <strong>
               ووفقا لإذن مزاولة مهنة للطبيب
              </strong>
          </h6>
          </div>



          <div class="branch-box card  p-3" style="text-align: center;
          width: 270px;
          position: absolute;
          top: 700px;
          right: 308px;
          background: linear-gradient(45deg, #ffcaca, #cc20281c);
          border: 2px dashed #6a6a6a;
          padding: 6px !important;
          font-size: 47px;">
            <h6 class="font-weight-bold">
                <strong>{{$license->licensable->manager?->name}}</strong>
            </h6>
        </div>



        <div class="his-national" style="    font-size: 25px;
        font-weight: bold !important;
        text-align: center;
        position: absolute;
        right: 593px;
        bottom: 374px;
        width: 50px;
        text-align: right;">
         <h6 class="font-weight-bold">
             <strong>
               الرقم النقابي
            </strong>
         </h6>
         </div>



         <div class="country-name-box card  p-3" style="text-align: center;
         width: 70px;
         position: absolute;
         top: 697px;
         right: 647px;
         background: linear-gradient(45deg, #ffcaca, #cc20281c);
         border: 2px dashed #6a6a6a;
         padding: 5px !important;
         font-size: 2px;">
            <h6 class="font-weight-bold">
               <strong>{{$license->licensable->manager?->id}}</strong>
            </h6>
      </div>



     
      <div class="expired card" dir="rtl" style="top:770px!important;">
        <h6 class="text-center font-weight-bold d-inline-block">
            <strong class="d-inline-block">تم إصدار هذا الإذن بتاريخ  </strong>
            <div class="issued d-inline-block">
                {{$license->issued_date}}
            </div>
            <strong class="d-inline-block"> ويعتبر ملغياََ بتاريخ  </strong>
            <div class="issued d-inline-block">
                {{$license->expiry_date}}
            </div>
        </h6>
    </div>

     <div class="signuture" style="bottom: 230px;">
      <h5><strong>نقابة أطباء {{$license->licensable->branch->name}} </strong></h5>
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