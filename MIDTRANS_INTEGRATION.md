# Midtrans Snap Integration Setup Guide

## Overview

Integrasi Midtrans Snap Payment Gateway telah diimplementasikan pada aplikasi PineusTilu untuk memproses pembayaran pesanan (booking). Sistem ini memungkinkan pelanggan untuk melakukan pembayaran melalui berbagai metode pembayaran yang disediakan Midtrans.

## Features

- ✅ Generate Snap transaction token dari backend
- ✅ Tampilkan Snap payment modal di frontend
- ✅ Embedded payment UI (terintegrasi langsung di halaman)
- ✅ Notifikasi pembayaran real-time
- ✅ Tracking status pembayaran
- ✅ Dukungan multiple payment methods (Credit Card, Bank Transfer, E-Wallet, QRIS, dll)

## Installation & Setup

### 1. Environment Configuration

Tambahkan konfigurasi Midtrans ke file `.env`:

```env
MIDTRANS_SERVER_KEY=your-server-key-here
MIDTRANS_CLIENT_KEY=your-client-key-here
MIDTRANS_IS_PRODUCTION=false  # Set to true for production
```

**Cara mendapatkan API Keys:**
1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Pilih Sandbox mode (untuk testing)
3. Pergi ke Settings > Access Keys
4. Copy Server Key dan Client Key

### 2. Database Migration

Pastikan Payment model sudah memiliki field `snaptoken`. Field yang diperlukan:
- `booking_id` - Foreign key ke booking
- `order_id` - Order ID dari transaksi
- `transaction_id` - Transaction ID dari Midtrans
- `snaptoken` - Snap token untuk embed di frontend
- `transaction_status` - Status transaksi (pending, settlement, dll)
- `gross_amount` - Total amount pembayaran
- `payment_type` - Tipe pembayaran
- `fraud_status` - Status fraud detection
- `expired_at` - Waktu token kadaluarsa

Model Payment sudah include migration dengan field-field ini.

### 3. API Endpoints

#### Get Snap Token
```
GET /api/payment/snap-token/{bookingToken}
```

Response:
```json
{
  "success": true,
  "token": "66e4fa55-fdac-4ef9-91b5-733b97d1b862",
  "client_key": "YOUR-CLIENT-KEY",
  "snap_js_url": "https://app.sandbox.midtrans.com/snap/snap.js",
  "booking_id": 1,
  "order_id": "GLM-000001-20260518120000"
}
```

#### Check Payment Status
```
GET /api/payment/status/{bookingToken}
```

Response:
```json
{
  "success": true,
  "status": {
    "transaction_status": "settlement",
    "transaction_id": "00001234567890",
    ...
  },
  "booking_status": "berhasil",
  "payment_status": "settlement"
}
```

#### Webhook Notification
```
POST /api/payment/notification
```

Midtrans akan mengirim POST request ke endpoint ini setiap ada perubahan status transaksi.

## Frontend Integration

### 1. Payment Button
Button "Pay Now" sudah tersedia di halaman `reservasi/detail-pesanan.blade.php`:

```blade
@elseif(($status ?? 'booking') === 'pembayaran')
<button type="button" data-action="complete-payment"
    class="px-8 py-3 rounded-2xl bg-gradient-to-r from-[#017249] to-[#0b5a3e] ...">
    Pay Now
</button>
```

### 2. Snap Modal
Modal untuk Snap payment sudah diimplementasikan di template:
- Loading state saat fetch token
- Snap embedded container
- Error handling

### 3. JavaScript Integration
File `resources/js/pages/detail-pesanan.js` sudah include:
- Fetch snap token dari backend
- Load Snap JS library
- Display embedded Snap payment
- Handle payment callbacks

## Transaction Flow

### 1. User clicks "Pay Now" button
```
User di halaman detail-pesanan, status: pembayaran
↓
Klik tombol "Pay Now"
```

### 2. Modal opens and fetch token
```
Modal snapPaymentModal dibuka
↓
Frontend fetch /api/payment/snap-token/{bookingToken}
↓
Backend generate token via Midtrans API
↓
Return token ke frontend
```

### 3. Snap payment displayed
```
Snap JS library dimuat
↓
Token diembed ke snap-container
↓
User memilih metode pembayaran dan membayar
```

### 4. Payment processed
```
User selesai membayar
↓
Midtrans mengirim notification ke /api/payment/notification
↓
Backend update payment status
↓
Frontend callback trigger onPaymentSuccess
↓
Booking status berubah menjadi "berhasil"
```

## Payment Methods Supported

Midtrans Snap mendukung berbagai metode pembayaran:

- 💳 **Credit Card** - Visa, Mastercard, Amex
- 🏦 **Bank Transfer** - BCA, BRI, BNI, Mandiri, CIMB, dll
- 📱 **E-Wallet** - GoPay, OVO, Dana, LinkAja
- **QRIS** - Semua bank/e-wallet yang support QRIS
- **Cardless Credit** - Kredivo, Akulaku, Kredivo
- **Over the Counter** - Alfamart, Indomaret

## Testing in Sandbox Mode

### Test Card Numbers

| Metode | Card Number | CVV | Exp |
|--------|-----------|-----|-----|
| Visa | 4811 1111 1111 1114 | 123 | Any future month/year |
| Mastercard | 5105 1051 0510 5100 | 123 | Any future month/year |

### Test Outcomes

Gunakan jumlah tertentu untuk mendapatkan hasil yang diinginkan:
- **10000** - Approved
- **20000** - Denied
- **30000** - Challenge (3DS)

Untuk OTP/3DS, gunakan: `112233`

## Midtrans Dashboard Configuration

### 1. Set Notification URL
1. Login ke Midtrans Dashboard
2. Settings → Configuration
3. Set **Payment Notification URL** ke: `https://yourdomain.com/api/payment/notification`
4. Pilih notification format: JSON

### 2. Set Redirect URLs
1. Settings → Snap Preference
2. System Settings
3. Set redirect URLs:
   - **Finish URL**: `https://yourdomain.com/reservasi/detail-pesanan/{token}`
   - **Unfinish URL**: `https://yourdomain.com/reservasi/detail-pesanan/{token}`
   - **Error URL**: `https://yourdomain.com/reservasi/detail-pesanan/{token}`

### 3. Customize Payment Methods
1. Settings → Snap Preference
2. Payment Methods - enable/disable metode pembayaran yang diinginkan

## Error Handling

### Common Errors

**401 Unauthorized**
- Cek Server Key di config/midtrans.php
- Pastikan Server Key valid

**Invalid Amount**
- Pastikan booking punya booking_details dengan total_price > 0
- Cek calculation di MidtransService

**Token Expired**
- Token memiliki validity 15 minutes-24 hours (default)
- Implementasi refresh token jika diperlukan

## Monitoring & Debugging

### Enable Logging

Semua transaction logs tersimpan di `storage/logs/laravel.log`

### Check Payment Status

Di backend, cek status pembayaran:
```php
$payment = Payment::where('booking_id', $bookingId)->latest()->first();
$paymentStatus = $payment->transaction_status;
```

### Midtrans Dashboard

Monitor semua transaksi di https://dashboard.midtrans.com/transactions

## Security Considerations

### 1. Server Key Protection
- Server Key hanya digunakan di backend
- Tidak boleh di-expose ke frontend
- Gunakan environment variable

### 2. Notification Verification
- Middleware `VerifyMidtransNotification` memverifikasi signature
- Implementasi di production untuk enhanced security

### 3. HTTPS Required
- Snap payment harus di halaman HTTPS
- Webhook notification juga menggunakan HTTPS

## Production Migration

### Steps:

1. **Dapatkan Production Keys**
   - Login ke Midtrans
   - Request live/production activation
   - Dapatkan production Server Key & Client Key

2. **Update Environment**
   ```env
   MIDTRANS_SERVER_KEY=live_server_key_here
   MIDTRANS_CLIENT_KEY=live_client_key_here
   MIDTRANS_IS_PRODUCTION=true
   ```

3. **Test di Production Keys**
   - Gunakan test credit card
   - Verify notification URL accessible

4. **Enable HTTPS**
   - Pastikan website punya SSL certificate
   - Webhook notification hanya bisa via HTTPS

## Troubleshooting

### Token tidak bisa di-generate
- Cek MIDTRANS_SERVER_KEY di .env
- Lihat error di storage/logs/laravel.log
- Pastikan booking punya valid booking_details

### Snap tidak tampil
- Cek browser console untuk error
- Verify MIDTRANS_CLIENT_KEY
- Pastikan Snap JS library loading dengan benar

### Notification tidak diterima
- Verify Payment Notification URL di dashboard
- Cek firewall/security rules
- Test menggunakan Midtrans test button

### Payment status tidak terupdate
- Verify token match di database
- Check notification signature verification
- Lihat logs di storage/logs/laravel.log

## Additional Resources

- [Midtrans Snap Integration Guide](https://docs.midtrans.com/docs/snap-snap-integration-guide)
- [Midtrans API Documentation](https://docs.midtrans.com/reference/api-documentation)
- [Midtrans FAQ](https://docs.midtrans.com/docs/help-center)
- [Testing Payments in Sandbox](https://docs.midtrans.com/docs/testing-payment-on-sandbox)

## Support

Untuk pertanyaan atau masalah:
1. Check Midtrans docs di https://docs.midtrans.com
2. Contact Midtrans support: support@midtrans.com
3. Check application logs: `storage/logs/laravel.log`
