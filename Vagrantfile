# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/trusty64"

  config.vm.provider :virtualbox do |v|
    v.name = "brander"
    v.memory = 1024
    v.cpus = 2
  end

  config.vm.hostname = "brander"
  config.vm.network "private_network", ip: "192.168.255.10"

  config.vm.define :brander do |brander|
  end

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "./deploy/project.yml"
    ansible.inventory_path = "./deploy/inventories/local.yml"
  end
    config.vm.synced_folder "./www", "/var/www", type: :nfs,  mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
end
