-- Structures UCAD : insère uniquement celles absentes côté serveur (comparaison par name), ne modifie jamais une structure existante.

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Faculté des Lettres et Sciences Humaines', 'FLSH', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Faculté des Lettres et Sciences Humaines');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Faculté de Médecine, de Pharmacie et d''Odonto-Stomatologie', 'FMPO', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Faculté de Médecine, de Pharmacie et d''Odonto-Stomatologie');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Faculté des Sciences et Techniques', 'FST', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Faculté des Sciences et Techniques');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Faculté des Sciences Juridiques et Politiques', 'FSJP', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Faculté des Sciences Juridiques et Politiques');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Faculté des Sciences Économiques et de Gestion', 'FASEG', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Faculté des Sciences Économiques et de Gestion');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Faculté des Sciences et Technologies de l''Éducation et de la Formation', 'FASTEF', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Faculté des Sciences et Technologies de l''Éducation et de la Formation');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Supérieure Polytechnique', 'ESP', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Supérieure Polytechnique');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École des Bibliothécaires, Archivistes et Documentalistes', 'EBAD', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École des Bibliothécaires, Archivistes et Documentalistes');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Normale Supérieure d''Enseignement Technique et Professionnel', 'ENSETP', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Normale Supérieure d''Enseignement Technique et Professionnel');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Supérieure d''Économie Appliquée', 'ESEA', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Supérieure d''Économie Appliquée');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Nationale Supérieure des Mines et de la Géologie', 'ENSMG', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Nationale Supérieure des Mines et de la Géologie');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Nationale de Développement Sanitaire et Social', 'ENDSS', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Nationale de Développement Sanitaire et Social');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut Supérieur de Formation à Distance', 'ISFAD', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut Supérieur de Formation à Distance');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut Supérieur des Arts et des Cultures', 'ISAC', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut Supérieur des Arts et des Cultures');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut National Supérieur d''Éducation Physique et Sportive', 'INSEPS', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut National Supérieur d''Éducation Physique et Sportive');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut Fondamental d''Afrique Noire', 'IFAN', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut Fondamental d''Afrique Noire');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Français pour les Étudiants Étrangers', 'IFE', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Français pour les Étudiants Étrangers');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Santé et Développement', 'ISED', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Santé et Développement');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Médecine Tropicale Appliquée', 'IMTA', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Médecine Tropicale Appliquée');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Technologie Nucléaire Appliquée', 'ITNA', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Technologie Nucléaire Appliquée');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Pédiatrie Sociale', 'IPS', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Pédiatrie Sociale');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Recherches sur l''Enseignement de la Mathématique, de la Physique et de la Technologie', 'IREMPT', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Recherches sur l''Enseignement de la Mathématique, de la Physique et de la Technologie');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Recherches et d''Enseignement de Psychopathologie (ex CRPP)', 'IREP', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Recherches et d''Enseignement de Psychopathologie (ex CRPP)');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut des Droits de l''Homme et de la Paix', 'IDHP', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut des Droits de l''Homme et de la Paix');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Formation et de Recherche en Population, Développement et Santé de la Reproduction', 'IPDSR', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Formation et de Recherche en Population, Développement et Santé de la Reproduction');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Gouvernance Territoriale et de Développement Local', 'IGTDL', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Gouvernance Territoriale et de Développement Local');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut Universitaire de Pêche et d''Aquaculture', 'IUPA', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut Universitaire de Pêche et d''Aquaculture');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Formation en Administration et Création d''Entreprise', 'IFACE', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Formation en Administration et Création d''Entreprise');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut de Prévoyance Médico-Social', 'IPMS', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut de Prévoyance Médico-Social');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut Confucius', 'IC', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut Confucius');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut Africain de Lutte contre le Cancer', 'IALC', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut Africain de Lutte contre le Cancer');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut des Politiques Publiques', 'IPP', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut des Politiques Publiques');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut d''Égyptologie', 'IEGY', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut d''Égyptologie');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Institut des Sciences du Médicament', 'ISMED', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Institut des Sciences du Médicament');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Centre d''Études des Sciences et Techniques de l''Information', 'CESTI', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Centre d''Études des Sciences et Techniques de l''Information');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Centre de Linguistique Appliquée de Dakar', 'CLAD', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Centre de Linguistique Appliquée de Dakar');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Centre d''Études et de Recherches sur les Énergies Renouvelables', 'CERER', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Centre d''Études et de Recherches sur les Énergies Renouvelables');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Centre Universitaire de Recherche et de Formations aux Technologies de l''Internet', 'CURI', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Centre Universitaire de Recherche et de Formations aux Technologies de l''Internet');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Centre d''Incubation et de Développement d''Entreprises Innovantes', 'INNODEV', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Centre d''Incubation et de Développement d''Entreprises Innovantes');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Doctorale des Sciences Juridiques, Politiques, Économiques et de Gestion', 'EDJPEG', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Doctorale des Sciences Juridiques, Politiques, Économiques et de Gestion');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Doctorale Sciences de la Vie, de la Santé et de l''Environnement', 'EDSEV', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Doctorale Sciences de la Vie, de la Santé et de l''Environnement');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Doctorale Arts, Cultures et Civilisations', 'EDARCIV', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Doctorale Arts, Cultures et Civilisations');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Doctorale Eau, Qualité et Usage de l''Eau', 'EDEQUE', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Doctorale Eau, Qualité et Usage de l''Eau');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Doctorale sur l''Homme et la Société', 'ED ETHOS', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Doctorale sur l''Homme et la Société');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Doctorale Physique, Chimie, Sciences de la Terre, de l''Univers et de l''Ingénieur', 'PCSTUI', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Doctorale Physique, Chimie, Sciences de la Terre, de l''Univers et de l''Ingénieur');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'École Doctorale Mathématiques et Informatique', 'EDMI', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'École Doctorale Mathématiques et Informatique');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Rectorat', 'RECTORAT', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Rectorat');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Bibliothèque Universitaire', 'BU', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Bibliothèque Universitaire');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'Office du Baccalauréat', 'OB', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'Office du Baccalauréat');

INSERT INTO structures (name, acronym, created_at, updated_at)
SELECT 'WASCAL', 'WAS', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM structures WHERE name = 'WASCAL');
