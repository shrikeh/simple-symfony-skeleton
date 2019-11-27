# -*- mode: ruby -*-
# vi: set ft=ruby :
#
# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = '2'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  # Automatically download required plugins
  required_plugins = %w( nugrant vagrant-hostsupdater vagrant-docker-compose vagrant-cachier )
  required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin};vagrant #{ARGV.join(' ')}" unless Vagrant.has_plugin? plugin || ARGV[0] == 'plugin'
  end

  config.user.defaults = {
      :vm => {
        :php74_box => 'hashicorp/bionic64',
        :ip => '192.168.15.107',
        :host_memory => 1024,
        :host_cpus => 2,
        :hostname => 'php74.shrikeh.vagrant',
        :synced_folder => '/vagrant',
        :user => 'vagrant'
      }
  }

  config.vm.define 'php74', primary: true do |php74|

    php74.vm.hostname = config.user.vm.hostname
    php74.vm.box = config.user.vm.php74_box

    # Disable the default synced folder...
    config.vm.synced_folder './', '/vagrant', disabled: true

    # Now use the one as set in the user config. This may be the same as above but there is no documented way
    # to change the default synced folder.
    php74.vm.synced_folder './', config.user.vm.synced_folder, create: true, disabled: false,
       owner: config.user.vm.user,
       group: 'www-data',
       mount_options: ['dmode=775,fmode=774']

    php74.vm.provider :virtualbox do |vb|
      config.cache.synced_folder_opts = {
        type: :nfs,
        # The nolock option can be useful for an NFSv3 client that wants to avoid the
        # NLM sideband protocol. Without this option, apt-get might hang if it tries
        # to lock files needed for /var/cache/* operations. All of this can be avoided
        # by using NFSv4 everywhere. Please note that the tcp option is not the default.
        mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
      }

      vb.customize ['modifyvm', :id, '--memory', config.user.vm.host_memory]
      vb.customize ['modifyvm', :id, '--cpus', config.user.vm.host_cpus]
      vb.customize ['guestproperty', 'set', :id, '/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold', 1000]
    end

    php74.vm.provision 'ansible' do |ansible|
      ansible.playbook = 'tools/ansible/playbook.yml'
      ansible.compatibility_mode = '2.0'
      ansible.galaxy_role_file = 'tools/ansible/requirements.yml'
      ansible.galaxy_roles_path = 'tools/ansible/galaxy'
      ansible.groups = {
          :php_test => ['php74'],
          :vagrant => ['php74']
      }
      ansible.extra_vars = {
          docker_users: [config.user.vm.user],
          vagrant_synced_folder_path: config.user.vm.synced_folder
      }
    end
  end
end