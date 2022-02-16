<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>solidao</title>

  

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

</head>



<div class="container">
    <img src="1.png" class="rounded" > <span></span><br><br>
    <h1 class="mb-5">Interface de simulation d'une facture d'électricité.</h1>
</div>


<?php
    $Tarifs = array("T1"=>0.794, "T2"=>0.883, "T3"=>0.9451, "T4"=> 1.0489, "T5"=>1.2915, "T6"=>1.4975);
    $tranch = array("tranch1"=>1, "tranch2"=>2,"tranch3"=>3,"tranch4"=>4,"tranch5"=>5,"tranch6"=>6);
    $Trn1 = 100 * $Tarifs["T1"];
    $Trn2 = 150 * $Tarifs["T2"];
    $Trn3 = 210 * $Tarifs["T3"];
    $Trn4 = 310 * $Tarifs["T4"];
    $Trn5 = 510 * $Tarifs["T5"];
    $Trn6 = 511 * $Tarifs["T6"];
    $tva = 14;
    $timbre = 0.45;
    $calibre = array( "calibre1" => 22.65, "calibre2" => 37.05, "calibre3" => 46.20);
?>


<?php 
        class Tranche{
            public $borne_minimale ;
            public $borne_maximale ;
            public $prix_unitaire ; 

            function __construct($borne_min,$borne_max,$prix) {
                $this -> borne_minimale = $borne_min;
                $this -> borne_maximale = $borne_max;
                $this -> prix_unitaire = $prix;
            }     
        }


        $tranche1 = new Tranche(0,100,$Tarifs["T1"]);
        $tranche2 = new Tranche(101,150,$Tarifs["T2"]);
        $tranche3 = new Tranche(151,210,$Tarifs["T3"]);
        $tranche4 = new Tranche(211,310,$Tarifs["T4"]);
        $tranche5 = new Tranche(311,510,$Tarifs["T5"]);
        $tranche6 = new Tranche(511,null,$Tarifs["T6"]);


        $Table = array();
        array_push($Table,$tranche1,$tranche2,$tranche3,$tranche4,$tranche5,$tranche6);
        $calibre = array( "calibre1" => 22.65, "calibre2" => 37.05, "calibre3" => 46.20);
        $max ;
        $min ;  
        $moyen; 
        $CalibreType;
        $Totale = array();
        $TotaleHT = array();
        $Total=0;
        if(isset($_POST["Submit"])){
            $max = $_POST["max"];
            $min = $_POST["min"];
            $THT=0;
            
            $CalibreType = $_POST['Calibre'];
            if (empty($max) || empty($min) || empty($CalibreType)) {
                echo "<script>alert(\"cannot be Empty\")</script>";

            } else {
                $moyen  = $max - $min;
            } 	       
            if($moyen <= 150){
                if($moyen <= $Table[0] -> borne_maximale ){
                    $Totale[0] = $moyen;
                    $TotaleHT[0] =  $moyen * $Table[0] -> prix_unitaire;
                    $Tranch = $tranch["tranch1"];
                    
                }
                elseif($moyen <= $Table[1] -> borne_maximale && $moyen >= $Table[1] -> borne_minimale){
                    $Totale[0] = 100;
                    $Totale[1] = $moyen - $Totale[0];
                    $TotaleHT[0] = $Totale[0] * $Table[0] -> prix_unitaire;
                    $TotaleHT[1] = $Totale[1] * $Table[1] -> prix_unitaire;
                    $Tranch = $tranch["tranch1"];
                    $Tranch = $tranch["tranch2"];

                     
                }
            }
            else {
                if($moyen <= $Table[2] -> borne_maximale && $moyen >= $Table[2] -> borne_minimale){
                    $Totale[2] = $moyen;
                    $TotaleHT[2] = $moyen * $Table[2] -> prix_unitaire;
                    $Tranch = $tranch["tranch3"];
                     
                }
                elseif ($moyen <= $Table[3]-> borne_maximale && $moyen >= $Table[3] -> borne_minimale) {
                    $Totale[3] = $moyen;
                    $TotaleHT[3] = $moyen * $Table[3] -> prix_unitaire;
                    $Tranch = $tranch["tranch4"];
                   
                }
                elseif ($moyen <= $Table[4] -> borne_maximale && $moyen >= $Table[4]-> borne_minimale) {
                    $Totale[4] = $moyen;
                    $TotaleHT[4] = $moyen * $Table[4] -> prix_unitaire;
                    $Tranch = $tranch["tranch5"];
                   
                }
                else{
                    $Totale[5] = $moyen;
                    $TotaleHT[5] = $moyen * $Table[5] -> prix_unitaire;
                    $Tranch = $tranch["tranch6"];
                  
                }

            }
              if($CalibreType == "min"){
                 $TypeCalibre =   $calibre["calibre1"];
              }
              elseif($CalibreType == "moyen"){
                  $TypeCalibre = $calibre["calibre2"];
              }
              elseif($CalibreType == "max"){
                  $TypeCalibre =  $calibre["calibre3"];
              }
            foreach($TotaleHT as $key => $value)
            {
              $THT += $TotaleHT[$key];
            }
            foreach($TotaleHT as $key => $value)
            {
              $Total +=($TotaleHT[$key]  * $tva /100)  ;
            } 
            $nbrTranche = 0;
            foreach($tranch as $key => $value){
              $nbrTranche += $tranch[$key];
            }
        }
        
?>


    <form  method="POST" class="mb-4" >

    <div  class="col-auto" > 
          <label for="fname" class="form-label">Nouvel index:</label>
          <input type="text"  name="max" placeholder="Max" class="form-control">

          <br>
          
          <label for="fname" class="form-label">Ancien index:</label>
          <input type="text" name="min" placeholder="Min" class="form-control">

          <br>

          <label class="form-label">Calibre:   <span class="pr-2"></span></label>
            <input type="radio" name="Calibre" value="min"  >5-10 
            <span class="pr-2"></span>
            <input type="radio" name="Calibre" value="moyen" >15-20 
            <span class="pr-2"></span>
            <input type="radio" name="Calibre" value="max" > >30
          <br>

   
          <input type="submit" value="Submit" name="Submit" class="btn btn-primary">
         </div>  
    </form>
    

 

 


  <?php 
    if(isset($_POST["Submit"])){
  ?>
  <div class="text-center">
    <div  >
      <?php 
      if(isset($_POST["Submit"])){ ?>
        <div class="size">Ancien index : <?php echo $max ?> kWh </div> 
        <div class="size">Nouvel index : <?php echo $min ?> kWh</div> 
        <div class="text-primary bg-warning">Consommation(kWh) : <?php  echo $moyen ?>kWh</div>
      <?php
            }
      ?>
    </div>
    
    <table  class="table table-hover table-responsive-xxl">

      <thead>
        <tr class="header">
          <th>-</th>
          <th>Facturé</th>
          <th>Prix unitaire</th>
          <th>Montant HT</th>
          <th>Taux TVA</th>
          <th>Montant Taxes</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td class="table-secondary">CONSOMMATION ELECTRICITE</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
      <?php 

      if(isset($_POST["Submit"])){
        foreach($Totale as $key => $value){
          
      ?>
        <tr>
            <td class="table-secondary">TRANCHE<?php echo " $Tranch "?></td>
            <td class="wiht"><?php echo $value ?></td>
            <td class="wiht"><?php echo $Table[$key]->prix_unitaire ?></td>
            <td class="wiht"><?php echo $TotaleHT[$key] ?></td>
            <td class="wiht"><?php echo $tva . "%";?></td>
            <td class="wiht"><?php echo $TotaleHT[$key] * $tva /100 ?></td>
            
        </tr>
        <?php
            }
        }
        ?>


        <?php 
          if(isset($_POST["Submit"])){
        ?>
        <tr>
          <td class="table-secondary"> REDEVANCE FIXE ELECTRICITE</td>
          <td></td>
          <td></td>
          <td class="wiht"><?php echo $TypeCalibre?></td>
          <td class="wiht"><?php echo $tva . "%";?></td>
          <td class="wiht"><?php echo $TypeCalibre * $tva /100 ?></td>
         
        </tr>
        <?php
        }
        ?>


        <tr>
          <td class="table-secondary">TAXES POUR LE COMPTE DE L’ETAT</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          
        </tr>
        <?php 
          if(isset($_POST["Submit"])){
            
        ?>
        <tr>
          <td class="table-secondary">TOTAL TVA</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="wiht"><?php echo $SOUS_Toatla = $Total + ($TypeCalibre * $tva /100)?></td>
          
        </tr>
        <?php
        }
        ?>


        <?php 
        if(isset($_POST["Submit"])){
        ?>
        <tr>
          <td class="table-secondary"> TIMBRE</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="wiht"><?php echo $timbre?></td>
         
        </tr>
        <?php
        }
        ?>


        <?php 
          if(isset($_POST["Submit"])){
        ?>
        <tr>
          <td class="table-secondary">SOUS-TOTAL</td>
          <td></td>
          <td></td>
          <td class="wiht"><?php echo $SOUS_THT = $THT+ $TypeCalibre ?></td>
          <td></td>
          <td class="wiht"><?php echo $SOUS_T = $SOUS_Toatla + $timbre?></td>
        </tr> 
        <?php
        }
        ?>


        <br><br> <?php 
          if(isset($_POST["Submit"])){
        ?>
        <tr>
          <td class="table-danger">TOTAL ÉLECTRICITÉ</td>
          <td></td>
          <td></td>
          <td></td>
          <td class="table-danger"><?php echo $SOUS_T + $SOUS_THT?></td>
          <td></td>
         
        </tr>
        
        <?php
        }
        ?>

      </tbody>
      <button onclick="window.print()" class="botton">Print this page</button><br><br>
    </table>
  
  
  </div>
  <?php
        }
        ?>
