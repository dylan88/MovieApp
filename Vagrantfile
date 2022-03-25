VAGRANTFILE_API_VERSION = "2"

# Cross-platform way of finding an executable in the $PATH.
def which(cmd)
  exts = ENV['PATHEXT'] ? ENV['PATHEXT'].split(';') : ['']
  ENV['PATH'].split(File::PATH_SEPARATOR).each do |path|
    exts.each { |ext|
      exe = File.join(path, "#{cmd}#{ext}")
      return exe if File.executable?(exe) && !File.directory?(exe)
    }
  end
  return nil
end

# Use config_vagrant.yml for basic VM configuration.
require 'yaml'
dir = File.dirname(File.expand_path(__FILE__))
if !File.exist?("#{dir}/ansible/vars/config_vagrant.yml")
  raise 'Configuration file not found! Please copy ansible/vars/config-system.example.yml to ansible/vars/config_vagrant.yml and try again.'
end
vconfig = YAML::load_file("#{dir}/ansible/vars/config_vagrant.yml")
if !File.exist?("#{dir}/ansible/vars/config_vagrant.local.yml")
  raise 'Local configuration file not found! Please use ansible/vars/config_vagrant.local.example.yml to create a custom ansible/vars/config_vagrant.local.yml then try again.'
end
vconfig.merge!(YAML::load_file("#{dir}/ansible/vars/config_vagrant.local.yml"))

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.hostname = vconfig['vagrant_hostname']
  if vconfig['vagrant_ip'] == "0.0.0.0" && Vagrant.has_plugin?("vagrant-auto_network")
    config.vm.network :private_network, :ip => vconfig['vagrant_ip'], :auto_network => true
  else
    config.vm.network :private_network, ip: vconfig['vagrant_ip']
  end

  if !vconfig['vagrant_public_ip'].empty? && vconfig['vagrant_public_ip'] == "0.0.0.0"
    config.vm.network :public_network
  elsif !vconfig['vagrant_public_ip'].empty?
    config.vm.network :public_network, ip: vconfig['vagrant_public_ip']
  end

  config.ssh.insert_key = false
  config.ssh.forward_agent = true

  config.vm.box = vconfig['vagrant_box']
  config.vm.box_version = vconfig['vagrant_box_version']
  
  # If hostsupdater plugin is installed, add all server names as aliases.
  if Vagrant.has_plugin?("vagrant-hostsupdater")
    config.hostsupdater.aliases = []
    # Add all hosts that aren't defined as Ansible vars.
    if vconfig['debianvm_webserver'] == "apache"
      for host in vconfig['apache_vhosts']
        unless host['servername'].include? "{{"
          config.hostsupdater.aliases.push(host['servername'])
        end
      end
    elsif vconfig['debianvm_webserver'] == "nginx"
      for host in vconfig['nginx_vhosts']
        unless host['server_name'].include? "{{"
          config.hostsupdater.aliases.push(host['server_name'])
        end
      end
    end
  end
  
  for synced_folder in vconfig['vagrant_synced_folders'];
    config.vm.synced_folder synced_folder['local_path'], synced_folder['destination'],
      type: synced_folder['type'],
      rsync__auto: "true",
      rsync__exclude: synced_folder['excluded_paths'],
      rsync__args: ["--verbose", "--archive", "--delete", "-z", "--chmod=ugo=rwX"],
      id: synced_folder['id'],
      create: synced_folder.include?('create') ? synced_folder['create'] : false,
      mount_options: synced_folder.include?('mount_options') ? synced_folder['mount_options'] : []
  end
  
  config.vm.provider :virtualbox do |v|
    v.name = vconfig['vagrant_hostname']
    v.memory = vconfig['vagrant_memory']
    v.cpus = vconfig['vagrant_cpus']
  end

  # Provision using Ansible provisioner if Ansible is installed on host.
  if which('ansible-playbook')
    config.vm.provision "ansible" do |ansible|
      ansible.playbook = "#{dir}/ansible/vagrant.yml"
      ansible.sudo = true
    end
  # Provision using shell provisioner and JJG-Ansible-Windows otherwise.
  else
    config.vm.provision "shell" do |sh|
      sh.path = "#{dir}/ansible/windows.sh"
      sh.args = "/ansible/setup.yml"
    end
  end
  
  # Set the name of the VM. See: http://stackoverflow.com/a/17864388/100134
  config.vm.define vconfig['vagrant_machine_name'] do |d|
  end

  if Vagrant.has_plugin?("vagrant-cachier")
    # Configure cached packages to be shared between instances of the same base box.
    # More info on http://fgrehm.viewdocs.io/vagrant-cachier/usage
    config.cache.scope = :box

    # OPTIONAL: If you are using VirtualBox, you might want to use that to enable
    # NFS for shared folders. This is also very useful for vagrant-libvirt if you
    # want bi-directional sync
    config.cache.synced_folder_opts = {
      type: :nfs,
      # The nolock option can be useful for an NFSv3 client that wants to avoid the
      # NLM sideband protocol. Without this option, apt-get might hang if it tries
      # to lock files needed for /var/cache/* operations. All of this can be avoided
      # by using NFSv4 everywhere. Please note that the tcp option is not the default.
      mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }
    # For more information please check http://docs.vagrantup.com/v2/synced-folders/basic_usage.html
    end

end