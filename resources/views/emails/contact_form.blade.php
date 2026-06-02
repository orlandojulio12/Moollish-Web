<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva solicitud de demo — Moollish</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:40px 20px;">
    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" align="center"
                   style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#2d1a00 0%,#7a3d00 100%);padding:36px 40px;text-align:center;">
                        <p style="margin:0;color:#F5A623;font-size:1.8rem;font-weight:800;letter-spacing:-0.5px;">Moollish</p>
                        <p style="margin:8px 0 0;color:rgba(255,255,255,0.8);font-size:0.95rem;">
                            Nueva solicitud de demo desde moollish.com
                        </p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:36px 40px;">
                        <p style="margin:0 0 24px;color:#2d1a00;font-size:1.15rem;font-weight:700;">
                            📬 Has recibido una nueva solicitud de demo
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                                    <span style="color:#888;font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;display:block;margin-bottom:4px;">Nombre</span>
                                    <span style="color:#2d1a00;font-size:1rem;font-weight:600;">{{ $data['name'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                                    <span style="color:#888;font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;display:block;margin-bottom:4px;">Email</span>
                                    <a href="mailto:{{ $data['email'] }}" style="color:#F5A623;font-size:1rem;font-weight:600;text-decoration:none;">{{ $data['email'] }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                                    <span style="color:#888;font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;display:block;margin-bottom:4px;">Teléfono</span>
                                    <span style="color:#2d1a00;font-size:1rem;font-weight:600;">{{ $data['phone'] ?? 'No especificado' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                                    <span style="color:#888;font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;display:block;margin-bottom:4px;">Tipo de usuario</span>
                                    <span style="color:#2d1a00;font-size:1rem;font-weight:600;">{{ $data['user_type'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                                    <span style="color:#888;font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;display:block;margin-bottom:4px;">Expectativas de Sat2Farm</span>
                                    <span style="color:#2d1a00;font-size:1rem;font-weight:600;">{{ implode(', ', $data['sat2farm_expectations']) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                                    <span style="color:#888;font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;display:block;margin-bottom:4px;">Objetivos de mejora</span>
                                    <span style="color:#2d1a00;font-size:1rem;font-weight:600;">{{ implode(', ', $data['improvement_goals']) }}</span>
                                </td>
                            </tr>
                            @if (!empty($data['message']))
                            <tr>
                                <td style="padding:12px 0;">
                                    <span style="color:#888;font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;display:block;margin-bottom:4px;">Mensaje</span>
                                    <span style="color:#2d1a00;font-size:1rem;line-height:1.6;">{{ $data['message'] }}</span>
                                </td>
                            </tr>
                            @endif
                        </table>

                        <div style="text-align:center;margin-top:32px;">
                            <a href="mailto:{{ $data['email'] }}"
                               style="display:inline-block;background:linear-gradient(135deg,#F5A623,#d4820e);color:#fff;font-weight:700;font-size:0.95rem;padding:14px 32px;border-radius:50px;text-decoration:none;">
                                Responder a {{ $data['name'] }}
                            </a>
                        </div>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#fafafa;padding:20px 40px;text-align:center;border-top:1px solid #f0f0f0;">
                        <p style="margin:0;color:#aaa;font-size:0.8rem;">
                            Mensaje generado automáticamente desde el formulario de contacto de
                            <a href="https://moollish.com" style="color:#F5A623;text-decoration:none;">moollish.com</a>.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
