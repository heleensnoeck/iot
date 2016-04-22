// connect to internet
// connect to server
// send buttonData to server 
// get led data from server

// server get data from esp
// set data to Json
// id senData != jsonData -> start function

//const char* ssid     = "iPhone van Martijn";  
//const char* password = "wortels18";

#include <ESP8266WiFi.h>
#include <ArduinoJson.h>


int buttonPin = D7;          // buttonPin input
int buttonPushCounter = 0;   // counter for the number of button presses
int buttonState = 0;         // current state of the button
int lastButtonState = 0;     // previous state of the button
int redPin = D6;             // get data from d6

String data;
String buttonValue;

const char* ssid     = "iPhone van Heleen";  
const char* password = "ribose7866";

const char* host     = "www.spotitshopit.com"; // Your domain  
String path          = "/iot_eindopdracht/led.json";  // path to json file 

 // set up connectie en variable
void setup() { 
  pinMode(buttonPin, INPUT); // geeft input aan programma
  pinMode(redPin, OUTPUT); // laadt gewoon iets zien
  Serial.begin(9600); 

  delay(10);
  
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);
  int wifi_ctr = 0;
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("WiFi connected");  
  Serial.println("IP address: " + WiFi.localIP());
}

// connect to host
void loop() {  
  Serial.print("connecting to ");
  Serial.println(host);
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failed");
    return; 
  }

// wat doet hij hier?
 client.print(String("GET ") + path + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" + 
               "Connection: keep-alive\r\n\r\n");             

 delay(500); // wait for server to respond

// read response 
  String section="header";
  while(client.available()){
    String line = client.readStringUntil('\r');
    // Serial.print(line);
    // we’ll parse the HTML body here
    if (section=="header") { // headers..
      Serial.print(".");
      if (line=="\n") { // skips the empty space at the beginning 
        section="json";
      }
    }
    else if (section=="json") {  // print the good stuff
      section="ignore";
      String result = line.substring(1);
      
      // Parse JSON
      int size = result.length() + 1;
      char json[size];
      result.toCharArray(json, size);
      StaticJsonBuffer<200> jsonBuffer;
      JsonObject& json_parsed = jsonBuffer.parseObject(json);
      if (!json_parsed.success())
      {
        Serial.println("parseObject() failed");
        return;
      }

      // Make the decision to turn off or on the LED
      if (strcmp(json_parsed["sitting"], "true") == 0) {
        redLed(255); // led uit
      }
      else {
          redLed(0); // red aan
      }
    }
    
  }

// POST
//// Define data
// String data;
// String button;
// button = String(digitalRead(D7));
// data = "but="+button;
  buttonState = digitalRead(buttonPin);
     if (buttonState == HIGH) {
       buttonValue = "true"; 
     } else {
       buttonValue = "false";
     }
     data = "sitting="+buttonValue;


 //check if and connect the nodeMCU to the server
 if(client.connect(host, httpPort)) {
   //make the POST headers and add the data string to it
   client.println("POST /iot_eindopdracht/index.php HTTP/1.1");
   client.println("Host: www.spotitshopit.com:80");
   client.println("Content-Type: application/x-www-form-urlencoded");
   client.println("Connection: close");
   client.print("Content-Length: ");
   client.println(data.length());
   client.println();
   client.print(data);
   client.println();
   Serial.println("Data send");

   
//  print the response to the USB
  while(client.available()){
    String line = client.readStringUntil('\r');
    Serial.print(line);
   }
 } else {
   Serial.println("Something went wrong");
 }

  Serial.print("closing connection. ");
}
 // waarneer wordt dit geactiveerd
void redLed(int red) {
   red = 255 - red;
   analogWrite(redPin, red);
}
 