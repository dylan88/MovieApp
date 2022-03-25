#!/usr/bin/env bash
ENVIRONNEMENT=$1
if [ ! -z "$ENVIRONNEMENT" ]
then
    #if [ "$ENVIRONNEMENT" = "integ" ]
    #then
        ansible-playbook setup.yml -i inventories/$ENVIRONNEMENT --extra-vars "environnement=$1" --ask-pass
    #else
    #    ansible-playbook setup.yml -i inventories/$ENVIRONNEMENT --extra-vars "environnement=$1" --ask-pass --skip-tags "download"
    #fi
else
    echo "ERREUR : l'environnement doit être passé en paramètre. ex: sh setup.sh environnement"
fi
