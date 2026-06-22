<?php

namespace Database\Seeders;

use App\Models\Structure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StructureSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Structure::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $structures = [
            // ── Facultés ──────────────────────────────────────────────────────────
            ['acronym' => 'FLSH',     'name' => 'Faculté des Lettres et Sciences Humaines'],
            ['acronym' => 'FMPO',     'name' => 'Faculté de Médecine, de Pharmacie et d\'Odonto-Stomatologie'],
            ['acronym' => 'FST',      'name' => 'Faculté des Sciences et Techniques'],
            ['acronym' => 'FSJP',     'name' => 'Faculté des Sciences Juridiques et Politiques'],
            ['acronym' => 'FASEG',    'name' => 'Faculté des Sciences Économiques et de Gestion'],
            ['acronym' => 'FASTEF',   'name' => 'Faculté des Sciences et Technologies de l\'Éducation et de la Formation'],

            // ── Écoles ────────────────────────────────────────────────────────────
            ['acronym' => 'ESP',      'name' => 'École Supérieure Polytechnique'],
            ['acronym' => 'EBAD',     'name' => 'École des Bibliothécaires, Archivistes et Documentalistes'],
            ['acronym' => 'ENSETP',   'name' => 'École Normale Supérieure d\'Enseignement Technique et Professionnel'],
            ['acronym' => 'ESEA',     'name' => 'École Supérieure d\'Économie Appliquée'],
            ['acronym' => 'ENSMG',    'name' => 'École Nationale Supérieure des Mines et de la Géologie'],
            ['acronym' => 'ENDSS',    'name' => 'École Nationale de Développement Sanitaire et Social'],
            ['acronym' => 'ISFAD',    'name' => 'Institut Supérieur de Formation à Distance'],
            ['acronym' => 'ISAC',     'name' => 'Institut Supérieur des Arts et des Cultures'],

            // ── Instituts ─────────────────────────────────────────────────────────
            ['acronym' => 'INSEPS',   'name' => 'Institut National Supérieur d\'Éducation Physique et Sportive'],
            ['acronym' => 'IFAN',     'name' => 'Institut Fondamental d\'Afrique Noire'],
            ['acronym' => 'IFE',      'name' => 'Institut de Français pour les Étudiants Étrangers'],
            ['acronym' => 'ISED',     'name' => 'Institut de Santé et Développement'],
            ['acronym' => 'IMTA',     'name' => 'Institut de Médecine Tropicale Appliquée'],
            ['acronym' => 'ITNA',     'name' => 'Institut de Technologie Nucléaire Appliquée'],
            ['acronym' => 'IPS',      'name' => 'Institut de Pédiatrie Sociale'],
            ['acronym' => 'IREMPT',   'name' => 'Institut de Recherches sur l\'Enseignement de la Mathématique, de la Physique et de la Technologie'],
            ['acronym' => 'IREP',     'name' => 'Institut de Recherches et d\'Enseignement de Psychopathologie (ex CRPP)'],
            ['acronym' => 'IDHP',     'name' => 'Institut des Droits de l\'Homme et de la Paix'],
            ['acronym' => 'IPDSR',    'name' => 'Institut de Formation et de Recherche en Population, Développement et Santé de la Reproduction'],
            ['acronym' => 'IGTDL',    'name' => 'Institut de Gouvernance Territoriale et de Développement Local'],
            ['acronym' => 'IUPA',     'name' => 'Institut Universitaire de Pêche et d\'Aquaculture'],
            ['acronym' => 'IFACE',    'name' => 'Institut de Formation en Administration et Création d\'Entreprise'],
            ['acronym' => 'IPMS',     'name' => 'Institut de Prévoyance Médico-Social'],
            ['acronym' => 'IC',       'name' => 'Institut Confucius'],
            ['acronym' => 'IALC',     'name' => 'Institut Africain de Lutte contre le Cancer'],
            ['acronym' => 'IPP',      'name' => 'Institut des Politiques Publiques'],
            ['acronym' => 'IEGY',     'name' => 'Institut d\'Égyptologie'],
            ['acronym' => 'ISMED',    'name' => 'Institut des Sciences du Médicament'],

            // ── Centres ───────────────────────────────────────────────────────────
            ['acronym' => 'CESTI',    'name' => 'Centre d\'Études des Sciences et Techniques de l\'Information'],
            ['acronym' => 'CLAD',     'name' => 'Centre de Linguistique Appliquée de Dakar'],
            ['acronym' => 'CERER',    'name' => 'Centre d\'Études et de Recherches sur les Énergies Renouvelables'],
            ['acronym' => 'CURI',     'name' => 'Centre Universitaire de Recherche et de Formations aux Technologies de l\'Internet'],
            ['acronym' => 'INNODEV',  'name' => 'Centre d\'Incubation et de Développement d\'Entreprises Innovantes'],

            // ── Écoles doctorales ─────────────────────────────────────────────────
            ['acronym' => 'EDJPEG',   'name' => 'École Doctorale des Sciences Juridiques, Politiques, Économiques et de Gestion'],
            ['acronym' => 'EDSEV',    'name' => 'École Doctorale Sciences de la Vie, de la Santé et de l\'Environnement'],
            ['acronym' => 'EDARCIV',  'name' => 'École Doctorale Arts, Cultures et Civilisations'],
            ['acronym' => 'EDEQUE',   'name' => 'École Doctorale Eau, Qualité et Usage de l\'Eau'],
            ['acronym' => 'ED ETHOS', 'name' => 'École Doctorale sur l\'Homme et la Société'],
            ['acronym' => 'PCSTUI',   'name' => 'École Doctorale Physique, Chimie, Sciences de la Terre, de l\'Univers et de l\'Ingénieur'],
            ['acronym' => 'EDMI',     'name' => 'École Doctorale Mathématiques et Informatique'],

            // ── Autres ────────────────────────────────────────────────────────────
            ['acronym' => 'RECTORAT', 'name' => 'Rectorat'],
            ['acronym' => 'BU',       'name' => 'Bibliothèque Universitaire'],
            ['acronym' => 'OB',       'name' => 'Office du Baccalauréat'],
            ['acronym' => 'WAS',      'name' => 'WASCAL'],
        ];

        Structure::insert($structures);
    }
}
