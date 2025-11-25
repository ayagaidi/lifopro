@extends('layouts.authapp')

@section('title', 'التحقق من الهوية')

@section('content')
<form method="POST" class="frm-single" id="otp-form" action="{{ route('2fa.verify') }}">
    @csrf

    <div class="inside">
      <a href="{{route('/')}}" style="text-align: center;color: #a25541;" class="button-back">
           رجوع الي الصفحة الرئيسية<span class="fa fa-arrow-left" style="color: #a25541;font-size: larger;"></span> 
          </a>

        <div class="title" style="font-weight:bold;color:#a25541;">
            <img src="{{ asset('logo.svg') }}" alt="" style="max-width:20% !important;">
            الاتـــحـاد الليبي للتـــأمين
        </div>

        <div class="title"><strong>التحقق من الهوية</strong></div>
        <p class="text-muted" style="margin-bottom:15px;">تم إرسال رمز التحقق إلى بريدك الإلكتروني</p>

        @if ($errors->any())
            <div class="alert alert-danger" style="text-align:left;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li style="direction:rtl;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="frm-input" style="text-align:center;">
            <label for="otp_code" style="display:block;margin-bottom:8px;font-weight:600;">رمز التحقق (6 أرقام)</label>
            <input type="text" class="frm-inp form-control text-center @error('otp_code') is-invalid @enderror"
                   id="otp_code" name="otp_code" maxlength="6" placeholder="000000" autofocus required
                   style="font-size:24px;font-weight:bold;letter-spacing:8px;text-align:center;direction:ltr;font-family:'Courier New',monospace;">
            <small class="form-text text-muted" style="display:block;margin-top:8px;">
                يرجى إدخال الرمز المكون من 6 أرقام المرسل إلى بريدك الإلكتروني
            </small>

            @error('otp_code')
            <span class="invalid-feedback" style="color: red;display:block;margin-top:8px;" role="alert">
                {{ $message }}
            </span>
            @enderror
        </div>

        <div class="clearfix margin-bottom-20" style="margin-top:15px;"></div>

        <button type="submit" class="frm-submit" id="verify-btn">
            <i class="fa fa-check"></i> تأكيد الرمز
        </button>

        <div style="margin-top:12px;text-align:center;">
            <div class="countdown-timer" style="margin:12px 0;">
                <p class="mb-2">
                    <small class="text-muted">
                        الرمز صالح لمدة: <span id="timer">{{ gmdate('i:s', $remainingTime) }}</span>
                    </small>
                </p>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm" id="resend-btn" style="border:1px solid #a25541;color:#a25541;">
                <i class="fas fa-redo"></i> إعادة إرسال الرمز
            </button>
        </div>

        <!-- Additional Info -->
        <div class="frm-footer" style="margin-top:18px;">
            <div class="card" style="padding:12px;border-radius:8px;">
                <p class="text-muted mb-2" style="margin:0;">
                    <i class="fas fa-info-circle"></i>
                    <strong>معلومات الأمان:</strong>
                </p>
                <ul class="list-unstyled text-muted small" style="margin:6px 0 0 0;padding:0;">
                    <li>• هذا الرمز صالح لمدة 10 دقائق فقط</li>
                    <li>• لا تشارك هذا الرمز مع أي شخص آخر</li>
                    <li>• إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة</li>
                </ul>
            </div>
            <div style="margin-top:8px;font-size:13px;color:#888;">
                <?php echo date("Y"); ?> &copy; {{ trans('login.copyright') }}
            </div>
        </div>
    </div>
</form>

<style>
    /* reuse button-back style from login */
    .button-back {
        display: inline-flex;
        align-items: center;
        padding: 8px 14px;
        color: #fff;
        background: transparent;
        border: 1px solid #a25541;
        border-radius: 25px;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .button-back:hover { transform: translateY(-2px); }
    .button-back:active { transform: translateY(1px); }

    /* OTP input specific */
    @media (max-width: 768px) {
        #otp_code {
            font-size:20px !important;
            letter-spacing:6px !important;
        }
        .inside { padding: 18px; }
    }

    .timer-warning {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<script>
    $(document).ready(function() {
        // Keep only digits, auto-submit at 6
        $('#otp_code').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            $(this).val(value);

            if (value.length === 6) {
                setTimeout(function() {
                    $('#otp-form').submit();
                }, 500);
            }
        });

        // Countdown timer
        let totalSeconds = {{ $remainingTime }};
        let timer = setInterval(function() {
            if (totalSeconds <= 0) {
                clearInterval(timer);
                $('#timer').html('00:00');
                $('#verify-btn').prop('disabled', true);
                if (!$('.countdown-timer').find('.expired-msg').length) {
                    $('.countdown-timer').append(
                        '<p class="text-danger expired-msg"><small>انتهت صلاحية الرمز. يرجى طلب رمز جديد.</small></p>'
                    );
                }
                return;
            }

            let minutes = Math.floor(totalSeconds / 60);
            let seconds = totalSeconds % 60;
            $('#timer').html(String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0'));

            if (totalSeconds <= 120) {
                $('#timer').addClass('timer-warning');
            } else {
                $('#timer').removeClass('timer-warning');
            }

            totalSeconds--;
        }, 1000);

        // Resend OTP
        $('#resend-btn').click(function() {
            let $btn = $(this);
            $btn.prop('disabled', true);
            $btn.html('<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...');

            $.ajax({
                url: '{{ route('2fa.resend') }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        // show message — use alert for simplicity (you can replace with nicer UI)
                        alert(response.message);
                        // Reset timer to 10 minutes
                        totalSeconds = 600;
                        $('#timer').removeClass('timer-warning');
                        $('.expired-msg').remove();
                        $('#verify-btn').prop('disabled', false);
                    } else {
                        alert(response.message || 'لم يتم إرسال الرمز. حاول لاحقًا.');
                    }
                },
                error: function() {
                    alert('حدث خطأ أثناء إرسال الرمز. يرجى المحاولة مرة أخرى.');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $btn.html('<i class="fas fa-redo"></i> إعادة إرسال الرمز');
                }
            });
        });

        // Prevent Enter unless 6 digits
        $('#otp-form').keypress(function(e) {
            if (e.which === 13 && $('#otp_code').val().length !== 6) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
