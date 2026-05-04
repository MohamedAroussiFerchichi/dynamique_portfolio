# 📚 Projet Portfolio Dynamique – Version Complète

## 🎯 Objectif
Créer un portfolio personnel dynamique avec :

- Gestion des projets
- Gestion des certificats / CV
- Liens vers LinkedIn, GitHub, site perso
- Utilisation de **HTML + CSS + PHP + MySQL via WAMP**

---

## 👤 Structure des pages

### 1️⃣ Page Accueil
- Nom / Prénom  
- Photo  
- Description courte  
- Menu de navigation  
- Liens : LinkedIn, GitHub, site perso  

### 2️⃣ Page À propos
- Description détaillée  
- Compétences techniques  
- Formation ou expérience  
- CV téléchargeable  

### 3️⃣ Page Projets
- CRUD pour projets : Titre, Description, Image, Technologie, Date  
- Affichage projets dynamiques  

### 4️⃣ Page Certificats
- CRUD pour certificats : Titre du certificat, Image ou PDF, Date obtention  
- Affichage pour téléchargement  

### 5️⃣ Page Contact
- Formulaire contact : Nom, Email, Message  
- Enregistrement dans la base  

---

## 🗄️ Base de données

### Table `projets`
- id (INT PK AI)  
- titre (VARCHAR 255)  
- description (TEXT)  
- image (VARCHAR 255)  
- technologie (VARCHAR 255)  
- date_projet (DATE)  

### Table `certificats`
- id (INT PK AI)  
- titre (VARCHAR 255)  
- fichier (VARCHAR 255)  
- date_obtention (DATE)  

### Table `contact`
- id (INT PK AI)  
- nom (VARCHAR 100)  
- email (VARCHAR 100)  
- message (TEXT)  

### Table `infos_personnelles`
- id (INT PK AI)  
- nom (VARCHAR 50)  
- prenom (VARCHAR 50)  
- description (TEXT)  
- photo (VARCHAR 255)  
- cv (VARCHAR 255)  
- linkedin (VARCHAR 255)  
- github (VARCHAR 255)  
- site_perso (VARCHAR 255)  

### Table `admin`
- id (INT PK AI)  
- username (VARCHAR 100)  
- password (VARCHAR 200)  

---

## 📁 Organisation projet

### Dossiers
- `css/`  
  - `style.css`  

- `fichiers/`  
  *(folder for uploaded files like CVs and certificates)*  

### Fichiers PHP
- `accueil.php`  
- `ajouter_certificat.php`  
- `ajouter_projet.php`  
- `apropos.php`  
- `certificats.php`  
- `contact.php`  
- `db.php`  
- `index.php`  
- `inscription.php`  
- `menu.php`  
- `modifier_certificat.php`  
- `modifier_projet.php`  
- `projets.php`  
- `supprimer_certificat.php`  
- `supprimer_projet.php`  

---

## 📌 Fonctionnalités supplémentaires

- **Upload CV**  
  Sur la page “À propos”, ajouter un formulaire permettant de télécharger un CV (PDF ou DOC).  
  Stocker le fichier dans `/fichiers/` et enregistrer le nom du fichier dans la base `infos_personnelles.cv`.  

- **Upload certificat**  
  Sur la page “Certificats”, permettre d’ajouter un certificat (Titre, PDF ou image, Date).  
  Stocker le fichier dans `/fichiers/` et enregistrer le nom du fichier dans la table `certificats`.  

- **Liens LinkedIn / GitHub / Site**  
  Dans `infos_personnelles`, ajouter champs `linkedin`, `github`, `site_perso`.  
  Afficher sous forme de boutons ou liens cliquables sur la page d’accueil.  

---

## 💻 Exemple code pour upload fichier en PHP

```php
// ajout_certificat.php
if(isset($_POST['submit'])){
    $titre = $_POST['titre'];
    $date = $_POST['date_obtention'];
    $fichier = $_FILES['fichier']['name'];
    $tmp_name = $_FILES['fichier']['tmp_name'];
    $destination = "fichiers/" . $fichier;

    if(move_uploaded_file($tmp_name, $destination)){
        $sql = "INSERT INTO certificats (titre, fichier, date_obtention) 
                VALUES ('$titre', '$fichier', '$date')";
        mysqli_query($conn, $sql);
        echo "Certificat ajouté avec succès!";
    } else {
        echo "Erreur lors de l'upload!";
    }
}
