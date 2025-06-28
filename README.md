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

├── 📡 ESP32 Firmware
│ ├── ScanRFID.ino → RFID scanning and send to server
│ ├── qrattendance.ino → QR attendance via scanner
│ ├── parkingesp32.ino → Parking ESP32 HTTP communication
│ ├── unlockdoor1.ino → Control Room 1 door
│ ├── unlockdoor2.ino → Control Room 2 door
│ ├── camera_pins.h → (if using ESP32-CAM pinout)
│
├── 🌐 Frontend Files
│ ├── index.html → Landing page
│ ├── dashboard.html → Dashboard UI
│ ├── register.html → Registration form
│ ├── manage_pins.html → Manage Room 1 and 2
│ ├── styles.css, stylespin.css → Style sheets
│ ├── user_management.css → Admin panel styles
│ ├── background.png → Dashboard background
│
├── 💾 Backend Logic (PHP)
│ ├── attendance.php → Attendance dashboard
│ ├── access_logs.php → Show access logs
│ ├── save_rfid.php → Register RFID to DB
│ ├── check_rfid.php/.php2 → Validate scanned RFID
│ ├── check_user.php → User existence verification
│ ├── check_qr.php → Validate scanned QR code
│ ├── display_users.php → Show user list
│ ├── manage_users.php → Full user management
│ ├── edit_user.php → Edit user info
│ ├── delete_user.php → Delete a user
│ ├── check_pin.php → Status of Room 1/2
│ ├── update_pins.php → Set Room GPIO status
│ ├── get_pins.php → Get pin state
│ ├── query_rfid.php → RFID query helper
│ ├── submit.php → Registration handler
│ ├── scanned_rfid.php → Show scanned RFID log
│
├── 📸 QR Code
│ ├── qrcode_script.js → Generate QR from RFID in frontend
│
└── 📄 README.md → You're here!

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

