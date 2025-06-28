#include <WiFi.h>
#include <HTTPClient.h>
#include <ESP32Servo.h>

// Define the UART pins for the QR scanner
#define RX_PIN 16   // Pin connected to TX of QR Scanner
#define TX_PIN 17   // Pin connected to RX of QR Scanner

// Define the Servo pins
#define SERVO1_PIN 19 // Pin connected to Servo 1
#define SERVO2_PIN 21 // Pin connected to Servo 2

// Define the IR sensor pins
#define IR_SENSOR_CHECKIN 22  // IR sensor for check-in
#define IR_SENSOR_CHECKOUT 23 // IR sensor for check-out

// Create a HardwareSerial object
HardwareSerial mySerial(1);  // Using UART1 for QR scanner communication

// WiFi credentials
const char* ssid = "A-Spot";     // Replace with your WiFi SSID
const char* password = "!Abadfam4699";    // Replace with your WiFi password

// Create Servo objects
Servo servo1;
Servo servo2;

void setup() {
  // Start the Serial Monitor for debugging
  Serial.begin(9600);

  pinMode(18, OUTPUT); // Setup pin for digital output activity
  pinMode(IR_SENSOR_CHECKIN, INPUT);  // Setup IR sensor for check-in
  pinMode(IR_SENSOR_CHECKOUT, INPUT); // Setup IR sensor for check-out

  // Initialize the hardware serial communication with the QR scanner
  mySerial.begin(9600, SERIAL_8N1, RX_PIN, TX_PIN);  // 9600 baud, 8N1 (8 data bits, no parity, 1 stop bit)

  // Attach the servos to the specified pins
  servo1.attach(SERVO1_PIN);
  servo2.attach(SERVO2_PIN);
  servo1.write(90); // Set Servo 1 to its initial position (0 degrees)
  servo2.write(90); // Set Servo 2 to its initial position (0 degrees)

  // Connect to WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");

  Serial.println("ESP32 QR Scanner UART Communication with Two Servos");
}

void loop() {
  digitalWrite(18, HIGH); // Simulate some digital output activity
  delay(10);
  digitalWrite(18, LOW);
  delay(3000);

  // Check if QR scanner has scanned data
  if (mySerial.available()) {
    String qrData = mySerial.readString();
    qrData.trim();
    qrData.toLowerCase();
    Serial.print("Scanned QR Data: ");
    Serial.println(qrData);

    // Send the QR data to the server
    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      http.begin("http://192.168.1.2/Thesis/attendance.php"); 
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      // Create the POST data
      String postData = "qrData=" + qrData;

      // Send the request
      int httpResponseCode = http.POST(postData);
      
      // Print response and control the servos
      if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println("Server Response: " + response);

        // Open the gate for check-in
        if (response.indexOf("Checked in:") != -1) { 
          Serial.println("Check-in detected! Opening entry gate...");
          servo1.write(0); // Move Servo 1 to 90 degrees
          servo2.write(180); // Move Servo 2 to 90 degrees

          // Wait until the IR sensor for check-in detects a person
          while (digitalRead(IR_SENSOR_CHECKIN) == HIGH) {
            delay(100); // Small delay to reduce CPU usage
          }

          // Close the gate after detection
          Serial.println("Person entered! Closing entry gate...");
          servo1.write(90);
          servo2.write(90);
          Serial.println("Entry gate closed.");
        }
        
        // Open the gate for check-out
        else if (response.indexOf("Checked out:") != -1) { 
          Serial.println("Check-out detected! Opening exit gate...");
          servo1.write(180); // Move Servo 1 to 90 degrees
          servo2.write(0); // Move Servo 2 to 90 degrees

          // Wait until the IR sensor for check-out detects a person
          while (digitalRead(IR_SENSOR_CHECKOUT) == HIGH) {
            delay(100); // Small delay to reduce CPU usage
          }

          // Close the gate after detection
          Serial.println("Person exited! Closing exit gate...");
          servo1.write(90);
          servo2.write(90);
          Serial.println("Exit gate closed.");
        }
        
        // If no valid check-in/check-out response, deny access
        else {
          Serial.println("QR Code is not registered.");
        }
      } else {
        Serial.print("Error on sending POST: ");
        Serial.println(httpResponseCode);
      }
      http.end();
    }
  }
}
