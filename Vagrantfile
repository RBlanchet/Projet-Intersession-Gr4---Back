# -*- mode: ruby -*-
# vi: set ft=ruby :
 Vagrant.configure("2") do |config|
 config.vm.box = "ubuntu/xenial64"
 config.vm.synced_folder  "./data", "/var/www/html"
 config.vm.network "private_network", ip: "192.168.33.10"
 config.vm.network "forwarded_port", guest: 8000, host: 8000
 config.vm.provision "shell", path: "setup.sh"
end
