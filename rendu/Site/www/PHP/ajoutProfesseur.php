<!DOCTYPE html>

<html lang="fr">

	<head>
		<title> Ajout Professeur </title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="../CSS/style_index.css"/ >
		<script src="../JS/planning_ludus.js"></script>
	</head>

	<body>

		<?php include("nav.php"); ?>
		<h1> Ajouter un professeur </h1>
		<!--
		CREATION D'UN FORM POUR RECUPERER DE QUOI CREER UN NOUVEAU PROFESSEUR
		-->
		<div>
	    <form action="ajoutProfesseur.php"method="POST">

	      <p class="titreForm">Nom professeur</p> <input type="text" name="nomP" value="" >
	      <p class="titreForm">Prenom professeur</p> <input type="text" name="prenomP" value="" >

	      <input type="submit" value="OK">
	    </form>
		</div>

    <?php
    //_____________________________________________________________________________________
    //ON RECUPERE LE FORMULAIRE ET TEST SI LE PROF ET IDENTIQUE SUR LA BASE (MEME NOM + PRENOM)
    //ON ENVOI LE RESULTAT DANS LA BASE DE DONNEE, UN NOUVEAU PROF EST CREE
    //_____________________________________________________________________________________
    require_once("connect_planning_ludus.php");

      if (!empty($_POST["nomP"]) && !empty($_POST["prenomP"]))
      {

        $nomProf=$_POST['nomP'];
        $prenomProf=$_POST['prenomP'];

        $sql="SELECT DISTINCT NOM_ENSEIGNANT FROM enseignant WHERE NOM_ENSEIGNANT='$nomProf'";

        if(!$connexion->query($sql))//test de connexion à la BDD
  			{
  				echo "Pb d'accés aux clients";
  			}
  			else
  			{

    			$req = $connexion->query($sql);
    			$reponse = $req->fetch();
    			$req->closeCursor();
//_____________________________________________________________________________________
//SI LE NOM ET LE PRENOM N'EXISTE PAS ON CREER UN NOUVEAU PROFESSEUR
//_____________________________________________________________________________________

  			  if($reponse[0]<>$nomProf)
  			  {

    				$sql = "INSERT INTO enseignant(NOM_ENSEIGNANT, PRENOM_ENSEIGNANT) VALUES (:nomP,:prenomP);";
    				$req = $connexion->prepare($sql);
    				$req->execute(array(
    					'nomP' => $nomProf,
    					'prenomP' => $prenomProf
    					));

    				echo '<p class=\"titreForm\">Le professeur à bien été ajouté.</p>';
    			}
    			else
    			{
    				echo "<p class=\"titreForm\">ce professeur existe déjà, entrez-en un autre ou réorthographiez-le</p>";
    			}
    		}
      }
  		else
  		{
  			echo "<p class=\"titreForm\">veuillez remplir tout les champs ci-dessus</p>";
  		}
      ?>
<!--
//_____________________________________________________________________________________
LECTURE DES PROFESSEURS POUR LEUR AJOUTER UNE MATIERE (OU ENLEVER)
//_____________________________________________________________________________________
-->

		  <h1> Ajouter une matière connue à un professeur </h1>
			<div>
	      <form action="ajoutProfesseur.php"method="POST">

	        <p class="titreForm"> Professeur </p><SELECT name="profP">

	        <?php

	        $sql="SELECT DISTINCT NOM_ENSEIGNANT FROM enseignant";

	        if(!$connexion->query($sql))
	  			{
	  				echo "Pb d'accés aux clients";
	  			}
	  			else
	  			{
	    			foreach ($connexion->query($sql) as $row)
	    			{
	    				echo "<OPTION>".$row['NOM_ENSEIGNANT']." </OPTION>";
	    			}

	    			echo "</SELECT>";

	        }

	        $sql="SELECT DISTINCT NOM_MATIERE from matiere";
	  			if(!$connexion->query($sql))
	  			{
	  				echo "Pb d'accés aux clients";
	  			}
	  			else
	  			{
	  				echo "<p class=\"titreForm\">matiere</p> <SELECT name=\"matiereP\">";
	  				foreach ($connexion->query($sql) as $row)
	  				{
	            echo "<OPTION>".$row['NOM_MATIERE']." </OPTION>";
	  				}
	  				echo "</SELECT>";
	  			}
	        ?>

	        <input type="submit" value="OK">

	      </form>
			</div>

      <?php

//_____________________________________________________________________________________
//TEST SI LE PROFESSEUR CONNAIT DEJA LA MATIERE SELECTIONNEE
//_____________________________________________________________________________________

      if (!empty($_POST["profP"]) && !empty($_POST["matiereP"]))
      {

        $nomProfP=$_POST['profP'];
        $matiere=$_POST['matiereP'];

        $sql="SELECT DISTINCT NOM_ENSEIGNANT FROM enseigner WHERE NOM_ENSEIGNANT='$nomProfP' AND NOM_MATIERE='$matiere'";

        if(!$connexion->query($sql))//test de connexion à la BDD
        {
          echo "Pb d'accés aux clients";
        }
        else
        {

          $req = $connexion->query($sql);
          $reponse = $req->fetch();
          $req->closeCursor();

          if($reponse[0]<>$nomProfP)
          {

            $sql = "INSERT INTO enseigner(NOM_MATIERE,NOM_ENSEIGNANT) VALUES (:matiereP,:profP);";
            $req = $connexion->prepare($sql);
            $req->execute(array(
              'matiereP' => $matiere,
              'profP' => $nomProfP
              ));

            echo '<p class=\"titreForm\">Le professeur à bien été ajouté.</p>';
          }
          else
          {
            echo "<p class=\"titreForm\">ce professeur connais déjà cette matière, entrez-en une autre</p>";
          }
        }
      }
      else
      {
        echo "<p class=\"titreForm\">veuillez faire votre choix et cliquer sur OK</p>";
      }
      ?>

	</body>
</html>
