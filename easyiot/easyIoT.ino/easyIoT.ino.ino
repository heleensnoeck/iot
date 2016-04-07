#include <EIoTCloudRestApi.h>
#include <EIoTCloudRestApiConfig.h>

EIoTCloudRestApi eiotcloud;

void setup() {
  Serial.begin(9600); 
   eiotcloud.begin();
}

void loop() {
 int sound = analogRead(A0);
 int button = digitalRead(D0);
//
// Serial.println(sound);
// Serial.println(button);
 eiotcloud.sendParameter("5703bd17c943a0661cf314a7/WUNC4ZcLDpXQzNnh", sound);
 eiotcloud.sendParameter("5703bd17c943a0661cf314a7/CdagahuWX73b1rnW", button);
}
