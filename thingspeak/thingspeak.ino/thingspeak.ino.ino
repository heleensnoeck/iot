
// Include libraries
#include "ThingSpeak.h"
#include <ESP8266WiFi.h>

// WiFi settings
char ssid[] = "iPhone van Heleen";
char pass[] = "ribose7866";
int status = WL_IDLE_STATUS;
WiFiClient  client;

long myChannelNumber = ; // Set channel number
const char * myWriteAPIKey = ""; // Set api key that points to a memory adress

void setup() {
  // Setup wifi
  WiFi.begin(ssid, pass);  
  ThingSpeak.begin(client);
}

void loop() {
  // Get volume and button data
  int volume = analogRead(A0);
  int button = digitalRead(D0);

  // Set the data in the fields of thingspeak
  ThingSpeak.setField(1,volume);
  ThingSpeak.setField(2,button);

  // Write fields
  ThingSpeak.writeFields(myChannelNumber, myWriteAPIKey);  

  delay(1000);
}
