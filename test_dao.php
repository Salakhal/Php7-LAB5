<?php
declare(strict_types=1);

// 1. On charge la configuration et l'autoloader
require_once __DIR__ . '/bootstrap.php';

// 2. On importe les classes (Une seule fois !)
use App\Log\Logger;
use App\Database\DBConnection;
use App\Dao\FiliereDao;
use App\Dao\EtudiantDao;
use App\Entity\Filiere;
use App\Entity\Etudiant;

// 3. Initialisation
$journal = new Logger(__DIR__ . '/logs/pdo_errors.log');
DBConnection::init($journal);

$daoFil = new FiliereDao($journal);
$daoEtu = new EtudiantDao($journal);

function afficherTest(string $etape, $resultat): void {
    $val = is_scalar($resultat) ? $resultat : json_encode($resultat, JSON_UNESCAPED_UNICODE);
    // On a remplacé \n par <br><br> pour que le navigateur saute la ligne
    echo "<strong>--> TEST [{$etape}] :</strong> {$val} <br><br>";
}

// ==== TEST FILIERE ====
$nouvelleFiliere = new Filiere(null, 'ECO', 'Economie');
$idFiliereCree = $daoFil->insert($nouvelleFiliere);
afficherTest('Création Filière (ID)', $idFiliereCree);

$filiereTrouvee = $daoFil->findById($idFiliereCree);
afficherTest('Recherche Filière', $filiereTrouvee ? $filiereTrouvee->getLibelle() : 'Introuvable');

$nouvelleFiliere->setLibelle('Economie et Gestion');
afficherTest('Mise à jour Filière', $daoFil->update($nouvelleFiliere));
afficherTest('Suppression Filière', $daoFil->delete($idFiliereCree));


// ==== TEST ETUDIANT ====
$filDeBase = $daoFil->findById(1);
if ($filDeBase === null) { 
    $filDeBase = new Filiere(null, 'INFO', 'Informatique'); 
    $daoFil->insert($filDeBase); 
}

// On génère un numéro unique pour ne plus bloquer sur les anciens tests
$cneUnique = 'CNE' . time();
$emailUnique = 'sara.' . time() . '@email.com';

$nouvelEtu = new Etudiant(null, $cneUnique, 'Benali', 'Sara', $emailUnique, (int)$filDeBase->getId());
$idEtuCree = $daoEtu->insert($nouvelEtu);
afficherTest('Création Etudiant (ID)', $idEtuCree);

$nouvelEtu->setPrenom('Sara Majdoline');
afficherTest('Mise à jour Etudiant', $daoEtu->update($nouvelEtu));
afficherTest('Suppression Etudiant', $daoEtu->delete($idEtuCree));


// ==== TEST ERREUR (Logs) ====
try {
    // On essaie d'insérer en double pour déclencher une exception (On utilise le même CNE unique généré plus haut)
    $etuErreur = new Etudiant(null, $cneUnique, 'Test', 'Test', $emailUnique, (int)$filDeBase->getId());
    $daoEtu->insert($etuErreur);
    $daoEtu->insert($etuErreur); // Doublon garanti
    afficherTest('Test Doublon Email', 'Echec : Pas d\'erreur détectée');
} catch (\PDOException $ex) {
    afficherTest('Test Doublon Email', 'Succès (Exception capturée, regarde tes logs)');
}

// ==== TEST TRANSACTION ====
$baseDeDonnees = DBConnection::get();
try {
    $baseDeDonnees->beginTransaction();
    
    // On met un code unique (ex: MED123, MED456...) pour éviter les doublons entre chaque test
    $codeUniqueFiliere = 'MED' . rand(100, 9999);
    $filiereTransac = new Filiere(null, $codeUniqueFiliere, 'Médecine');
    $daoFil->insert($filiereTransac);
    
    // On met aussi un CNE et Email uniques pour l'étudiant
    $etuTransac = new Etudiant(null, 'CNE' . rand(1000, 9999), 'Touimi', 'Omar', 'omar.med' . time() . '@email.ma', (int)$filiereTransac->getId());
    $daoEtu->insert($etuTransac);
    
    $baseDeDonnees->commit();
    afficherTest('Transaction Finale', 'Validée (COMMIT)');
} catch (\PDOException $ex) {
    if ($baseDeDonnees->inTransaction()) { 
        $baseDeDonnees->rollBack(); 
    }
    afficherTest('Transaction Finale', 'Annulée (ROLLBACK) - ' . $ex->getMessage());
}
