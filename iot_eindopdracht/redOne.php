<?php            
    sleep(10);
    
    $file = fopen("led.json", "w") or die("can't open file"); // pakt json file        
    fwrite($file, '{"sitting": "true"}'); // schrijg naar led.json 
    fclose($file);
?>

