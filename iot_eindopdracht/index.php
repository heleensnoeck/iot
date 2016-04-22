<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $file = fopen("button.json", "w") or die("can't open file"); // pakt json file
                
        fwrite($file, '{"sitting": "false"}'); // je weet nog niet of hij zit of beweegt dus doe niks
        fclose($file);
        $counterSet = false;
            
        // init
        $data = $_POST["sitting"];
        file_put_contents("output.txt", $data . "\n"); // de button data = de post die je binnen krijgt word in de output.txt gezet
    
        if($data == "true"){ // als de server iets binnen krijgt schrijf hem dan we in readOne.
                
            if ( $counterSet == true ) {
                
                // setTimeout
                fwrite($file, '{"sitting": "true"}');
                fclose($file);    

            } else {
                
                // setTimeout
                fwrite($file, '{"sitting": "true"}');
                fclose($file);                

                file_get_contents('http://www.spotitshopit.com/iot_eindopdracht/redOne.php');

                $counterSet = true;

            }
        
        } else if ($data == "false") {
        
            // Set light to red
            fwrite($file, '{"sitting": "false"}');
            fclose($file);
            
        }
    
    }

?>

<html>
 <head>      
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <title>Get-up Stand-up</title>
  
    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Lobster|Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>    

 </head>
 <body>
   <section>     
        <?php 
            // Open the file
            $fp = @fopen('output.txt', 'r');
        
            // Add each line to an array
            if ($fp) { 
               $array = explode("\n", fread($fp, filesize('output.txt'))); // lees de array uit in de output.txt
            }
    
            foreach ($array as $value) { // voor elke item in de array the value is true r false
                if ( $value == "true" ) {
                
                    $status = "Sitting";


                } else if ( $value == "false" ) {
                    
                    $status = "Standing";                    
                    
                }        
            };

        ?>
            
            <?php  if ($status == "Sitting") { ?>

                    <main>
                      <h1>Just</h1>    
                      <h1>Take a break:</h1>
                    <div class='wrapper'>
                    <div class='time-part-wrapper'>  
                      <div class='time-part minutes tens'>
                        <div class='digit-wrapper'>
                          <span class='digit'>0</span>
                          <span class='digit'>5</span>
                          <span class='digit'>4</span>
                          <span class='digit'>3</span>
                          <span class='digit'>2</span>
                          <span class='digit'>1</span>
                          <span class='digit'>0</span>
                        </div>
                      </div>
                      
                      <div class='time-part minutes ones'>
                        <div class='digit-wrapper'>
                          <span class='digit'>0</span>
                          <span class='digit'>9</span>
                          <span class='digit'>8</span>
                          <span class='digit'>7</span>
                          <span class='digit'>6</span>
                          <span class='digit'>5</span>
                          <span class='digit'>4</span>
                          <span class='digit'>3</span>
                          <span class='digit'>2</span>
                          <span class='digit'>1</span>
                          <span class='digit'>0</span>
                        </div>
                      </div>
                     </div> <!-- close time-part-wrapper -->
                     
                    <div class='time-part-wrapper'>
                      
                      <div class='time-part seconds tens'>
                        <div class='digit-wrapper'>
                          <span class='digit'>0</span>
                          <span class='digit'>5</span>
                          <span class='digit'>4</span>
                          <span class='digit'>3</span>
                          <span class='digit'>2</span>
                          <span class='digit'>1</span>
                          <span class='digit'>0</span>
                        </div>
                      </div>
                      
                      <div class='time-part seconds ones'>
                        <div class='digit-wrapper'>
                          <span class='digit'>0</span>
                          <span class='digit'>9</span>
                          <span class='digit'>8</span>
                          <span class='digit'>7</span>
                          <span class='digit'>6</span>
                          <span class='digit'>5</span>
                          <span class='digit'>4</span>
                          <span class='digit'>3</span>
                          <span class='digit'>2</span>
                          <span class='digit'>1</span>
                          <span class='digit'>0</span>
                        </div>
                      </div>
                     </div> <!-- close time-part-wrapper -->
                  <!--     <h4>last for 1 hour</h4>
                       -->

                       </div>
                      <ul>
                        <li><input type="checkbox" id="test1" />
                          <label for="test1">lunch</label></li>
                        <li><input type="checkbox" id="test2" />
                          <label for="test2">sport</label></li>
                        <li><input type="checkbox" id="test3" />
                          <label for="test3">drink</label></li>
                      </ul>
                    </main>
            
            <?php } else  { ?>

              <main>
                <h1>Just</h1>
                <h1>Work, one hour till break</h1>
              
                
              <div class='wrapper'>
              <div class='time-part-wrapper'>  
                <div class='time-part minutes tens'>
                  <div class='digit-wrapper'>
                    <span class='digit'>0</span>
                    <span class='digit'>5</span>
                    <span class='digit'>4</span>
                    <span class='digit'>3</span>
                    <span class='digit'>2</span>
                    <span class='digit'>1</span>
                    <span class='digit'>0</span>
                  </div>
                </div>
                
                <div class='time-part minutes ones'>
                  <div class='digit-wrapper'>
                    <span class='digit'>0</span>
                    <span class='digit'>9</span>
                    <span class='digit'>8</span>
                    <span class='digit'>7</span>
                    <span class='digit'>6</span>
                    <span class='digit'>5</span>
                    <span class='digit'>4</span>
                    <span class='digit'>3</span>
                    <span class='digit'>2</span>
                    <span class='digit'>1</span>
                    <span class='digit'>0</span>
                  </div>
                </div>
               </div> <!-- close time-part-wrapper -->
               
              <div class='time-part-wrapper'>
                
                <div class='time-part seconds tens'>
                  <div class='digit-wrapper'>
                    <span class='digit'>0</span>
                    <span class='digit'>5</span>
                    <span class='digit'>4</span>
                    <span class='digit'>3</span>
                    <span class='digit'>2</span>
                    <span class='digit'>1</span>
                    <span class='digit'>0</span>
                  </div>
                </div>
                
                <div class='time-part seconds ones'>
                  <div class='digit-wrapper'>
                    <span class='digit'>0</span>
                    <span class='digit'>9</span>
                    <span class='digit'>8</span>
                    <span class='digit'>7</span>
                    <span class='digit'>6</span>
                    <span class='digit'>5</span>
                    <span class='digit'>4</span>
                    <span class='digit'>3</span>
                    <span class='digit'>2</span>
                    <span class='digit'>1</span>
                    <span class='digit'>0</span>
                  </div>
                </div>
               </div>
             </div>
            </main>

            <?php } ?>
  
 </body>
</html>