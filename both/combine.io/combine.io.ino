#include <EIoTCloudRestApi.h>
#include <EIoTCloudRestApiConfig.h>
#include "ThingSpeak.h"
#include <ESP8266WiFi.h>

// WiFi settings
char ssid[] = "iPhone van Heleen";
char pass[] = "ribose7866";
int status = WL_IDLE_STATUS;
WiFiClient  client;

EIoTCloudRestApi eiotcloud;
long myChannelNumber = 106563; // Set channel number
const char * myWriteAPIKey = "JY7EPBOMYCOQSP72"; // Set api key that points to a memory adress

void setup() {
   Serial.begin(9600); 
   eiotcloud.begin();
   
   WiFi.begin(ssid, pass);  
   ThingSpeak.begin(client);
}

void loop() {
 int sound = analogRead(A0);
 int button = digitalRead(D0);

   // Set the data in the fields of thingspeak
  ThingSpeak.setField(1,sound);
  ThingSpeak.setField(2,button);

  // Write fields
  ThingSpeak.writeFields(myChannelNumber, myWriteAPIKey);   
//
// Serial.println(sound);
// Serial.println(button);
 eiotcloud.sendParameter("5703bd17c943a0661cf314a7/WUNC4ZcLDpXQzNnh", sound);
 eiotcloud.sendParameter("5703bd17c943a0661cf314a7/CdagahuWX73b1rnW", button);
}
