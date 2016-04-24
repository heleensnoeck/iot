#Timer with esp8226
Build a timer that tells you when you need to get away from the computer and start doing something. 

##Hardware Configuration
We are now going to build the project you will need:
- one red led
- esp8226
- button
- breadboard
- jumper wires
- esp kabel (from esp to computer) 
- ohm resistance

##Part 1: Hardware: wire everything up


##Part 2: Arduino Code
I used the ArduinoIDE software to connect with the ESP8266 module. If you install ArduinoIDE the development process will be a lot easyer. 

! this does not mean you need a Arduino it just makes the process mutch easer.

Download ArduinoIDE: https://www.arduino.cc/en/Main/Software

###1. install the ESP8266 packages in ArduinoIDE
For this you need Arduino 1.6.4 or higher. Open the Arduino software and go to preferences (in the toolbar) and then go to "additional Board Manager URL". Then enter the following line: 

http://arduino.esp8266.com/package_esp8266com_index.json

when the libarary find it click on install. 
Restart the Arduino program
Now you can work with json files.

You also need wifi to connect to a server
The ESP8266 is a wifimodule. 

###Install wifi lib
- Go to https://github.com/ekstrand/ESP8266wifi
Or just follow allong here

- Download the library as a zip from https://github.com/ekstrand/ESP8266wifi/archive/master.zip
- Unzip and place in ARDUINO_HOME/libraries/ directory as ESP8266wifi
- Restart the arduino IDE
- In your sketch do a ```#include <ESP8266WiFi.h>```


###2. The arduino code
Ok, now the libraries are installled, we can start doing some stuff. Open a new file.

#### including the libs
```
# include <ESP8266WiFi.h>
# Include <ArduinoJSon.h>
```
#### Setting up some variable
As you remember from the hardware setup. You where putting your wires in some outputs en inputs. We have to set up these things so are code can read them out. 

```
int buttonPin = D7;          // buttonPin input
int buttonPushCounter = 0;   // counter for the number of button presses
int buttonState = 0;         // current state of the button
int lastButtonState = 0;     // previous state of the button
int redPin = D6;             // get data from d6

String data;					  // this is a function
String buttonValue; 		  // this is a function
```

#### Setting some basic configiration
```
const char* ssid     = "iPhone van name"; // your wifi network   
const char* password = "password"; // your wifi password

const char* host     = "www.domain.com"; // Your domain  
String path          = "/iot_eindopdracht/led.json";  // path to json file  
```
#### Void setup()
In ```void setup() ```, you declare the ```pinMode``` there you tell if the pin is a input or a output. Een input is something that gives back a value to the computer (server in this case) like true or false (0,1 or a number). The light in is a output because it gives processed information back to the user. 

```Serial.begin``` shows what happens in the serial port this you cann see when you click on the magnifying glass in the top left corner of the Arduino code file. 

``Serial.print``` prints stuff to the serial port window. 


```
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
```

#### Void loop()
Next we start with the ```void loop()``` function. This function will Arduino run and rerun. Here we will connect to the led.json, and print the HTTP respons (the info you get from the url)

```client.print(...)``` is called an HTTP Request. This request will look at the path (url) and the host and sends the url to the server.

```
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

client.print(String("GET ") + path + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" + 
               "Connection: keep-alive\r\n\r\n");             

 delay(500); // wait for server to respond

```
#### Respons

I start with a varibale name ``` section ```. Here i keep track of what section of the HTTP respons is reading.

The program then reads the http respons line by ```line``` in the while loop. When it reach a emty line ```if (line=="\n")``` the program sets section="json".

Now we parse the JSON line and store the json into a ```json_parsed ``` variable.

Depending on the result we turn the led on or off. This you do with ``` strcmp(json_parsed["sitting"], "true") == 0)``` this means if the key ```sitting``` is set to true then turn on the led. 
We do this by ```redLed(0);```.

-> Reads the respons from the esp (Denk ik)
en pushed het dan naar de led.json. 

*En zodra deze wijzigd past de esp led zich ook aan in rood 

```
// read response 
  String section="header";
  while(client.available()){
    String line = client.readStringUntil('\r');
    //Serial.print(line);
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
        redLed(0); // red aan
      }
      else {
          redLed(255); // led uit
      }
    }
    
  }
```

#### Post
Post data on the server. First i start by reading out the ```buttonState```. Then i save the value of the button by the line ``` data = "sitting="+buttonValue; ```  

The we connect the esp to the server.

and after that we deside if the led has to be red or off. 


-> Post dat mocht de led aan staan ga dan uit mocht de button state uit zijn ga dan uit.

```
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

 } else {
   Serial.println("Something went wrong");
 }

  Serial.print("closing connection. ");
}
 // waarneer wordt dit geactiveerd dit is een functie
  void redLed(int red) {
   red = 255 - red;
   analogWrite(redPin, red);
}
```     

#### Final Arduino code
```
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>

int buttonPin = D7;          // buttonPin input
int buttonPushCounter = 0;   // counter for the number of button presses
int buttonState = 0;         // current state of the button
int lastButtonState = 0;     // previous state of the button
int redPin = D6;             // get data from d6

String data;
String buttonValue;

const char* ssid     = "iPhone van name"; // your wifi network   
const char* password = "password"; // your wifi password

const char* host     = "www.domain.com"; // Your domain  
String path          = "/iot_eindopdracht/led.json";  // path 

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

      // Make the decision to turn off or on the LED Redled functie staat hier beneden
      if (strcmp(json_parsed["sitting"], "true") == 0) {
          redLed(0); // red aan
      }
      else {
          redLed(255); // led uit
      }
    }
    
  }


// POST button value to the server
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

 } else {
   Serial.println("Something went wrong");
 }

  Serial.print("closing connection. ");
}
  void redLed(int red) {
   red = 255 - red;
   analogWrite(redPin, red);
}
 
```
# Part 3: JSON & PHP
for
