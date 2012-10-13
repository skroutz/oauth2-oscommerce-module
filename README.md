osCommerce v.2.3 Skroutz Easy module
====================================

## English

### Requirements

 - [osCommerce v.2.3 - v.2.3.3](http://www.oscommerce.com)
 - [PHP cURL](http://php.net/manual/en/book.curl.php)

### Installation instructions

1. Copy every file to the catalog root according to its place in this folder  
   e.g. if your catalog resides in */var/www/catalog*

    ` $ export CATALOG=/var/www/catalog`  
    ` $ cp skroutz.php $CATALOG/skroutz.php`  
    ` $ cp skroutz_login.php $CATALOG/skroutz_login.php`  
    ` $ cp images/skroutz_logo_90x30.png $CATALOG/images/skroutz_logo_90x30.png`  
    ` $ cp includes/languages/english/modules/boxes/bm_skroutz.php $CATALOG/includes/languages/english/modules/boxes/bm_skroutz.php`  
    ` $ cp includes/languages/greek/modules/boxes/bm_skroutz.php $CATALOG/includes/languages/greek/modules/boxes/bm_skroutz.php`  
    ` $ cp includes/modules/boxes/bm_skroutz.php $CATALOG/includes/modules/boxes/bm_skroutz.php`  

    **Note**: you might need to create the folder hierarchy, if it don't exist, before you copy the files e.g.:
    `$ mkdir -p $CATALOG/includes/languages/english/modules/boxes/`

2. Login to the 'Administration Tool' and enable the 'Module'
    - Select 'Boxes' from the 'Modules' menu
    - Click 'Install Module' on the top right
    - Install the module using 'Install Module'
    - Select the module from the list and click 'Edit'
    - Select the column you want the module to appear ('Left' or 'Right')
    - Select the module order (we recommend to display it immediately bellow the 'Shopping Cart')

3. Edit *skroutz.php* (should be in *$CATALOG/skroutz.php*) and add the keys you obtained from Skroutz:
    - client_id
    - client_secret

## Greek

### Απαιτήσεις

 - [osCommerce v.2.3 - v.2.3.3](http://www.oscommerce.com)
 - [PHP cURL](http://php.net/manual/en/book.curl.php)

### Οδηγίες εγκατάστασης

1. Αντιγράψτε κάθε αρχείο στο ριζικό κατάλογο ανάλογα με το που βρίσκεται στον φάκελο  
    π.χ. αν ο κατάλογος είναι στην τοποθεσία */var/www/catalog*

    ` $ export CATALOG=/var/www/catalog`  
    ` $ cp skroutz.php $CATALOG/skroutz.php`  
    ` $ cp skroutz_login.php $CATALOG/skroutz_login.php`  
    ` $ cp images/skroutz_logo_90x30.png $CATALOG/images/skroutz_logo_90x30.png`  
    ` $ cp includes/languages/english/modules/boxes/bm_skroutz.php $CATALOG/includes/languages/english/modules/boxes/bm_skroutz.php`  
    ` $ cp includes/languages/greek/modules/boxes/bm_skroutz.php $CATALOG/includes/languages/greek/modules/boxes/bm_skroutz.php`  
    ` $ cp includes/modules/boxes/bm_skroutz.php $CATALOG/includes/modules/boxes/bm_skroutz.php`  

    **Σημείωση**: ίσως χρειαστεί να δημιουργήσετε την ιεραρχία των φακέλων, αν δεν υπάρχουν, πριν αντιγράψετε τα αρχεία π.χ.:
    `$ mkdir -p $CATALOG/includes/languages/english/modules/boxes/`

2. Συνδεθείτε στην 'Πλατφόρμα Διαχείρισης' και ενεργοποίηστε τη 'Λειτουργία'
    - Επιλέξτε 'Πλαίσια' από το μενού 'Λειτουργίες'
    - Κάντε κλικ στο 'Εισαγωγή Λειτουργίας' πάνω δεξιά
    - Εγκαταστήστε τη λειτουργία επιλέγοντας την 'Εισαγωγής Λειτουργίας'
    - Επιλέξτε τη λειτουργία από τη λίστα και κάντε κλικ στην 'Επεξεργασία' (Επεξ.)
    - Επιλέξτε τη στήλη στην οποία θέλετε να εμφανίζεται η λειτουργία ('Αριστερή' ή 'Δεξιά')
    - Επιλέξτε τη σειρά με την οποία θέλετε να εμφανίζονται οι λειτουργίας (προτείνουμε να εμφανίζεται αμέσως κάτω από το 'Καλάθι Αγορών')

3. Ανοίξτε το αρχείο *skroutz.php* (πρέπει να είναι στο *$CATALOG/skroutz.php*) και προσθέστε τα κλειδιά που πήρατε από το Skroutz:
    - client_id
    - client_secret
