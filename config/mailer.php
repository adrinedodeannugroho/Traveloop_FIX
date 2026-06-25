<?php
// config/mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Ganti dengan load manual 3 file inti PHPMailer:
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

/**
 * Kirim email notifikasi ke admin saat ada pesan kontak masuk.
 *
 * @param array $data  Data pesan: nama, email, no_wa, topik, pesan
 * @return bool        true = berhasil, false = gagal
 */
function kirimEmailNotifikasi(array $data): bool {
    $mail = new PHPMailer(true);

    try {
        // ── Server settings ───────────────────────────────────────
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'withtraveloop@gmail.com'; 
        $mail->Password   = 'xoxx xoxd kyhq svmj';      
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // ── Pengirim & penerima ───────────────────────────────────
        $mail->setFrom('withtraveloop@gmail.com', 'Traveloop Website');
        $mail->addAddress('withtraveloop@gmail.com', 'Admin Traveloop'); 
        $mail->addReplyTo($data['email'], $data['nama']);                 

        // ── Konten email ──────────────────────────────────────────
        $mail->isHTML(true);
        $mail->Subject = '[Traveloop] Pesan Baru: ' . ($data['topik'] ?: 'Umum') . ' dari ' . $data['nama'];
        $mail->Body    = templateEmailAdmin($data);
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $mail->Body));

        $mail->send();
        return true;

    } catch (Exception $e) {
        // Catat error tapi jangan crash halaman
        error_log('[Traveloop Mailer] Gagal kirim email: ' . $mail->ErrorInfo);
        return false;
    }
}


function templateEmailAdmin(array $d): string {
    $topik   = htmlspecialchars($d['topik']  ?: '-');
    $nama    = htmlspecialchars($d['nama']);
    $email   = htmlspecialchars($d['email']);
    $no_wa   = htmlspecialchars($d['no_wa'] ?: '-');
    $pesan   = nl2br(htmlspecialchars($d['pesan']));
    $tanggal = date('d F Y, H:i') . ' WIB';

    return <<<HTML
    <!DOCTYPE html>
    <html lang="id">
    <head><meta charset="UTF-8"></head>
    <body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f6f9;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:30px 0;">
        <tr><td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">

            <!-- Header -->
            <tr>
              <td style="background:linear-gradient(135deg,#1B3A6B,#2E5FA3);padding:32px 40px;text-align:center;">
                <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:1px;">Traveloop</h1>
                <p style="margin:8px 0 0;color:rgba(255,255,255,0.8);font-size:14px;">Notifikasi Pesan Kontak Baru</p>
              </td>
            </tr>

            <!-- Alert bar -->
            <tr>
              <td style="background:#FFF3CD;padding:12px 40px;border-left:4px solid #F59E0B;">
                <p style="margin:0;color:#92400E;font-size:13px;">
                  <strong>Pesan baru masuk</strong> pada {$tanggal}
                </p>
              </td>
            </tr>

            <!-- Body -->
            <tr>
              <td style="padding:32px 40px;">
                <h2 style="margin:0 0 24px;color:#1B3A6B;font-size:18px;border-bottom:2px solid #E5E7EB;padding-bottom:12px;">
                  Detail Pengirim
                </h2>

                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #F3F4F6;width:140px;">
                      <span style="color:#6B7280;font-size:13px;">Nama</span>
                    </td>
                    <td style="padding:10px 0;border-bottom:1px solid #F3F4F6;">
                      <strong style="color:#111827;font-size:14px;">{$nama}</strong>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #F3F4F6;">
                      <span style="color:#6B7280;font-size:13px;">Email</span>
                    </td>
                    <td style="padding:10px 0;border-bottom:1px solid #F3F4F6;">
                      <a href="mailto:{$email}" style="color:#2E5FA3;text-decoration:none;font-size:14px;">{$email}</a>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #F3F4F6;">
                      <span style="color:#6B7280;font-size:13px;">WhatsApp</span>
                    </td>
                    <td style="padding:10px 0;border-bottom:1px solid #F3F4F6;">
                      <span style="color:#111827;font-size:14px;">{$no_wa}</span>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:10px 0;">
                      <span style="color:#6B7280;font-size:13px;">Topik</span>
                    </td>
                    <td style="padding:10px 0;">
                      <span style="background:#DBEAFE;color:#1E40AF;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">{$topik}</span>
                    </td>
                  </tr>
                </table>

                <!-- Pesan -->
                <h2 style="margin:28px 0 12px;color:#1B3A6B;font-size:18px;border-bottom:2px solid #E5E7EB;padding-bottom:12px;">
                  Isi Pesan
                </h2>
                <div style="background:#F9FAFB;border-left:4px solid #2E5FA3;border-radius:0 8px 8px 0;padding:16px 20px;color:#374151;font-size:14px;line-height:1.7;">
                  {$pesan}
                </div>

                <!-- Action buttons -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:28px;">
                  <tr>
                    <td align="center" style="padding:0 4px;">
                      <a href="mailto:{$email}?subject=Re: {$topik}" 
                         style="display:inline-block;background:#2E5FA3;color:#ffffff;padding:12px 24px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;">
                        Balas via Email
                      </a>
                    </td>
                    <td align="center" style="padding:0 4px;">
                      <a href="https://wa.me/62{$no_wa}" 
                         style="display:inline-block;background:#25D366;color:#ffffff;padding:12px 24px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;">
                        Hubungi via WA
                      </a>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td style="background:#F9FAFB;padding:20px 40px;text-align:center;border-top:1px solid #E5E7EB;">
                <p style="margin:0;color:#9CA3AF;font-size:12px;">
                  Email ini dikirim otomatis oleh sistem Traveloop.<br>
                  Jangan balas email ini langsung — gunakan tombol di atas.
                </p>
              </td>
            </tr>

          </table>
        </td></tr>
      </table>
    </body>
    </html>
    HTML;
}