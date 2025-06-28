#include <WiFi.h>
#include <HTTPClient.h>
#include <MFRC522.h>
#include <SPI.h>
#include <Keypad.h>

// WiFi credentials
const char* ssid = "A-Spot";
const char* password = "!Abadfam4699";

// Server URLs
const char* serverNameRFID = "http://192.168.1.2/Thesis/check_rfid.php";
const char* serverNamePIN = "http://192.168.1.2/Thesis/check_pin.php";

// RFID Setup
#define SS_PIN 5
#define RST_PIN 22
MFRC522 rfid(SS_PIN, RST_PIN);

// LED Pins
#define LED_PIN_WIFI 2
#define LED_PIN_UNLOCKED 4

// Buzzer & Solenoid
#define BUZZER_PIN 15
#define SOLENOID_PIN 4

// Keypad Setup
const byte ROW_NUM = 4;
const byte COLUMN_NUM = 4;
char keys[ROW_NUM][COLUMN_NUM] = {
  {'1', '2', '3', 'A'},
  {'4', '5', '6', 'B'},
  {'7', '8', '9', 'C'},
  {'*', '0', '#', 'D'}
};
byte pin_rows[ROW_NUM] = {32, 33, 25, 26};
byte pin_column[COLUMN_NUM] = {27, 14, 12, 13};
Keypad keypad = Keypad(makeKeymap(keys), pin_rows, pin_column, ROW_NUM, COLUMN_NUM);

String inputPin = "";
const String masterPIN = "9999";  // Offline PIN for manual unlock

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();

  pinMode(LED_PIN_WIFI, OUTPUT);
  pinMode(LED_PIN_UNLOCKED, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(SOLENOID_PIN, OUTPUT);

  digitalWrite(LED_PIN_WIFI, LOW);
  digitalWrite(LED_PIN_UNLOCKED, LOW);
  digitalWrite(BUZZER_PIN, LOW);
  digitalWrite(SOLENOID_PIN, LOW);

  Serial.println("Initializing...");

  // Connect to WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
  digitalWrite(LED_PIN_WIFI, HIGH);

  systemReadyBuzzer();
}

void systemReadyBuzzer() {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(100);
  digitalWrite(BUZZER_PIN, LOW);
  Serial.println("System Ready!");
}

void unlockDoor() {
  Serial.println("✅ Door Unlocked!");
  digitalWrite(LED_PIN_UNLOCKED, HIGH);
  digitalWrite(SOLENOID_PIN, HIGH);
  digitalWrite(BUZZER_PIN, HIGH);
  delay(200);
  digitalWrite(BUZZER_PIN, LOW);
  delay(5000);  // Keep door unlocked for 5 seconds
  digitalWrite(SOLENOID_PIN, LOW);
  digitalWrite(LED_PIN_UNLOCKED, LOW);
  Serial.println("🔒 Door Locked Again");
}

void checkRFID() {
  if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
    String rfidString = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
      rfidString += String(rfid.uid.uidByte[i], HEX);
    }
    Serial.print("RFID tag detected: ");
    Serial.println(rfidString);

    buzzerFeedback(); // Buzzer sound on RFID detect

    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      String url = String(serverNameRFID) + "?rfid=" + rfidString + "&room_loc=" + "2B";
      http.begin(url.c_str());
      int httpResponseCode = http.GET();

      if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println(response);

        if (response.indexOf("\"access\":\"granted\"") != -1) {
          unlockDoor();
        } else {
          Serial.println("❌ RFID Not Recognized");
          for (int i = 0; i < 3; i++) {
            buzzerFeedback(); // Three quick beeps for denied access
            delay(200);
          }
        }
      }
      http.end();
    } else {
      Serial.println("⚠️ No WiFi - Cannot verify RFID");
    }
    delay(1000);
  }
}

void buzzerFeedback() {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(50); // Short beep duration
  digitalWrite(BUZZER_PIN, LOW);
  delay(50); // Short pause
}

void checkKeypad() {
  char key = keypad.getKey();
  if (key) {
    keypadFeedback(key); // Call the new feedback function

    if (key == '#') {
      Serial.print("Entered PIN: ");
      Serial.println(inputPin);

      // Offline check first
      if (inputPin == masterPIN) {
        Serial.println("✅ Master PIN correct - Unlocking manually!");
        unlockDoor();
      } else if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        String url = String(serverNamePIN) + "?room=room2&pin=" + inputPin;
        http.begin(url.c_str());
        int httpResponseCode = http.GET();

        if (httpResponseCode > 0) {
          String response = http.getString();
          Serial.println(response);

          if (response.indexOf("\"pin_valid\":true") != -1) {
            Serial.println("✅ PIN correct - Unlocking Room!");
            unlockDoor();
          } else {
            Serial.println("❌ Incorrect PIN.");
            for (int i = 0; i < 3; i++) {
              buzzerFeedback(); // Three quick beeps for incorrect PIN
              delay(200);
            }
          }
        }
        http.end();
      } else {
        Serial.println("⚠️ No WiFi - PIN verification failed.");
      }
      inputPin = "";  // Reset after checking
    } else if (key == '*') {
      inputPin = "";  // Clear input
      Serial.println("PIN input cleared.");
    } else {  
      inputPin += key;  // Append digit
      Serial.print("Key pressed: ");
      Serial.println(key); // Show pressed key in serial monitor
    }
  }
}

void keypadFeedback(char pressedKey) {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(50); // Short beep for key press
  digitalWrite(BUZZER_PIN, LOW);
}

void loop() {
  checkRFID();
  checkKeypad();
}