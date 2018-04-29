<!DOCTYPE html>

<html lang="fr">

	<head>
		<title> Ajout Etudiant </title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="../CSS/style_index.css">
		<script src="../JS/planning_ludus.js"></script>
	</head>

	<body>
		<?php include("nav.php"); ?>
		<h1> Creer un nouvel élève </h1>
		<!--
		CREATION D'UN FORM POUR RECUPERER DE QUOI CREER UN NOUVEL ETUDIANT
	-->
		<div>
			<form action="ajoutEtudiant.php"method="POST">
				<?php require_once("connect_planning_ludus.php");
				$sql="SELECT * from niveau";

				if(!$connexion->query($sql))//test de connexion à la BDD
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					echo "<p class=\"titreForm\"> Annee </p><SELECT name=\"annee\">";
					foreach ($connexion->query($sql) as $row)
					{
						if ($row['NOM_NIVEAU']<>"F1")
						{
							echo "<OPTION>".$row['NOM_NIVEAU']." </OPTION>";
						}
						else
						{
							echo "<OPTION SELECTED>".$row['NOM_NIVEAU']." </OPTION>";
						}
					}
					echo "</SELECT>";
				}

				$sql="SELECT distinct ID_GROUPE from ELEVE";

				if(!$connexion->query($sql))
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					echo "<p class=\"titreForm\">Groupe</p> <SELECT name=\"groupe\">";
					foreach ($connexion->query($sql) as $row)
					{
						if ($row['ID_GROUPE']<>"1")
						{
							echo "<OPTION>".$row['ID_GROUPE']." </OPTION>";
						}
						else
						{
							echo "<OPTION SELECTED>".$row['ID_GROUPE']." </OPTION>";
						}
					}
					echo "</SELECT>";
				}
				?>

				<p class="titreForm">Nom</p> <input type="text" name="nom" value="" >
				<p class="titreForm">Prenom</p> <input type="text" name="prenom" value="" >

				<input type="submit" value="OK">
			</form>
		</div>
		</br>

		<?php
		//on continue si les champs sont remplis
		if (!empty($_POST["nom"]) && !empty($_POST["prenom"]))
		{

//_____________________________________________________________________________________
//ON CHERCHE l'ID_ELEVE LE PLUS GRAND ET ON L'INCREMENTE POUR LE DONNER AU NOUVEL ELEVE
//_____________________________________________________________________________________

			$sql="SELECT COUNT(ID_ELEVE) FROM eleve;";

			$req = $connexion->query("$sql");
			$reponse = $req->fetch();
			$req->closeCursor();
			$idEleve = $reponse[0]+1;

//_____________________________________________________________________________________
//ON RECUPERE LE FORMULAIRE ET TEST SI L'ELEVE ET IDENTIQUE SUR LA BASE (MEME NOM + PRENOM)
//ON ENVOI LE RESULTAT DANS LA BASE DE DONNEE, UN NOUVEL ELEVE EST CREE
//_____________________________________________________________________________________

			$nomEleve=$_POST['nom'];
			$prenomEleve=$_POST['prenom'];
			$idGroupe=$_POST['groupe'];

			$sql = "SELECT * FROM `eleve` WHERE `NOM_ELEVE`='$nomEleve' AND `PRENOM_ELEVE`='$prenomEleve';";
			$req = $connexion->query($sql);
			$reponse = $req->fetch();
			$req->closeCursor();

//si le nom et prénom n'existe pas déjà on créé l'élève

			if($reponse[1]<>$nomEleve && $reponse[2]<>$prenomEleve)
			{
				$sql = "INSERT INTO eleve(ID_ELEVE, NOM_ELEVE, PRENOM_ELEVE, ID_GROUPE) VALUES (:idE,:nomE,:prenomE,:idG);";
				$req = $connexion->prepare($sql);
				$req->execute(array(
					'idE' => $idEleve,
					'nomE' => $nomEleve,
					'prenomE' => $prenomEleve,
					'idG' => $idGroupe,
					));

				echo '<p class=\"titreForm\">L\'étudiant à bien été ajouté.</p>';
			}
			else
			{
				echo "<p class=\"titreForm\">cet élève existe déjà, entrez-en un autre ou réorthographiez-le</p>";
			}
		}
		else
		{
			echo "<p class=\"titreForm\">veuillez remplir tout les champs ci-dessus</p>";
		}
//_____________________________________________________________________________________
		 ?>
	</body>
</html>
