
CREATE TABLE Utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    motDePasse VARCHAR(255),
    role ENUM('CLIENT', 'VENDEUR') NOT NULL
);

CREATE TABLE Client (
    id INT PRIMARY KEY,
    localisation VARCHAR(255),
    FOREIGN KEY (id) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE Vendeur (
    id INT PRIMARY KEY,
    localisation VARCHAR(255),
    FOREIGN KEY (id) REFERENCES Utilisateur(id) ON DELETE CASCADE
);


CREATE TABLE Livre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255),
    auteur VARCHAR(255),
    prix DOUBLE,
    disponibilite BOOLEAN,
    vendeur_id INT,
    FOREIGN KEY (vendeur_id) REFERENCES Vendeur(id) ON DELETE CASCADE
);

CREATE TABLE Avis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUtilisateur INT,
    idLivre INT,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date DATE,
    FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (idLivre) REFERENCES Livre(id) ON DELETE CASCADE
);

CREATE TABLE Commande (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idClient INT,
    dateCommande DATE,
    etat ENUM('EN_COURS', 'EXPEDIEE', 'ANNULEE') DEFAULT 'EN_COURS',
    FOREIGN KEY (idClient) REFERENCES Client(id) ON DELETE CASCADE
);

CREATE TABLE Paiement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idCommande INT,
    montant DOUBLE,
    methode VARCHAR(50),
    FOREIGN KEY (idCommande) REFERENCES Commande(id) ON DELETE CASCADE
);


CREATE TABLE Panier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idClient INT,
    FOREIGN KEY (idClient) REFERENCES Client(id) ON DELETE CASCADE
);

CREATE TABLE Panier_Livre (
    idPanier INT,
    idLivre INT,
    PRIMARY KEY (idPanier, idLivre),
    FOREIGN KEY (idPanier) REFERENCES Panier(id) ON DELETE CASCADE,
    FOREIGN KEY (idLivre) REFERENCES Livre(id) ON DELETE CASCADE
);

-- Table Livraison
CREATE TABLE Livraison (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idCommande INT,
    livre_id INT,
    etat ENUM('EN_COURS', 'LIVRE', 'ANNULE') DEFAULT 'EN_COURS',
    FOREIGN KEY (idCommande) REFERENCES Commande(id) ON DELETE CASCADE,
    FOREIGN KEY (livre_id) REFERENCES Livre(id) ON DELETE CASCADE
);

CREATE TABLE Categorie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) UNIQUE
);

CREATE TABLE Livre_Categorie (
    idLivre INT,
    idCategorie INT,
    PRIMARY KEY (idLivre, idCategorie),
    FOREIGN KEY (idLivre) REFERENCES Livre(id) ON DELETE CASCADE,
    FOREIGN KEY (idCategorie) REFERENCES Categorie(id) ON DELETE CASCADE
);
