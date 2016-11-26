#NAO


A Symfony project created on October 16, 2016, 9:48 am.

##INSTALL

Init project with composer

`composer install`
`bower install`

###Download and import last TAXREF class AVES

[Taxref csv file](https://inpn.mnhn.fr/telechargement/referentielEspece/referentielTaxo)

`php bin/console gsquad:import:taxref path/to/taxref.csv` 

###Load Fixtures

`php bin/console doctrine:fixtures:load`

##Authentification

You must create application on Facebook and Google API 
Twitter is not yet implemented

