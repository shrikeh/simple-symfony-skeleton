# -*- mode: ruby -*-
# vi: set ft=ruby :
#
# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  # Automatically download required plugins
  required_plugins = %w( nugrant vagrant-hostsupdater vagrant-docker-compose )
  required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin};vagrant #{ARGV.join(" ")}" unless Vagrant.has_plugin? plugin || ARGV[0] == 'plugin'
  end

  config.user.defaults = {
      "vm" => {
          "php74_box" => "hashicorp/bionic64",
          "ip" => "192.168.15.107",
          "host_memory" => 1024,
          "host_cpus" => 2,
          "hostname" => "php74.shrikeh.vagrant"
      }
  }

  config.vm.define "php74", primary: true do |php74|
    php74.vm.hostname = config.user.vm.hostname
    php74.vm.box = config.user.vm.php74_box

    php74.vm.synced_folder "./", "/vagrant", create: true,
       owner: "vagrant",
       group: "www-data",
       mount_options: ["dmode=775,fmode=774"]

    php74.vm.provider :virtualbox do |vb|
      vb.customize ["modifyvm", :id, "--memory", config.user.vm.host_memory]
      vb.customize ["modifyvm", :id, "--cpus", config.user.vm.host_cpus]
      vb.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 1000]
    end

    php74.vm.provision "ansible" do |ansible|
      ansible.playbook = "tools/ansible/playbook.yml"
      ansible.compatibility_mode = "2.0"
      ansible.galaxy_role_file = "tools/ansible/requirements.yml"
      ansible.galaxy_roles_path = "tools/ansible/galaxy"
      ansible.groups = {
          "php_test" => ["php74"],
          "vagrant" => ["php74"]
      }
      ansible.extra_vars = {
          docker_users: ["vagrant"]
      }
    end
  end
end