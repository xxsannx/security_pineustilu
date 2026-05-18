# 🚀 Midtrans Snap Payment Integration - Quick Start Guide

## Checklist Implementasi

- [x] Membuat `MidtransService` untuk generate Snap token
- [x] Membuat `PaymentController` dengan API endpoints
- [x] Membuat `PaymentModel` migration dengan snaptoken field
- [x] Menambahkan routes untuk payment endpoints
- [x] Membuat Snap payment modal UI
- [x] Update JavaScript untuk handle payment
- [x] Membuat webhook handler untuk notification
- [x] Middleware untuk verify Midtrans signature
- [x] Membuat test command

## Setup Steps

### 1️⃣ Environment Configuration

Buka `.env` dan tambahkan:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR-SANDBOX-KEY
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR-SANDBOX-KEY
MIDTRANS_IS_PRODUCTION=false
```

**Cara mendapatkan Sandbox Keys:**
1. Daftar di https://midtrans.com/
2. Login ke https://dashboard.midtrans.com
3. Go to Settings → Access Keys → Copy Sandbox keys

### 2️⃣ Verify Database

Pastikan `payments` table punya field `snaptoken`:

```bash
php artisan migrate
```

Jika belum ada Payment model, sudah include di codebase. Check di `app/Models/Payment.php`

### 3️⃣ Test Integration

Jalankan command untuk test:

```bash
php artisan midtrans:test
```

atau dengan specific booking:

```bash
php artisan midtrans:test 1
```

### 4️⃣ Manual Testing

1. Buka halaman booking: `http://localhost:8000/reservasi/detail-pesanan/{booking-code}`
2. Pastikan status booking adalah "booking"
3. Klik "Continue Order" untuk ubah ke "pembayaran"
4. Klik "Pay Now" untuk buka Snap payment
5. Gunakan test credit card: `4811 1111 1111 1114`
6. CVV: `123`, Exp: any future month/year
7. OTP (jika diminta): `112233`

## File-File Yang Ditambahkan/Dimodifikasi

### New Files:
- ✅ `app/Services/MidtransService.php` - Main payment service
- ✅ `app/Http/Controllers/PaymentController.php` - API endpoints
- ✅ `app/Http/Middleware/VerifyMidtransNotification.php` - Notification verification
- ✅ `app/Console/Commands/TestMidtransIntegration.php` - Test command
- ✅ `.env.midtrans.example` - Example configuration

### Modified Files:
- ✅ `routes/web.php` - Added payment routes
- ✅ `resources/views/reservasi/detail-pesanan.blade.php` - Added Snap modal
- ✅ `resources/js/pages/detail-pesanan.js` - Added Snap integration logic

### Documentation:
- ✅ `MIDTRANS_INTEGRATION.md` - Comprehensive guide

## API Endpoints

### Get Snap Token
```
GET /api/payment/snap-token/{bookingToken}
Authorization: Not required (booking token is identifier)
```

### Check Payment Status
```
GET /api/payment/status/{bookingToken}
```

### Receive Webhook
```
POST /api/payment/notification
Body: Midtrans notification JSON
```

## Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     User Flow                               │
└─────────────────────────────────────────────────────────────┘

    1. User views order
         ↓
    2. Click "Continue Order"
         ↓
    3. Status changes to "pembayaran"
         ↓
    4. Click "Pay Now"
         ↓
    5. Snap modal opens
         ↓
    6. Frontend fetches /api/payment/snap-token/{token}
         ↓
    7. Backend generates Snap token via Midtrans API
         ↓
    8. Frontend displays Snap payment UI
         ↓
    9. User completes payment
         ↓
   10. Midtrans sends notification to /api/payment/notification
         ↓
   11. Backend updates booking & payment status
         ↓
   12. Success: status changes to "berhasil"
```

## Payment Methods Available

| Category | Methods |
|----------|---------|
| **Debit/Credit Card** | Visa, Mastercard, Amex (3DS support) |
| **Bank Transfer** | BCA, BRI, BNI, Mandiri, CIMB, Permata |
| **E-Wallet** | GoPay, OVO, Dana, LinkAja |
| **QRIS** | All QRIS-enabled banks/e-wallets |
| **Cardless Credit** | Kredivo, Akulaku, Kredivo |
| **OTC** | Alfamart, Indomaret |

## Test Payment Scenarios

### Successful Payment (Amount: 10000)
- Card: `4811 1111 1111 1114`
- CVV: `123`
- Exp: any future date
- OTP: `112233`
- Result: Success ✓

### Denied Payment (Amount: 20000)
- Use same card
- Result: Denied ✗

### Challenge/3DS (Amount: 30000)
- Will prompt for OTP
- Use: `112233`
- Result: Success ✓

## Debugging

### Check logs
```bash
tail -f storage/logs/laravel.log
```

### Test token generation manually
```bash
php artisan tinker
>>> $booking = App\Models\Booking::latest()->first()
>>> app(App\Services\MidtransService::class)->generateSnapToken($booking)
```

### Check payment record
```bash
php artisan tinker
>>> App\Models\Payment::latest()->first()
```

### Enable HTTP debugging
Add to `.env`:
```env
APP_DEBUG=true
```

## Common Issues & Solutions

### "Snap token generation failed"
- ✓ Check `MIDTRANS_SERVER_KEY` in .env
- ✓ Verify booking has `bookingDetails`
- ✓ Ensure `total_price > 0`

### "Snap modal doesn't appear"
- ✓ Check browser console for JavaScript errors
- ✓ Verify `MIDTRANS_CLIENT_KEY` is correct
- ✓ Check that booking token is valid

### "Notification not received"
- ✓ Set Notification URL in Midtrans Dashboard
- ✓ Test using Midtrans "Send Test Notification"
- ✓ Ensure URL is accessible from internet (HTTPS)

### "Payment status not updating"
- ✓ Check `storage/logs/laravel.log` for errors
- ✓ Verify notification signature verification
- ✓ Test with `php artisan tinker` manual update

## Production Setup

When ready for production:

1. **Get Production Keys**
   - Contact Midtrans support
   - Request production activation
   - Get production Server & Client keys

2. **Update .env**
   ```env
   MIDTRANS_SERVER_KEY=your-production-server-key
   MIDTRANS_CLIENT_KEY=your-production-client-key
   MIDTRANS_IS_PRODUCTION=true
   ```

3. **Update Notification URL**
   - Go to Midtrans Dashboard
   - Settings → Configuration
   - Set to: `https://yourdomain.com/api/payment/notification`

4. **Enable HTTPS**
   - Ensure SSL certificate installed
   - Test notification delivery

5. **Test with Real Card**
   - Use real test card from your bank
   - Verify all payment methods work
   - Monitor first few transactions

## Support & Resources

- **Midtrans Documentation**: https://docs.midtrans.com
- **Midtrans Support**: support@midtrans.com
- **Local Support**: Check application logs at `storage/logs/laravel.log`

## Next Steps

1. ✅ Setup environment variables
2. ✅ Run migration
3. ✅ Test with command: `php artisan midtrans:test`
4. ✅ Test payment via web interface
5. ✅ Set Notification URL in Midtrans Dashboard
6. ✅ Test webhook notification
7. ✅ Deploy to production when ready

---

**Questions?** Check `MIDTRANS_INTEGRATION.md` for detailed documentation.
