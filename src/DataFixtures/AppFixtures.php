<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Entity\Materiel;
use App\Entity\Instruments;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            'Sonorisation' => [
                'Enceintes' => [
                    ['libelle' => 'Enceinte JBL EON615', 'reference' => 'SON-ENC-001', 'stock_total' => 6, 'stock_dispo' => 4, 'prix' => 80.00],
                    ['libelle' => 'Caisson de basse RCF SUB 8004', 'reference' => 'SON-ENC-002', 'stock_total' => 4, 'stock_dispo' => 2, 'prix' => 120.00],
                    ['libelle' => 'Enceinte de retour Yamaha SM12V', 'reference' => 'SON-ENC-003', 'stock_total' => 8, 'stock_dispo' => 8, 'prix' => 50.00],
                ],
                'Microphones' => [
                    ['libelle' => 'Micro Shure SM58',        'reference' => 'SON-MIC-001', 'stock_total' => 10, 'stock_dispo' => 7,  'prix' => 25.00],
                    ['libelle' => 'Micro Sennheiser e835',    'reference' => 'SON-MIC-002', 'stock_total' => 6,  'stock_dispo' => 6,  'prix' => 20.00],
                    ['libelle' => 'Micro HF Sennheiser EW 100', 'reference' => 'SON-MIC-003', 'stock_total' => 4, 'stock_dispo' => 0, 'prix' => 45.00],
                    ['libelle' => 'Micro Shure SM57',         'reference' => 'SON-MIC-004', 'stock_total' => 8,  'stock_dispo' => 8,  'prix' => 22.00],
                    ['libelle' => 'Micro Shure Beta 52A',     'reference' => 'SON-MIC-005', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 35.00],
                    ['libelle' => 'Micro Shure SM57 Batterie','reference' => 'SON-MIC-006', 'stock_total' => 6,  'stock_dispo' => 6,  'prix' => 22.00],
                    ['libelle' => 'Micro Shure SM81',         'reference' => 'SON-MIC-007', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 40.00],
                    ['libelle' => 'Micro Shure Beta 91A',     'reference' => 'SON-MIC-008', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 50.00],
                ],
                'Tables de mixage' => [
                    ['libelle' => 'Table Yamaha MG16XU', 'reference' => 'SON-MIX-001', 'stock_total' => 3, 'stock_dispo' => 2, 'prix' => 90.00],
                    ['libelle' => 'Table Allen & Heath ZEDi-10FX', 'reference' => 'SON-MIX-002', 'stock_total' => 2, 'stock_dispo' => 1, 'prix' => 70.00],
                ],
            ],
            'Éclairage' => [
                'Jeux de lumière' => [
                    ['libelle' => 'Lyre Beam Cameo Zenit B200', 'reference' => 'ECL-JDL-001', 'stock_total' => 8, 'stock_dispo' => 6, 'prix' => 150.00],
                    ['libelle' => 'Par LED ADJ Mega Par Profile', 'reference' => 'ECL-JDL-002', 'stock_total' => 12, 'stock_dispo' => 12, 'prix' => 30.00],
                    ['libelle' => 'Barre LED Chauvet DJ COLORband', 'reference' => 'ECL-JDL-003', 'stock_total' => 6, 'stock_dispo' => 4, 'prix' => 40.00],
                ],
                'Effets spéciaux' => [
                    ['libelle' => 'Machine à fumée Antari Z-800', 'reference' => 'ECL-EFF-001', 'stock_total' => 3, 'stock_dispo' => 3, 'prix' => 35.00],
                    ['libelle' => 'Machine à bulles ADJ Bubble Blast', 'reference' => 'ECL-EFF-002', 'stock_total' => 2, 'stock_dispo' => 1, 'prix' => 25.00],
                ],
                'Contrôle DMX' => [
                    ['libelle' => 'Console DMX Chauvet DJ Obey 40', 'reference' => 'ECL-DMX-001', 'stock_total' => 2, 'stock_dispo' => 2, 'prix' => 60.00],
                    ['libelle' => 'Interface DMX USB Enttec Open DMX', 'reference' => 'ECL-DMX-002', 'stock_total' => 3, 'stock_dispo' => 2, 'prix' => 40.00],
                ],
            ],
            'Structures' => [
                'Pieds & supports' => [
                    ['libelle' => 'Pied enceinte K&M 26785', 'reference' => 'STR-PDS-001', 'stock_total' => 10, 'stock_dispo' => 8, 'prix' => 15.00],
                    ['libelle' => 'Pied micro K&M 210/9', 'reference' => 'STR-PDS-002', 'stock_total' => 15, 'stock_dispo' => 15, 'prix' => 10.00],
                ],
                'Scènes & podiums' => [
                    ['libelle' => 'Praticable 1m x 1m (hauteur 40cm)', 'reference' => 'STR-SCN-001', 'stock_total' => 20, 'stock_dispo' => 16, 'prix' => 20.00],
                    ['libelle' => 'Praticable 2m x 1m (hauteur 40cm)', 'reference' => 'STR-SCN-002', 'stock_total' => 10, 'stock_dispo' => 10, 'prix' => 35.00],
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
                    $materiel->setPrix($m['prix']);
                    $materiel->setUrlMateriel(null);
                    $materiel->setSousCategorie($sousCategorie);
                    $manager->persist($materiel);
                }
            }
        }

        // ---- INSTRUMENTS ----
        $instrumentsData = [
            ['libelle' => 'Guitare électrique', 'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/guitare-electrique.png'],
            ['libelle' => 'Guitare basse',       'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/guitare-basse.png'],
            ['libelle' => 'Guitare acoustique',  'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Batterie',            'type' => 'instrument', 'largeur' => 200, 'hauteur' => 150, 'url' => '/images/instruments/tambour.png'],
            ['libelle' => 'Clavier / Piano',     'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/musique.png'],
            ['libelle' => 'Violon',              'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Saxophone',           'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Trompette',           'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Flûte',               'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Micro chant',         'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/micro.png'],
            ['libelle' => 'Ampli guitare',       'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/ampli-guitare.png'],
            ['libelle' => 'Ampli basse',         'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/ampli.png'],
            ['libelle' => 'Retour de scène',     'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
        ];

        foreach ($instrumentsData as $d) {
            $instrument = new Instruments();
            $instrument->setLibelle($d['libelle']);
            $instrument->setType($d['type']);
            $instrument->setUrlInstrument($d['url']);
            $manager->persist($instrument);
        }

        // ---- UTILISATEURS ----
        $admin = new User();
        $admin->setNom('Admin');
        $admin->setPrenom('Super');
        $admin->setEmail('blairet.quentin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setDateNaissance(new \DateTime('1990-01-01'));
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin1234'));
        $manager->persist($admin);

        $user = new User();
        $user->setNom('Dupont');
        $user->setPrenom('Jean');
        $user->setEmail('blairet.quentin2@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setDateNaissance(new \DateTime('1995-06-15'));
        $user->setPassword($this->hasher->hashPassword($user, 'user1234'));
        $manager->persist($user);

        $manager->flush();
    }
}
