L'envoi d'email ne marche plus : le SMTP utilisé était celui de PULSAR INFORMATIQUE, je ne peux pas divulguer les codes. Par contre, en regardant le code, 
vous verrez que l'envoi d'email était fonctionnel (dans le service MovieHandler)

Pensez à faire un composer update pour récupérer le bundle de pagination KnpPaginator
J'ai installé une extension pour que doctrine comprenne le YEAR de SQL dans son DQL (pour les recherches par date)

En local, tout marchait bien, si vous rencontrez un quelquonque problème c'est que le git ne doit pas être complet :)

je n'ai pas mis un @Assert\Length sur le titre du film, car je ne trouvais pas d'exemple de film avec plus de 10 caractères ! -mais j'ai donc mis un notBlank sur le title


Merci, bonne soirée !