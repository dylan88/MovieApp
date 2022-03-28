# Installation

* Installer [VirtualBox](https://www.virtualbox.org/wiki/Downloads) et [Vagrant](https://www.vagrantup.com/downloads)
* Installer [NodeJS](https://nodejs.org/) sur votre ordinateur et lancer la commande `npm install --global yarn` localement
* Copier le fichier ansible/vars/config_vagrant.local.example.yml vers ansible/vars/config_vagrant.local.yml et modifier le avec vos préférences.

# Hosts
Ajouter ces entrées dans le fichiers hosts de votre machine : `formation.symfo.local`

L'url est en https

# Lancement de la VM
Exécuter la commande `vagrant up` depuis un terminal