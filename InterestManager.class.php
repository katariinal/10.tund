<?php
class InterestManager{
    
        private $connection;
        private $user_id;
        
        function __construct($mysqli, $user_id){
            
            $this->connection = $mysqli;
            $this->user_id = $user_id;
            
        }
        
        function addInterest($name){
            
            //kontrollid, et sellist veel ei ole
            //kui ei ole, lisad uue 
            //objket, kus tagastame error(id, message) või success'i(mesage)
            $response = New StdClass();
        
            $stmt = $this->connection->prepare("SELECT id FROM interests WHERE name=?");
            $stmt->bind_param("s", $name);
            $stmt->bind_result($id);
            $stmt->execute();
        
        //kas saime era andmeid
            if($stmt->fetch()){
                //email on juba olemas
                $error = new StdClass();
                $error->id = 0;
                $error->message = "Huviala '".$name."' on juba olemas";
            
                $response->error = $error;
            
                //pärast return käsku, fn'i enam edasi ei vaadata
                return $response;
            }

            //siia olen jõunud, siis kui emaili ei olnud 
        
            $stmt = $this->connection->prepare("INSERT INTO interests (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            if($stmt->execute()){
                //sisestamine õnnestus
                $success = new StdClass();
                $success->message = "Huviala edukalt lisatud";
            
                $response->success = $success;
            }else{
                //ei õnnestunud
                $error = new StdClass();
                $error->id = 1;
                $error->message = "Midagi läks katki";
            
                $response->error = $error;
            }
            $stmt->close();
        
            return $response;
        
    
        }
        
        function createDropdown(){
            $html='';
            //liidan eelmisele juurde 
            $html .='<select name ="dropdown_interest">';
            
            $stmt = $this->connection->prepare("SELECT id, name FROM interests");
            $stmt->bind_result($id, $name);
            $stmt->execute();
            
            //iga rea kohta
            while($stmt->fetch()){
                $html .='<option value="'.$id.'">'.$name.'<option>';
            }
            
            $stmt->close();
            
            //$html .='<option selected>Test 2<option>';
            
            $html .='</select>';
            
            return $html;
        }
        
        function addUserInterest($interest_id){
            

            $response = New StdClass();
        
            $stmt = $this->connection->prepare("SELECT id FROM user_interests WHERE interests_id=?");
            $stmt->bind_param("s", $interests_id);
            $stmt->bind_result($id);
            $stmt->execute();
        
        
            if($stmt->fetch()){
                
                $error = new StdClass();
                $error->id = 0;
                $error->message = "Huviala '".$name."' on juba olemas";
            
                $response->error = $error;
            
                //pärast return käsku, fn'i enam edasi ei vaadata
                return $response;
            }

            //siia olen jõunud, siis kui emaili ei olnud 
        
            $stmt = $this->connection->prepare("INSERT INTO user_interests (user_id, interests_id) VALUES (?,?)");
            $stmt->bind_param("ii", $this->user_id, $interest_id);
            if($stmt->execute()){
                //sisestamine õnnestus
                $success = new StdClass();
                $success->message = "Huviala edukalt lisatud";
            
                $response->success = $success;
            }else{
                //ei õnnestunud
                $error = new StdClass();
                $error->id = 1;
                $error->message = "Midagi läks katki";
            
                $response->error = $error;
            }
            $stmt->close();
        
            return $response;
        
    
        }
}
?>