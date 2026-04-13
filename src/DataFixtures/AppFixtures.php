<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Entity\Materiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ---- CATÉGORIES & SOUS-CATÉGORIES ----

        $data = [
            'Sonorisation' => [
                'Enceintes' => [
                    ['libelle' => 'Enceinte JBL EON615', 'reference' => 'SON-ENC-001', 'stock_total' => 6, 'stock_dispo' => 4],
                    ['libelle' => 'Caisson de basse RCF SUB 8004', 'reference' => 'SON-ENC-002', 'stock_total' => 4, 'stock_dispo' => 2],
                    ['libelle' => 'Enceinte de retour Yamaha SM12V', 'reference' => 'SON-ENC-003', 'stock_total' => 8, 'stock_dispo' => 8],
                ],
                'Microphones' => [
                    ['libelle' => 'Micro Shure SM58', 'reference' => 'SON-MIC-001', 'stock_total' => 10, 'stock_dispo' => 7],
                    ['libelle' => 'Micro Sennheiser e835', 'reference' => 'SON-MIC-002', 'stock_total' => 6, 'stock_dispo' => 6],
                    ['libelle' => 'Micro HF Sennheiser EW 100', 'reference' => 'SON-MIC-003', 'stock_total' => 4, 'stock_dispo' => 0],
                ],
                'Tables de mixage' => [
                    ['libelle' => 'Table Yamaha MG16XU', 'reference' => 'SON-MIX-001', 'stock_total' => 3, 'stock_dispo' => 2],
                    ['libelle' => 'Table Allen & Heath ZEDi-10FX', 'reference' => 'SON-MIX-002', 'stock_total' => 2, 'stock_dispo' => 1],
                ],
            ],
            'Éclairage' => [
                'Jeux de lumière' => [
                    ['libelle' => 'Lyre Beam Cameo Zenit B200', 'reference' => 'ECL-JDL-001', 'stock_total' => 8, 'stock_dispo' => 6],
                    ['libelle' => 'Par LED ADJ Mega Par Profile', 'reference' => 'ECL-JDL-002', 'stock_total' => 12, 'stock_dispo' => 12],
                    ['libelle' => 'Barre LED Chauvet DJ COLORband', 'reference' => 'ECL-JDL-003', 'stock_total' => 6, 'stock_dispo' => 4],
                ],
                'Effets spéciaux' => [
                    ['libelle' => 'Machine à fumée Antari Z-800', 'reference' => 'ECL-EFF-001', 'stock_total' => 3, 'stock_dispo' => 3],
                    ['libelle' => 'Machine à bulles ADJ Bubble Blast', 'reference' => 'ECL-EFF-002', 'stock_total' => 2, 'stock_dispo' => 1],
                ],
                'Contrôle DMX' => [
                    ['libelle' => 'Console DMX Chauvet DJ Obey 40', 'reference' => 'ECL-DMX-001', 'stock_total' => 2, 'stock_dispo' => 2],
                    ['libelle' => 'Interface DMX USB Enttec Open DMX', 'reference' => 'ECL-DMX-002', 'stock_total' => 3, 'stock_dispo' => 2],
                ],
            ],
            'Structures' => [
                'Pieds & supports' => [
                    ['libelle' => 'Pied enceinte K&M 26785', 'reference' => 'STR-PDS-001', 'stock_total' => 10, 'stock_dispo' => 8],
                    ['libelle' => 'Pied micro K&M 210/9', 'reference' => 'STR-PDS-002', 'stock_total' => 15, 'stock_dispo' => 15],
                ],
                'Scènes & podiums' => [
                    ['libelle' => 'Praticable 1m x 1m (hauteur 40cm)', 'reference' => 'STR-SCN-001', 'stock_total' => 20, 'stock_dispo' => 16],
                    ['libelle' => 'Praticable 2m x 1m (hauteur 40cm)', 'reference' => 'STR-SCN-002', 'stock_total' => 10, 'stock_dispo' => 10],
                ],
            ],
        ];

        foreach ($data as $catLibelle => $sousCategories) {
            
            $categorie = new Categorie();
            $categorie->setLibelle($catLibelle);
            $manager->persist($categorie);

            foreach ($sousCategories as $scatLibelle => $materiels) {
                $sousCategorie = new SousCategorie();
                $sousCategorie->setLibelle($scatLibelle);
                $sousCategorie->setCategorie($categorie);
                $manager->persist($sousCategorie);

                foreach ($materiels as $m) {
                    $materiel = new Materiel();
                    $materiel->setLibelle($m['libelle']);
                    $materiel->setReference($m['reference']);
                    $materiel->setStockTotal($m['stock_total']);
                    $materiel->setStockDispo($m['stock_dispo']);
                    $materiel->setUrlMateriel(null);
                    $materiel->setSousCategorie($sousCategorie);
                    $manager->persist($materiel);
                }
            }
        }
        $manager->flush();
    }
}