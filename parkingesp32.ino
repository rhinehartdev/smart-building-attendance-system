#include <SPI.h>
#include <MFRC522.h>
#include <WiFi.h>
#include <HTTPClient.h>

// Define pins for RFID reader
#define RST_PIN  5   // Reset pin for MFRC522
#define SS_PIN   21  // Slave select pin for MFRC522

MFRC522 mfrc522(SS_PIN, RST_PIN);  // Create MFRC522 instance

// WiFi credentials
const char* ssid = "A-Spot";       // Replace with your WiFi SSID
const char* password = "!Abadfam4699";   // Replace with your WiFi password

// Server URL
const char* server = "http://192.168.1.2/Thesis/query_rfid.php"; // Replace with your server address

// UART communication with Mega (using Serial2)
HardwareSerial mySerial(2);  // Using UART2 for communication with Mega

// LED pin for WiFi indication
const int wifiLED = 2;

// LED function: Light up LED on pin 2 when WiFi is connected
void indicateWiFiConnected() {
  pinMode(wifiLED, OUTPUT);
  digitalWrite(wifiLED, HIGH);
  Serial.println("WiFi connected: LED on pin 2 is lit.");
}

void setup() {
  // Start UART communication with Mega
  mySerial.begin(115200);

  // Start serial communication with the computer for debugging
  Serial.begin(115200);
  while (!Serial); // Wait for Serial Monitor to open

  // Initialize WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
  
  // Call the LED function once WiFi is connected
  indicateWiFiConnected();

  // Initialize MFRC522
  SPI.begin();         // Initialize SPI bus
  mfrc522.PCD_Init();  // Initialize the RFID reader
  Serial.println("Place your RFID card near the reader");

  delay(1000);
}

void loop() {
  // Look for a new RFID tag
  if (mfrc522.PICC_IsNewCardPresent()) {
    if (mfrc522.PICC_ReadCardSerial()) {
      // Get the RFID UID as a string
      String rfid = "";
      for (byte i = 0; i < mfrc522.uid.size; i++) {
        rfid += String(mfrc522.uid.uidByte[i], HEX);
      }

      // Print RFID UID to Serial Monitor (for debugging)
      Serial.print("Scanned RFID: ");
      Serial.println(rfid);

      // Send RFID to the server and retrieve the room_loc and plate_number
      String plate_number = "";
      String room_loc = queryDatabaseForRoomLocation(rfid, plate_number);
      room_loc.trim();  // Remove any extra whitespace/newlines
      plate_number.trim();

      // Validate the room_loc and, if valid, send it to the Mega
      if (isValidRoomLocation(room_loc)) {
        mySerial.println(room_loc);  // Send exactly the room_loc (e.g., "2A")
        Serial.print("Room location sent to Mega: ");
        Serial.println(room_loc);
        Serial.print("Plate number: ");
        Serial.println(plate_number);
      } else {
        Serial.println("Invalid room location. Sending default command.");
        mySerial.println("00");
      }

      delay(2000);  // Wait before scanning the next RFID
    }
  }
}

// Function to query the database via HTTP GET request
String queryDatabaseForRoomLocation(String rfid, String &plate_number) {
  HTTPClient http;
  String room_loc = "";  // Default empty string

  // Construct the URL with the RFID
  String url = String(server) + "?rfid=" + rfid;
  http.begin(url);  // Start the HTTP request
  int httpCode = http.GET();  // Send the GET request

  if (httpCode > 0) {
    if (httpCode == HTTP_CODE_OK) {
      String response = http.getString();  // Get the response body
      int commaIndex = response.indexOf(','); // Find the comma separator
      if (commaIndex != -1) {
        room_loc = response.substring(0, commaIndex); // Extract room location
        plate_number = response.substring(commaIndex + 1); // Extract plate number
      }
    }
  } else {
    Serial.println("Error in HTTP request");
  }
  http.end();  // End the HTTP request
  return room_loc;
}

// Function to check if the room_loc is valid (only "2A", "2B", "3A", or "3B" are allowed)
bool isValidRoomLocation(String room_loc) {
  String validLocations[] = {"2A", "2B", "3A", "3B"};
  for (int i = 0; i < 4; i++) {
    if (room_loc == validLocations[i]) {
      return true;
    }
  }
  return false;
}
