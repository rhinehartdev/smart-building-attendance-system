# 🏢 Smart Building IoT Attendance System

This project is a fully integrated **IoT-based smart building system** that automates attendance logging, user management, parking monitoring, and room control using **RFID**, **QR codes**, **ESP32 microcontrollers**, and a **PHP/MySQL backend**.

It supports real-time communication via HTTP, and features a web-based dashboard for monitoring and control.

---

## 🚀 Features

- ✅ Register new users via RFID tag scan
- ✅ Automatically logs **Check-In** and **Check-Out** events
- ✅ Converts RFID strings to **QR codes** for alternate user check-in
- ✅ QR codes are scanned using **MH-ET Live 1D/2D Barcode Scanner** via ESP32
- ✅ Real-time RFID and QR check-in logs sent via HTTP POST to database
- ✅ View scanned RFID and QR logs in web dashboard
- ✅ Manage user information (Edit/Delete)
- ✅ Control **Room 1** and **Room 2** remotely (via ESP32 pins)
- ✅ View and log **Smart Parking** activities using ESP32-based mechanism
- ✅ Uses MySQL for data storage and PHP for web backend

---

## 🖥️ Technologies Used

| Category        | Technology                             |
|----------------|-----------------------------------------|
| Microcontroller| ESP32 (30 pins)                         |
| RFID Scanner   | MFRC522 RFID Module                     |
| QR Scanner     | MH-ET Live 1D/2D Barcode Scanner         |
| Communication  | HTTP Protocol (ESP32 → PHP endpoint)    |
| Backend        | PHP, MySQL                              |
| Frontend       | HTML, CSS                               |
| QR Generation  | PHP QR Code Generator (e.g., `phpqrcode`)|
| Hosting        | XAMPP / Localhost for development       |

---
```
## 📂 Project Structure

📁 smart-building-attendance-system/
├── index.php → Dashboard landing/login
├── register_user.php → User registration via RFID
├── manage_users.php → View/edit/delete users
├── attendance.php → View check-in/check-out logs
├── generate_qr.php → Convert RFID to QR code
├── scan_qr_handler.php → Handle scanned QR from ESP32
├── qr_checkin_log.php → Logs for QR-based check-ins
├── scanned_rfid.php → Display latest scanned RFID
├── parking_logs.php → ESP32 parking data logs
├── room_control.php → Control Room 1 & 2 GPIO states
├── esp32_post_handler.php → HTTP POST data from ESP32
├── database.sql → MySQL DB structure
├── style.css → UI styling
└── README.md

---
```
## 🧪 Testing & Validation

- ✅ RFID tag scans sent from ESP32 verified via serial and database logs  
- ✅ Generated QR codes were scanned using MH-ET Live scanner  
- ✅ QR scans sent to ESP32, then to server and logged correctly  
- ✅ PHP pages tested manually to ensure user CRUD and log display works  
- ✅ Room control tested with physical relay/switch connected to ESP32  
- ✅ Parking mechanism tested with proximity sensor or servo logging via ESP32

---



---

## 🧑‍💻 Author

**Rhinehart Dejucos**  
📍 Quezon City, Philippines  
🔗 [GitHub Profile](https://github.com/rhinehartdev)

---

## 🛠️ Setup Instructions

1. 🔌 Flash your ESP32 boards with the appropriate `.ino` files
2. 🌐 Connect ESP32 to Wi-Fi; point HTTP POSTs to your local server
3. 💾 Import `database.sql` to your MySQL (phpMyAdmin)
4. 📂 Place all `.php` and `.css` files into your XAMPP `htdocs` folder
5. ▶️ Run XAMPP and access via `localhost/index.php`
6. 🧪 Test RFID and QR scans with ESP32 + sensors

---

## 📌 Future Improvements

- Add user roles (Admin/User)
- Integrate email notifications
- Mobile app interface (Flutter or React Native)
- Support for cloud database (Firebase or AWS)

---

