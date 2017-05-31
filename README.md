L'envoi d'email ne marche plus : je ne peux pas divulguer les codes SMTP - c'est celui que j'utilise pour mes sites en prod. Par contre, en regardant le code, 
vous verrez que l'envoi d'email était fonctionnel (dans le service MovieHandler)

Pensez à faire un composer update pour récupérer les différents bundles

En local, tout marchait bien, si vous rencontrez un quelquonque problème c'est que le git ne doit pas être complet :)

Je n'ai pas mis un @Assert\Length sur le titre du film, car je ne trouvais pas d'exemple de film avec plus de 10 caractères ! -mais j'ai donc mis un notBlank sur le title

En ce qui concerne les traductions, je n'ai pas mis de fichiers de traductions espagnogles, mais ce n'est qu'une question de contenu !

Je n'ai pas traduit automatiquement d'autres contenus que les tags =>
 pour les films, ou les acteurs, je pense que celà n'aurait pas eu d'intérêt, mais je reconnais qu'en ce qui concerne les catégories/thèmes, ce serait une amélioration intéressante, mais le temps m'était compté,...

Pour voir fonctionner la génération de pdf, il faut installer la bibliothèque wkhtmltopdf avec la commande =>
 
sudo apt-get install wkhtmltopdf


Merci, bonne journée !

