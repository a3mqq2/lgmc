<?php
// App/Mail/RejectionMedicalFacilityEmail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectionMedicalFacilityEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $medicalFacility;
    public $editReason;

    /**
     * Create a new message instance.
     */
    public function __construct($medicalFacility, string $editReason)
    {
        $this->medicalFacility = $medicalFacility;
        $this->editReason = $editReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'طلب تعديل بيانات المنشأة الطبية - ' . $this->medicalFacility->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rejection-medical-facility-email',
            with: [
                'medicalFacility' => $this->medicalFacility,
                'editReason' => $this->editReason,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

?>

{{-- resources/views/emails/rejection-medical-facility-email.blade.php --}}
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب تعديل بيانات المنشأة الطبية</title>
    <style>
        body {
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        
        .email-container {
            max-width: 650px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .header .subtitle {
            margin: 8px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .facility-info {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-right: 5px solid #007bff;
        }
        
        .facility-info h3 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .facility-details {
            display: grid;
            gap: 12px;
        }
        
        .detail-row {
            display: flex;
            align-items: center;
            font-size: 15px;
            color: #495057;
        }
        
        .detail-label {
            font-weight: 600;
            min-width: 120px;
            color: #6c757d;
        }
        
        .detail-value {
            font-weight: 500;
            color: #2c3e50;
        }
        
        .reason-section {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            position: relative;
        }
        
        .reason-section::before {
            content: '⚠️';
            position: absolute;
            top: -15px;
            right: 20px;
            background: #ffc107;
            padding: 8px 12px;
            border-radius: 50%;
            font-size: 20px;
        }
        
        .reason-title {
            color: #856404;
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 15px 0;
            padding-right: 35px;
        }
        
        .reason-text {
            background: rgba(255, 255, 255, 0.8);
            padding: 18px;
            border-radius: 8px;
            color: #6c5700;
            font-size: 16px;
            line-height: 1.6;
            font-weight: 500;
            border-right: 4px solid #ffc107;
        }
        
        .message-body {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            margin: 20px 0;
        }
        
        .action-section {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        
        .action-title {
            color: #0c5460;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .action-text {
            color: #155724;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .cta-button {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }
        
        .footer {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .contact-info {
            margin: 20px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .contact-info a {
            color: #74b9ff;
            text-decoration: none;
        }
        
        .copyright {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 13px;
            opacity: 0.8;
        }
        
        .divider {
            height: 3px;
            background: linear-gradient(90deg, #007bff, #6f42c1, #e83e8c);
            margin: 30px 0;
            border-radius: 2px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .content {
                padding: 25px 20px;
            }
            
            .header {
                padding: 25px 20px;
            }
            
            .facility-info, .reason-section, .action-section {
                padding: 20px;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <span class="icon">🏥</span>
                <h1>طلب تعديل بيانات المنشأة الطبية</h1>
                <p class="subtitle">النقابة العامة للاطباء - ليبيا</p>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                السيد المحترم / مدير {{ $medicalFacility->name }}
            </div>
            
            <p class="message-body">
                تحية طيبة وبعد،<br>
                نتواصل معكم بخصوص طلب تسجيل المنشأة الطبية الخاصة بكم لدى النقابة العامة للاطباء - ليبيا.
            </p>

            <!-- Facility Information -->
            <div class="facility-info">
                <h3>🏥 معلومات المنشأة</h3>
                <div class="facility-details">
                    <div class="detail-row">
                        <span class="detail-label">اسم المنشأة:</span>
                        <span class="detail-value">{{ $medicalFacility->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">العنوان:</span>
                        <span class="detail-value">{{ $medicalFacility->address }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">رقم الهاتف:</span>
                        <span class="detail-value">{{ $medicalFacility->phone_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">تاريخ التقديم:</span>
                        <span class="detail-value">{{ $medicalFacility->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <p class="message-body">
                بعد مراجعة دقيقة للمستندات والبيانات المقدمة، نود إبلاغكم بأن الطلب يحتاج إلى تعديلات قبل الموافقة النهائية.
            </p>

            <!-- Reason Section -->
            <div class="reason-section">
                <h3 class="reason-title">التعديلات المطلوبة</h3>
                <div class="reason-text">
                    {{ $editReason }}
                </div>
            </div>

            <!-- Action Section -->
            <div class="action-section">
                <h4 class="action-title">الخطوات التالية</h4>
                <p class="action-text">
                    يرجى مراجعة التعديلات المطلوبة أعلاه وإجراء التصحيحات اللازمة، ثم إعادة تقديم الطلب مع المستندات المحدثة.
                </p>
                <a href="{{ config('app.url') }}" class="cta-button">
                    🔗 الدخول إلى النظام
                </a>
            </div>

            <div class="divider"></div>

            <p class="message-body">
                <strong>ملاحظة مهمة:</strong> لديكم مدة 30 يوماً من تاريخ هذه الرسالة لإجراء التعديلات المطلوبة وإعادة تقديم الطلب. في حالة عدم الاستجابة خلال هذه المدة، سيتم إلغاء الطلب تلقائياً.
            </p>

            <p class="message-body">
                نحن في خدمتكم في أي وقت للإجابة على استفساراتكم أو تقديم المساعدة اللازمة في عملية التسجيل.
            </p>

            <p class="message-body">
                <strong>شكراً لتعاونكم وثقتكم بنا.</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="contact-info">
                <p><strong>للاستفسارات والدعم التقني:</strong></p>
                <p>📧 البريد الإلكتروني: <a href="mailto:support@libyan-doctors.ly">support@libyan-doctors.ly</a></p>
                <p>📞 الهاتف: 021-123-4567</p>
                <p>🌐 الموقع الإلكتروني: <a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
            </div>
            
            <div class="copyright">
                <p>© {{ date('Y') }} جميع الحقوق محفوظة - النقابة العامة للاطباء - ليبيا</p>
                <p>هذه رسالة آلية، يرجى عدم الرد على هذا البريد الإلكتروني</p>
            </div>
        </div>
    </div>
</body>
</html>