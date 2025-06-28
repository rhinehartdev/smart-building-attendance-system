#include <WiFi.h>
#include <HTTPClient.h>
#include <MFRC522.h>
#include <SPI.h>

// WiFi credentials
const char* ssid = "A-Spot";
const char* password = "!Abadfam4699";

// Server URL
const char* serverName = "http://192.168.1.2/Thesis/save_rfid.php"; // Replace with your PHP server address

// RFID
#define SS_PIN 5
#define RST_PIN 22
MFRC522 rfid(SS_PIN, RST_PIN);
MFRC522::MIFARE_Key key;

// LED Pins
#define LED_PIN_WIFI_GREEN 2
#define LED_PIN_WIFI_RED 13

// Buzzer Pin
#define BUZZER_PIN 14

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();
  Serial.println("RFID scanner initialized");

  // Initialize LED pins
  pinMode(LED_PIN_WIFI_GREEN, OUTPUT);
  pinMode(LED_PIN_WIFI_RED, OUTPUT);
  digitalWrite(LED_PIN_WIFI_GREEN, LOW);
  digitalWrite(LED_PIN_WIFI_RED, HIGH); // Initially set to not connected

  // Initialize Buzzer pin
  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

  // Connect to WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
  digitalWrite(LED_PIN_WIFI_GREEN, HIGH);
  digitalWrite(LED_PIN_WIFI_RED, LOW);
}

void loop() {
  // Check for WiFi connection
  if (WiFi.status() != WL_CONNECTED) {
    digitalWrite(LED_PIN_WIFI_GREEN, LOW);
    digitalWrite(LED_PIN_WIFI_RED, HIGH);
    delay(1000);
    return;
  } else {
    digitalWrite(LED_PIN_WIFI_GREEN, HIGH);
    digitalWrite(LED_PIN_WIFI_RED, LOW);
  }

  // Check for new RFID card
  if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
    String rfidString = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
      rfidString += String(rfid.uid.uidByte[i], HEX);
    }

    Serial.print("RFID tag detected: ");
    Serial.println(rfidString);

    // Sound the buzzer
    digitalWrite(BUZZER_PIN, HIGH);
    delay(200); // Buzz for 200 milliseconds
    digitalWrite(BUZZER_PIN, LOW);

    // Send RFID data to server
    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      String url = String(serverName) + "?rfid=" + rfidString;
      http.begin(url.c_str());
      int httpResponseCode = http.GET();

      if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println(httpResponseCode);
        Serial.println(response);
      } else {
        Serial.printf("Error code: %d\n", httpResponseCode);
      }
      http.end();
    }

    delay(1000); // Wait for a second to avoid multiple detections
  }
}
