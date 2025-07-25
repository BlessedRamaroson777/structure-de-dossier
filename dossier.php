<?php

function creerStructureDepuisTexte($cheminParent, $lignes) {
    $stack = [ ['path' => $cheminParent, 'indent' => -1] ]; // Pile de dossiers ouverts

    foreach ($lignes as $ligne) {
        // Ignore lignes vides
        if (trim($ligne) === '') continue;

        // Compter les espaces au début pour trouver le niveau d'indentation
        preg_match('/^(\s*)/', $ligne, $matches);
        $indent = strlen($matches[1]);

        // Nettoyer la ligne (enlever espaces en début et fin)
        $nom = trim($ligne);

        // Trouver le dossier parent correspondant à ce niveau d'indent
        while (!empty($stack) && $stack[count($stack)-1]['indent'] >= $indent) {
            array_pop($stack);
        }

        $parent = $stack[count($stack)-1]['path'];

        // Le chemin complet de l'élément courant
        $chemin = $parent . DIRECTORY_SEPARATOR . rtrim($nom, '/');

        if (substr($nom, -1) === '/') {
            // C'est un dossier
            if (!is_dir($chemin)) {
                mkdir($chemin, 0777, true);
            }
            // Empiler ce dossier avec son niveau d'indentation
            $stack[] = ['path' => $chemin, 'indent' => $indent];
        } else {
            // C'est un fichier, on crée un fichier vide
            if (!file_exists($chemin)) {
                file_put_contents($chemin, '');
            }
        }
    }
}

// Demander le chemin du fichier txt avec la structure
echo "Chemin du fichier texte avec la structure : ";
$cheminFichier = trim(fgets(STDIN));

// Vérifier que le fichier existe
if (!file_exists($cheminFichier)) {
    echo "Erreur : fichier non trouvé.\n";
    exit(1);
}

// Demander le chemin du dossier parent où créer la structure
echo "Chemin du dossier parent où créer la structure : ";
$cheminParent = trim(fgets(STDIN));
if (!is_dir($cheminParent)) {
    echo "Erreur : dossier parent introuvable.\n";
    exit(1);
}

// Lire le fichier texte
$lignes = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Créer la structure
creerStructureDepuisTexte($cheminParent, $lignes);

echo "Structure créée avec succès.\n";

?>
