#!/usr/bin/env bash

ANSIBLE_PLAYBOOK=$1

isDebian=true
isRedHat=false

if [ "$isDebian" = true ];
then

    debianVersion=$(cat /etc/issue)
    debian8="Debian GNU/Linux 8 \n \l"
    debian9="Debian GNU/Linux 9 \n \l"
    debian10="Debian GNU/Linux 10 \n \l"
    debian11="Debian GNU/Linux 11 \n \l"

    if [ "$debianVersion" = "$debian8" ];
    then
        echo "Debian 8 install"
        sudo echo "deb http://ppa.launchpad.net/ansible/ansible/ubuntu trusty main" > /etc/apt/sources.list.d/ansible_repo.list

        sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 93C4A3FD7BB9C367
    fi

    if [ "$debianVersion" = "$debian9" ];
    then
        #configuration de apt-get pour qu'il fonctionne avec le proxy micropole
        echo "Debian 9 install"
        echo "Acquire::http::Pipeline-Depth \"0\";" >> /etc/apt/apt.conf.d/99nohttppipelineproxyfix

        sudo echo "deb http://ppa.launchpad.net/ansible/ansible/ubuntu trusty main" > /etc/apt/sources.list.d/ansible_repo.list
        #package require to add ansible package key
        sudo apt-get install dirmngr
        sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 93C4A3FD7BB9C367
    fi

    if [ "$debianVersion" = "$debian10" ];
    then
        #configuration de apt-get pour qu'il fonctionne avec le proxy micropole
        echo "Debian 10 install"
        echo "Acquire::http::Pipeline-Depth \"0\";" >> /etc/apt/apt.conf.d/99nohttppipelineproxyfix

        sudo echo "deb http://ppa.launchpad.net/ansible/ansible/ubuntu bionic main" > /etc/apt/sources.list.d/ansible_repo.list
        #package require to add ansible package key
        sudo apt-get install -y dirmngr
        sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 93C4A3FD7BB9C367
    fi

    if [ "$debianVersion" = "$debian11" ];
    then
        #configuration de apt-get pour qu'il fonctionne avec le proxy micropole
        echo "Debian 11 install"
        echo "Acquire::http::Pipeline-Depth \"0\";" >> /etc/apt/apt.conf.d/99nohttppipelineproxyfix

        sudo echo "deb http://ppa.launchpad.net/ansible/ansible/ubuntu focal main" > /etc/apt/sources.list.d/ansible_repo.list
        sudo echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | sudo tee /etc/apt/sources.list.d/symfony-cli.list
        #package require to add ansible package key
        sudo apt-get install -y dirmngr
        sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 93C4A3FD7BB9C367
    fi

    # Update Repositories
    sudo apt-get update
    sudo apt-get install -y symfony-cli
    sudo apt-get install -y ansible

    echo "=> Ansible Package Installed."

fi

if [ "$isRedHat" = true ];
then
    sudo rpm -iUvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm

    sudo yum makecache

    sudo yum install -y ansible
fi

#Ansible galaxy
ansible-galaxy install -r /vagrant/ansible/requirements.yml

sudo ansible-playbook "/vagrant/${ANSIBLE_PLAYBOOK}" -e hostname=default --inventory=/vagrant/ansible/inventories/dev --extra-vars="environnement=dev"
