<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Entity\Materiel;
use App\Entity\Instruments;
use App\Entity\MaterielSuggere;
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
                    ['libelle' => 'Enceinte JBL EON615',             'reference' => 'SON-ENC-001', 'stock_total' => 6,  'stock_dispo' => 4, 'prix' => 80.00,  'url' => null],
                    ['libelle' => 'Caisson de basse RCF SUB 8004',   'reference' => 'SON-ENC-002', 'stock_total' => 4,  'stock_dispo' => 2, 'prix' => 120.00, 'url' => null],
                    ['libelle' => 'Enceinte de retour Yamaha SM12V',  'reference' => 'SON-ENC-003', 'stock_total' => 8,  'stock_dispo' => 8, 'prix' => 50.00,  'url' => null],
                    ['libelle' => 'Enceinte QSC K12.2',              'reference' => 'SON-ENC-004', 'stock_total' => 4,  'stock_dispo' => 3, 'prix' => 95.00,  'url' => null],
                    ['libelle' => 'Caisson de basse EV ELX200-18SP', 'reference' => 'SON-ENC-005', 'stock_total' => 3,  'stock_dispo' => 2, 'prix' => 110.00, 'url' => null],
                    ['libelle' => 'Enceinte Electro-Voice ZLX-12P',  'reference' => 'SON-ENC-006', 'stock_total' => 6,  'stock_dispo' => 5, 'prix' => 75.00,  'url' => null],
                    ['libelle' => 'Enceinte RCF ART 712-A MK4',      'reference' => 'SON-ENC-007', 'stock_total' => 4,  'stock_dispo' => 4, 'prix' => 100.00, 'url' => null],
                    ['libelle' => 'Caisson de basse Yamaha DXS15',   'reference' => 'SON-ENC-008', 'stock_total' => 2,  'stock_dispo' => 1, 'prix' => 130.00, 'url' => null],
                    ['libelle' => "Enceinte de retour d'Adamson S10",'reference' => 'SON-ENC-009', 'stock_total' => 5,  'stock_dispo' => 5, 'prix' => 60.00,  'url' => null],
                ],
                'Microphones' => [
                    ['libelle' => 'Micro Shure SM58',              'reference' => 'SON-MIC-001', 'stock_total' => 10, 'stock_dispo' => 7,  'prix' => 25.00, 'url' => null],
                    ['libelle' => 'Micro Sennheiser e835',         'reference' => 'SON-MIC-002', 'stock_total' => 6,  'stock_dispo' => 6,  'prix' => 20.00, 'url' => null],
                    ['libelle' => 'Micro HF Sennheiser EW 100',    'reference' => 'SON-MIC-003', 'stock_total' => 4,  'stock_dispo' => 0,  'prix' => 45.00, 'url' => null],
                    ['libelle' => 'Micro Shure SM57',              'reference' => 'SON-MIC-004', 'stock_total' => 8,  'stock_dispo' => 8,  'prix' => 22.00, 'url' => null],
                    ['libelle' => 'Micro Shure Beta 52A',          'reference' => 'SON-MIC-005', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 35.00, 'url' => null],
                    ['libelle' => 'Micro Shure SM57 Batterie',     'reference' => 'SON-MIC-006', 'stock_total' => 6,  'stock_dispo' => 6,  'prix' => 22.00, 'url' => null],
                    ['libelle' => 'Micro Shure SM81',              'reference' => 'SON-MIC-007', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 40.00, 'url' => null],
                    ['libelle' => 'Micro Shure Beta 91A',          'reference' => 'SON-MIC-008', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 50.00, 'url' => null],
                    ['libelle' => 'Micro AKG C414',                'reference' => 'SON-MIC-009', 'stock_total' => 3,  'stock_dispo' => 3,  'prix' => 60.00, 'url' => null],
                    ['libelle' => 'Micro Rode NT1-A',              'reference' => 'SON-MIC-010', 'stock_total' => 4,  'stock_dispo' => 2,  'prix' => 55.00, 'url' => null],
                    ['libelle' => 'Micro HF Shure BLX24/SM58',     'reference' => 'SON-MIC-011', 'stock_total' => 3,  'stock_dispo' => 1,  'prix' => 50.00, 'url' => null],
                    ['libelle' => 'Micro Sennheiser MD421',        'reference' => 'SON-MIC-012', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 45.00, 'url' => null],
                    ['libelle' => 'Micro Audix D6',                'reference' => 'SON-MIC-013', 'stock_total' => 3,  'stock_dispo' => 3,  'prix' => 38.00, 'url' => null],
                    ['libelle' => 'Micro Neumann KM184',           'reference' => 'SON-MIC-014', 'stock_total' => 2,  'stock_dispo' => 2,  'prix' => 70.00, 'url' => null],
                    ['libelle' => 'Micro Beyerdynamic TG V70d',    'reference' => 'SON-MIC-015', 'stock_total' => 5,  'stock_dispo' => 5,  'prix' => 30.00, 'url' => null],
                    ['libelle' => 'Micro HF AKG WMS45',            'reference' => 'SON-MIC-016', 'stock_total' => 3,  'stock_dispo' => 0,  'prix' => 40.00, 'url' => null],
                ],
                'Tables de mixage' => [
                    ['libelle' => 'Table Yamaha MG16XU',           'reference' => 'SON-MIX-001', 'stock_total' => 3, 'stock_dispo' => 2, 'prix' => 90.00,  'url' => null],
                    ['libelle' => 'Table Allen & Heath ZEDi-10FX', 'reference' => 'SON-MIX-002', 'stock_total' => 2, 'stock_dispo' => 1, 'prix' => 70.00,  'url' => null],
                    ['libelle' => 'Table Behringer X32',           'reference' => 'SON-MIX-003', 'stock_total' => 2, 'stock_dispo' => 2, 'prix' => 150.00, 'url' => null],
                    ['libelle' => 'Table Soundcraft Signature 12', 'reference' => 'SON-MIX-004', 'stock_total' => 2, 'stock_dispo' => 1, 'prix' => 80.00,  'url' => null],
                    ['libelle' => 'Table Mackie ProFX16v3',        'reference' => 'SON-MIX-005', 'stock_total' => 2, 'stock_dispo' => 2, 'prix' => 95.00,  'url' => null],
                    ['libelle' => 'Table Allen & Heath SQ-5',      'reference' => 'SON-MIX-006', 'stock_total' => 1, 'stock_dispo' => 1, 'prix' => 200.00, 'url' => null],
                ],
                'Câbles & connectique' => [
                    ['libelle' => 'Câble XLR 5m',           'reference' => 'SON-CAB-001', 'stock_total' => 30, 'stock_dispo' => 25, 'prix' => 5.00,  'url' => null],
                    ['libelle' => 'Câble XLR 10m',          'reference' => 'SON-CAB-002', 'stock_total' => 20, 'stock_dispo' => 18, 'prix' => 8.00,  'url' => null],
                    ['libelle' => 'Câble Jack 6.35mm 5m',   'reference' => 'SON-CAB-003', 'stock_total' => 20, 'stock_dispo' => 15, 'prix' => 5.00,  'url' => null],
                    ['libelle' => 'Multipaire 16 voies 30m','reference' => 'SON-CAB-004', 'stock_total' => 2,  'stock_dispo' => 2,  'prix' => 60.00, 'url' => null],
                    ['libelle' => 'Direct Box Radial J48',  'reference' => 'SON-CAB-005', 'stock_total' => 6,  'stock_dispo' => 4,  'prix' => 20.00, 'url' => null],
                ],
            ],
            'Éclairage' => [
                'Jeux de lumière' => [
                    ['libelle' => 'Lyre Beam Cameo Zenit B200',       'reference' => 'ECL-JDL-001', 'stock_total' => 8,  'stock_dispo' => 6,  'prix' => 150.00, 'url' => null],
                    ['libelle' => 'Par LED ADJ Mega Par Profile',     'reference' => 'ECL-JDL-002', 'stock_total' => 12, 'stock_dispo' => 12, 'prix' => 30.00,  'url' => null],
                    ['libelle' => 'Barre LED Chauvet DJ COLORband',   'reference' => 'ECL-JDL-003', 'stock_total' => 6,  'stock_dispo' => 4,  'prix' => 40.00,  'url' => null],
                    ['libelle' => 'Lyre Wash Cameo ZENIT W300',       'reference' => 'ECL-JDL-004', 'stock_total' => 6,  'stock_dispo' => 4,  'prix' => 130.00, 'url' => null],
                    ['libelle' => 'Par LED Chauvet DJ SlimPAR Pro H', 'reference' => 'ECL-JDL-005', 'stock_total' => 10, 'stock_dispo' => 8,  'prix' => 35.00,  'url' => null],
                    ['libelle' => 'Barre LED ADJ Mega Bar RGBA',      'reference' => 'ECL-JDL-006', 'stock_total' => 6,  'stock_dispo' => 6,  'prix' => 45.00,  'url' => null],
                    ['libelle' => 'Lyre Spot Chauvet Intimidator',    'reference' => 'ECL-JDL-007', 'stock_total' => 4,  'stock_dispo' => 3,  'prix' => 160.00, 'url' => null],
                    ['libelle' => 'Projecteur Fresnel LED 100W',      'reference' => 'ECL-JDL-008', 'stock_total' => 8,  'stock_dispo' => 8,  'prix' => 50.00,  'url' => null],
                    ['libelle' => 'Barre LED Cameo PIXBAR 600 PRO',   'reference' => 'ECL-JDL-009', 'stock_total' => 4,  'stock_dispo' => 2,  'prix' => 55.00,  'url' => null],
                ],
                'Effets spéciaux' => [
                    ['libelle' => 'Machine à fumée Antari Z-800',      'reference' => 'ECL-EFF-001', 'stock_total' => 3, 'stock_dispo' => 3, 'prix' => 35.00, 'url' => 'images/catalogue/fume.jpg'],
                    ['libelle' => 'Machine à bulles ADJ Bubble Blast', 'reference' => 'ECL-EFF-002', 'stock_total' => 2, 'stock_dispo' => 1, 'prix' => 25.00, 'url' => 'images/catalogue/machineabulle.jpg'],
                    ['libelle' => 'Machine à neige ADJ Flurry Frenzy', 'reference' => 'ECL-EFF-003', 'stock_total' => 2, 'stock_dispo' => 2, 'prix' => 30.00, 'url' => null],
                    ['libelle' => 'Stroboscope Chauvet DJ Xpress',     'reference' => 'ECL-EFF-004', 'stock_total' => 4, 'stock_dispo' => 4, 'prix' => 20.00, 'url' => null],
                    ['libelle' => 'Laser ADJ Micro Royal Rave',        'reference' => 'ECL-EFF-005', 'stock_total' => 2, 'stock_dispo' => 1, 'prix' => 40.00, 'url' => null],
                    ['libelle' => 'Boule à facettes 50cm + moteur',    'reference' => 'ECL-EFF-006', 'stock_total' => 3, 'stock_dispo' => 3, 'prix' => 15.00, 'url' => null],
                ],
                'Contrôle DMX' => [
                    ['libelle' => 'Console DMX Chauvet DJ Obey 40',     'reference' => 'ECL-DMX-001', 'stock_total' => 2, 'stock_dispo' => 2, 'prix' => 60.00, 'url' => 'images/catalogue/obey40.jpg'],
                    ['libelle' => 'Interface DMX USB Enttec Open DMX',  'reference' => 'ECL-DMX-002', 'stock_total' => 3, 'stock_dispo' => 2, 'prix' => 40.00, 'url' => 'images/catalogue/interfacedmx.jpg'],
                    ['libelle' => 'Console DMX Behringer LC2412',        'reference' => 'ECL-DMX-003', 'stock_total' => 1, 'stock_dispo' => 1, 'prix' => 80.00, 'url' => null],
                    ['libelle' => 'Interface DMX USB Enttec Pro',        'reference' => 'ECL-DMX-004', 'stock_total' => 2, 'stock_dispo' => 2, 'prix' => 55.00, 'url' => null],
                    ['libelle' => 'Splitter DMX 8 sorties',              'reference' => 'ECL-DMX-005', 'stock_total' => 3, 'stock_dispo' => 3, 'prix' => 30.00, 'url' => null],
                    ['libelle' => 'Câble DMX 5 broches 5m',              'reference' => 'ECL-DMX-006', 'stock_total' => 20,'stock_dispo' => 18, 'prix' => 6.00,  'url' => null],
                ],
                'Câbles & alimentation' => [
                    ['libelle' => 'Câble secteur 3m',       'reference' => 'ECL-ALI-001', 'stock_total' => 30, 'stock_dispo' => 25, 'prix' => 4.00,  'url' => null],
                    ['libelle' => 'Multiprise 6 prises 3m', 'reference' => 'ECL-ALI-002', 'stock_total' => 10, 'stock_dispo' => 8,  'prix' => 10.00, 'url' => null],
                    ['libelle' => 'Tableau électrique 32A', 'reference' => 'ECL-ALI-003', 'stock_total' => 2,  'stock_dispo' => 2,  'prix' => 25.00, 'url' => null],
                ],
            ],
            'Structures' => [
                'Pieds & supports' => [
                    ['libelle' => 'Pied enceinte K&M 26785',   'reference' => 'STR-PDS-001', 'stock_total' => 10, 'stock_dispo' => 8,  'prix' => 15.00, 'url' => null],
                    ['libelle' => 'Pied micro K&M 210/9',      'reference' => 'STR-PDS-002', 'stock_total' => 15, 'stock_dispo' => 15, 'prix' => 10.00, 'url' => null],
                    ['libelle' => 'Pied de lyre 2m',           'reference' => 'STR-PDS-003', 'stock_total' => 8,  'stock_dispo' => 6,  'prix' => 20.00, 'url' => null],
                    ['libelle' => 'Support écran plat 40-75"', 'reference' => 'STR-PDS-004', 'stock_total' => 4,  'stock_dispo' => 3,  'prix' => 25.00, 'url' => null],
                    ['libelle' => 'Pied de table régie',       'reference' => 'STR-PDS-005', 'stock_total' => 6,  'stock_dispo' => 6,  'prix' => 18.00, 'url' => null],
                    ['libelle' => 'Support lyre T-Bar 1m20',   'reference' => 'STR-PDS-006', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 22.00, 'url' => null],
                ],
                'Scènes & podiums' => [
                    ['libelle' => 'Praticable 1m x 1m (hauteur 40cm)', 'reference' => 'STR-SCN-001', 'stock_total' => 20, 'stock_dispo' => 16, 'prix' => 20.00, 'url' => null],
                    ['libelle' => 'Praticable 2m x 1m (hauteur 40cm)', 'reference' => 'STR-SCN-002', 'stock_total' => 10, 'stock_dispo' => 10, 'prix' => 35.00, 'url' => null],
                    ['libelle' => 'Praticable 2m x 1m (hauteur 80cm)', 'reference' => 'STR-SCN-003', 'stock_total' => 8,  'stock_dispo' => 6,  'prix' => 45.00, 'url' => null],
                    ['libelle' => 'Escalier de scène 2 marches',       'reference' => 'STR-SCN-004', 'stock_total' => 6,  'stock_dispo' => 4,  'prix' => 30.00, 'url' => null],
                    ['libelle' => 'Garde-corps de scène 2m',           'reference' => 'STR-SCN-005', 'stock_total' => 10, 'stock_dispo' => 10, 'prix' => 15.00, 'url' => null],
                    ['libelle' => 'Praticable 1m x 1m (hauteur 20cm)', 'reference' => 'STR-SCN-006', 'stock_total' => 12, 'stock_dispo' => 12, 'prix' => 15.00, 'url' => null],
                ],
                'Accroche & truss' => [
                    ['libelle' => 'Truss carré 2m',              'reference' => 'STR-TRS-001', 'stock_total' => 10, 'stock_dispo' => 8,  'prix' => 25.00, 'url' => null],
                    ['libelle' => 'Truss carré 1m',              'reference' => 'STR-TRS-002', 'stock_total' => 10, 'stock_dispo' => 10, 'prix' => 15.00, 'url' => null],
                    ['libelle' => 'Pied de truss télescopique',  'reference' => 'STR-TRS-003', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 40.00, 'url' => null],
                    ['libelle' => 'Coude 90° truss carré',       'reference' => 'STR-TRS-004', 'stock_total' => 8,  'stock_dispo' => 6,  'prix' => 12.00, 'url' => null],
                    ['libelle' => 'Croix de truss carré',        'reference' => 'STR-TRS-005', 'stock_total' => 4,  'stock_dispo' => 4,  'prix' => 18.00, 'url' => null],
                    ['libelle' => 'Serre-câble Ø 10mm',          'reference' => 'STR-TRS-006', 'stock_total' => 20, 'stock_dispo' => 20, 'prix' => 3.00,  'url' => null],
                ],
            ],
            'Vidéo' => [
                'Projection' => [
                    ['libelle' => 'Vidéoprojecteur Epson EB-X41',   'reference' => 'VID-PRJ-001', 'stock_total' => 3, 'stock_dispo' => 2, 'prix' => 80.00,  'url' => null],
                    ['libelle' => 'Vidéoprojecteur Optoma HD28HDR', 'reference' => 'VID-PRJ-002', 'stock_total' => 2, 'stock_dispo' => 2, 'prix' => 100.00, 'url' => null],
                    ['libelle' => 'Écran de projection 3m x 2m',   'reference' => 'VID-PRJ-003', 'stock_total' => 3, 'stock_dispo' => 3, 'prix' => 40.00,  'url' => null],
                    ['libelle' => 'Écran de projection 4m x 3m',   'reference' => 'VID-PRJ-004', 'stock_total' => 2, 'stock_dispo' => 1, 'prix' => 60.00,  'url' => null],
                    ['libelle' => "Pied d'écran réglable",          'reference' => 'VID-PRJ-005', 'stock_total' => 4, 'stock_dispo' => 4, 'prix' => 20.00,  'url' => null],
                ],
                'Câbles vidéo' => [
                    ['libelle' => 'Câble HDMI 5m',        'reference' => 'VID-CAB-001', 'stock_total' => 15, 'stock_dispo' => 12, 'prix' => 8.00,  'url' => null],
                    ['libelle' => 'Câble HDMI 10m',       'reference' => 'VID-CAB-002', 'stock_total' => 10, 'stock_dispo' => 8,  'prix' => 12.00, 'url' => null],
                    ['libelle' => 'Câble VGA 5m',         'reference' => 'VID-CAB-003', 'stock_total' => 8,  'stock_dispo' => 8,  'prix' => 6.00,  'url' => null],
                    ['libelle' => 'Adaptateur HDMI/VGA',  'reference' => 'VID-CAB-004', 'stock_total' => 10, 'stock_dispo' => 10, 'prix' => 5.00,  'url' => null],
                    ['libelle' => 'Switch HDMI 4 entrées','reference' => 'VID-CAB-005', 'stock_total' => 3,  'stock_dispo' => 3,  'prix' => 25.00, 'url' => null],
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
                    $materiel->setUrlMateriel($m['url'] ?? null);
                    $materiel->setSousCategorie($sousCategorie);
                    $manager->persist($materiel);

                    $this->addReference('materiel-' . $m['libelle'], $materiel);
                }
            }
        }

        $instrumentsData = [
            // Instruments
            ['libelle' => 'Guitare électrique',       'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/guitare-electrique.png'],
            ['libelle' => 'Guitare basse',            'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/guitare-basse.png'],
            ['libelle' => 'Guitare acoustique',       'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Batterie',                 'type' => 'instrument', 'largeur' => 200, 'hauteur' => 150, 'url' => '/images/instruments/tambour.png'],
            ['libelle' => 'Clavier / Piano',          'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/musique.png'],
            ['libelle' => 'Violon',                   'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Saxophone',                'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Trompette',                'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Flûte',                    'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Accordéon',                'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Contrebasse',              'type' => 'instrument', 'largeur' => 75,  'hauteur' => 100, 'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Ukulélé',                  'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Banjo',                    'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Mandoline',                'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Trombone',                 'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Clarinette',               'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Cor',                      'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Tuba',                     'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Harpe',                    'type' => 'instrument', 'largeur' => 75,  'hauteur' => 100, 'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Percussions latines',      'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Djembé',                   'type' => 'instrument', 'largeur' => 50,  'hauteur' => 75,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Marimba',                  'type' => 'instrument', 'largeur' => 100, 'hauteur' => 75,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Xylophone',                'type' => 'instrument', 'largeur' => 100, 'hauteur' => 75,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Sitar',                    'type' => 'instrument', 'largeur' => 50,  'hauteur' => 75,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Didgeridoo',               'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Cor des Alpes',            'type' => 'instrument', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/default.svg'],
            ['libelle' => 'Basse électro-acoustique', 'type' => 'instrument', 'largeur' => 75,  'hauteur' => 75,  'url' => '/images/instruments/default.svg'],

            // Équipements (uniquement ceux d'origine)
            ['libelle' => 'Micro chant',     'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/micro.png'],
            ['libelle' => 'Ampli guitare',   'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/ampli-guitare.png'],
            ['libelle' => 'Ampli basse',     'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/ampli.png'],
            ['libelle' => 'Retour de scène', 'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
            ['libelle' => 'En direct',       'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
            ['libelle' => 'Cymballe',        'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
            ['libelle' => 'Tom',             'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
            ['libelle' => 'Grosse caisse',   'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
            ['libelle' => 'Caisse claire',   'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
            ['libelle' => 'Charleston',      'type' => 'equipement', 'largeur' => 50,  'hauteur' => 50,  'url' => '/images/instruments/retour.png'],
        ];

        foreach ($instrumentsData as $d) {
            $instrument = new Instruments();
            $instrument->setLibelle($d['libelle']);
            $instrument->setType($d['type']);
            $instrument->setUrlInstrument($d['url']);
            $manager->persist($instrument);

            $this->addReference('instrument-' . $d['libelle'], $instrument);
        }

        $materielSuggereData = [
            ['instrument' => 'Ampli guitare', 'materiel' => 'Micro Shure SM57',     'quantite' => 1],
            ['instrument' => 'Ampli basse',   'materiel' => 'Micro Shure Beta 52A', 'quantite' => 1],
            ['instrument' => 'Cymballe',      'materiel' => 'Micro Shure SM81',     'quantite' => 1],
            ['instrument' => 'Tom',           'materiel' => 'Micro Shure SM57',     'quantite' => 1],
            ['instrument' => 'Grosse caisse', 'materiel' => 'Micro Shure Beta 52A', 'quantite' => 1],
            ['instrument' => 'Caisse claire', 'materiel' => 'Micro Shure SM57',     'quantite' => 1],
            ['instrument' => 'Charleston',    'materiel' => 'Micro Shure SM57',     'quantite' => 1],
        ];

        foreach ($materielSuggereData as $ms) {
            $instrument  = $this->getReference('instrument-' . $ms['instrument'], Instruments::class);
            $materielObj = $this->getReference('materiel-'   . $ms['materiel'], Materiel::class);

            $materielSuggere = new MaterielSuggere();
            $materielSuggere->setInstrument($instrument);
            $materielSuggere->setMateriel($materielObj);
            $materielSuggere->setQuantite($ms['quantite']);
            $manager->persist($materielSuggere);
        }

        // ── Users ──────────────────────────────────────────────────────────────

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

        $users = [
            ['nom' => 'Martin',   'prenom' => 'Marie',   'email' => 'marie.martin@gmail.com',   'date' => '2000-03-22'],
            ['nom' => 'Durand',   'prenom' => 'Pierre',  'email' => 'pierre.durand@gmail.com',  'date' => '1998-07-14'],
            ['nom' => 'Bernard',  'prenom' => 'Sophie',  'email' => 'sophie.bernard@gmail.com', 'date' => '1995-11-30'],
            ['nom' => 'Petit',    'prenom' => 'Lucas',   'email' => 'lucas.petit@gmail.com',    'date' => '2001-05-18'],
            ['nom' => 'Robert',   'prenom' => 'Emma',    'email' => 'emma.robert@gmail.com',    'date' => '1999-09-05'],
            ['nom' => 'Richard',  'prenom' => 'Hugo',    'email' => 'hugo.richard@gmail.com',   'date' => '1997-02-28'],
            ['nom' => 'Moreau',   'prenom' => 'Léa',     'email' => 'lea.moreau@gmail.com',     'date' => '2002-12-10'],
            ['nom' => 'Simon',    'prenom' => 'Tom',     'email' => 'tom.simon@gmail.com',      'date' => '1996-04-03'],
            ['nom' => 'Laurent',  'prenom' => 'Camille', 'email' => 'camille.laurent@gmail.com','date' => '2003-08-25'],
            ['nom' => 'Lefebvre', 'prenom' => 'Nathan',  'email' => 'nathan.lefebvre@gmail.com','date' => '1994-01-17'],
            ['nom' => 'Michel',   'prenom' => 'Chloé',   'email' => 'chloe.michel@gmail.com',   'date' => '2000-06-09'],
            ['nom' => 'Garcia',   'prenom' => 'Mathis',  'email' => 'mathis.garcia@gmail.com',  'date' => '1998-10-21'],
            ['nom' => 'David',    'prenom' => 'Inès',    'email' => 'ines.david@gmail.com',     'date' => '2001-03-14'],
            ['nom' => 'Bertrand', 'prenom' => 'Théo',    'email' => 'theo.bertrand@gmail.com',  'date' => '1997-07-07'],
            ['nom' => 'Roux',     'prenom' => 'Manon',   'email' => 'manon.roux@gmail.com',     'date' => '1999-11-02'],
            ['nom' => 'Vincent',  'prenom' => 'Antoine', 'email' => 'antoine.vincent@gmail.com','date' => '2002-05-30'],
            ['nom' => 'Fournier', 'prenom' => 'Jade',    'email' => 'jade.fournier@gmail.com',  'date' => '1996-09-16'],
            ['nom' => 'Morel',    'prenom' => 'Alexis',  'email' => 'alexis.morel@gmail.com',   'date' => '2003-01-08'],
            ['nom' => 'Girard',   'prenom' => 'Lucie',   'email' => 'lucie.girard@gmail.com',   'date' => '1995-04-20'],
            ['nom' => 'Andre',    'prenom' => 'Maxime',  'email' => 'maxime.andre@gmail.com',   'date' => '2000-08-13'],
        ];

        foreach ($users as $u) {
            $newUser = new User();
            $newUser->setNom($u['nom']);
            $newUser->setPrenom($u['prenom']);
            $newUser->setEmail($u['email']);
            $newUser->setRoles(['ROLE_USER']);
            $newUser->setDateNaissance(new \DateTime($u['date']));
            $newUser->setPassword($this->hasher->hashPassword($newUser, 'user1234'));
            $manager->persist($newUser);
        }

        $manager->flush();
    }
}
