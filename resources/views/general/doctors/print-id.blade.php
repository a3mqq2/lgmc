
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://app.tmc.org.ly/css/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .print-card {
            width: 8.5cm;
            height: 5.37cm;
            overflow: hidden;
            /*font-family: "Simplified Arabic";*/
        }

        @media  print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
</head>
<body style="font-family: 'Cairo', sans-serif;">
<!-- بطاقة عربية -->
<div class="print-card flex justify-between text-xs leading-4 bg-white rounded-lg shadow-lg overflow-hidden">
   <!-- البيانات -->
   <div class="w-3/4 p-3 flex flex-col">
       <div class="flex items-center gap-2">
           <img src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" class="w-28">
       </div>

       <ul class="flex flex-col justify-center flex-1 mt-1 space-y-1">
           <li class="flex">
               <span class="w-2/5 text-red-600">الاسم</span>
               <span class="w-3/5 font-bold truncate">{{ $doctor->name }}</span>
           </li>
           <li class="flex leading-4">
               <span class="w-2/5 text-red-600">الصفة المهنية</span>
               <span class="w-3/5 font-bold">
                   {{ $doctor->doctor_rank->name }} {{ $doctor->specialization }}
               </span>
           </li>
           <li class="flex">
               <span class="w-2/5 text-red-600">رقم جواز السفر</span>
               <span class="w-3/5 font-bold">{{ $doctor->passport_number }}</span>
           </li>
           <li class="flex">
               <span class="w-2/5 text-red-600">صالحة إلى</span>
               <span class="w-3/5 font-bold">{{ date('Y-m-d', strtotime($doctor->membership_expiration_date)) }}</span>
           </li>
       </ul>
   </div>

   <!-- الصورة -->
   <div class="w-1/4 flex flex-col">
       <div class="flex-1 bg-red-600 flex items-center justify-center" style="-webkit-print-color-adjust:exact">
           <img src="{{ Storage::url($doctor->files->first()?->file_path) }}" class="w-full object-cover border-4 border-white">
       </div>
       <div class="bg-red-600 text-white text-center py-1 font-bold" style="-webkit-print-color-adjust:exact;font-size:10px !important;">
           CODE / {{$doctor->code}}
       </div>
   </div>
</div>

{{-- <!-- بطاقة إنجليزية -->
<div class="print-card flex justify-between text-xs leading-4 bg-white  shadow-lg overflow-hidden mt-3">
   <!-- QR + موقع -->
   <div class="w-1/4 bg-red-600 flex flex-col items-center justify-center p-2" style="-webkit-print-color-adjust:exact">
       <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="#ffffff">
           <rect width="70" height="70" rx="8" />
       </svg>
       <div class="text-white font-bold text-sm mt-2">tmc.org.ly</div>
   </div>

   <!-- البيانات -->
   <div class="w-3/4 p-3 flex flex-col" dir="ltr">
       <div class="flex items-center gap-2">
           <img src="{{asset('/assets/images/lgmc-dark.png?v=44')}}" class="w-14">
       </div>

       <ul class="flex flex-col justify-center flex-1 mt-1 space-y-1">
           <li class="flex">
               <span class="w-2/5 text-red-600">Name</span>
               <span class="w-3/5 font-bold truncate">{{}}</span>
           </li>
           <li class="flex leading-4">
               <span class="w-2/5 text-red-600">Profession</span>
               <span class="w-3/5 font-bold">Junior Doctor / GYNE & OBS</span>
           </li>
           <li class="flex">
               <span class="w-2/5 text-red-600">Passport No</span>
               <span class="w-3/5 font-bold">330253 - Libya</span>
           </li>
           <li class="flex">
               <span class="w-2/5 text-red-600">Valid to</span>
               <span class="w-3/5 font-bold">2026-05-04</span>
           </li>
       </ul>
   </div>
</div> --}}



<button class="mt-10 bg-red-600  text-white px-3 py-2 no-print" onClick="window.print()">
    <span class="glyphicon glyphicon-print"></span>
    طباعة البطاقة</button>

</body>
</html>
