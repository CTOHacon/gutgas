<!DOCTYPE html>
<html lang="{{ str_replace('ua', 'uk', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="color-scheme" content="light">
    <title>{{ __('product-registration.email_subject') }}</title>
</head>

{{-- Inline styles and table layout only: Gmail and Outlook drop most <style> rules. --}}
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; color: #1f2937;">
    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        {{ __('product-registration.email_preheader') }}
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden;">

                    {{-- Header: brand dark band with red accent --}}
                    <tr>
                        <td align="center" style="background-color: #151515; padding: 28px 20px 24px; border-bottom: 4px solid #f24942;">
                            <div style="font-size: 26px; line-height: 30px; font-weight: 800; letter-spacing: 4px; color: #ffffff;">GUT<span style="color: #f24942;">GAS</span></div>
                            <div style="margin-top: 14px; font-size: 20px; line-height: 26px; font-weight: 700; color: #ffffff;">
                                {{ __('product-registration.email_heading') }}
                            </div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 30px 24px 8px;">
                            <p style="margin: 0 0 16px; font-size: 16px; line-height: 24px; color: #374151;">
                                {{ __('product-registration.email_data_intro') }}
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border-radius: 6px;">
                                @foreach ($rows as $label => $value)
                                    <tr>
                                        <td valign="top" style="padding: 10px 12px 10px 16px; width: 42%; font-size: 14px; line-height: 20px; font-weight: 600; color: #374151; {{ $loop->last ? '' : 'border-bottom: 1px solid #e2e8f0;' }}">
                                            {{ $label }}
                                        </td>
                                        <td valign="top" style="padding: 10px 16px 10px 0; font-size: 14px; line-height: 20px; color: #4b5563; word-break: break-word; {{ $loop->last ? '' : 'border-bottom: 1px solid #e2e8f0;' }}">
                                            {{ $value }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <p style="margin: 28px 0 20px; font-size: 16px; line-height: 24px; color: #374151;">
                                {{ __('product-registration.email_cta_intro') }}
                            </p>

                            {{-- Bulletproof button --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto;">
                                <tr>
                                    <td align="center" bgcolor="#f24942" style="border-radius: 6px; background-color: #f24942;">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 16px 44px; font-size: 16px; line-height: 20px; font-weight: 700; letter-spacing: 1px; color: #ffffff; text-decoration: none; border-radius: 6px;">
                                            {{ mb_strtoupper(__('product-registration.email_button')) }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 28px 0 6px; font-size: 13px; line-height: 19px; color: #6b7280;">
                                {{ __('product-registration.email_link_fallback') }}
                            </p>
                            <p style="margin: 0 0 24px; font-size: 13px; line-height: 19px; word-break: break-all;">
                                <a href="{{ $url }}" target="_blank" style="color: #f24942; text-decoration: underline;">{{ $url }}</a>
                            </p>

                            <p style="margin: 0 0 24px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 13px; line-height: 19px; color: #6b7280;">
                                {{ __('product-registration.email_ignore') }}
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 20px; font-size: 14px; line-height: 20px; color: #6b7280;">
                            {{ __('emails.companyName') }}<br>
                            {{ __('emails.companyContact') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
