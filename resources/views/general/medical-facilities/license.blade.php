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
    <title> طباعة اذن مزاولة رقم : #{{$license->code}} </title>


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
                <p class="m-0">تاريخ الطباعة : {{$license->medicalFacility->created_at->format('Y-m-d')}}</p>
                <p class="m-0">     الأشاري  ............................................... </p>
            </div>

            <div class="doctor-photo">
                <img src="{{ Storage::url($license->medicalFacility->manager->files->first()?->file_path) }}" alt="صورة الطبيب">
            </div>


            <div class="card-title" dir="rtl" style="">
                  اذن مزاولة رقم (  <span class="text-primary"><strong>{{$license->code}}</strong></span> )
            </div>



            {{-- <p class="numbero">رقم الاذن   : #{{$license->code}}</p> --}}

            <p class="sub-title "> للعيادات والمصحات وشركات الخدمات الطبية </p>

            <div class="libyan">


                <div class="branch-sett" style="position: absolute;
                right: 50px;
                top: 540px;">
                </div>

          



                <div class="doctor-name">

                </div>



                <div class="card-permissions">
                    <p class="main-head m-0" style="font-size: 15px;margin-bottom: 10px !important;margin-bottom:0!important;"> استناداََ على    </p>
                    <ol class="list-unstyled" dir="rtl" style="font-size: 18px!important;margin-bottom:0!important;">
                        <li class="p-2 font-weight-bold" style="font-weight: bold!important;"><span>&#8592;</span>  المادة 8 من القانون رقم 96 لسنة 1976  </li>
                        <li class="p-2 font-weight-bold" style="font-weight: bold!important;"><span>&#8592;</span> والمادة 17 من النظام الأساسي للنقابة العامة لأطباء ليبيا </li>
                    </ol>
                </div>



                <div class="lice">
                     <p class="text-center font-weight-bold" style="    text-align: right;
                     position: absolute;
                     right: 0;
                     font-weight: bold;
                     top: 28.6rem;
                     right: 2rem;
                     font-size: 19px;">يـــــؤذن لـ{{$license->medicalFacility->type == "private_clinic" ? "عيادة" : "شركة" }}</p>

                     <p class="text-center font-weight-bold" style="text-align: right;
                     position: absolute;
                     right: 0;
                     font-weight: bold;
                     top: 28.3rem;
                     right: 12rem;
                     font-size: 20px;
                     background: #cc000033;
                     padding: 3px;
                     border-radius: 9px;
                     width: 540px;
                     border: 4px dashed #fff;"> {{$license->medicalFacility->name}} </p>



                     <p class="text-center font-weight-bold" style="    text-align: right;
                     position: absolute;
                     right: 0;
                     font-weight: bold;
                     top: 31.8rem;
                     right: 2rem;
                     font-size: 19px;">والمسجلة بالنقابة العامة لأطباء ليبيا تحت رقم</p>



                     <p class="text-center font-weight-bold" style="text-align: right;
                     position: absolute;
                     right: 0;
                     font-weight: bold;
                     top: 31.3rem;
                     right: 29rem;
                     font-size: 17px;
                     background: #cc000033;
                     padding: 5px;
                     border-radius: 9px;
                     width: 267px;
                     border: 4px dashed #fff;"> {{$license->medicalFacility->code}} </p>


                     <p class="text-center font-weight-bold" style="    text-align: right;
                     position: absolute;
                     right: 0;
                     font-weight: bold;
                     top: 35rem;
                     right: 2rem;
                     font-size: 19px;">ووفقاََ لسجلها التجاري رقم </p>


                                       
                  <p class="text-center font-weight-bold" style="text-align: right;
                  position: absolute;
                  right: 0;
                  font-weight: bold;
                  top: 34.5rem;
                  right: 18rem;
                  font-size: 20px;
                  background: #cc000033;
                  padding: 2px;
                  border-radius: 9px;
                  width: 210px;
                  border: 4px dashed #fff;"> {{$license->medicalFacility->commercial_number}} </p>





                  <p class="text-center font-weight-bold" style="    text-align: right;
                  position: absolute;
                  right: 0;
                  font-weight: bold;
                  top: 35rem;
                  right: 32rem;
                  font-size: 19px;">
                 بمزاولــــة نشــــــاط طبي     
               </p>



                  <p class="text-center font-weight-bold" style="    text-align: right;
                  position: absolute;
                  right: 0;
                  font-weight: bold;
                  top: 38rem;
                  right: 2rem;
                  font-size: 20px;"> ووفقاً لإذن مزاولة المهنة {{$license->medicalFacility->manager->gender->value == "male" ? "للطبيب" : "للطبيبة"}}  </p>




                  <p class="text-center font-weight-bold" style="text-align: right;
                  position: absolute;
                  right: 0;
                  font-weight: bold;
                  top: 37.5rem;
                  right: 23rem;
                  font-size: 20px;
                  background: #cc000033;
                  padding: 2px;
                  border-radius: 9px;
                  width: 364px;
                  border: 4px dashed #fff;"> {{$license->medicalFacility->manager->name}} </p>



            <p class="text-center font-weight-bold" style="    text-align: right;
            position: absolute;
            right: 0;
            font-weight: bold;
            top: 41rem;
            right: 2rem;
            font-size: 20px;"> الرقم النقابي </p>




            <p class="text-center font-weight-bold" style="text-align: right;
            position: absolute;
            right: 0;
            font-weight: bold;
            top: 40.5rem;
            right: 11rem;
            font-size: 20px;
            background: #cc000033;
            padding: 2px;
            border-radius: 9px;
            width: 274px;
            border: 4px dashed #fff;"> {{$license->medicalFacility->manager->code}} </p>




            <p class="text-center font-weight-bold" style="text-align: right;
            position: absolute;
            right: 0;
            font-weight: bold;
            top: 41rem;
            right: 29rem;
            font-size: 20px;"> لــمدة ســـــنة اعـتباراََ من</p>




            <p class="text-center font-weight-bold" style="    text-align: right;
            position: absolute;
            right: 0;
            font-weight: bold;
            top: 44.5rem;
            right: 2rem;
            font-size: 23px;
            background: #ffffffe3;
            padding: 10px;
            border-radius: 9px;
            width: 697px;
            border: 4px dashed #cc0100;
            color: #cc0100;" dir="rtl"> {{$license->medicalFacility->membership_expiration_date->subYear()->format('Y-m-d')}}  وينتهي بتاريخ {{$license->medicalFacility->membership_expiration_date->format('Y-m-d')}}     </p>


                



                </div>
                

                <div class="important_notes" style="    position: absolute;
                bottom: 60px;
                direction: rtl;
                right: 26px;
                font-size: 10px !important;
                text-align: right;">
                    <p class="m-0 font-weight-bold"><strong class="arrow" >←</strong> يجب ان يراعى التجديد حسب التاريخ المذكور اعلاه</p>
                    <p class="m-0 font-weight-bold"><strong class="arrow">←</strong> يلغى هذا الاذن في حالة مخالفة القوانين واللوائح المنظمة للمهنة </p>
                </div>
   

            @isset($signature)
                <div class="signature" style="position: absolute;
                bottom: 162px;
                left: 120px;">
                    <h6 class="text-center">{{$signature->name}}</h6>
                    <h6 class="text-center">{{$signature->job_title_ar}}</h6>
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