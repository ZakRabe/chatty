# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.box = "docker64"
  config.vm.box_url = "https://oss-binaries.phusionpassenger.com/vagrant/boxes/latest/ubuntu-12.04-amd64-vbox.box"

  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.auto_detect = true
    # If you are using VirtualBox, you might want to enable NFS for shared folders
    # config.cache.enable_nfs  = true
    config.cache.enable :apt
    config.cache.enable :gem
  end

  config.vm.network :forwarded_port, guest: 80, host: 6080
  config.vm.network :forwarded_port, guest: 3306, host: 6033
  config.vm.provision :shell, :path => "vagrant.sh"
  config.vm.synced_folder ".", "/var/www/application"

  config.vm.provider :virtualbox do |vb|
    # Don't boot with headless mode
    vb.gui = true
  
    # Use VBoxManage to customize the VM. For example to change memory:
    vb.customize ["modifyvm", :id, "--ioapic", "on"]
    vb.customize ["modifyvm", :id, "--memory", "512"]
    vb.customize ["modifyvm", :id, "--cpus", "4"]
  end


end