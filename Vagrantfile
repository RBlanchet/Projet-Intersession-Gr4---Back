# -*- mode: ruby -*-
# vi: set ft=ruby :
 Vagrant.configure("2") do |config|
 config.vm.box = "ubuntu/xenial64"
 config.vm.synced_folder  "./data", "/var/www/html", id: "application", :nfs => true
 config.vm.synced_folder ".", "/vagrant", disabled: true
 config.vm.network "private_network", ip: "192.168.33.10"
 config.vm.network "forwarded_port", guest: 8000, host: 8000
 config.vm.provider "virtualbox" do |vb|  
  # Customize the amount of memory on the VM:
  vb.memory = "2048"
 end
 config.vm.provision "shell", path: "setup.sh"
 if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :machine

    config.cache.synced_folder_opts = {
      type: :nfs,
      mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }

    config.cache.enable :generic, {
      "cache"  => { cache_dir: "/var/www/html/intersession/app/cache" },
      "logs"   => { cache_dir: "/var/www/html/intersession/logs" },
      "vendor" => { cache_dir: "/var/www/html/intersession/vendor" },
    }
  end
end
