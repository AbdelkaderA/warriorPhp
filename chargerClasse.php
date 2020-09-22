<?php
// On enregistre notre autoload.
function chargerClasse($classname)
{
  require $classname . '.php';
}

spl_autoload_register('chargerClasse');

$db = new PDO('mysql:host=localhost;dbname=combats', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

$manager = new PersonnagesManager($db);

if (isset($_POST['creer']) && isset($_POST['nom'])) // Si on a voulu créer un personnage.
{
  $perso = new Personnage(['nom' => $_POST['nom']]); // On crée un nouveau personnage.

  if (!$perso->nomValide()) {
    $message = 'Le nom choisi est invalide.';
    unset($perso);
  } elseif ($manager->exists($perso->nom())) {
    $message = 'Le nom du personnage est déjà pris.';
    unset($perso);
  } else {
    $manager->add($perso);
  }
} elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) // Si on a voulu utiliser un personnage.
{
  if ($manager->exists($_POST['nom'])) // Si celui-ci existe.
  {
    $perso = $manager->get($_POST['nom']);
  } else {
    $message = 'Ce personnage n\'existe pas !'; // S'il n'existe pas, on affichera ce message.
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>TP : Mini jeu de combat</title>

  <meta charset="utf-8" />
</head>

<body>
  <p>Nombre de personnages créés : <?= $manager->count() ?></p>
  <?php
  if (isset($message)) // On a un message à afficher ?
    echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
  ?>
  <form action="" method="post">
    <p>
      Nom : <input type="text" name="nom" maxlength="50" />
      <input type="submit" value="Créer ce personnage" name="creer" />
      <input type="submit" value="Utiliser ce personnage" name="utiliser" />
    </p>
  </form>
</body>

</html>