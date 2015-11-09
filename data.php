<?php
    // kõik mis seotud andmetabeliga, lisamine ja tabeli kujul esitamine
    require_once("functions.php");
    require_once("InterestManager.class.php");
    
    //kui kasutaja ei ole sisse logitud, suuna teisele lehele
    //kontrollin kas sessiooni muutuja olemas
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        //ära enne suunamist midagi tee 
        exit();
    }
    
    // aadressireale tekkis ?logout=1
    if(isset($_GET["logout"])){
        //kustutame sessiooni muutujad
        session_destroy();
        header("Location: login.php");
    }
 
    //****************
    //****HALDUS******
    //****************
    
    $InterestManager = new InterestManager($mysqli, $_SESSION['user_id']);
    
    if(isset($_GET["new_interest"])){
        $add_interest_response = $InterestManager->addInterest($_GET["new_interest"]);
    }
    ?>
    
    

<p>
Tere, <?=$_SESSION['user_email'];?> <a href="?logout=1">Logi välja</a>
</p>
<br>
<h2>Lisa huviala</h2>
<?php if(isset($add_interest_response->error)): ?>
  
  <p style="color:red"><?=$add_interest_response->error->message;?></p>
<?php elseif(isset($add_interest_response->success)): ?>

<p style="color:green;">
    <?=$add_interest_response->success->message;?>
</p>
  <?php endif; ?>
<form>
    <input name="new_interest"> <br><br>
  	<input type="submit" value="Lisa">
</form>

<h2>Minu huvialad</h2>
<?=$InterestManager->createDropdown();?>