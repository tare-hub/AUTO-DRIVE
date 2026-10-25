<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AutoDrive</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0b0e14; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f1f5f9; -webkit-font-smoothing: antialiased; line-height: 1.6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #0b0e14; padding: 32px 16px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #141721; border-radius: 14px; border: 1px solid #252c3c; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);">
                    
                    <!-- Header with Gold Accent -->
                    <tr>
                        <td style="padding: 32px 32px 24px 32px; background: linear-gradient(180deg, #181d2a 0%, #141721 100%); border-bottom: 1px solid #252c3c; text-align: center;">
                            <div style="display: inline-block; padding: 5px 14px; background-color: rgba(229, 169, 82, 0.12); border: 1px solid rgba(229, 169, 82, 0.35); border-radius: 9999px; margin-bottom: 16px;">
                                <span style="color: #e5a952; font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;">AutoDrive Premium Automotive</span>
                            </div>
                            <h1 style="margin: 0 0 8px 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.02em;">
                                Welcome to AutoDrive, {{ $user->name }}!
                            </h1>
                            <p style="margin: 0; color: #94a3b8; font-size: 14px;">
                                Your customer account is active and ready. Experience top-tier vehicle rentals and premium sales in one seamless platform.
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 28px 32px;">
                            <!-- Greeting -->
                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #cbd5e1;">
                                Hello <strong style="color: #ffffff;">{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 14px; color: #94a3b8; line-height: 1.6;">
                                Thank you for joining AutoDrive. Whether you need a high-performance sports car for the weekend, an executive SUV for business travel, or you are looking to purchase your dream car, we have you covered with transparent pricing, instant booking, and dedicated support.
                            </p>

                            <!-- Account Summary Box -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #1a1f2c; border: 1px solid #2a3346; border-radius: 10px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #e5a952; margin-bottom: 12px;">
                                            Registered Account Details
                                        </div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size: 13px;">
                                            <tr>
                                                <td style="padding: 4px 0; color: #94a3b8; width: 40%;">Full Name:</td>
                                                <td style="padding: 4px 0; color: #ffffff; font-weight: 600;">{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #94a3b8;">Email Address:</td>
                                                <td style="padding: 4px 0; color: #ffffff; font-weight: 600;">{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #94a3b8;">Contact Phone:</td>
                                                <td style="padding: 4px 0; color: #ffffff; font-weight: 600;">{{ $user->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #94a3b8;">Driver's License:</td>
                                                <td style="padding: 4px 0; color: #ffffff; font-weight: 600;">{{ $user->driver_license_number ?? 'Not provided yet' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #94a3b8;">Account Status:</td>
                                                <td style="padding: 4px 0;">
                                                    <span style="display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 600; color: #34d399; background-color: rgba(16, 185, 129, 0.15); border-radius: 4px;">
                                                        ● Active Customer
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Features List -->
                            <div style="margin-bottom: 28px;">
                                <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #ffffff; margin-bottom: 14px;">
                                    What You Can Do Right Now
                                </div>
                                
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 12px;">
                                    <tr>
                                        <td width="28" valign="top" style="padding-top: 2px; color: #e5a952; font-size: 16px;">✓</td>
                                        <td style="font-size: 13px; color: #cbd5e1; line-height: 1.5;">
                                            <strong style="color: #ffffff;">Browse & Reserve Rentals:</strong> Choose from luxury sedans, sports coupes, and SUVs with transparent daily rates, insurance, and pickup locations.
                                        </td>
                                    </tr>
                                </table>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 12px;">
                                    <tr>
                                        <td width="28" valign="top" style="padding-top: 2px; color: #e5a952; font-size: 16px;">✓</td>
                                        <td style="font-size: 13px; color: #cbd5e1; line-height: 1.5;">
                                            <strong style="color: #ffffff;">Explore Vehicles For Sale:</strong> Submit purchase and test drive requests on thoroughly inspected, certified vehicles.
                                        </td>
                                    </tr>
                                </table>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td width="28" valign="top" style="padding-top: 2px; color: #e5a952; font-size: 16px;">✓</td>
                                        <td style="font-size: 13px; color: #cbd5e1; line-height: 1.5;">
                                            <strong style="color: #ffffff;">Self-Service Dashboard:</strong> Access your digital receipts, manage upcoming trips, view vehicle inspection reports, and submit support tickets anytime.
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 32px 0 24px 0; text-align: center;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('customer.dashboard') }}" target="_blank" style="display: inline-block; background-color: #e5a952; color: #0a0b0d; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 8px; letter-spacing: 0.02em; box-shadow: 0 4px 12px rgba(229, 169, 82, 0.35);">
                                            Go to Customer Dashboard →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Secondary Links -->
                            <div style="text-align: center; font-size: 13px; color: #94a3b8; padding-top: 8px;">
                                Quick links:
                                <a href="{{ route('vehicles.rent') }}" style="color: #e5a952; text-decoration: none; margin: 0 8px;">Rent Vehicles</a> •
                                <a href="{{ route('vehicles.buy') }}" style="color: #e5a952; text-decoration: none; margin: 0 8px;">Buy Vehicles</a> •
                                <a href="{{ route('customer.tickets.index') }}" style="color: #e5a952; text-decoration: none; margin: 0 8px;">Support Tickets</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; background-color: #0e1118; border-top: 1px solid #252c3c; text-align: center; font-size: 12px; color: #64748b;">
                            <p style="margin: 0 0 8px 0;">
                                You are receiving this automated email because a customer account was created on AutoDrive with the address <strong style="color: #94a3b8;">{{ $user->email }}</strong>.
                            </p>
                            <p style="margin: 0 0 12px 0;">
                                If you did not sign up for this account, please disregard this email or <a href="{{ route('contact') }}" style="color: #94a3b8; text-decoration: underline;">contact our support team</a>.
                            </p>
                            <p style="margin: 0; color: #475569;">
                                &copy; {{ date('Y') }} AutoDrive Inc. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
