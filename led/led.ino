//Program khusus board esp8266 (Nodemcu)

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Arduino_JSON.h>

const char* ssid = "LIFEMEDIA LT2"; //masukkan ssid
const char* password = "lifemediajaya"; //masukkan password
const int pinBuzzer = D2;

const long interval = 10; //5000
unsigned long previousMillis = 0;

int board = 1;


void setup () {
  
  Serial.begin(115200);
  WiFi.begin(ssid, password); 

  while (WiFi.status() != WL_CONNECTED) {

    delay(1000);
    Serial.println("Connecting..");

  }

  if(WiFi.status() == WL_CONNECTED){
    Serial.println("Connected!!!");
  }
  else{
    Serial.println("Connected Failed!!!");
  }

}

void loop() { 
    HTTPClient http;
    WiFiClient client;

    String url = "http://172.16.101.178/button/esp_iot/proses.php?board=" + String(board);
    http.begin(client, url);
    int httpCode = http.GET();

    unsigned long currentMillis = millis();
  
    if(currentMillis - previousMillis >= interval) {
        if (WiFi.status() == WL_CONNECTED) {
            if (httpCode > 0) {
                String payload = http.getString();
                JSONVar myObject = JSON.parse(payload);
        
                Serial.print("JSON object = ");
                Serial.println(myObject);
            
                JSONVar keys = myObject.keys();
            
                for (int i = 0; i < keys.length(); i++) {
                    JSONVar value = myObject[keys[i]];
                    Serial.print("GPIO: ");
                    Serial.print(keys[i]);
                    Serial.print(" - SET to: ");
                    Serial.println(value);
                    
                    int pinNumber = atoi(keys[i]);
                    pinMode(pinNumber, OUTPUT);
                    
                    // Menyalakan atau mematikan LED berdasarkan nilai dari server
                    digitalWrite(pinNumber, atoi(value));
                    
                    // Jika nilai SET to: = 1, buat LED berkedip setiap 0.5 detik
                    if (atoi(value) == 1) {
                        fireTruckSiren();
                        digitalWrite(pinNumber, HIGH);
                        delay(500);
                        digitalWrite(pinNumber, LOW);
                        delay(500);
                    }
                }  
               previousMillis = currentMillis;    
            }
            http.end();
        }
    }
}

void fireTruckSiren() {
  // Pattern 1: Quick high pitch
  tone(pinBuzzer, 1500, 100);
  delay(100);

  // Pattern 2: Alternating tones
  tone(pinBuzzer, 1200, 200);
  delay(200);
  noTone(pinBuzzer);
  delay(100);
  tone(pinBuzzer, 1500, 200);
  delay(200);

  // Pattern 3: Quick high pitch
  tone(pinBuzzer, 1500, 100);
  delay(100);

  noTone(pinBuzzer);
}