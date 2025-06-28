function showQRCode(rfid, id) {
  QRCode.toDataURL(rfid, { type: 'image/png', errorCorrectionLevel: 'H' }, function(err, url) {
    if (err) console.error(err);
    document.getElementById('qrcode').src = url;
  });
  document.getElementById('qr-container').style.display = 'block';
  document.getElementById('qr-text').innerText = "QR Code for User ID: " + id;
}
