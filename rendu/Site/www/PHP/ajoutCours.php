<!DOCTYPE html>

<html lang="fr">

	<head>
		<title> Ajout Cours </title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="../CSS/style_index.css"/ >
		<script src="../JS/planning_ludus.js"></script>
	</head>

	<body>
    <?php include("nav.php"); ?>

    <h1>Ajouter un cours</h1>

		<div>
			<form action="ajoutCours.php"method="POST">
				<p class="titreForm"> Choix tranche horaire </p>
				<p> 1 = 9h-11h</p>
				<p> 2 = 11h-13h</p>
				<p> 3 = 14h-16h</p>
				<p> 4 = 16h-18h</p>
				<p> 5 = 18h-20h</p>

				<SELECT name="horaire">

				<?php

				//POUR AJOUTER UN COURS,IL FAUT UN ID_TRANCHE_HORAIRE,ID_GROUPE,NOM_JOUR,NOM_MATIERE,NOM_SALLE ET TYPE
			  //IL FAUDRA TESTER CHAQUE CLEFS POUR EVITER TOUTE ERREUR (COPIER COLLER DU PREMIER REUSSI...)

	//_____________________________________________________________________________________
	//AJOUT DE LA TRANCHE HORAIRE
	//_____________________________________________________________________________________

				require_once("connect_planning_ludus.php");

				$sql="SELECT DISTINCT ID_TRANCHE_HORAIRE FROM tranche_horaire";

				if(!$connexion->query($sql))
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					foreach ($connexion->query($sql) as $row)
					{
						echo "<OPTION>".$row['ID_TRANCHE_HORAIRE']." </OPTION>";
					}
				}
				?>
				</SELECT>

				</br>
				</br>
	<!--
	//_____________________________________________________________________________________
	//AJOUT DU GROUPE/CLASSE
	//_____________________________________________________________________________________
	-->

				<p class="titreForm"> Choix groupe/classe </p>
				<p> 1-4 = F1</p>
				<p> 5_6 = F2</p>
				<p> 7-8 = F31 F32 (BACHELOR)</p>
				<p> 9-10= M1 M2 (MASTER)</p>

				<SELECT name="groupe">

				<?php

				$sql="SELECT DISTINCT ID_GROUPE FROM groupe";

				if(!$connexion->query($sql))
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					foreach ($connexion->query($sql) as $row)
					{
						echo "<OPTION>".$row['ID_GROUPE']." </OPTION>";
					}

					echo "</SELECT>";

				}
				?>

				</br>
				</br>

	<!--
	//_____________________________________________________________________________________
	//AJOUT DU JOUR
	//_____________________________________________________________________________________
	-->

				<p class="titreForm"> Choix jour </p>

				<SELECT name="jour">

				<?php

				$sql="SELECT DISTINCT NOM_JOUR FROM jour";

				if(!$connexion->query($sql))
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					foreach ($connexion->query($sql) as $row)
					{
						echo "<OPTION>".$row['NOM_JOUR']." </OPTION>";
					}

					echo "</SELECT>";

				}
				?>

				</br>
				</br>

	<!--
	//_____________________________________________________________________________________
	//AJOUT DE LA MATIERE
	//_____________________________________________________________________________________
	-->

				<p class="titreForm"> Choix matière </p>

				<SELECT name="matiere">

				<?php

				$sql="SELECT DISTINCT NOM_MATIERE FROM matiere";

				if(!$connexion->query($sql))
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					foreach ($connexion->query($sql) as $row)
					{
						echo "<OPTION>".$row['NOM_MATIERE']." </OPTION>";
					}

					echo "</SELECT>";

				}
				?>

				</br>
				</br>

	<!--
	//_____________________________________________________________________________________
	//AJOUT DE LA SALLE
	//_____________________________________________________________________________________
	-->

				<p class="titreForm"> Choix salle </p>

				<SELECT name="salle">

				<?php

				$sql="SELECT DISTINCT NOM_SALLE FROM salle";

				if(!$connexion->query($sql))
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					foreach ($connexion->query($sql) as $row)
					{
						echo "<OPTION>".$row['NOM_SALLE']." </OPTION>";
					}

					echo "</SELECT>";

				}
				?>

				</br>
				</br>

	<!--
	//_____________________________________________________________________________________
	//AJOUT DU GROUPE/CLASSE
	//_____________________________________________________________________________________
	-->

				<p class="titreForm"> Choix du type de cours </p>
				<p> CM = Cours magistrale</p>
				<p> TP = Travaux pratique</p>

				<SELECT name="type">

				<?php

				$sql="SELECT DISTINCT TYPE FROM type_matiere";

				if(!$connexion->query($sql))
				{
					echo "Pb d'accés aux clients";
				}
				else
				{
					foreach ($connexion->query($sql) as $row)
					{
						echo "<OPTION>".$row['TYPE']." </OPTION>";
					}

					echo "</SELECT>";

				}
				?>

				</br>
				</br>

				<input type="submit" value="OK">

			</form>
		</div>

		<?php

		//_____________________________________________________________________________________
		//TEST SI LE COURS N'EXISTE PAS DEJA, ON AJOUTE LE COURS
		//_____________________________________________________________________________________

		if (!empty($_POST["groupe"]))
		{

			$horaire=$_POST['horaire'];
			$groupe=$_POST['groupe'];
			$jour=$_POST['jour'];
			$matiere=$_POST['matiere'];
			$type=$_POST['type'];
			$salle=$_POST['salle'];

			$sql="SELECT ID_TRANCHE_HORAIRE FROM cours WHERE ID_TRANCHE_HORAIRE='$horaire' AND ID_GROUPE='$groupe'
						AND NOM_JOUR ='$jour' AND NOM_MATIERE='$matiere' AND NOM_SALLE='$salle' AND TYPE='$type'";

			if(!$connexion->query($sql))//test de connexion à la BDD
			{
				echo "Pb d'accés aux clients";
			}
			else
			{

				$req = $connexion->query($sql);
				$reponse = $req->fetch();
				$req->closeCursor();

				if($reponse[0]<>$horaire)
				{

					$sql = "INSERT INTO cours(ID_TRANCHE_HORAIRE,ID_GROUPE,NOM_JOUR,NOM_MATIERE,NOM_SALLE,TYPE)
									VALUES (:horaire,:groupe,:jour,:matiere,:salle,:type);";
					$req = $connexion->prepare($sql);
					$req->execute(array(
						'horaire' => $horaire,
						'groupe' => $groupe,
						'jour' => $jour,
						'matiere' => $matiere,
						'salle' => $salle,
						'type' => $type
						));

					echo '<p class=\"titreForm\">Le cours à bien été ajouté.</p>';
				}
				else
				{
					echo "<p class=\"titreForm\">ce cours existe déjà, entrez-en une autre</p>";
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
