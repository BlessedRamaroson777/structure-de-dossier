<?php

function afficherStructureDossier($chemin, $fichierSortie, $indentation = 0) {
    if (is_dir($chemin)) {
        $dossier = opendir($chemin);

        while (($fichier = readdir($dossier)) !== false) {
            if ($fichier != "." && $fichier != "..") {
                fwrite($fichierSortie, str_repeat("    ", $indentation) . $fichier . "\n");

                $nouveauChemin = $chemin . DIRECTORY_SEPARATOR . $fichier;
                if (is_dir($nouveauChemin)) {
                    afficherStructureDossier($nouveauChemin, $fichierSortie, $indentation + 1);
                }
            }
        }

        closedir($dossier);
    } else {
        fwrite($fichierSortie, "Le chemin spécifié n'est pas un dossier.\n");
    }
}

echo "Veuillez entrer le chemin du dossier : ";
$chemin = rtrim(fgets(STDIN));

// Générer un nom unique de fichier (structure.txt, structure(1).txt, structure(2).txt...)
$baseName = "structure";
$extension = ".txt";
$index = 0;

do {
    $suffix = $index === 0 ? "" : "($index)";
    $fichierSortiePath = __DIR__ . DIRECTORY_SEPARATOR . $baseName . $suffix . $extension;
    $index++;
} while (file_exists($fichierSortiePath));

// Ouvrir le fichier en écriture
$fichierSortie = fopen($fichierSortiePath, 'w');

if (is_dir($chemin)) {
    fwrite($fichierSortie, "Structure du dossier :\n");
    afficherStructureDossier($chemin, $fichierSortie);
    echo "Structure enregistrée dans " . basename($fichierSortiePath) . "\n";
} else {
    fwrite($fichierSortie, "Le dossier spécifié n'existe pas.\n");
    echo "Le dossier spécifié n'existe pas.\n";
}

fclose($fichierSortie);
?>
